<?php

namespace App\Livewire\Site;

use Livewire\Component;

class AttendanceSuccessController extends Component
{
    public function render()
    {
        return view('livewire.admin.attendance_success')
            ->extends('layouts.loginAndRegister')
            ->section('content');
    }

    public function closeWindow()
    {
        // Simplemente dispara un evento JS para cerrar
        $this->dispatch('close-window');
    }
}
