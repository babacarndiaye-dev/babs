<div class="space-y-6">
    <div class="rounded-2xl border border-black/5 bg-white p-6">
        <p class="text-sm text-ink/50">Solde total à payer</p>
        <p class="mt-1 text-3xl font-semibold text-primary">{{ number_format($totalBalance, 0, ',', ' ') }} {{ setting('finance.currency') }}</p>
    </div>

    @forelse ($invoices as $invoice)
        <div wire:key="inv-{{ $invoice->id }}" class="rounded-2xl border border-black/5 bg-white p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="font-semibold text-ink">{{ $invoice->invoice_number }}</p>
                    <p class="text-xs text-ink/50">
                        Total {{ number_format((float) $invoice->total_amount, 0, ',', ' ') }} {{ setting('finance.currency') }}
                        — Solde {{ number_format($invoice->balance(), 0, ',', ' ') }} {{ setting('finance.currency') }}
                    </p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $invoice->status === 'paye' ? 'bg-primary/10 text-primary' : 'bg-black/5 text-ink/50' }}">
                    {{ ['en_attente' => 'En attente', 'partiel' => 'Partiel', 'paye' => 'Payé', 'en_retard' => 'En retard', 'annule' => 'Annulé'][$invoice->status] }}
                </span>
            </div>

            <div class="space-y-1.5 mb-4">
                @foreach ($invoice->lines as $line)
                    <div class="flex justify-between text-sm text-ink/70">
                        <span>{{ $line->label }}</span>
                        <span>{{ number_format((float) $line->amount, 0, ',', ' ') }}</span>
                    </div>
                @endforeach
            </div>

            @if ($invoice->payments->isNotEmpty())
                <div class="border-t border-black/5 pt-3 space-y-2">
                    @foreach ($invoice->payments as $payment)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-ink/70">{{ $payment->paid_at?->format('d/m/Y') }} — {{ $payment->paymentMethod->name }} — {{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ setting('finance.currency') }}</span>
                            @if ($payment->receipt)
                                <a href="{{ route('receipts.pdf', $payment) }}" target="_blank" class="text-primary hover:underline font-medium">Reçu PDF</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-black/10 bg-white p-12 text-center text-ink/50">
            Aucune facture pour le moment.
        </div>
    @endforelse
</div>
