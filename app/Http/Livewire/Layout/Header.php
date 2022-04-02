<?php

namespace App\Http\Livewire\Layout;

use Livewire\Component;

class Header extends Component
{
    public $page;

    public function render()
    {
        return view('livewire.layout.header');
    }

    public function mount()
    {

    }

}
