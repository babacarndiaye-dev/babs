<div>
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.classes.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux classes</a>
        <a href="{{ route('admin.classes.edit', $class) }}" class="text-sm font-medium text-primary hover:underline">Modifier</a>
    </div>

    @if (session('status'))
        <div class="mb-6 rounded-lg bg-primary/10 px-4 py-3 text-sm text-primary">{{ session('status') }}</div>
    @endif

    <div class="mb-6 rounded-2xl border border-black/5 bg-white p-6">
        <h2 class="font-semibold text-ink text-lg">{{ $class->name }}</h2>
        <p class="text-sm text-ink/50">{{ $class->formation->title }} &middot; {{ $class->academicYear->name }} &middot; {{ $class->room?->name ?? 'Salle non définie' }}</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="rounded-2xl border border-black/5 bg-white p-6">
            <h3 class="font-semibold text-ink mb-4">Étudiants inscrits ({{ $class->students->count() }})</h3>
            <div class="space-y-2">
                @forelse ($class->students as $student)
                    <div wire:key="roster-{{ $student->id }}" class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-2.5 text-sm">
                        <span class="text-ink">{{ $student->fullName() }}</span>
                        <span class="text-ink/40">{{ $student->matricule }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink/50">Aucun étudiant inscrit dans cette classe.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-6">
            <h3 class="font-semibold text-ink mb-4">Matières & enseignants</h3>

            <div class="space-y-2 mb-5">
                @forelse ($class->subjectAssignments as $assignment)
                    <div wire:key="assign-{{ $assignment->id }}" class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-2.5 text-sm">
                        <div>
                            <span class="font-medium text-ink">{{ $assignment->subject->name }}</span>
                            <span class="text-ink/50"> &mdash; {{ $assignment->teacher->fullName() }}</span>
                        </div>
                        <button wire:click="removeAssignment({{ $assignment->id }})" class="text-red-600 hover:underline text-xs font-medium">Retirer</button>
                    </div>
                @empty
                    <p class="text-sm text-ink/50">Aucune matière affectée.</p>
                @endforelse
            </div>

            <form wire:submit="addAssignment" class="grid grid-cols-2 gap-3 border-t border-black/5 pt-4">
                <select wire:model="subject_id" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">Matière</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <select wire:model="teacher_id" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                    <option value="">Enseignant</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->fullName() }}</option>
                    @endforeach
                </select>
                <input type="number" wire:model="coefficient" step="0.5" min="0" placeholder="Coefficient"
                       class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <button type="submit" class="rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white hover:opacity-90 transition">
                    Affecter
                </button>
                @error('subject_id') <p class="col-span-2 text-xs text-red-600">{{ $message }}</p> @enderror
                @error('teacher_id') <p class="col-span-2 text-xs text-red-600">{{ $message }}</p> @enderror
            </form>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-black/5 bg-white p-6">
        <h3 class="font-semibold text-ink mb-4">Emploi du temps</h3>

        <div class="space-y-2 mb-5">
            @forelse ($class->schedules->sortBy(['day_of_week', 'start_time']) as $schedule)
                <div wire:key="schedule-{{ $schedule->id }}" class="flex items-center justify-between rounded-lg border border-black/5 px-4 py-2.5 text-sm">
                    <div>
                        <span class="font-medium text-ink">{{ $days[$schedule->day_of_week] }}</span>
                        <span class="text-ink/50">{{ substr($schedule->start_time, 0, 5) }}–{{ substr($schedule->end_time, 0, 5) }}</span>
                        &middot; {{ $schedule->subject->name }}
                        <span class="text-ink/50">({{ $schedule->teacher->fullName() }}{{ $schedule->room ? ', '.$schedule->room->name : '' }})</span>
                    </div>
                    <button wire:click="removeSchedule({{ $schedule->id }})" class="text-red-600 hover:underline text-xs font-medium">Retirer</button>
                </div>
            @empty
                <p class="text-sm text-ink/50">Aucun créneau planifié.</p>
            @endforelse
        </div>

        <form wire:submit="addSchedule" class="grid sm:grid-cols-5 gap-3 border-t border-black/5 pt-4">
            <select wire:model="schedule_subject_id" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <option value="">Matière</option>
                @foreach ($class->subjectAssignments as $assignment)
                    <option value="{{ $assignment->subject_id }}">{{ $assignment->subject->name }}</option>
                @endforeach
            </select>
            <select wire:model="day_of_week" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @foreach ($days as $n => $label)
                    <option value="{{ $n }}">{{ $label }}</option>
                @endforeach
            </select>
            <input type="time" wire:model="start_time" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            <input type="time" wire:model="end_time" class="rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            <button type="submit" class="rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white hover:opacity-90 transition">
                Ajouter
            </button>
            <select wire:model="schedule_room_id" class="sm:col-span-2 rounded-lg border border-black/10 px-3 py-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <option value="">Salle (par défaut : {{ $class->room?->name ?? 'aucune' }})</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
            @error('schedule_subject_id') <p class="col-span-5 text-xs text-red-600">{{ $message }}</p> @enderror
            @error('end_time') <p class="col-span-5 text-xs text-red-600">{{ $message }}</p> @enderror
        </form>
    </div>
</div>
