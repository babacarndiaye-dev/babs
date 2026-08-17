<?php

namespace App\Livewire\Public\News;

use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public News $news;

    public function mount(string $slug): void
    {
        $this->news = News::where('slug', $slug)->where('status', 'publie')->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.news.show');
    }
}
