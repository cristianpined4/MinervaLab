<?php
namespace App\Livewire\Site;

use Livewire\Component;
use App\Models\AdminAttendance;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Room;
use App\Models\VrGlasses;
use App\Models\Scene;
use Illuminate\Support\Facades\DB;

class AdminPanelController extends Component
{
    public $record_id;
    public $fields = [
        'key' => '',
    ];
    public $file;

    public $opciones = [
        [
            'titulo' => 'Horarios',
            'descripcion' => 'Administrar horarios, días feriados y disponibilidad de reservas.',
            'color' => 'blue',
            'icono' => 'fa-clock',
            'link' => 'admin-schedule',
        ],
        [
            'titulo' => 'Reservaciones',
            'descripcion' => 'Administrar reservaciones autorizadas, por autorizar y declinadas.',
            'color' => 'green',
            'icono' => 'fa-calendar-check',
            'link' => 'admin-reservation',
        ],
        [
            'titulo' => 'Usuarios',
            'descripcion' => 'Administrar usuarios, roles, información y activación de cuentas.',
            'color' => 'orange',
            'icono' => 'fa-users',
            'link' => 'admin-user',
        ],
        [
            'titulo' => 'Escenas VR',
            'descripcion' => 'Gestión de recursos multimedia y escenas VR disponibles.',
            'color' => 'purple',
            'icono' => 'fa-vr-cardboard',
            'link' => 'admin-scene',
        ],
        [
            'titulo' => 'Salas y equipos',
            'descripcion' => 'Administrar información de las salas y equipos de Realidad Virtual.',
            'color' => 'cyan',
            'icono' => 'fa-door-open',
            'link' => 'admin-room',
        ],
        [
            'titulo' => 'Mantenimiento',
            'descripcion' => 'Administración de salones, equipos VR y registro de mantenimientos.',
            'color' => 'rose',
            'icono' => 'fa-screwdriver-wrench',
            'link' => 'admin-mantenaince',
        ],
        [
            'titulo' => 'Reportes',
            'descripcion' => 'Generación de reportes de uso, asistencia y reservaciones.',
            'color' => 'indigo',
            'icono' => 'fa-chart-bar',
            'link' => 'admin-report',
        ],
        [
            'titulo' => 'Noticias',
            'descripcion' => 'Gestionar noticias, imágenes y videos del sitio principal.',
            'color' => 'teal',
            'icono' => 'fa-newspaper',
            'link' => 'admin-news',
        ],
    ];

    public function render()
    {
        $stats = [
            'usuarios' => User::count(),
            'reservaciones' => Reservation::count(),
            'pendientes' => Reservation::where('status', 0)->count(),
            'salas' => Room::count(),
            'escenas' => Scene::count(),
            'lentes' => VrGlasses::whereNull('deleted_at')->count(),
        ];

        return view('livewire.admin.dashboard', compact('stats'))
            ->extends('layouts.site')
            ->section('content');
    }

    public function abrirModal()
    {
        $this->resetErrorBag();

        // firstOrCreate evita el crash cuando la tabla está vacía
        $registro = AdminAttendance::firstOrCreate([], ['key' => 'CLAVE-INICIAL-123']);

        $this->fields['key'] = $registro->key;
        $this->record_id = $registro->id;

        $this->dispatch('abrir-modal', [
            'modal' => 'modal-home',
            'fields' => $this->fields,
        ]);
    }

    public function store_update()
    {
        $this->validate(
            ['fields.key' => 'required|min:10'],
            [
                'fields.key.required' => 'Ingrese una clave válida',
                'fields.key.min' => 'La clave debe tener al menos 10 caracteres',
            ]
        );

        try {
            DB::beginTransaction();

            if ($this->record_id) {
                AdminAttendance::find($this->record_id)->update($this->fields);
            } else {
                $nuevo = AdminAttendance::create($this->fields);
                $this->record_id = $nuevo->id;
            }

            $this->dispatch('swal:notify', ['message' => 'Clave actualizada correctamente']);
            DB::commit();
            $this->dispatch('cerrar-modal', ['modal' => 'modal-home']);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('swal:notify', ['message' => 'Error al guardar: ' . $th->getMessage(), 'icon' => 'error']);
        }
    }
}
