<div>
    <div class="mb-6">
        <a href="{{ route('teacher.assessments.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux évaluations</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="mb-6 rounded-2xl border border-black/5 bg-white p-6">
        <h2 class="font-semibold text-ink text-lg">{{ $assessment->title }}</h2>
        <p class="text-sm text-ink/50">{{ $assessment->schoolClass->name }} &middot; {{ $assessment->subject->name }} &middot; Barème /{{ $assessment->max_score }}</p>
    </div>

    <form wire:submit="save" class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">Étudiant</th>
                    <th class="px-5 py-3 font-medium">Note / {{ $assessment->max_score }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($assessment->schoolClass->students as $student)
                    <tr wire:key="grade-{{ $student->id }}">
                        <td class="px-5 py-3 text-ink">{{ $student->fullName() }}</td>
                        <td class="px-5 py-3">
                            <input type="number" step="0.25" min="0" max="{{ $assessment->max_score }}"
                                   wire:model="scores.{{ $student->id }}"
                                   class="w-24 rounded-lg border border-black/10 px-3 py-1.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-5 py-12 text-center text-ink/50">Aucun étudiant dans cette classe.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if ($assessment->schoolClass->students->isNotEmpty())
            <div class="p-5 border-t border-black/5">
                @error('scores.*') <p class="mb-3 text-xs text-red-600">{{ $message }}</p> @enderror
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Enregistrer les notes
                </button>
            </div>
        @endif
    </form>
</div>
