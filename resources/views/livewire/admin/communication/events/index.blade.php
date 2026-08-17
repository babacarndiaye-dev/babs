<div>
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.communication.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour à la communication</a>
        <a href="{{ route('admin.communication.events.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvel événement
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Événement</th>
                    <th class="px-5 py-3 font-medium">Lieu</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($events as $event)
                    <tr wire:key="event-{{ $event->id }}">
                        <td class="px-5 py-3.5 font-medium text-ink">{{ $event->title }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $event->location ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $event->start_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $event->status === 'publie' ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                                {{ ['brouillon' => 'Brouillon', 'publie' => 'Publié', 'archive' => 'Archivé'][$event->status] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-3">
                            <a href="{{ route('admin.communication.events.edit', $event) }}" class="text-primary hover:underline font-medium">Modifier</a>
                            <button wire:click="delete({{ $event->id }})" wire:confirm="Supprimer cet événement ?"
                                    class="text-red-600 hover:underline font-medium">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucun événement.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $events->links() }}</div>
</div>
