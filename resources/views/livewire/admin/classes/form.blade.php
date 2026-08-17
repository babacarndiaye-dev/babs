<div>
    <div class="mb-6">
        <a href="{{ route('admin.classes.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux classes</a>
    </div>

    <form wire:submit="save" class="max-w-2xl rounded-2xl border border-black/5 bg-white p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-ink/80">Nom de la classe</label>
            <input type="text" wire:model="name" placeholder="Ex: BTS Informatique - 1ère année"
                   class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink/80">Formation</label>
                <select wire:model.live="formation_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($formations as $formation)
                        <option value="{{ $formation->id }}">{{ $formation->title }}</option>
                    @endforeach
                </select>
                @error('formation_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Spécialité</label>
                <select wire:model="specialty_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Niveau</label>
                <select wire:model="level_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Année académique</label>
                <select wire:model="academic_year_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </select>
                @error('academic_year_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Salle</label>
                <select wire:model="room_id" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">—</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Capacité</label>
                <input type="number" wire:model="capacity" min="0"
                       class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
        </div>

        <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
            Enregistrer
        </button>
    </form>
</div>
