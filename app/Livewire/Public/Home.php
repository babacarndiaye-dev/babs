<?php

namespace App\Livewire\Public;

use App\Models\Formation;
use App\Models\Student;
use App\Models\Teacher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Home extends Component
{
    #[Computed]
    public function formations()
    {
        return Formation::with('formationType')
            ->where('is_published', true)
            ->latest()
            ->take(4)
            ->get();
    }

    #[Computed]
    public function heroFormations()
    {
        return $this->formations->take(3);
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'students' => Student::count(),
            'formations' => Formation::where('is_published', true)->count(),
            'teachers' => Teacher::count(),
            'success_rate' => (int) setting('academic.success_rate_display', 90),
        ];
    }

    public function render()
    {
        return view('livewire.public.home');
    }
}
