<?php

namespace App\Livewire;

use App\Models\Cours as ModelsCours;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Courses')]
class Cours extends Component
{
    public $courses = [];

    public function mount()
    {
        $this->courses = ModelsCours::select('titre', 'description', 'content')->get()->map(function($course) {
            // Ensure all fields are properly loaded
            return [
                'titre' => $course->titre,
                'description' => $course->description,
                'content' => $course->content
            ];
        });
    }

    public function render()
    {
        return view('livewire.cours');
    }
}
