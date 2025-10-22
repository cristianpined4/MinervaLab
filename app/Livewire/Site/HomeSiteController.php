<?php

namespace App\Livewire\Site;

use Livewire\Component;

class HomeSiteController extends Component
{
    // Propiedades reutilizables
    public $record_id;
    public $fields = [];   // inputs normales
    public $file;     
    
    public function render()
    {
        return view('livewire.site.home-site')
            ->extends('layouts.site')
            ->section('content');
    }
}
