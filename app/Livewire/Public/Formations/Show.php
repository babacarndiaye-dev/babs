<?php

namespace App\Livewire\Public\Formations;

use App\Models\Formation;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public Formation $formation;

    public function mount(string $slug): void
    {
        $this->formation = Formation::with(['formationType', 'domain', 'subjects'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.formations.show');
    }
}
