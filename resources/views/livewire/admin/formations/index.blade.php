<div>
    <div class="flex items-center justify-between gap-4 mb-6">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher une formation..."
               class="w-full max-w-sm rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">

        <a href="{{ route('admin.formations.create') }}"
           class="whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvelle formation
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Formation</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Durée</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($formations as $formation)
                    <tr wire:key="formation-{{ $formation->id }}">
                        <td class="px-5 py-3.5 font-medium text-ink">{{ $formation->title }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $formation->formationType->name }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $formation->durationLabel() }}</td>
                        <td class="px-5 py-3.5">
                            <button wire:click="togglePublished({{ $formation->id }})"
                                    class="rounded-full px-3 py-1 text-xs font-semibold {{ $formation->is_published ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                                {{ $formation->is_published ? 'Publiée' : 'Brouillon' }}
                            </button>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-3">
                            <a href="{{ route('admin.formations.edit', $formation) }}" class="text-primary hover:underline font-medium">Modifier</a>
                            <button wire:click="delete({{ $formation->id }})"
                                    wire:confirm="Supprimer cette formation ?"
                                    class="text-red-600 hover:underline font-medium">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucune formation.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $formations->links() }}</div>
</div>
