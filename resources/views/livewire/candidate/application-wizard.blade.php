<div>
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="mb-8 flex items-center gap-2">
        @foreach ([1 => 'Formation', 2 => 'Infos personnelles', 3 => 'Parcours scolaire', 4 => 'Documents'] as $n => $label)
            <div class="flex items-center gap-2 {{ $n < 4 ? 'flex-1' : '' }}">
                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold
                            {{ $step >= $n ? 'bg-primary text-white' : 'bg-black/10 text-ink/50' }}">
                    {{ $n }}
                </div>
                <span class="hidden sm:inline text-xs font-medium {{ $step >= $n ? 'text-ink' : 'text-ink/40' }}">{{ $label }}</span>
                @if ($n < 4)
                    <div class="h-px flex-1 {{ $step > $n ? 'bg-primary' : 'bg-black/10' }}"></div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="rounded-2xl border border-black/5 bg-white p-6 max-w-2xl">
        @if ($step === 1)
            <div wire:key="wizard-step-1">
                <h2 class="font-semibold text-ink mb-4">Choisissez une formation</h2>
                <div class="grid gap-2">
                    @foreach ($formations as $formation)
                        <label class="flex items-center gap-3 rounded-lg border p-3 cursor-pointer {{ $formation_id === $formation->id ? 'border-primary bg-primary/5' : 'border-black/10' }}">
                            <input type="radio" wire:model="formation_id" value="{{ $formation->id }}" class="text-primary focus:ring-primary">
                            <div>
                                <p class="text-sm font-medium text-ink">{{ $formation->title }}</p>
                                <p class="text-xs text-ink/50">{{ $formation->durationLabel() }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('formation_id') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

        @elseif ($step === 2)
            <div wire:key="wizard-step-2">
                <h2 class="font-semibold text-ink mb-4">Informations personnelles</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Prénom</label>
                        <input type="text" wire:model="first_name" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Nom</label>
                        <input type="text" wire:model="last_name" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Sexe</label>
                        <select wire:model="gender" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="">—</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Date de naissance</label>
                        <input type="date" wire:model="date_of_birth" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Téléphone</label>
                        <input type="text" wire:model="phone" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Email</label>
                        <input type="email" wire:model="email" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-ink/80">Adresse</label>
                        <input type="text" wire:model="address" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                </div>
            </div>

        @elseif ($step === 3)
            <div wire:key="wizard-step-3">
                <h2 class="font-semibold text-ink mb-4">Parcours scolaire</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Dernier établissement fréquenté</label>
                        <input type="text" wire:model="previous_school" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Dernier diplôme obtenu</label>
                        <input type="text" wire:model="last_diploma" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                </div>
            </div>

        @elseif ($step === 4)
            <div wire:key="wizard-step-4">
                <h2 class="font-semibold text-ink mb-4">Pièces justificatives</h2>
                <div class="space-y-4">
                    @foreach ($documentTypes as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-ink/80">{{ $label }}</label>
                            <input type="file" wire:model="documents.{{ $key }}" class="mt-1.5 w-full text-sm">
                            @error('documents.'.$key) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 flex justify-between">
            @if ($step > 1)
                <button type="button" wire:click="previousStep" class="rounded-lg border border-black/10 px-5 py-2.5 text-sm font-medium text-ink/70 hover:bg-surface transition">
                    Précédent
                </button>
            @else
                <span></span>
            @endif

            @if ($step < 4)
                <button type="button" wire:click="nextStep" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Suivant
                </button>
            @else
                <button type="button" wire:click="submit" wire:loading.attr="disabled"
                        class="rounded-lg bg-accent px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Soumettre ma candidature
                </button>
            @endif
        </div>
    </div>
</div>
