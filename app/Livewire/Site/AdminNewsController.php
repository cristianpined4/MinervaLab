<?php

namespace App\Livewire\Site;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminNewsController extends Component
{
  use WithPagination, WithFileUploads;

  public $record_id;
  public $fields = [
    'resource_type' => 'article',
    'title' => null,
    'description' => null,
    'path' => null,
    'date' => null,
  ];
  public $upload; // archivo temporal (imagen o video)
  public $upload_preview_url; // URL de previsualización del archivo subido
  public $current_media_url; // URL del archivo guardado (edición)

  public $search = '';
  public $paginate = 10;

  protected $listeners = ['erase' => 'erase', 'confirmar-eliminar' => 'confirmarEliminar'];

  public function paginationView()
  {
    return 'vendor.livewire.tailwind';
  }

  public function updatingSearch(): void
  {
    $this->resetPage();
  }

  public function render()
  {
    $query = News::query();

    if (!empty($this->search)) {
      $query->where(function ($q) {
        $q->where('title', 'like', '%' . $this->search . '%')
          ->orWhere('description', 'like', '%' . $this->search . '%')
          ->orWhere('resource_type', 'like', '%' . $this->search . '%');
      });
    }

    $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

    return view('livewire.admin.admin_news', compact('data'))
      ->extends('layouts.site')
      ->section('content');
  }

  /* ---------------------------------------------------------------
   | MODAL
   --------------------------------------------------------------- */
  public function abrirModal($id = null)
  {
    $this->resetErrorBag();
    $this->upload = null;
    $this->upload_preview_url = null;

    if ($id) {
      $this->record_id = $id;
      $reg = News::find($id);
      $this->fields['resource_type'] = $reg->resource_type;
      $this->fields['title'] = $reg->title;
      $this->fields['description'] = $reg->description;
      $this->fields['path'] = $reg->path;
      $this->fields['date'] = $reg->date ? \Carbon\Carbon::parse($reg->date)->format('Y-m-d') : null;

      $folder = match ($this->fields['resource_type']) {
        'video' => 'news-videos',
        default => 'news-images',
      };

      // Asignar URL del media guardado si existe
      $this->current_media_url = ($reg->path && Storage::disk($folder)->exists($reg->path))
        ? Storage::disk($folder)->url($reg->path)
        : null;
    } else {
      $this->record_id = null;
      $this->fields['resource_type'] = 'article';
      $this->fields['title'] = null;
      $this->fields['description'] = null;
      $this->fields['path'] = null;
      $this->fields['date'] = now()->format('Y-m-d');
      $this->current_media_url = null;
    }

    $this->dispatch('abrir-modal', [
      'modal' => 'modal-news',
      'fields' => array_merge($this->fields, ['record_id' => $this->record_id])
    ]);
  }

  /**
   * Limpiar archivo cuando cambia el tipo de publicación
   */
  public function updatedFieldsResourceType()
  {
    $this->upload = null;
    $this->upload_preview_url = null;
  }

  /**
   * Generar previsualización cuando se sube un archivo
   */
  public function updatedUpload()
  {
    if ($this->upload) {
      $this->upload_preview_url = $this->upload->temporaryUrl();
    } else {
      $this->upload_preview_url = null;
    }
  }

  /**
   * Cerrar modal
   */
  public function cerrarModal($value = false)
  {
    // Placeholder
  }

  /* ---------------------------------------------------------------
   | GUARDAR / ACTUALIZAR
   --------------------------------------------------------------- */
  public function store_update()
  {
    $this->validate([
      'fields.resource_type' => 'required|in:video,image,article',
      'fields.title' => 'required|string|max:255',
      'fields.description' => 'nullable|string',
      'fields.date' => 'required|date',
      'upload' => 'nullable|file|max:51200', // 50 MB
    ], [
      'fields.resource_type.required' => 'Seleccione el tipo de recurso.',
      'fields.title.required' => 'El título es obligatorio.',
      'fields.date.required' => 'La fecha es obligatoria.',
    ]);

    try {
      DB::beginTransaction();

      $data = [
        'resource_type' => $this->fields['resource_type'],
        'title' => $this->fields['title'],
        'description' => $this->fields['description'],
        'date' => $this->fields['date'],
      ];

      // Si se subió un archivo nuevo
      if ($this->upload) {
        $ext = $this->upload->getClientOriginalExtension();
        $filename = time() . '_' . \Str::slug($this->fields['title'] ?? 'news') . '.' . $ext;

        // Determinar carpeta según tipo
        $folder = match ($this->fields['resource_type']) {
          'video' => 'news-videos',
          default => 'news-images',
        };

        // Borrar archivo anterior si existe
        if ($this->record_id) {
          $old = News::find($this->record_id);
          if ($old && $old->path && Storage::disk($folder)->exists($old->path)) {
            Storage::disk($folder)->delete($old->path);
          }
        }

        $storedPath = $this->upload->storeAs('', $filename, $folder);
        $data['path'] = $storedPath;
      } elseif ($this->record_id) {
        // Conservar path existente
        $data['path'] = $this->fields['path'];
      }

      if ($this->record_id) {
        News::findOrFail($this->record_id)->update($data);
        $this->dispatch('swal:notify', ['message' => 'Noticia actualizada correctamente', 'icon' => 'success']);
      } else {
        News::create($data);
        $this->dispatch('swal:notify', ['message' => 'Noticia creada correctamente', 'icon' => 'success']);
      }

      DB::commit();
      $this->dispatch('cerrar-modal', ['modal' => 'modal-news']);
      $this->reset('upload');

    } catch (\Throwable $th) {
      DB::rollBack();
      $this->dispatch('swal:notify', ['message' => 'Error: ' . $th->getMessage(), 'icon' => 'error']);
    }

    if (File::exists(storage_path('app/private'))) {
      File::deleteDirectory(storage_path('app/private'));
    }

    if (File::exists(storage_path('app/public/livewire-tmp'))) {
      File::deleteDirectory(storage_path('app/public/livewire-tmp'));
    }
  }

  /* ---------------------------------------------------------------
   | ELIMINAR
   --------------------------------------------------------------- */
  public function confirmarEliminar($id)
  {
    $this->dispatch('confirmar-eliminar', [
      'id' => $id,
      'title' => 'Eliminar noticia',
      'text' => '¿Estás seguro de eliminar esta noticia?',
      'confirmButtonText' => 'Sí, eliminar',
      'cancelButtonText' => 'Cancelar',
    ]);
  }

  public function erase($id)
  {
    $news = News::find($id);
    if ($news) {
      $folder = match ($news->resource_type) {
        'video' => 'news-videos',
        default => 'news-images',
      };
      if ($news->path && Storage::disk($folder)->exists($news->path)) {
        Storage::disk($folder)->delete($news->path);
      }
      $news->delete();
    }
    $this->dispatch('swal:notify', ['message' => 'Noticia eliminada correctamente', 'icon' => 'success']);
  }
}