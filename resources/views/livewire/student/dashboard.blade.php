<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-kpi-card label="Moyenne générale" :value="$generalAverage !== null ? $generalAverage.'/20' : '—'" />
    <x-kpi-card label="Taux de présence" :value="$presenceRate !== null ? $presenceRate.'%' : '—'" />
    <x-kpi-card label="Solde à payer" :value="number_format($balance, 0, ',', ' ').' '.setting('finance.currency')" />
    <x-kpi-card label="Statut" :value="ucfirst($student?->status ?? '—')" />
    <x-kpi-card label="Ma formation" :value="$student?->currentClass?->formation?->title ?? '—'" />
    <x-kpi-card label="Ma classe" :value="$student?->currentClass?->name ?? '—'" />
</div>
