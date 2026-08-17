<?php

namespace App\Livewire\Public\News;

use App\Models\Event;
use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.public.news.index', [
            'newsItems' => News::where('status', 'publie')->latest('published_at')->paginate(9),
            'upcomingEvents' => Event::where('status', 'publie')->where('start_at', '>=', now())->orderBy('start_at')->take(4)->get(),
        ]);
    }
}
