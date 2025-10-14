<?php

namespace App\Livewire\Site;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Component
{
    use WithPagination, WithFileUploads;
    // archivo temporal
    public $search = '';
    public $paginate = 10;

    public function paginationView()
    {
        return 'vendor.livewire.tailwind';
    }

    public function render()
    {
        /* $query = Home::query();

        if (!empty($this->search)) {
            foreach ((new Home())->getFillable() as $field) {
                $query->orWhere($field, 'like', '%' . $this->search . '%');
            }
        }

        $records = $query->orderBy('id', 'desc')->paginate($this->paginate); */

        return view('livewire.site.home'/* , compact('records') */)
            ->extends('layouts.site')
            ->section('content');
    }
}