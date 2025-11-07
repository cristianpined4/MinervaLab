<?php
namespace App\Livewire\Site;

use App\Models\Schedule;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class AdminScheduleController extends Component
{
    public $record_id;
    public $fields = [
        'day' => null,
        'starts_at' => null,
        'ends_at' => null
    ];   // inputs normales
    public $days = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 50;


    public function render()
    {
        $query = Schedule::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new Schedule())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }
        $data = $query->orderBy('starts_at', 'ASC')->paginate($this->paginate);

        return view('livewire.admin.admin_schedule', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }


    //Modales
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = Schedule::find($id);
            $this->fields['day'] = $registro->day;
            $this->fields['starts_at'] = $registro->starts_at;
            $this->fields['ends_at'] = $registro->ends_at;
        } else {
            $this->record_id = null;
            $this->fields = [
                'day' => null,
                'starts_at' => null,
                'ends_at' => null
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
        Schedule::find($id)->delete();
        $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
    }

    public function store_update()
    {
        //dd($this->fields);
        $this->validate([
                'fields.day' => 'required',
                'fields.starts_at' => 'required',
                'fields.ends_at' => 'required',
            ],
            [
                'fields.day.required' => 'Seleccione una dia de la semana',
                'fields.starts_at.required' => 'Seleccione una hora valida',
                'fields.ends_at.required' => 'Seleccione una hora valida',
            ]
        );
        try {
            DB::beginTransaction();
            if($this->record_id != null){
                Schedule::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro actualizado correctamente']);
                DB::commit();
            }else{
                Schedule::create($this->fields);
                DB::commit();
                $this->dispatch('swal:notify', ['message' => 'Registro creado correctamente']);
            }
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            return json_encode(['status' => 'error', 'message' => 'Ocurrio un error al guardar el registro']);
        }
    }

}
