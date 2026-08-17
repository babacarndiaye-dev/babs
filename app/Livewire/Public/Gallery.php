<?php

namespace App\Livewire\Public;

use App\Models\GalleryCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Gallery extends Component
{
    public function render()
    {
        return view('livewire.public.gallery', [
            'categories' => GalleryCategory::with('items')->get()->filter(fn ($c) => $c->items->isNotEmpty()),
        ]);
    }
}
