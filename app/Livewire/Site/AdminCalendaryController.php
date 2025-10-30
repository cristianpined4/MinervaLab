<?php
namespace App\Livewire\Site;

use Livewire\Component;

class AdminCalendaryController extends Component
{
    public $record_id;
    public $fields = [];   // inputs normales
    public $file;

    //
    public function render()
    {
        return view('livewire.admin.admin_calendary')
        ->extends('layouts.site')
        ->section('content');
    }
}
