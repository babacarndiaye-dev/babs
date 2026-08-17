<div class="space-y-6">
    @forelse ($bySubject as $subject => $data)
        <div wire:key="subj-{{ Illuminate\Support\Str::slug($subject) }}" class="rounded-2xl border border-black/5 bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-ink">{{ $subject }}</h3>
                @if ($data['average'] !== null)
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-sm font-semibold text-primary">
                        Moyenne : {{ $data['average'] }}/20
                    </span>
                @endif
            </div>
            <div class="space-y-2">
                @foreach ($data['grades'] as $grade)
                    <div class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-2.5 text-sm">
                        <span class="text-ink">{{ $grade->assessment->title }}</span>
                        <span class="font-medium text-ink">{{ $grade->score }}/{{ $grade->assessment->max_score }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Aucune note disponible pour le moment.
        </div>
    @endforelse
</div>
