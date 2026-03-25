<?php
namespace App\Livewire\Site;

use App\Models\Scene;
use App\Models\SceneCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class AdminSceneController extends Component
{
    use WithPagination, WithFileUploads;

    public $record_id;
    public $resource_demo;
    public $current_video_url;
    public $resource_demo_preview_url;
    public $fields = [
        'id_scene_category' => null,
        'description' => null,
        'duration' => null,
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
    public function verVideo($id)
    {
        $scene = Scene::find($id);

        if ($scene && $scene->resource_demo && Storage::disk('videos-scenes')->exists($scene->resource_demo)) {
            $videoUrl = asset('videos/scenes/' . $scene->resource_demo);
            $this->dispatch('abrir-video', [
                'modal' => 'modal-video',
                'videoUrl' => $videoUrl,
                'description' => $scene->description,
            ]);
        } else {
            $this->dispatch('swal:notify', [
                'message' => 'No se encontró el video para esta escena',
                'icon' => 'error'
            ]);
        }
    }

    public function abrirModal($id = null)
    {
        $this->resetErrorBag();
        if (File::exists(storage_path('app/private'))) {
            File::deleteDirectory(storage_path('app/private'));
        }

        if (File::exists(storage_path('app/public/livewire-tmp'))) {
            File::deleteDirectory(storage_path('app/public/livewire-tmp'));
        }

        if ($id) {
            $this->record_id = $id;
            $registro = Scene::find($id);
            $this->fields['id_scene_category'] = $registro->id_scene_category;
            $this->fields['description'] = $registro->description;
            $this->fields['duration'] = $registro->duration;
            $this->resource_demo = null;
            $this->resource_demo_preview_url = null;
            $this->current_video_url = ($registro->resource_demo && Storage::disk('videos-scenes')->exists($registro->resource_demo))
                ? asset('videos/scenes/' . $registro->resource_demo)
                : null;
        } else {
            $this->record_id = null;
            $this->fields = [
                'id_scene_category' => null,
                'description' => null,
                'duration' => null,
            ];
            $this->resource_demo = null;
            $this->resource_demo_preview_url = null;
            $this->current_video_url = null;
        }

        $this->dispatch('abrir-modal', [
            'modal' => 'modal-home',
            'fields' => $this->fields,
        ]);
    }

    public function updatedResourceDemo(): void
    {
        if ($this->resource_demo) {
            $this->resource_demo_preview_url = $this->resource_demo->temporaryUrl();
            $this->current_video_url = null;
            return;
        }

        $this->resource_demo_preview_url = null;
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
        if ($scene && $scene->resource_demo && Storage::disk('videos-scenes')->exists($scene->resource_demo)) {
            Storage::disk('videos-scenes')->delete($scene->resource_demo);
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
            'resource_demo' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/avi|max:20480',
        ], [
            'fields.id_scene_category.required' => 'Seleccione una categoría',
            'fields.description.required' => 'Ingrese una descripción',
            'fields.duration.required' => 'Ingrese la duración',
            'fields.duration.numeric' => 'Debe ser un número válido',
            'resource_demo.file' => 'El video de demostración debe ser un archivo válido.',
            'resource_demo.mimetypes' => 'El video de demostración debe ser de tipo: video/mp4, video/mpeg o video/avi.',
            'resource_demo.max' => 'El video de demostración no debe superar los 20 MB.',
        ]);

        $path = null;
        try {
            DB::beginTransaction();

            $data = [
                'id_scene_category' => $this->fields['id_scene_category'],
                'description' => $this->fields['description'],
                'duration' => $this->fields['duration'],
            ];

            if (!empty($this->resource_demo)) {
                $filename = uniqid('scene_', true) . '.' . $this->resource_demo->getClientOriginalExtension();
                /* guardar en el sistema de archivos videos-scenes */
                $path = $this->resource_demo->storeAs('', $filename, 'videos-scenes');
                $data['resource_demo'] = $filename;
            }

            if ($this->record_id) {
                $scene = Scene::find($this->record_id);

                if (!$scene) {
                    throw new \Exception('No se encontró la escena a editar.');
                }

                if (isset($data['resource_demo']) && $scene->resource_demo) {
                    Storage::disk('videos-scenes')->delete($scene->resource_demo);
                }

                $scene->update($data);
                $this->dispatch('swal:notify', [
                    'message' => 'Escena actualizada correctamente'
                ]);
            } else {
                Scene::create($data);
                $this->dispatch('swal:notify', [
                    'message' => 'Escena creada correctamente'
                ]);
            }

            DB::commit();
            $this->resource_demo = null;
            $this->resource_demo_preview_url = null;
            $this->current_video_url = null;
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            if (!empty($path)) {
                Storage::disk('videos-scenes')->delete($path);
            }
            $this->dispatch('swal:notify', [
                'message' => 'Error al guardar la escena: ' . $th->getMessage(),
                'icon' => 'error'
            ]);
        }

        if (File::exists(storage_path('app/private'))) {
            File::deleteDirectory(storage_path('app/private'));
        }

        if (File::exists(storage_path('app/public/livewire-tmp'))) {
            File::deleteDirectory(storage_path('app/public/livewire-tmp'));
        }
    }
}