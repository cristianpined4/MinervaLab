<?php

namespace App\Livewire\Site;

use App\Models\VrGlasses;
use App\Models\Room;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AdminVrController extends Component
{
    public $record_id;
    public $id_room;
    public $room;
    public $fields = [
        'code' => null,
        'entry_date' => null,
        'life_hours' => null,
        'usefull_years' => null,
    ];
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;

    public function mount()
    {
        $this->id_room = request()->get('id');
        $this->room = Room::find($this->id_room);
    }

    public function render()
    {
        $query = VrGlasses::where('id_room', $this->id_room)->whereNull('deleted_at');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new VrGlasses())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.admin.admin_vr', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }

    // Modales
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = VrGlasses::find($id);
            $this->fields['code'] = $registro->code;
            $this->fields['entry_date'] = $registro->entry_date;
            $this->fields['life_hours'] = $registro->life_hours;
            $this->fields['usefull_years'] = $registro->usefull_years;
        } else {
            $this->record_id = null;
            $this->fields = [
                'code' => null,
                'entry_date' => null,
                'life_hours' => null,
                'usefull_years' => null,
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
        $registro = VrGlasses::find($id);
        if ($registro) {
            $registro->delete();
        }
        $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
    }

    public function store_update()
    {
        $this->validate([
            'fields.code' => 'required|string|max:50',
            'fields.entry_date' => 'required|date',
            'fields.life_hours' => 'required|numeric|min:0',
            'fields.usefull_years' => 'required|numeric|min:0',
        ], [
            'fields.code.required' => 'Ingrese un código',
            'fields.entry_date.required' => 'Seleccione una fecha válida',
            'fields.life_hours.required' => 'Ingrese las horas de vida útil',
            'fields.usefull_years.required' => 'Ingrese los años útiles',
        ]);

        try {
            DB::beginTransaction();

            $this->fields['id_room'] = $this->id_room;

            if ($this->record_id) {
                VrGlasses::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro actualizado correctamente']);
            } else {
                VrGlasses::create($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro creado correctamente']);
            }

            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return json_encode(['status' => 'error', 'message' => 'Ocurrió un error al guardar el registro']);
        }
    }
}
