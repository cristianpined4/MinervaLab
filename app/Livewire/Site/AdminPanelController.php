<?php
namespace App\Livewire\Site;

use Livewire\Component;
use App\Models\AdminAttendance;
use Illuminate\Support\Facades\DB;

class AdminPanelController extends Component
{
    public $record_id;
    public $fields = [
        'key' => '',
    ];   // inputs normales
    public $file;

    public $opciones = [
        [
        'titulo' => 'Horarios',
        'descripcion' => 'Administrar horarios, dias feriados y disponibilidad de reservas.',
        'color' => 'blue',
        'icono' => 'fa-calendar',
        'link' => 'admin-schedule'
        ],
        [
        'titulo' => 'Reservaciones',
        'descripcion' => 'Administrar reservaciones autorizadas, por autorizar y declinación',
        'color' => 'green',
        'icono' => 'fa-lock',
        'link' => 'admin-reservation',
        ],
        [
        'titulo' => 'Usuarios',
        'descripcion' => 'Administrar usuarios, roles, informacion, recuperacion de cuenta y activacion',
        'color' => 'orange',
        'icono' => 'fa-user',
        'link' => 'admin-user'
        ],
        [
        'titulo' => 'Escenas VR',
        'descripcion' => 'Gestion de recursos multimedia y escenas VR disponibles',
        'color' => 'purple',
        'icono' => 'fa-play',
        'link' => 'admin-scene',
        ],
        [
        'titulo' => 'Salas y equipos',
        'descripcion' => 'Administrar informacion de las salas y equipos de Realidad Virtual',
        'color' => 'cyan',
        'icono' => 'fa-vr-cardboard',
        'link' => 'admin-room',
        ],
        [
        'titulo' => 'Mantenimiento y equipos',
        'descripcion' => 'Administracion de Salones, equipos de Realidad Virtual y mantenimiento',
        'color' => 'blue',
        'icono' => 'fa-gear',
        'link' => 'admin-mantenaince'
        ]
    ];

    //
    public function render()
    {
        return view('livewire.admin.dashboard')
        ->extends('layouts.site')
        ->section('content');
    }
    //Modales
    public function abrirModal()
    {
        $this->resetErrorBag();

        $registro = AdminAttendance::get(['id','key'])->first();
        $this->fields['key'] = $registro->key;
        $this->record_id = $registro->id;
        $this->dispatch('abrir-modal', [
            'modal' => 'modal-home',
            'fields' => $this->fields
        ]);
    }

    public function store_update()
    {
        $this->validate([
            'fields.key' => 'required|min:10',
        ],
        [
            'fields.key.required' => 'Ingrese una clave valida',
            'fields.key.min' => 'La clave debe tener al menos 10 caracteres',
        ]
        );
        try {
            DB::beginTransaction();
            AdminAttendance::find($this->record_id)->update($this->fields);
            $this->dispatch('swal:notify', ['message' => 'Clave actualizada']);
            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
            //$this->dispatchBrowserEvent('reload-delay');
        } catch (\Throwable $th) {
            return json_encode(['status' => 'error', 'message' => 'Ocurrio un error al guardar el registro' . $th->getMessage()]);
        }
    }
}
