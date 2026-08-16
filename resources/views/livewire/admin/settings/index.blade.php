<div>
    <div class="mb-6 flex flex-wrap gap-2">
        @foreach ($groups as $key => $label)
            <button wire:click="$set('activeGroup', '{{ $key }}')"
                    class="rounded-full px-4 py-2 text-sm font-medium transition {{ $activeGroup === $key ? 'bg-primary text-white' : 'bg-white border border-black/10 text-ink/70 hover:bg-surface' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <form wire:submit="save" class="rounded-2xl border border-black/5 bg-white p-6 space-y-5 max-w-2xl">
        @forelse ($settings as $setting)
            @php $local = Illuminate\Support\Str::after($setting->key, '.'); @endphp
            <div>
                <label class="block text-sm font-medium text-ink/80">{{ $setting->label ?? $setting->key }}</label>

                @if ($setting->type === 'boolean')
                    <label class="mt-2 flex items-center gap-2 text-sm text-ink/70">
                        <input type="checkbox" wire:model="values.{{ $activeGroup }}.{{ $local }}" value="1"
                               class="rounded border-black/20 text-primary focus:ring-primary">
                        Activé
                    </label>
                @elseif (str_starts_with($setting->key, 'design.color_'))
                    <div class="mt-1.5 flex items-center gap-3">
                        <input type="color" wire:model="values.{{ $activeGroup }}.{{ $local }}"
                               class="h-10 w-14 rounded-lg border border-black/10 p-1">
                        <input type="text" wire:model="values.{{ $activeGroup }}.{{ $local }}"
                               class="flex-1 rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                @else
                    <input type="text" wire:model="values.{{ $activeGroup }}.{{ $local }}"
                           class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @endif
            </div>
        @empty
            <p class="text-sm text-ink/50">Aucun paramètre dans ce groupe.</p>
        @endforelse

        @if ($settings->isNotEmpty())
            <button type="submit"
                    class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Enregistrer
            </button>
        @endif
    </form>
</div>
