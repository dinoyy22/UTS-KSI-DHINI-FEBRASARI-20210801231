<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Home page')]
class Home extends Component
{
    public function render()
    {
        return view('Home');
    }
}
