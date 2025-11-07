<?php
namespace App\Livewire\Site;

use App\Models\SceneCategory;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AdminSceneCategoryController extends Component
{
    public $record_id;
    public $fields = [
        'description' => null,
        'color' => null,
    ];

    public $search = '';
    public $paginate = 10;

    protected $listeners = ['erase' => 'erase'];

    public function render()
    {
        $query = SceneCategory::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new SceneCategory())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.admin.admin_scene_category', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }

    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = SceneCategory::find($id);
            $this->fields['description'] = $registro->description;
            $this->fields['color'] = $registro->color;
        } else {
            $this->record_id = null;
            $this->fields = [
                'description' => null,
                'color' => null,
            ];
        }

        $this->dispatch('abrir-modal', [
            'modal' => 'modal-home',
            'fields' => $this->fields,
        ]);
    }

    public function confirmarEliminar($id)
    {
        $this->dispatch('confirmar-eliminar', [
            'id' => $id,
            'title' => 'Eliminar',
            'text' => '¿Estás seguro de eliminar esta categoría?',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }

    public function erase($id)
    {
        SceneCategory::find($id)?->delete();
        $this->dispatch('swal:notify', ['message' => 'Categoría eliminada correctamente']);
    }

    public function store_update()
    {
        $this->validate([
            'fields.description' => 'required|string|max:100',
            'fields.color' => 'required|string|max:20',
        ], [
            'fields.description.required' => 'Ingrese una descripción',
            'fields.description.max' => 'Máximo 100 caracteres',
            'fields.color.required' => 'Seleccione un color',
        ]);

        try {
            DB::beginTransaction();

            if ($this->record_id) {
                SceneCategory::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Categoría actualizada correctamente']);
            } else {
                SceneCategory::create($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Categoría creada correctamente']);
            }

            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('swal:notify', ['message' => 'Error al guardar la categoría']);
        }
    }
}
