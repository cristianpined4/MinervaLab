<?php

namespace App\Livewire\Site;

use Livewire\Component;
use App\Models\Room;
use App\Models\RoomMantenaince;
use App\Models\Schedule;
use App\Models\Scene;
use App\Models\Reservation;
use App\Models\VrGlasses;
use Carbon\Carbon;

class ReservationController extends Component
{
    public $record_id;
    public $fields = [];
    public $file;
    public $search = '';

    public $total_time = 0;
    public $selected_scenes = [];
    public $numeroEstudiantes = 1;
    public $duracion;
    public $notas;
    public $fecha;
    public $room_id;
    public $horarioSeleccionado;

    public $disponibilidad = [];
    public $warnings = [];
    public $sessions = 1;
    public $vrCount = 0;
    public $maxStudents = 0;

    public function render()
    {
        $rooms = Room::all();
        $scenes = Scene::where('description', 'like', "%{$this->search}%")->get();
        return view('livewire.site.reservation', compact('scenes', 'rooms'))
            ->extends('layouts.site')->section('content');
    }

    public function addScene($id)
    {
        if (!in_array($id, $this->selected_scenes)) {
            $this->selected_scenes[] = $id;
            $this->calcTime();
            $this->dispatch('swal:notify', [
                'message' => 'Escena agregada correctamente',
                'icon' => 'success'
            ]);
        }
    }

    public function removeScene($id)
    {
        $this->selected_scenes = array_values(array_filter($this->selected_scenes, fn($sceneId) => $sceneId != $id));
        $this->calcTime();
    }

    public function calcTime()
    {
        $room = Room::find($this->room_id);
        if( $room != null){
            $vrCount = VrGlasses::where('id_room', $this->room_id)->count();
            $this->vrCount = $vrCount;
            $this->maxStudents = $room->max_students ?? ($vrCount * 2);
        }
        if (empty($this->selected_scenes)) {
            $this->total_time = 0;
            $this->warnings = [];
            $this->getDispose();
            return;
        }

        $this->total_time = (int) Scene::whereIn('id', $this->selected_scenes)->sum('duration');
        $this->warnings = [];
        if( Room::find($this->room_id) != null){
            if($this->numeroEstudiantes > $this->vrCount) {
                $this->total_time *= 2;
                $this->sessions = 2;
            }else{
                $this->sessions = 1;
            }
        }else{
            $this->sessions = 1;
        }

        if ($this->total_time <= 0) {
            $this->warnings[] = 'Error en duración de escenas. Verifica los valores de duración en la base de datos.';
        }

        if ($this->total_time > 270) {
            $this->warnings[] = "La duración total ({$this->total_time} min) excede el máximo continuo permitido (270 min). Considera dividir la sesión.";
        }

        $this->getDispose();
    }

    public function verVideo($id)
    {
        $scene = Scene::find($id);
        if ($scene && $scene->resource_demo) {
            $videoUrl = asset('storage/videos/' . $scene->resource_demo);
            $this->dispatch('abrir-video', [
                'modal' => 'modal-video',
                'videoUrl' => $videoUrl,
                'description' => $scene->description,
            ]);
        } else {
            $this->dispatch('swal:notify', [
                'message' => 'No se encontró el video para esta escena',
                'icon' => 'error'
            ]);
        }
    }

    public function seleccionarHorario($key)
    {
        if (!isset($this->disponibilidad[$key])) {
            return;
        }

        $slot = $this->disponibilidad[$key];

        if (!$slot['disponible']) {
            $this->dispatch('swal:notify', [
                'message' => 'Horario no disponible',
                'icon' => 'warning'
            ]);
            return;
        }

        $slotStart = Carbon::parse($slot['starts_at']);
        $slotEnd = Carbon::parse($slot['ends_at']);
        $availableMinutes = $slotStart->diffInMinutes($slotEnd);

        if ($this->total_time > $availableMinutes) {
            $this->dispatch('swal:notify', [
                'message' => "El tiempo total de escenas ({$this->total_time} min) no cabe en el horario seleccionado ({$availableMinutes} min).",
                'icon' => 'warning'
            ]);
            $this->horarioSeleccionado = null;
            return;
        }

        $starts_at = $slotStart->format('H:i:s');
        $ends_at = $slotStart->copy()->addMinutes($this->total_time)->format('H:i:s');

        $this->horarioSeleccionado = [
            'key' => $key,
            'starts_at' => $starts_at,
            'ends_at' => $ends_at,
            'label' => $slot['hora'],
            'raw_slot' => $slot,
        ];

        $this->dispatch('swal:notify', [
            'message' => 'Horario seleccionado: ' . $slot['hora'],
            'icon' => 'success'
        ]);
    }

