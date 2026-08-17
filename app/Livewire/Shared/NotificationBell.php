<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead(string $id): void
    {
        $notification = auth()->user()->notifications()->whereKey($id)->first();
        $notification?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.shared.notification-bell', [
            'notifications' => auth()->user()->notifications()->latest()->take(8)->get(),
            'unreadCount' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
