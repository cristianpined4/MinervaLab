<?php
namespace App\Livewire\Site;

use App\Models\Room;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class AdminRoomController extends Component
{
    public $record_id;
    public $fields = [
        'description' => null,
        'max_students' => null,
        'status' => null
    ];

    protected $listeners = ['erase' => 'erase'];
    public $search = '';
    public $paginate = 10;

    public function render()
    {
        $query = Room::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach ((new Room())->getFillable() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($this->paginate);

        return view('livewire.admin.admin_room', compact('data'))
            ->extends('layouts.site')
            ->section('content');
    }

    public function estado($id)
    {
        $room = Room::find($id);
        $room->status = !$room->status;
        $room->save();
        $this->dispatch('swal:notify', ['message' => 'Estado actualizado']);
    }
    public function abrirModal($id = null)
    {
        $this->resetErrorBag();

        if ($id) {
            $this->record_id = $id;
            $registro = Room::find($id);
            $this->fields['description'] = $registro->description;
            $this->fields['max_students'] = $registro->max_students;
            $this->fields['status'] = $registro->status;
        } else {
            $this->record_id = null;
            $this->fields = [
                'description' => null,
                'max_students' => null,
                'status' => null
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
            'text' => '¿Estás seguro de eliminar este salón?',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
        ]);
    }

    public function erase($id)
    {
        Room::find($id)?->delete();
        $this->dispatch('swal:notify', ['message' => 'Salón eliminado correctamente']);
    }

    public function store_update()
    {
        $this->validate([
            'fields.description' => 'required|string|max:80',
            'fields.max_students' => 'required|integer|min:1',
            'fields.status' => 'required|boolean',
        ], [
            'fields.description.required' => 'Ingrese una descripción válida',
            'fields.max_students.required' => 'Ingrese un número válido de estudiantes',
            'fields.status.required' => 'Seleccione un estado',
        ]);

        try {
            DB::beginTransaction();
            if ($this->record_id != null) {
                Room::find($this->record_id)->update($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Salón actualizado correctamente']);
            } else {
                Room::create($this->fields);
                $this->dispatch('swal:notify', ['message' => 'Salón creado correctamente']);
            }
            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return json_encode(['status' => 'error', 'message' => 'Ocurrió un error al guardar el registro']);
        }
    }

    public function abrirEquiposVr($id)
    {
        return redirect()->route('admin-vr-glasses', ['id' => $id]);
    }
}