    public function getDispose()
    {
        $this->disponibilidad = [];
        $this->horarioSeleccionado = null;

        if (!$this->fecha || !$this->room_id) {
            return;
        }

        $room = Room::find($this->room_id);
        $vrCount = VrGlasses::where('id_room', $this->room_id)->count();
        $maxStudents = $room->max_students ?? ($vrCount * 2);

        $dayOfWeek = date('N', strtotime($this->fecha));
        $schedules = Schedule::where('day', $dayOfWeek)->get();

        $mantenimientos = RoomMantenaince::where('id_room', $this->room_id)
            ->where(function ($query) {
                $query->whereDate('starts_at', '<=', $this->fecha)
                      ->whereDate('ends_at', '>=', $this->fecha);
            })
            ->get();

        $reservas = Reservation::where('id_room', $this->room_id)
            ->whereDate('date', $this->fecha)
            ->whereIn('status', ['pending', 'pendiente', 'confirmed', 'confirmada'])
            ->get();

        $slots = [];

        foreach ($schedules as $schedule) {
            $inicio = Carbon::parse("{$this->fecha} {$schedule->starts_at}");
            $fin = Carbon::parse("{$this->fecha} {$schedule->ends_at}");

            if ($fin->lte($inicio)) {
                continue;
            }

            $segmentos = [[$inicio->copy(), $fin->copy(), true]];

            foreach ($mantenimientos as $m) {
                $m_inicio = Carbon::parse($m->starts_at);
                $m_fin = Carbon::parse($m->ends_at);
                $nuevos = [];

                foreach ($segmentos as [$seg_inicio, $seg_fin, $disponible]) {
                    if (!$disponible || $m_fin <= $seg_inicio || $m_inicio >= $seg_fin) {
                        $nuevos[] = [$seg_inicio, $seg_fin, $disponible];
                        continue;
                    }
                    if ($m_inicio > $seg_inicio) {
                        $nuevos[] = [$seg_inicio, $m_inicio, true];
                    }
                    $nuevos[] = [max($seg_inicio, $m_inicio), min($seg_fin, $m_fin), false];
                    if ($m_fin < $seg_fin) {
                        $nuevos[] = [$m_fin, $seg_fin, true];
                    }
                }
                $segmentos = $nuevos;
            }

            foreach ($reservas as $r) {
                $r_inicio = Carbon::parse("{$this->fecha} {$r->starts_at}");
                $r_fin = Carbon::parse("{$this->fecha} {$r->ends_at}");
                $nuevos = [];

                foreach ($segmentos as [$seg_inicio, $seg_fin, $disponible]) {
                    if (!$disponible || $r_fin <= $seg_inicio || $r_inicio >= $seg_fin) {
                        $nuevos[] = [$seg_inicio, $seg_fin, $disponible];
                        continue;
                    }
                    if ($r_inicio > $seg_inicio) {
                        $nuevos[] = [$seg_inicio, $r_inicio, $disponible];
                    }
                    $nuevos[] = [max($seg_inicio, $r_inicio), min($seg_fin, $r_fin), false];
                    if ($r_fin < $seg_fin) {
                        $nuevos[] = [$r_fin, $seg_fin, $disponible];
                    }
                }
                $segmentos = $nuevos;
            }

            $blocked_by_rest = [];
            foreach ($reservas as $r) {
                $r_inicio = Carbon::parse("{$this->fecha} {$r->starts_at}");
                $r_fin = Carbon::parse("{$this->fecha} {$r->ends_at}");
                $blocked_by_rest[] = [$r_fin->copy(), $r_fin->copy()->addMinutes(120)];
                $blocked_by_rest[] = [$r_inicio->copy()->subMinutes(120), $r_inicio->copy()];
            }

            $final_segments = [];
            foreach ($segmentos as [$seg_inicio, $seg_fin, $disponible]) {
                if ($disponible) {
                    $tmp = [[$seg_inicio, $seg_fin, true]];
                    foreach ($blocked_by_rest as [$b_inicio, $b_fin]) {
                        $new_tmp = [];
                        foreach ($tmp as [$t_inicio, $t_fin, $t_disp]) {
                            if ($b_fin <= $t_inicio || $b_inicio >= $t_fin) {
                                $new_tmp[] = [$t_inicio, $t_fin, $t_disp];
                                continue;
                            }
                            if ($b_inicio > $t_inicio) {
                                $new_tmp[] = [$t_inicio, $b_inicio, true];
                            }
                            $new_tmp[] = [max($t_inicio, $b_inicio), min($t_fin, $b_fin), false];
                            if ($b_fin < $t_fin) {
                                $new_tmp[] = [$b_fin, $t_fin, true];
                            }
                        }
                        $tmp = $new_tmp;
                    }
                    foreach ($tmp as $t) {
                        $final_segments[] = $t;
                    }
                } else {
                    $final_segments[] = [$seg_inicio, $seg_fin, false];
                }
            }

            if (empty($final_segments)) {
                $final_segments = $segmentos;
            }

            foreach ($final_segments as [$seg_inicio, $seg_fin, $disponible]) {
                if ($seg_fin->gt($seg_inicio)) {
                    $slots[] = [
                        'starts' => $seg_inicio->copy(),
                        'ends' => $seg_fin->copy(),
                        'disponible' => $disponible,
                    ];
                }
            }
        }

        $i = 0;
        foreach ($slots as $slot) {
            $label = $slot['starts']->format('h:i A') . ' - ' . $slot['ends']->format('h:i A');
            $restantes = $slot['disponible'] ? 1 : 0;
            $this->disponibilidad[] = [
                'key' => $i,
                'hora' => $label,
                'starts_at' => $slot['starts']->format('Y-m-d H:i:s'),
                'ends_at' => $slot['ends']->format('Y-m-d H:i:s'),
                'disponible' => $slot['disponible'],
                'restantes' => $restantes,
                'max_students' => $maxStudents,
                'vr_count' => $vrCount,
            ];
            $i++;
        }
    }

