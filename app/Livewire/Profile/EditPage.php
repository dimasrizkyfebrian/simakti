<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Edit Profil')]

class EditPage extends Component
{
    public function render(): View
    {
        return view('livewire.profile.edit-page', [
            'user' => Auth::user(),
        ]);
    }
}
