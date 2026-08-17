<div>
    <div class="grid sm:grid-cols-2 gap-4 mb-6">
        <x-kpi-card label="Total facturé" :value="number_format((float) $kpis['billed'], 0, ',', ' ').' '.setting('finance.currency')" />
        <x-kpi-card label="Factures en attente" :value="$kpis['outstanding']" />
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Rechercher (n° facture, étudiant)..."
                   class="w-full max-w-xs rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            <select wire:model.live="status" class="rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <option value="">Tous les statuts</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.finance.fee-types.index') }}" class="rounded-lg border border-black/10 px-4 py-2.5 text-sm font-medium text-ink/70 hover:bg-surface transition">
                Types de frais
            </a>
            <a href="{{ route('admin.finance.invoices.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                + Nouvelle facture
            </a>
        </div>
    </div>

    <div class="rounded-2xl border border-black/5 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-surface text-left text-ink/60">
                <tr>
                    <th class="px-5 py-3 font-medium">N° Facture</th>
                    <th class="px-5 py-3 font-medium">Étudiant</th>
                    <th class="px-5 py-3 font-medium">Montant</th>
                    <th class="px-5 py-3 font-medium">Solde</th>
                    <th class="px-5 py-3 font-medium">Statut</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($invoices as $invoice)
                    <tr wire:key="invoice-{{ $invoice->id }}">
                        <td class="px-5 py-3.5 font-medium text-ink">{{ $invoice->invoice_number }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ $invoice->student->fullName() }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ number_format((float) $invoice->total_amount, 0, ',', ' ') }}</td>
                        <td class="px-5 py-3.5 text-ink/70">{{ number_format($invoice->balance(), 0, ',', ' ') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold
                                {{ $invoice->status === 'paye' ? 'bg-primary/10 text-primary' : ($invoice->status === 'en_retard' ? 'bg-red-100 text-red-700' : 'bg-black/5 text-ink/50') }}">
                                {{ $statuses[$invoice->status] ?? $invoice->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ route('admin.finance.invoices.show', $invoice) }}" class="text-primary hover:underline font-medium">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-ink/50">Aucune facture.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $invoices->links() }}</div>
</div>
