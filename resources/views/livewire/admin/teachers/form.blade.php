<div>
    <div class="mb-6">
        <a href="{{ route('admin.teachers.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux enseignants</a>
    </div>

    <form wire:submit="save" class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">Identité</h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Prénom</label>
                        <input type="text" wire:model="first_name" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Nom</label>
                        <input type="text" wire:model="last_name" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Matricule</label>
                        <input type="text" wire:model="matricule" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @error('matricule') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Sexe</label>
                        <select wire:model="gender" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            <option value="">—</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Date de naissance</label>
                        <input type="date" wire:model="date_of_birth" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink/80">Date d'embauche</label>
                        <input type="date" wire:model="hire_date" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">Contact & spécialité</h3>
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
                        <label class="block text-sm font-medium text-ink/80">Spécialité</label>
                        <input type="text" wire:model="specialty" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink/80">Qualifications</label>
                    <textarea wire:model="qualifications" rows="3" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none"></textarea>
                </div>

                @if (! $teacher)
                    <label class="flex items-center gap-2 text-sm text-ink/70">
                        <input type="checkbox" wire:model="createAccount" class="rounded border-black/20 text-primary focus:ring-primary">
                        Créer un accès à l'espace enseignant avec l'email renseigné ci-dessus
                    </label>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-ink/80">Photo</label>
                    <input type="file" wire:model="photo" class="mt-1.5 w-full text-sm">
                    @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @if ($teacher?->getFirstMediaUrl('photo'))
                        <img src="{{ $teacher->getFirstMediaUrl('photo') }}" class="mt-2 h-20 w-20 rounded-full object-cover">
                    @endif
                </div>

                <label class="flex items-center gap-2 text-sm text-ink/70">
                    <input type="checkbox" wire:model="is_active" class="rounded border-black/20 text-primary focus:ring-primary">
                    Enseignant actif
                </label>

                <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>
