<div>
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="mb-6 flex flex-wrap gap-3">
        <select wire:model.live="assignmentId" class="rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            @forelse ($assignments as $a)
                <option value="{{ $a->id }}">{{ $a->schoolClass->name }} — {{ $a->subject->name }}</option>
            @empty
                <option value="">Aucune classe affectée</option>
            @endforelse
        </select>
        <input type="date" wire:model.live="date" class="rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
    </div>

    @if ($assignment)
        <form wire:submit="save" class="rounded-2xl border border-black/5 bg-white overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left text-ink/60">
                    <tr>
                        <th class="px-5 py-3 font-medium">Étudiant</th>
                        <th class="px-5 py-3 font-medium">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($assignment->schoolClass->students as $student)
                        <tr wire:key="att-{{ $student->id }}">
                            <td class="px-5 py-3 text-ink">{{ $student->fullName() }}</td>
                            <td class="px-5 py-3">
                                <select wire:model="statuses.{{ $student->id }}" class="rounded-lg border border-black/10 px-3 py-1.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                                    <option value="present">Présent</option>
                                    <option value="absent">Absent</option>
                                    <option value="retard">Retard</option>
                                    <option value="excuse">Excusé</option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-5 py-12 text-center text-ink/50">Aucun étudiant dans cette classe.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @if ($assignment->schoolClass->students->isNotEmpty())
                <div class="p-5 border-t border-black/5">
                    <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                        Enregistrer les présences
                    </button>
                </div>
            @endif
        </form>
    @else
        <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Aucune classe ne vous est affectée pour le moment.
        </div>
    @endif
</div>
