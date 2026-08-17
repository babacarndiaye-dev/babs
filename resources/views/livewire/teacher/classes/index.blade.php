<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($assignments as $classId => $group)
        @php $class = $group->first()->schoolClass; @endphp
        <div wire:key="class-{{ $classId }}" class="rounded-2xl border border-black/5 bg-white p-5">
            <p class="font-semibold text-ink">{{ $class->name }}</p>
            <p class="text-xs text-ink/50">{{ $class->formation->title }}</p>
            <div class="mt-3 flex flex-wrap gap-1.5">
                @foreach ($group as $assignment)
                    <span class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">{{ $assignment->subject->name }}</span>
                @endforeach
            </div>
        </div>
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Aucune classe ne vous est affectée pour le moment.
        </div>
    @endforelse
</div>
