@props(['status'])

@php
    $map = [
        'nouveau' => ['Nouveau', 'bg-blue-100 text-blue-700'],
        'en_etude' => ['En étude', 'bg-amber-100 text-amber-700'],
        'incomplet' => ['Incomplet', 'bg-orange-100 text-orange-700'],
        'pieces_complementaires_demandees' => ['Pièces complémentaires demandées', 'bg-orange-100 text-orange-700'],
        'admis' => ['Admis', 'bg-primary/10 text-primary'],
        'refuse' => ['Refusé', 'bg-red-100 text-red-700'],
        'inscrit' => ['Inscrit', 'bg-primary text-white'],
    ];
    [$label, $classes] = $map[$status] ?? [$status, 'bg-black/5 text-ink/60'];
@endphp

<span {{ $attributes->merge(['class' => "inline-block rounded-full px-3 py-1 text-xs font-semibold $classes"]) }}>
    {{ $label }}
</span>
