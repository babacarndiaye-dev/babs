<div>
    <div class="mb-6">
        <a href="{{ route('admin.students.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux étudiants</a>
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
                        <label class="block text-sm font-medium text-ink/80">Lieu de naissance</label>
                        <input type="text" wire:model="place_of_birth" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">Contact</h3>
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

                @if (! $student)
                    <label class="flex items-center gap-2 text-sm text-ink/70">
                        <input type="checkbox" wire:model="createAccount" class="rounded border-black/20 text-primary focus:ring-primary">
                        Créer un accès à l'espace étudiant avec l'email renseigné ci-dessus
                    </label>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6 space-y-4">
                <h3 class="font-semibold text-ink">Scolarité</h3>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Classe</label>
                    <select wire:model="current_class_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <option value="">—</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Statut</label>
                    <select wire:model="status" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        <option value="actif">Actif</option>
                        <option value="suspendu">Suspendu</option>
                        <option value="diplome">Diplômé</option>
                        <option value="abandonne">Abandonné</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Date d'inscription</label>
                    <input type="date" wire:model="enrolled_at" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink/80">Photo</label>
                    <input type="file" wire:model="photo" class="mt-1.5 w-full text-sm">
                    @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @if ($student?->getFirstMediaUrl('photo'))
                        <img src="{{ $student->getFirstMediaUrl('photo') }}" class="mt-2 h-20 w-20 rounded-full object-cover">
                    @endif
                </div>

                <button type="submit" class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>
