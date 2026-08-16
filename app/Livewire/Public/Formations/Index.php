<?php

namespace App\Livewire\Public\Formations;

use App\Models\Formation;
use App\Models\FormationType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public ?int $type = null;

    public function render()
    {
        return view('livewire.public.formations.index', [
            'formations' => Formation::with('formationType')
                ->where('is_published', true)
                ->when($this->type, fn ($q) => $q->where('formation_type_id', $this->type))
                ->orderBy('title')
                ->paginate(9),
            'types' => FormationType::orderBy('name')->get(),
        ]);
    }
}
