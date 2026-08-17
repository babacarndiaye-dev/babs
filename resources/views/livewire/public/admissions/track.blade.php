<div class="mx-auto max-w-lg px-6 py-16">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-ink">Suivre ma candidature</h1>
        <p class="mt-2 text-sm text-ink/60">Entrez votre numéro de dossier et l'email utilisé lors de votre candidature.</p>
    </div>

    <form wire:submit="search" class="mt-8 space-y-4 rounded-2xl border border-black/5 bg-white p-6">
        <div>
            <label class="block text-sm font-medium text-ink/80">Numéro de dossier</label>
            <input type="text" wire:model="application_number" placeholder="CAND-2026-0001"
                   class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            @error('application_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-ink/80">Email</label>
            <input type="email" wire:model="email"
                   class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            Vérifier
        </button>
    </form>

    @if ($searched)
        <div class="mt-6">
            @if ($result)
                <div class="rounded-2xl border border-black/5 bg-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-ink">{{ $result->formation->title }}</p>
                            <p class="text-xs text-ink/50">N° {{ $result->application_number }}</p>
                        </div>
                        <x-application-status-badge :status="$result->status" />
                    </div>

                    @if ($result->statusHistory->isNotEmpty())
                        <ul class="mt-5 space-y-3 border-t border-black/5 pt-4">
                            @foreach ($result->statusHistory as $entry)
                                <li class="text-sm">
                                    <span class="text-ink/40">{{ $entry->created_at->format('d/m/Y') }}</span>
                                    — <span class="text-ink/70">{{ $entry->comment ?? $entry->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @else
                <p class="text-center text-sm text-red-600">Aucune candidature trouvée avec ces informations.</p>
            @endif
        </div>
    @endif
</div>
