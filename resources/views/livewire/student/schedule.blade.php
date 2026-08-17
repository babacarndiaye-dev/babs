<div class="space-y-6">
    @foreach ($days as $n => $label)
        @if ($schedulesByDay->has($n))
            <div wire:key="day-{{ $n }}">
                <h3 class="text-sm font-semibold text-ink/50 uppercase tracking-wide mb-3">{{ $label }}</h3>
                <div class="space-y-2">
                    @foreach ($schedulesByDay[$n] as $schedule)
                        <div class="flex items-center justify-between rounded-xl border border-black/5 bg-white px-5 py-3.5">
                            <div>
                                <p class="font-medium text-ink">{{ $schedule->subject->name }}</p>
                                <p class="text-xs text-ink/50">{{ $schedule->teacher->fullName() }} &middot; {{ $schedule->room?->name ?? 'Salle non définie' }}</p>
                            </div>
                            <span class="text-sm font-medium text-primary">{{ substr($schedule->start_time, 0, 5) }}–{{ substr($schedule->end_time, 0, 5) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    @if ($schedulesByDay->isEmpty())
        <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Aucun créneau planifié pour le moment.
        </div>
    @endif
</div>
