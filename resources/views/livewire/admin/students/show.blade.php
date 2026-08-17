<div>
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.students.index') }}" class="text-sm text-ink/60 hover:text-primary">&larr; Retour aux étudiants</a>
        <a href="{{ route('admin.students.edit', $student) }}" class="text-sm font-medium text-primary hover:underline">Modifier</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6">
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
                        <p class="text-sm text-ink/50">{{ $student->matricule }} &middot; {{ $student->currentClass?->name ?? 'Aucune classe' }}</p>
                    </div>
                </div>

                <div class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-ink/50">Formation</span><p class="text-ink">{{ $student->currentClass?->formation?->title ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Statut</span><p class="text-ink">{{ ucfirst($student->status) }}</p></div>
                    <div><span class="text-ink/50">Date de naissance</span><p class="text-ink">{{ $student->date_of_birth?->format('d/m/Y') ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Lieu de naissance</span><p class="text-ink">{{ $student->place_of_birth ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Téléphone</span><p class="text-ink">{{ $student->phone ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Email</span><p class="text-ink">{{ $student->email ?? '—' }}</p></div>
                    <div><span class="text-ink/50">Tuteur</span><p class="text-ink">{{ $student->guardian_name ?? '—' }} {{ $student->guardian_phone ? '('.$student->guardian_phone.')' : '' }}</p></div>
                    <div><span class="text-ink/50">Inscrit le</span><p class="text-ink">{{ $student->enrolled_at?->format('d/m/Y') ?? '—' }}</p></div>
                </div>
            </div>

            <div class="rounded-2xl border border-dashed border-black/10 bg-white p-6 text-center text-ink/50 text-sm">
                Notes, présences et bulletins seront disponibles ici en Phase 11-13.
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <h3 class="font-semibold text-ink mb-3">Compte de connexion</h3>
                @if ($student->user)
                    <p class="text-sm text-ink/70">{{ $student->user->email }}</p>
                    <p class="text-xs text-ink/40 mt-1">Accès à l'espace étudiant actif</p>
                @else
                    <p class="text-sm text-ink/50">Aucun compte de connexion lié.</p>
                @endif
            </div>

            <div class="rounded-2xl border border-black/5 bg-white p-6">
                <h3 class="font-semibold text-ink mb-3">Finance</h3>
                @php $balance = $student->invoices->sum(fn ($i) => $i->balance()); @endphp
                <p class="text-2xl font-semibold text-ink">{{ number_format($balance, 0, ',', ' ') }} {{ setting('finance.currency') }}</p>
                <p class="text-xs text-ink/40">Solde restant</p>
            </div>
        </div>
    </div>
</div>
