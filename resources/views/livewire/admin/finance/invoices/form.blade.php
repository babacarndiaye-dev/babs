<div>
    <div class="mb-6">
        <a href="{{ route('admin.finance.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour à la finance</a>
    </div>

    <form wire:submit="save" class="max-w-3xl space-y-6">
        <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-ink/80">Étudiant</label>
                    <select wire:model="student_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <option value="">—</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->fullName() }} ({{ $student->matricule }})</option>
                        @endforeach
                    </select>
                    @error('student_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Date d'échéance</label>
                    <input type="date" wire:model="due_date" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-6">
            <h3 class="font-semibold text-ink mb-4">Lignes de facturation</h3>

            <div class="space-y-3">
                @foreach ($lines as $index => $line)
                    <div wire:key="line-{{ $index }}" class="grid grid-cols-12 gap-2 items-start">
                        <select wire:model="lines.{{ $index }}.fee_type_id" class="col-span-3 rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">Type</option>
                            @foreach ($feeTypes as $feeType)
                                <option value="{{ $feeType->id }}">{{ $feeType->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model="lines.{{ $index }}.label" placeholder="Libellé"
                               class="col-span-5 rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <input type="number" wire:model="lines.{{ $index }}.amount" placeholder="Montant" min="0" step="500"
                               class="col-span-3 rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <button type="button" wire:click="removeLine({{ $index }})" class="col-span-1 text-red-600 hover:underline text-xs font-medium py-2">
                            Retirer
                        </button>
                        @error('lines.'.$index.'.label') <p class="col-span-12 text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('lines.'.$index.'.amount') <p class="col-span-12 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>

            <button type="button" wire:click="addLine" class="mt-4 text-sm font-medium text-primary hover:underline">
                + Ajouter une ligne
            </button>

            <div class="mt-6 flex items-center justify-between border-t border-black/5 pt-4">
                <span class="font-semibold text-ink">Total : {{ number_format($this->total(), 0, ',', ' ') }} {{ setting('finance.currency') }}</span>
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Créer la facture
                </button>
            </div>
        </div>
    </form>
</div>
