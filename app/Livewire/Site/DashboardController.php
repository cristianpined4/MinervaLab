<?php

namespace App\Livewire\Site;

use Livewire\Component;

class DashboardController extends Component
{
    public $record_id;
    public $fields = [];   // inputs normales
    public $file;     
    
    public function render()
    {
        return view('livewire.site.dashboard')
            ->extends('layouts.site')
            ->section('content');
    }
}
