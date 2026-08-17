<div>
    <div class="flex items-center justify-between gap-4 mb-6">
        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher une classe..."
               class="w-full max-w-sm rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">

        <a href="{{ route('admin.classes.create') }}"
           class="whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvelle classe
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Classe</th>
                    <th class="px-5 py-3 font-medium">Formation</th>
                    <th class="px-5 py-3 font-medium">Année académique</th>
                    <th class="px-5 py-3 font-medium">Effectif</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($classes as $class)
                    <tr wire:key="class-{{ $class->id }}">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.classes.show', $class) }}" class="font-medium text-ink hover:text-primary">{{ $class->name }}</a>
                        </td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $class->formation->title }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $class->academicYear->name }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $class->students_count }}{{ $class->capacity ? '/'.$class->capacity : '' }}</td>
                        <td class="px-5 py-3.5 text-right space-x-3">
                            <a href="{{ route('admin.classes.edit', $class) }}" class="text-primary hover:underline font-medium">Modifier</a>
                            <button wire:click="delete({{ $class->id }})" wire:confirm="Supprimer cette classe ?"
                                    class="text-red-600 hover:underline font-medium">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucune classe.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $classes->links() }}</div>
</div>
