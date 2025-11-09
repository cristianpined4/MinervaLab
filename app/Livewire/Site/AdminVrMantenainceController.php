<?php

namespace App\Livewire\Site;

use App\Models\Room;
use App\Models\VrMantenaince;
use App\Models\VrGlasses;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AdminVrMantenainceController extends Component
{
    public $record_id;
    public $fields = [
        'starts_at' => null,
        'ends_at' => null,
        'description' => null,
        'id_vr' => null,
    ];
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;
    public $vr_glasses = [];
    public $rooms = [];
    public $room = 1;

    public function mount()
    {
        $this->vr_glasses = VrGlasses::all();
        $this->rooms = Room::all();
        $this->room = $this->rooms[0]->id;
    }

    public function render()
    {
        $query = VrMantenaince::with('vrGlasses');
        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new VrMantenaince())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.admin.admin_vr_mantenaince', [
            'data' => $data,
            'vr_glasses' => $this->vr_glasses,
            'rooms' => $this->rooms
        ])
            ->extends('layouts.site')
            ->section('content');
    }

    // Modales
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = VrMantenaince::find($id);
            $this->fields['starts_at'] = $registro->starts_at;
            $this->fields['ends_at'] = $registro->ends_at;
            $this->fields['description'] = $registro->description;
            $this->fields['id_vr'] = $registro->id_vr;
        } else {
            $this->record_id = null;
            $this->fields = [
                'starts_at' => null,
                'ends_at' => null,
                'description' => null,
                'id_vr' => null,
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
        $registro = VrMantenaince::find($id);
        if ($registro) {
            $registro->delete();
        }
        $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
    }

    public function store_update()
    {
        $this->validate([
            'fields.starts_at' => 'required|date',
            'fields.ends_at' => 'required|date|after_or_equal:fields.starts_at',
            'fields.description' => 'required|string|max:255',
            'fields.id_vr' => 'required|exists:vr_glasses,id',
        ], [
            'fields.starts_at.required' => 'Seleccione la fecha de inicio',
            'fields.ends_at.required' => 'Seleccione la fecha de fin',
            'fields.description.required' => 'Ingrese una descripción',
            'fields.id_vr.required' => 'Seleccione un equipo',
        ]);

        try {
            DB::beginTransaction();

            if ($this->record_id) {
                VrMantenaince::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro actualizado correctamente']);
            } else {
                VrMantenaince::create($this->fields);
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
