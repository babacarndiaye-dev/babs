<?php

namespace App\Livewire\Admin\Communication\Events;

use App\Models\Event;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public function delete(Event $event): void
    {
        $event->delete();

        session()->flash('status', 'Événement supprimé.');
    }

    public function render()
    {
        return view('livewire.admin.communication.events.index', [
            'events' => Event::orderByDesc('start_at')->paginate(15),
        ]);
    }
}
