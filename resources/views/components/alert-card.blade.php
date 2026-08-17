@props(['color' => 'red', 'icon' => 'bell', 'label', 'value', 'sub' => null])

@php
    $tint = match ($color) {
        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'value' => 'text-red-600'],
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'value' => 'text-blue-600'],
        'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'value' => 'text-amber-600'],
        'green' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'value' => 'text-emerald-600'],
        default => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'value' => 'text-primary'],
    };
@endphp

<div class="rounded-2xl border border-black/5 bg-white p-5 text-center">
    <span class="mx-auto flex h-9 w-9 items-center justify-center rounded-full {{ $tint['bg'] }}">
        <x-nav-icon :name="$icon" class="h-5 w-5 {{ $tint['text'] }}" />
    </span>
    <p class="mt-2 text-sm font-medium text-ink/70">{{ $label }}</p>
    <p class="mt-1 text-2xl font-extrabold {{ $tint['value'] }}">{{ $value }}</p>
    @if ($sub)
        <p class="text-xs text-ink/45">{{ $sub }}</p>
    @endif
</div>
