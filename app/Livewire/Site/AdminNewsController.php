<?php

namespace App\Livewire\Site;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Component
{
  use WithPagination, WithFileUploads;

  public $record_id;
  public $openModal = false;
  public $fields = [
    'resource_type' => 'article',
    'title' => null,
    'description' => null,
    'path' => null,
    'date' => null,
  ];
  public $upload; // archivo temporal (imagen o video)

  public $search = '';
  public $paginate = 10;

  protected $listeners = ['erase' => 'erase', 'updateOpenModal' => 'cerrarModal'];

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

    if ($id) {
      $this->record_id = $id;
      $reg = News::findOrFail($id);
      $this->fields = [
        'resource_type' => $reg->resource_type,
        'title' => $reg->title,
        'description' => $reg->description,
        'path' => $reg->path,
        'date' => $reg->date ? \Carbon\Carbon::parse($reg->date)->format('Y-m-d') : null,
      ];
    } else {
      $this->record_id = null;
      $this->fields = [
        'resource_type' => 'article',
        'title' => null,
        'description' => null,
        'path' => null,
        'date' => now()->format('Y-m-d'),
      ];
    }

    $this->openModal = true;
    $this->dispatch('abrir-modal-noticia');
  }

  /**
   * Limpiar archivo cuando cambia el tipo de publicación
   */
  public function updatedFieldsResourceType()
  {
    $this->upload = null;
  }

  /**
   * Cerrar modal
   */
  public function cerrarModal($value = false)
  {
    $this->openModal = $value;
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
          'video' => 'news/videos',
          default => 'news/images',
        };

        // Borrar archivo anterior si existe
        if ($this->record_id) {
          $old = News::find($this->record_id);
          if ($old && $old->path && Storage::disk('public')->exists($old->path)) {
            Storage::disk('public')->delete($old->path);
          }
        }

        $storedPath = $this->upload->storeAs($folder, $filename, 'public');
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
      $this->openModal = false;
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
      if ($news->path && Storage::disk('public')->exists($news->path)) {
        Storage::disk('public')->delete($news->path);
      }
      $news->delete();
    }
    $this->dispatch('swal:notify', ['message' => 'Noticia eliminada correctamente', 'icon' => 'success']);
  }
}