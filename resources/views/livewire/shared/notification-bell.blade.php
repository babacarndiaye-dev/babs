<div class="relative" x-data="{ open: false }" @click.outside="open = false" wire:poll.30s>
    <button @click="open = ! open" class="relative flex h-9 w-9 items-center justify-center rounded-full text-ink/60 hover:bg-surface transition">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-accent px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak x-transition
         class="absolute right-0 z-50 mt-2 w-80 rounded-2xl border border-black/5 bg-white shadow-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-black/5 px-4 py-3">
            <span class="text-sm font-semibold text-ink">Notifications</span>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs font-medium text-primary hover:underline">Tout marquer comme lu</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-black/5">
            @forelse ($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}"
                   wire:click="markAsRead('{{ $notification->id }}')"
                   class="block px-4 py-3 text-sm hover:bg-surface transition {{ $notification->read_at ? '' : 'bg-primary/5' }}">
                    <p class="font-medium text-ink">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <p class="mt-0.5 text-ink/60 text-xs">{{ $notification->data['body'] ?? '' }}</p>
                    <p class="mt-1 text-ink/40 text-[11px]">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p class="px-4 py-8 text-center text-sm text-ink/50">Aucune notification.</p>
            @endforelse
        </div>
    </div>
</div>
