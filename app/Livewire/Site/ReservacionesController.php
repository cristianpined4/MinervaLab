<?php

namespace App\Livewire\Site;

use Livewire\Component;

class ReservacionesController extends Component
{

    public $record_id;
    public $fields = [];   // inputs normales
    public $file;     
    
    public function render()
    {
        return view('livewire.site.reservaciones')
            ->extends('layouts.site')
            ->section('content');
    }
}
