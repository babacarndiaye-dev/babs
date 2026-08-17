<div class="max-w-2xl">
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-black/5 bg-white p-6 mb-6">
        <div class="flex items-center gap-4">
            @if ($student->getFirstMediaUrl('photo'))
                <img src="{{ $student->getFirstMediaUrl('photo') }}" class="h-16 w-16 rounded-full object-cover">
            @else
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary text-xl font-semibold">
                    {{ Illuminate\Support\Str::of($student->first_name)->substr(0, 1) }}{{ Illuminate\Support\Str::of($student->last_name)->substr(0, 1) }}
                </span>
            @endif
            <div>
                <h2 class="font-semibold text-ink text-lg">{{ $student->fullName() }}</h2>
                <p class="text-sm text-ink/50">{{ $student->matricule }}</p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
        <h3 class="font-semibold text-ink">Mes informations</h3>

        <div>
            <label class="block text-sm font-medium text-ink/80">Photo</label>
            <input type="file" wire:model="photo" class="mt-1.5 w-full text-sm">
            @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink/80">Téléphone</label>
                <input type="text" wire:model="phone" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Email</label>
                <input type="email" wire:model="email" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-ink/80">Adresse</label>
                <input type="text" wire:model="address" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Nom du tuteur</label>
                <input type="text" wire:model="guardian_name" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Téléphone du tuteur</label>
                <input type="text" wire:model="guardian_phone" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            Enregistrer
        </button>
    </form>
</div>
