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
            'descripcion' => 'Listado de reservaciones realizadas en el mes actual',
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

    private function getStatusByCode($code)
    {
        $code = (string) $code;
        return match ($code) {
            '0' => 'Pendiente',
            '1' => 'Aprobada',
            '2' => 'Rechazada',
            '3' => 'Cancelada',
            '4' => 'Perdida',
            default => 'Desconocido',
        };
    }

    public function getReservations()
    {
        $data = Reservation::with(['HasUser', 'HasRoom'])
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($r) {
                return [
                    'usuario' => $r->HasUser?->first_name . ' ' . $r->HasUser?->last_name,
                    'sala' => $r->HasRoom?->description,
                    'fecha' => formatDate($r->date),
                    'inicio' => formatTime($r->starts_at),
                    'fin' => formatTime($r->ends_at),
                    'estado' => $this->getStatusByCode($r->status),
                ];
            })
            ->toArray();


        $pdf = (new FPDFF())
            ->setTitle('Reporte de Reservaciones')
            ->setSubTitle('Reporte general')
            ->setDate(formatDateTime(now()))
            ->setModelColumns(['usuario', 'sala', 'fecha', 'inicio', 'fin', 'estado'])
            ->setColumnLabels([
                'usuario' => 'Usuario',
                'sala' => 'Salon',
                'fecha' => 'Fecha',
                'inicio' => 'Comenzo',
                'fin' => 'Termino',
                'estado' => 'Estado',
            ])
            ->setColumnWidths([50, 35, 25, 25, 25, 25])
            ->build($data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'reservaciones_' . now()->format('Y-m-d') . '.pdf', [
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
                    'telefono' => $u->phone,
                    'rol' => $u->Rol?->name ?? 'Sin rol',
                    'facultad' => $u->Faculty?->description ?? 'Sin facultad',
                    'estado' => $u->active ? 'Activo' : 'Inactivo',
                ];
            })
            ->toArray();

        $pdf = (new FPDFF())
            ->setTitle('Reporte de Usuarios')
            ->setSubTitle('Usuarios por estado y rol')
            ->setDate(formatDateTime(now()))
            ->setModelColumns(['usuario', 'telefono', 'rol', 'facultad', 'estado'])
            ->setColumnLabels([
                'usuario' => 'Usuario',
                'telefono' => 'Teléfono',
                'rol' => 'Rol',
                'facultad' => 'Facultad',
                'estado' => 'Estado',
            ])
            ->setColumnWidths([50, 25, 25, 65, 20])
            ->build($data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'usuarios_' . now()->format('Y-m-d') . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

}