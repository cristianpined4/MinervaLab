<?php
namespace App\Livewire\Site;

use App\Models\Holiday;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class AdminCalendaryController extends Component
{
    public $record_id;
    public $fields = [
        'starts_at' => null,
        'ends_at' => null,
        'description' => null
    ];   // inputs normales
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;


    public function render()
    {
        $query = Holiday::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new Holiday())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }


        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.admin.admin_calendary', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }


    //Modales
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = Holiday::find($id);
            $this->fields['starts_at'] = $registro->starts_at;
            $this->fields['ends_at'] = $registro->ends_at;
            $this->fields['description'] = $registro->description;
        } else {
            $this->record_id = null;
            $this->fields = [
                'starts_at' => null,
                'ends_at' => null,
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
        Holiday::find($id)->delete();
        $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
        $this->dispatch('reload-delay');
    }

    public function store_update()
    {
        $this->validate([
            'fields.starts_at' => 'required',
            'fields.ends_at' => 'required',
            'fields.description' => 'required|string|max:80',
        ],
        [
            'fields.starts_at.required' => 'Seleccione una fecha valida',
            'fields.ends_at.required' => 'Seleccione una fecha valida',
            'fields.description.required' => 'Ingrese una descripcion',
            'fields.description.max' => 'La longitud maxima es de 80',
        ]
        );
        try {
            DB::beginTransaction();
            if($this->record_id != null){
                Holiday::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Registro actualizado correctamente']);
                DB::commit();
            }else{
                Holiday::create($this->fields);
                DB::commit();
                $this->dispatch('swal:notify', ['message' => 'Registro creado correctamente']);
            }
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
            $this->dispatchBrowserEvent('reload-delay');
        } catch (\Throwable $th) {
            return json_encode(['status' => 'error', 'message' => 'Ocurrio un error al guardar el registro']);
        }
    }

}
