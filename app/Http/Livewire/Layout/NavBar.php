<?php

namespace App\Http\Livewire\Layout;

use Livewire\Component;

class NavBar extends Component
{
    public $page, $projectId, $tab;

    public function render()
    {
        return view('livewire.layout.nav-bar');
    }

    public function mount()
    {

    }

}
