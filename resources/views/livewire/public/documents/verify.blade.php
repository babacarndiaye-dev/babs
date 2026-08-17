<div class="mx-auto max-w-lg px-6 py-16">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-ink">Vérification de document</h1>
        <p class="mt-2 text-sm text-ink/60">Entrez la référence figurant sur le document ou scannez son QR code.</p>
    </div>

    <form wire:submit="search" class="mt-8 flex gap-3">
        <input type="text" wire:model="reference" placeholder="CERT-2026-0001"
               class="flex-1 rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            Vérifier
        </button>
    </form>
    @error('reference') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

    @if ($searched)
        <div class="mt-8">
            @if ($result)
                <div class="rounded-2xl border-2 border-primary/30 bg-primary/5 p-6 text-center">
                    <span class="inline-block rounded-full bg-primary px-4 py-1.5 text-xs font-semibold text-white">
                        ✓ Document authentique
                    </span>
                    <div class="mt-5 grid grid-cols-2 gap-3 text-left text-sm">
                        <div><span class="text-ink/50">Type</span><p class="text-ink font-medium">{{ $result->template->name }}</p></div>
                        <div><span class="text-ink/50">Référence</span><p class="text-ink font-medium">{{ $result->reference }}</p></div>
                        <div><span class="text-ink/50">Titulaire</span><p class="text-ink font-medium">{{ $result->documentable?->fullName() }}</p></div>
                        <div><span class="text-ink/50">Établissement</span><p class="text-ink font-medium">{{ setting('identity.name') }}</p></div>
                        <div><span class="text-ink/50">Date d'émission</span><p class="text-ink font-medium">{{ $result->issued_at?->format('d/m/Y') }}</p></div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border-2 border-red-200 bg-red-50 p-6 text-center">
                    <span class="inline-block rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold text-white">
                        ✕ Document introuvable
                    </span>
                    <p class="mt-4 text-sm text-red-700">Aucun document ne correspond à cette référence. Vérifiez la saisie ou contactez l'établissement.</p>
                </div>
            @endif
        </div>
    @endif
</div>
