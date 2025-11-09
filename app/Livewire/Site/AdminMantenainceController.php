<?php
namespace App\Livewire\Site;

use App\Models\RoomMantenaince;
use App\Models\Room;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AdminMantenainceController extends Component
{
    public $record_id;
    public $fields = [
        'starts_at' => null,
        'ends_at' => null,
        'id_room' => null,
        'description' => null
    ];
    public $search = '';
    public $paginate = 10;
    protected $listeners = ['erase' => 'erase'];

    public function render()
    {
        $query = RoomMantenaince::with('room');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new RoomMantenaince())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);
        $rooms = Room::all();

        return view('livewire.admin.admin_mantenaince', compact('data', 'rooms'))
            ->extends('layouts.site')
            ->section('content');
    }

    public function equipos()
    {
        return redirect()->route('admin-mantenaince-vr');
    }
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = RoomMantenaince::find($id);
            $this->fields = [
                'starts_at' => $registro->starts_at,
                'ends_at' => $registro->ends_at,
                'id_room' => $registro->id_room,
                'description' => $registro->description
            ];
        } else {
            $this->record_id = null;
            $this->fields = [
                'starts_at' => null,
                'ends_at' => null,
                'id_room' => null,
                'description' => null
            ];
        }

        $this->dispatch('abrir-modal', [
            'modal' => 'modal-home',
            'fields' => $this->fields
        ]);
    }

    public function confirmarEliminar($id)
    {
        $this->dispatch('confirmar-eliminar', [
            'id' => $id,
            'title' => 'Eliminar',
            'text' => '¿Estás seguro de eliminar este registro?',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }

    public function erase($id)
    {
        RoomMantenaince::find($id)->delete();
        $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
    }

    public function store_update()
    {
        $this->validate([
            'fields.starts_at' => 'required',
            'fields.ends_at' => 'required',
            'fields.id_room' => 'required|exists:room,id',
            'fields.description' => 'required|string|max:80',
        ], [
            'fields.starts_at.required' => 'Seleccione una fecha válida',
            'fields.ends_at.required' => 'Seleccione una fecha válida',
            'fields.id_room.required' => 'Seleccione una habitación',
            'fields.description.required' => 'Ingrese una descripción',
            'fields.description.max' => 'La longitud máxima es de 80',
        ]);

        try {
            DB::beginTransaction();
            if ($this->record_id != null) {
                RoomMantenaince::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro actualizado correctamente']);
            } else {
                RoomMantenaince::create($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro creado correctamente']);
            }
            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return json_encode(['status' => 'error', 'message' => 'Ocurrió un error al guardar el registro']);
        }
    }
}
