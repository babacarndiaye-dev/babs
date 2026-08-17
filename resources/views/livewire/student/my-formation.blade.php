<div class="max-w-2xl">
    @if (! $class)
        <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Vous n'êtes affecté(e) à aucune classe pour le moment.
        </div>
    @else
        <div class="rounded-2xl border border-black/5 bg-white p-6 mb-6">
            <span class="text-xs font-semibold text-accent uppercase tracking-wide">{{ $class->formation->formationType->name }}</span>
            <h2 class="mt-1 font-semibold text-ink text-lg">{{ $class->formation->title }}</h2>
            <p class="text-sm text-ink/50">{{ $class->name }} &middot; {{ $class->room?->name ?? 'Salle non définie' }}</p>

            <div class="mt-4 grid sm:grid-cols-2 gap-4 text-sm">
                <div><span class="text-ink/50">Durée</span><p class="text-ink">{{ $class->formation->durationLabel() }}</p></div>
                @if ($class->formation->tuition_fee)
                    <div><span class="text-ink/50">Frais de scolarité</span><p class="text-ink">{{ number_format((float) $class->formation->tuition_fee, 0, ',', ' ') }} {{ setting('finance.currency') }}</p></div>
                @endif
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-6">
            <h3 class="font-semibold text-ink mb-4">Matières & enseignants</h3>
            <div class="space-y-2">
                @forelse ($class->subjectAssignments as $assignment)
                    <div wire:key="subj-{{ $assignment->id }}" class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-2.5 text-sm">
                        <span class="font-medium text-ink">{{ $assignment->subject->name }}</span>
                        <span class="text-ink/50">{{ $assignment->teacher->fullName() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink/50">Aucune matière affectée pour le moment.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
