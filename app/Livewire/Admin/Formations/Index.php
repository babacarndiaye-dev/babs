<?php

namespace App\Livewire\Admin\Formations;

use App\Models\Formation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function togglePublished(Formation $formation): void
    {
        $formation->update(['is_published' => ! $formation->is_published]);
    }

    public function delete(Formation $formation): void
    {
        $formation->delete();

        session()->flash('status', 'Formation supprimée.');
    }

    public function render()
    {
        return view('livewire.admin.formations.index', [
            'formations' => Formation::with('formationType')
                ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
                ->latest()
                ->paginate(15),
        ]);
    }
}
