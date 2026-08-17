<div>
    <div class="flex items-center justify-between gap-4 mb-6">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher un enseignant..."
               class="w-full max-w-sm rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">

        <a href="{{ route('admin.teachers.create') }}"
           class="whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvel enseignant
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Enseignant</th>
                    <th class="px-5 py-3 font-medium">Spécialité</th>
                    <th class="px-5 py-3 font-medium">Contact</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($teachers as $teacher)
                    <tr wire:key="teacher-{{ $teacher->id }}">
                        <td class="px-5 py-3.5 font-medium text-ink">{{ $teacher->fullName() }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $teacher->specialty ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $teacher->phone ?? $teacher->email ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $teacher->is_active ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                                {{ $teacher->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-3">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-primary hover:underline font-medium">Modifier</a>
                            <button wire:click="delete({{ $teacher->id }})" wire:confirm="Supprimer cet enseignant ?"
                                    class="text-red-600 hover:underline font-medium">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucun enseignant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $teachers->links() }}</div>
</div>
