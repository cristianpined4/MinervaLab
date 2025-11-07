<?php
namespace App\Livewire\Site;

use App\Models\Scene;
use App\Models\SceneCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminSceneController extends Component
{
    use WithPagination, WithFileUploads;

    public $record_id;
    public $fields = [
        'id_scene_category' => null,
        'description' => null,
        'duration' => null,
        'resource_demo' => null,
    ];

    public $search = '';
    public $paginate = 10;

    protected $listeners = ['erase' => 'erase'];

    public function render()
    {
        $query = Scene::query()->with('sceneCategory');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('duration', 'like', '%' . $this->search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);
        $categories = SceneCategory::all();

        return view('livewire.admin.admin_scene', compact('data', 'categories'))
            ->extends('layouts.site')
            ->section('content');
    }

    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = Scene::find($id);
            $this->fields['id_scene_category'] = $registro->id_scene_category;
            $this->fields['description'] = $registro->description;
            $this->fields['duration'] = $registro->duration;
            $this->fields['resource_demo'] = null; // no se carga el archivo directamente
        } else {
            $this->record_id = null;
            $this->fields = [
                'id_scene_category' => null,
                'description' => null,
                'duration' => null,
                'resource_demo' => null,
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
            'text' => '¿Estás seguro de eliminar esta escena?',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }

    public function erase($id)
    {
        $scene = Scene::find($id);
        if ($scene && $scene->resource_demo && Storage::disk('public')->exists(str_replace('storage/', '', $scene->resource_demo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $scene->resource_demo));
        }
        if ($scene) {
            $scene->delete();
        }
        $this->dispatch('swal:notify', ['message' => 'Escena eliminada correctamente']);
    }

    public function store_update()
    {
        $this->validate([
            'fields.id_scene_category' => 'required|integer',
            'fields.description' => 'required|string|max:255',
            'fields.duration' => 'required|numeric',
            'fields.resource_demo' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/avi|max:20480',
        ], [
            'fields.id_scene_category.required' => 'Seleccione una categoría',
            'fields.description.required' => 'Ingrese una descripción',
            'fields.duration.required' => 'Ingrese la duración',
            'fields.duration.numeric' => 'Debe ser un número válido',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'id_scene_category' => $this->fields['id_scene_category'],
                'description' => $this->fields['description'],
                'duration' => $this->fields['duration'],
            ];

            if ($this->fields['resource_demo']) {
                $path = $this->fields['resource_demo']->store('videos', 'public');
                $data['resource_demo'] = 'storage/' . $path;
            }

            if ($this->record_id) {
                $scene = Scene::find($this->record_id);
                if (isset($data['resource_demo']) && $scene->resource_demo) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $scene->resource_demo));
                }
                $scene->update($data);
                $this->dispatch('swal:notify', ['message' => 'Escena actualizada correctamente']);
            } else {
                Scene::create($data);
                $this->dispatch('swal:notify', ['message' => 'Escena creada correctamente']);
            }

            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('swal:notify', ['message' => 'Error al guardar la escena']);
        }
    }
}
