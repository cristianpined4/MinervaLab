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
        'link' => 'admin-calendary',
        //'link' => 'admin-auth'
        ],
        [
        'titulo' => 'Usuarios',
        'descripcion' => 'Administrar usuarios, roles, informacion, recuperacion de cuenta y activacion',
        'color' => 'gray',
        'icono' => 'fa-user',
        'link' => 'admin-user'
        ],
        [
        'titulo' => 'Mantenimiento y equipos',
        'descripcion' => 'Mantenimiento de equipos del salon VR y habilitacion',
        'color' => 'blue',
        'icono' => 'fa-gear',
        'link' => 'admin-calendary',
        //'link' => 'admin-mantenaince'
        ],
        [
        'titulo' => 'Escenas VR',
        'descripcion' => 'Gestion de recursos multimedia y escenas VR disponibles',
        'color' => 'purple',
        'icono' => 'fa-play',
        'link' => 'admin-scene',
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
