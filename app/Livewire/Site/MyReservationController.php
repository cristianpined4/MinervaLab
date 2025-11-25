<?php
namespace App\Livewire\Site;

use App\Models\Reservation;
use App\Models\ReservationAttendance;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Services\FPDFF;
class MyReservationController extends Component
{
    public $record_id;
    public $attendance_list = [];
    public $fields = [
        'id_user' => null,
        'id_room' => null,
        'date' => null,
        'starts_at' => null,
        'ends_at' => null,
        'time' => null,
        'students' => null,
        'status' => null
    ];
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;

    public function render()
    {
        $query = Reservation::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new Reservation())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            })->orWhere('id_user', auth()->id());
        } else {
            $query->where('id_user',auth()->id() );
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.site.my_reservation', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }

    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = Reservation::find($id);
            $this->fields = [
                'id_user' => $registro->id_user,
                'id_room' => $registro->id_room,
                'date' => $registro->date,
                'starts_at' => $registro->starts_at,
                'ends_at' => $registro->ends_at,
                'time' => $registro->time,
                'students' => $registro->students,
                'status' => $registro->status,
            ];
        } else {
            $this->record_id = null;
            $this->fields = [
                'id_user' => null,
                'id_room' => null,
                'date' => null,
                'starts_at' => null,
                'ends_at' => null,
                'time' => null,
                'students' => null,
                'status' => null,
            ];
        }

        $this->dispatch('abrir-modal', [
            'modal' => 'modal-reservation',
            'fields' => $this->fields
        ]);
    }

    public function confirmarRechazar($id)
    {
        $this->dispatch('confirmar-rechazar', [
            'id' => $id,
            'title' => 'Rechazar',
            'text' => '¿Estás seguro de rechazar esta reservación?',
            'confirmButtonText' => 'Sí, declinar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }


    public function confirmarAutorizar($id)
    {
        $this->dispatch('confirmar-autorizar', [
            'id' => $id,
            'title' => 'Autorizar',
            'text' => '¿Estás seguro de autorizar esta reservación?',
            'confirmButtonText' => 'Sí, autorizar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }
    public function confirmarCancelar($id)
    {
        $this->dispatch('confirmar-cancelar', [
            'id' => $id,
            'title' => 'Cancelar',
            'text' => '¿Estás seguro de cancelar esta reservación?',
            'confirmButtonText' => 'Sí, Cancelar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }

    public function reject($id)
    {
        Reservation::find($id)->update(
            ['status' => 2]
        );
        $this->dispatch('swal:notify', ['message' => 'Reservación rechazada']);
        $this->dispatch('reload-delay');
    }
    public function accept($id)
    {
        Reservation::find($id)->update(
            ['status' => 1]
        );
        $this->dispatch('swal:notify', ['message' => 'Reservación autorizada']);
        $this->dispatch('reload-delay');
    }
    public function cancel($id)
    {
        Reservation::find($id)->update(
            ['status' => 3]
        );
        $this->dispatch('swal:notify', ['message' => 'Reservación cancelada']);
        $this->dispatch('reload-delay');
    }

    public function store_update()
    {
        $this->validate([
            'fields.id_user' => 'required|integer',
            'fields.id_room' => 'required|integer',
            'fields.date' => 'required|date',
            'fields.starts_at' => 'required|date',
            'fields.ends_at' => 'required|date',
            'fields.time' => 'required|string|max:10',
            'fields.students' => 'required|integer',
            'fields.status' => 'required|string|max:20',
        ], [
            'fields.id_user.required' => 'Seleccione un usuario',
            'fields.id_room.required' => 'Seleccione una sala',
            'fields.date.required' => 'Ingrese la fecha',
            'fields.starts_at.required' => 'Ingrese la hora de inicio',
            'fields.ends_at.required' => 'Ingrese la hora final',
            'fields.time.required' => 'Ingrese la duración',
            'fields.students.required' => 'Ingrese la cantidad de estudiantes',
            'fields.status.required' => 'Ingrese el estado',
        ]);

        try {
            DB::beginTransaction();
            if ($this->record_id != null) {
                Reservation::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Reservación actualizada correctamente']);
            } else {
                Reservation::create($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Reservación creada correctamente']);
            }
            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-reservation']);
            $this->dispatchBrowserEvent('reload-delay');
        } catch (\Throwable $th) {
            DB::rollBack();
            return json_encode(['status' => 'error', 'message' => 'Ocurrió un error al guardar la reservación']);
        }
    }
    public function mostrarAsistencia($id)
    {
        $this->resetErrorBag();

        // Obtener lista de asistencia vinculada a la reservación
        $this->attendance_list = ReservationAttendance::where('id_reservation', $id)->get();

        // Lanzar el modal
        $this->dispatch('abrir-modal', [
            'modal' => 'modal-asistencia',
        ]);
    }
    public function pdfAttendance($id)
    {

        $data = ReservationAttendance::where('id_reservation', $id)->get()->toArray();

        $pdf = (new FPDFF())
            ->setTitle('Reporte de Asistencia')
            ->setSubTitle('Listado de reservacion #' . $id)
            ->setDate(now()->format('Y-m-d'))
            ->setModelColumns(['carnet', 'date', 'attendance'])
            ->setColumnLabels([
                'carnet'     => 'Carnet del Estudiante',
                'date'     => 'Fecha',
                'attendance' => 'Hora de llegada',
            ])
            ->setColumnWidths([65,65,55])
            ->build($data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'asistencia_' . $id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }





}
