@props(['color' => 'blue', 'icon' => 'chart-bar', 'label', 'value', 'sub' => null])

@php
    $bg = match ($color) {
        'blue' => 'bg-blue-600',
        'green' => 'bg-emerald-600',
        'purple' => 'bg-violet-600',
        'orange' => 'bg-orange-500',
        default => 'bg-primary',
    };
@endphp

<div class="rounded-2xl {{ $bg }} p-5 text-white shadow-sm">
    <div class="flex items-start justify-between">
        <p class="text-sm font-medium text-white/85">{{ $label }}</p>
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15">
            <x-nav-icon :name="$icon" class="h-5 w-5 text-white" />
        </span>
    </div>
    <p class="mt-3 text-3xl font-extrabold tracking-tight">{{ $value }}</p>
    @if ($sub)
        <p class="mt-1 text-xs text-white/75">{{ $sub }}</p>
    @endif
</div>
