<?php

namespace App\Livewire\Admin\Communication\News;

use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public function delete(News $news): void
    {
        $news->delete();

        session()->flash('status', 'Actualité supprimée.');
    }

    public function render()
    {
        return view('livewire.admin.communication.news.index', [
            'newsItems' => News::latest()->paginate(15),
        ]);
    }
}
