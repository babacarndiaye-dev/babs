<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
        @endif

        <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-surface text-left text-ink/60">
                    <tr>
                        <th class="px-5 py-3 font-medium">Évaluation</th>
                        <th class="px-5 py-3 font-medium">Classe</th>
                        <th class="px-5 py-3 font-medium">Date</th>
                        <th class="px-5 py-3 font-medium">Notes saisies</th>
                        <th class="px-5 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse ($assessments as $assessment)
                        <tr wire:key="assessment-{{ $assessment->id }}">
                            <td class="px-5 py-3.5 font-medium text-ink">{{ $assessment->title }}</td>
                            <td class="px-5 py-3.5 text-ink/70">{{ $assessment->schoolClass->name }}</td>
                            <td class="px-5 py-3.5 text-ink/70">{{ $assessment->date?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-ink/70">{{ $assessment->grades_count }} note(s)</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('teacher.assessments.grades', $assessment) }}" class="text-primary hover:underline font-medium">Saisir les notes</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-ink/50">Aucune évaluation créée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <form wire:submit="save" class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
            <h3 class="font-semibold text-ink">Nouvelle évaluation</h3>

            <div>
                <label class="block text-sm font-medium text-ink/80">Classe & matière</label>
                <select wire:model="assignmentId" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($assignments as $a)
                        <option value="{{ $a->id }}">{{ $a->schoolClass->name }} — {{ $a->subject->name }}</option>
                    @endforeach
                </select>
                @error('assignmentId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-ink/80">Type</label>
                <select wire:model="type" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-ink/80">Titre</label>
                <input type="text" wire:model="title" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-ink/80">Date</label>
                    <input type="date" wire:model="date" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Barème</label>
                    <input type="number" wire:model="max_score" min="1" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-ink/80">Coefficient</label>
                <input type="number" wire:model="coefficient" step="0.5" min="0" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>

            <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Créer l'évaluation
            </button>
        </form>
    </div>
</div>
