<?php
namespace App\Livewire\Site;

use Livewire\Component;

class AdminPanelController extends Component
{
    public $record_id;
    public $fields = [];   // inputs normales
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
        'color' => 'gray',
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
}
