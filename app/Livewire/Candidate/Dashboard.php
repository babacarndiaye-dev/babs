<?php

namespace App\Livewire\Candidate;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.candidate')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.candidate.dashboard', [
            'applications' => auth()->user()->candidate
                ?->applications()
                ->with('formation')
                ->whereNotNull('submitted_at')
                ->latest()
                ->get() ?? collect(),
        ]);
    }
}
