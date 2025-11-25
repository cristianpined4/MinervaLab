<?php
namespace App\Livewire\Site;

use Livewire\Component;
use App\Models\AdminAttendance;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\FPDFF;

class ReportController extends Component
{
    public $record_id;
    public $fields = [
        'key' => '',
    ];   // inputs normales
    public $file;

    public $opciones = [
        [
        'titulo' => 'Reporte de Reservaciones General',
        'descripcion' => 'Listado de reservaciones realizadas',
        'color' => 'green',
        'icono' => 'fa-calendar',
        'fn' => 'getReservations('
        ],
        [
        'titulo' => 'Reporte de Usuarios General',
        'descripcion' => 'Listado de usuarios por facultad',
        'color' => 'blue',
        'icono' => 'fa-user',
        'fn' => 'getUsersReport('
        ]
    ];

    //
    public function render()
    {
        return view('livewire.admin.reports')
        ->extends('layouts.site')
        ->section('content');
    }

    public function getReservations()
    {
        $data = Reservation::with(['HasUser', 'HasRoom'])
        ->get()
        ->map(function ($r) {
            return [
                'usuario'   => $r->HasUser?->first_name . ' ' . $r->HasUser?->last_name,
                'sala'      => $r->HasRoom?->description,
                'fecha'     => $r->date,
                'inicio'    => $r->starts_at,
                'fin'       => $r->ends_at,
                'estado'    => $r->status,
            ];
        })
        ->toArray();


        $pdf = (new FPDFF())
            ->setTitle('Reporte de Reservaciones')
            ->setSubTitle('Reporte general')
            ->setDate(now()->format('Y-m-d'))
            ->setModelColumns(['usuario','sala','fecha','inicio','fin','estado'])
            ->setColumnLabels([
                'usuario'   => 'Usuario',
                'sala'      => 'Salon',
                'fecha'     => 'Fecha',
                'inicio'    => 'Comenzo',
                'fin'       => 'Termino',
                'estado'    => 'Estado',
            ])
            ->setColumnWidths([50,35,25,25,25,25])
            ->build($data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'reservaciones.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
    public function getUsersReport()
{
    $data = User::with(['Rol', 'Faculty'])
        ->get()
        ->map(function ($u) {
            return [
                'usuario' => $u->first_name . ' ' . $u->last_name,
                'telefono'=> $u->phone,
                'rol'     => $u->Rol?->name ?? 'Sin rol',
                'facultad'=> $u->Faculty?->description ?? 'Sin facultad',
                'estado'  => $u->active ? 'Activo' : 'Inactivo',
            ];
        })
        ->toArray();

    $pdf = (new FPDFF())
        ->setTitle('Reporte de Usuarios')
        ->setSubTitle('Usuarios por estado y rol')
        ->setDate(now()->format('Y-m-d'))
        ->setModelColumns(['usuario','telefono','rol','facultad','estado'])
        ->setColumnLabels([
            'usuario'  => 'Usuario',
            'telefono' => 'Teléfono',
            'rol'      => 'Rol',
            'facultad' => 'Facultad',
            'estado'   => 'Estado',
        ])
        ->setColumnWidths([35,25,25,70,30])
        ->build($data);

    return response()->streamDownload(function () use ($pdf) {
        echo $pdf;
    }, 'usuarios.pdf', [
        'Content-Type' => 'application/pdf',
    ]);
}

}
