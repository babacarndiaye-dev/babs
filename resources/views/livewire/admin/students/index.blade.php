<div>
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher (nom, matricule)..."
                   class="w-full max-w-xs rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">

            <select wire:model.live="status" class="rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <option value="">Tous les statuts</option>
                <option value="actif">Actif</option>
                <option value="suspendu">Suspendu</option>
                <option value="diplome">Diplômé</option>
                <option value="abandonne">Abandonné</option>
            </select>

            <select wire:model.live="classId" class="rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <option value="">Toutes les classes</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('admin.students.create') }}"
           class="whitespace-nowrap rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            + Nouvel étudiant
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Étudiant</th>
                    <th class="px-5 py-3 font-medium">Matricule</th>
                    <th class="px-5 py-3 font-medium">Classe</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($students as $student)
                    <tr wire:key="student-{{ $student->id }}">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary text-xs font-semibold">
                                    {{ Illuminate\Support\Str::of($student->first_name)->substr(0, 1) }}{{ Illuminate\Support\Str::of($student->last_name)->substr(0, 1) }}
                                </span>
                                <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-ink hover:text-primary">
                                    {{ $student->fullName() }}
                                </a>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $student->matricule }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $student->currentClass?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $student->status === 'actif' ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-3">
                            <a href="{{ route('admin.students.edit', $student) }}" class="text-primary hover:underline font-medium">Modifier</a>
                            <button wire:click="delete({{ $student->id }})" wire:confirm="Supprimer cet étudiant ?"
                                    class="text-red-600 hover:underline font-medium">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucun étudiant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $students->links() }}</div>
</div>
