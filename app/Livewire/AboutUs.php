<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use \App\Models\User;

#[Title('About Me')]

class AboutUs extends Component
{

    public $admin;

    public function mount(){
        $this->admin = User::where('is_admin', 0)->first();
    }


    public function render()
    {
        return view('livewire.about-us');
    }

    public function redirectToContact()
    {
        return $this->redirect('/contact',navigate:true);
    }
}
