<div>
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.finance.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux factures</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-ink text-lg">{{ $invoice->invoice_number }}</h2>
                        <p class="text-sm text-ink/50">{{ $invoice->student->fullName() }} ({{ $invoice->student->matricule }})</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                        {{ $invoice->status === 'paye' ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                        {{ ['en_attente' => 'En attente', 'partiel' => 'Partiel', 'paye' => 'Payé', 'en_retard' => 'En retard', 'annule' => 'Annulé'][$invoice->status] }}
                    </span>
                </div>

                <table class="w-full text-sm mt-6">
                    <thead class="text-left text-ink/50 border-b border-black/5">
                        <tr><th class="pb-2 font-medium">Libellé</th><th class="pb-2 font-medium text-right">Montant</th></tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach ($invoice->lines as $line)
                            <tr>
                                <td class="py-2 text-ink">{{ $line->label }}</td>
                                <td class="py-2 text-right text-ink">{{ number_format((float) $line->amount, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-black/5 font-semibold">
                            <td class="pt-2 text-ink">Total</td>
                            <td class="pt-2 text-right text-ink">{{ number_format((float) $invoice->total_amount, 0, ',', ' ') }} {{ setting('finance.currency') }}</td>
                        </tr>
                        <tr>
                            <td class="pt-1 text-ink/50">Solde restant</td>
                            <td class="pt-1 text-right text-ink/50">{{ number_format($invoice->balance(), 0, ',', ' ') }} {{ setting('finance.currency') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <h3 class="font-semibold text-ink mb-4">Paiements</h3>
                <div class="space-y-2">
                    @forelse ($invoice->payments as $payment)
                        <div wire:key="pay-{{ $payment->id }}" class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-2.5 text-sm">
                            <div>
                                <span class="font-medium text-ink">{{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ setting('finance.currency') }}</span>
                                <span class="text-ink/50">— {{ $payment->paymentMethod->name }} — {{ $payment->paid_at?->format('d/m/Y') }}</span>
                            </div>
                            @if ($payment->receipt)
                                <a href="{{ route('receipts.pdf', $payment) }}" target="_blank" class="text-primary hover:underline font-medium">Reçu PDF</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-ink/50">Aucun paiement enregistré.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            @if ($invoice->balance() > 0)
                <form wire:submit="recordPayment" class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                    <h3 class="font-semibold text-ink">Enregistrer un paiement</h3>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Montant</label>
                        <input type="number" wire:model="amount" step="500" min="0"
                               class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Moyen de paiement</label>
                        <select wire:model="payment_method_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">—</option>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}{{ $method->is_online ? ' (en ligne — interface à venir)' : '' }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Référence (optionnel)</label>
                        <input type="text" wire:model="reference" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                        Enregistrer le paiement
                    </button>
                </form>
            @else
                <div class="rounded-2xl border border-primary/20 bg-primary/5 p-6 text-center text-sm text-primary font-medium">
                    Facture entièrement payée.
                </div>
            @endif
        </div>
    </div>
</div>
