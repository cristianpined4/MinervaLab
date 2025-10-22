<?php

namespace App\Livewire\Site;

use Livewire\Component;

class EscenaController extends Component
{
    public $record_id;
    public $fields = [];   // inputs normales
    public $file;     

    public function render()
    {
        return view('livewire.site.escena')
            ->extends('layouts.site')
            ->section('content');
    }
}
