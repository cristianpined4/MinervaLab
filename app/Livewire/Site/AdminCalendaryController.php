<?php
namespace App\Livewire\Site;

use App\Models\Holiday;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination; // <-- CRUCIAL: Necesario para usar $this->resetPage()

class AdminCalendaryController extends Component
{
    use WithPagination; // <-- 1. Asegúrate de que esta línea esté presente

    public $record_id;
    public $fields = [
        'starts_at' => null,
        'ends_at' => null,
        'description' => null
    ];
    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;
    protected $paginationTheme = 'tailwind';

    // Este método se ejecuta automáticamente cuando $search cambia
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $query = Holiday::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
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
            // Formateo de fechas para que el input datetime-local las lea correctamente
            $this->fields['starts_at'] = \Carbon\Carbon::parse($registro->starts_at)->format('Y-m-d\TH:i');
            $this->fields['ends_at'] = \Carbon\Carbon::parse($registro->ends_at)->format('Y-m-d\TH:i');
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
        try {
            Holiday::find($id)->delete();
            
            // 2. EL DOBLE RESETEO PARA FORZAR LA RECARGA DE LA TABLA:
            // a) Resetear el filtro de búsqueda.
            $this->reset('search');
            // b) Resetear la página actual a 1.
            $this->resetPage(); 
            
            $this->dispatch('swal:notify', ['message' => 'Registro eliminado correctamente']);
        } catch (\Throwable $th) {
            $this->dispatch('swal:notify', ['message' => 'Error al eliminar: ' . $th->getMessage()]);
        }
    }

    public function store_update()
    {
        $this->validate([
            'fields.starts_at' => 'required',
            'fields.ends_at' => 'required|after_or_equal:fields.starts_at', // Validación mejorada
            'fields.description' => 'required|string|max:80',
        ],
        [
            'fields.starts_at.required' => 'Seleccione una fecha válida de inicio.',
            'fields.ends_at.required' => 'Seleccione una fecha válida final.',
            'fields.ends_at.after_or_equal' => 'La fecha final debe ser igual o posterior a la de inicio.',
            'fields.description.required' => 'Ingrese una descripción.',
            'fields.description.max' => 'La longitud máxima es de 80 caracteres.',
        ]
        );
        try {
            DB::beginTransaction();

            // Formatear las fechas antes de guardar
            $dataToSave = [
                'starts_at' => \Carbon\Carbon::parse($this->fields['starts_at']),
                'ends_at' => \Carbon\Carbon::parse($this->fields['ends_at']),
                'description' => $this->fields['description'],
            ];

            if($this->record_id != null){
                Holiday::find($this->record_id)->update($dataToSave);
                $message = 'Registro actualizado correctamente';
            }else{
                Holiday::create($dataToSave);
                // Si es un nuevo registro, también reseteamos la paginación a la página 1
                $this->resetPage(); 
                $message = 'Registro creado correctamente';
            }
            DB::commit();
            
            $this->dispatch('swal:notify', ['message' => $message]);
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // Mostrar error en caso de fallo al guardar
            $this->dispatch('swal:notify', ['message' => 'Ocurrió un error al guardar el registro: ' . $th->getMessage()]);
        }
    }

}