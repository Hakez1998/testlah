<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class AuthFailed extends Component
{
    public function render()
    {
        return view('livewire.auth.auth-failed')
        ->layout('layouts.guest');
    }
}