    public function confirmarReservacion()
    {
        if (empty($this->selected_scenes)) {
            $this->dispatch('swal:notify', [
                'message' => 'Debes seleccionar al menos una escena',
                'icon' => 'warning'
            ]);
            return;
        }

        if (!$this->fecha || !$this->horarioSeleccionado || !$this->room_id) {
            $this->dispatch('swal:notify', [
                'message' => 'Selecciona fecha, sala y horario',
                'icon' => 'warning'
            ]);
            return;
        }

        $room = Room::find($this->room_id);
        $vrCount = VrGlasses::where('id_room', $this->room_id)->count();
        $maxStudents = $room->max_students ?? ($vrCount * 2);

        if ($this->numeroEstudiantes > $maxStudents || $this->numeroEstudiantes > ($vrCount * 2)) {
            $this->dispatch('swal:notify', [
                'message' => 'La sala no tiene capacidad para ese número de estudiantes',
                'icon' => 'warning'
            ]);
            return;
        }

        if ($this->total_time > 270) {
            $this->dispatch('swal:notify', [
                'message' => 'La duración total excede el máximo de uso continuo (270 min). Divide la sesión.',
                'icon' => 'warning'
            ]);
            return;
        }

        $start = Carbon::parse("{$this->fecha} {$this->horarioSeleccionado['starts_at']}");
        $end = Carbon::parse("{$this->fecha} {$this->horarioSeleccionado['ends_at']}");

        $lastBefore = Reservation::where('id_room', $this->room_id)
            ->whereDate('date', $this->fecha)
            ->where('ends_at', '<=', $start->format('H:i:s'))
            ->orderBy('ends_at', 'desc')
            ->first();

        if ($lastBefore) {
            $diffMinutes = $start->diffInMinutes(Carbon::parse("{$this->fecha} {$lastBefore->ends_at}"));
            $lastDuration = Carbon::parse($lastBefore->ends_at)
                ->diffInMinutes(Carbon::parse($lastBefore->starts_at));

            if ($diffMinutes < 120 && $lastDuration >= 270) {
                $this->dispatch('swal:notify', [
                    'message' => 'Debe cumplirse el descanso obligatorio de 2 horas entre sesiones largas.',
                    'icon' => 'warning'
                ]);
                return;
            }
        }

        Reservation::create([
            'id_user' => auth()->id(),
            'id_room' => $this->room_id,
            'date' => $this->fecha,
            'starts_at' => $start->format('H:i:s'),
            'ends_at' => $start->copy()->addMinutes($this->total_time)->format('H:i:s'),
            'time' => $this->total_time,
            'students' => $this->numeroEstudiantes,
            'status' => 0,
        ]);

        $this->dispatch('swal:success', [
            'message' => 'Reservación creada en estado PENDIENTE. Pronto recibirá confirmación.'
        ]);

        $this->selected_scenes = [];
        $this->total_time = 0;
        $this->horarioSeleccionado = null;
        $this->getDispose();
    }

}
