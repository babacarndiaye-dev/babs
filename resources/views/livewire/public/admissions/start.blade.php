<div class="mx-auto max-w-lg px-6 py-16">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-ink">Candidater</h1>
        @if ($preselectedFormation)
            <p class="mt-2 text-sm text-ink/60">
                Pour la formation <span class="font-semibold text-primary">{{ $preselectedFormation->title }}</span>
            </p>
        @else
            <p class="mt-2 text-sm text-ink/60">Créez votre compte candidat pour déposer votre dossier.</p>
        @endif
    </div>

    <div class="mt-8 flex rounded-full bg-surface p-1">
        <button wire:click="$set('mode', 'register')"
                class="flex-1 rounded-full py-2 text-sm font-medium transition {{ $mode === 'register' ? 'bg-white shadow-sm text-primary' : 'text-ink/60' }}">
            Créer un compte
        </button>
        <button wire:click="$set('mode', 'login')"
                class="flex-1 rounded-full py-2 text-sm font-medium transition {{ $mode === 'login' ? 'bg-white shadow-sm text-primary' : 'text-ink/60' }}">
            J'ai déjà un compte
        </button>
    </div>

    @if ($mode === 'register')
        <form wire:submit="register" class="mt-6 space-y-4 rounded-2xl border border-black/5 bg-white p-6">
            <div class="grid grid-cols-2 gap-4">
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
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Email</label>
                <input type="email" wire:model="email" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Téléphone</label>
                <input type="text" wire:model="phone" placeholder="+221 7X XXX XX XX" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Mot de passe</label>
                <input type="password" wire:model="password" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full rounded-lg bg-accent px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Créer mon compte et continuer
            </button>
        </form>
    @else
        <form wire:submit="login" class="mt-6 space-y-4 rounded-2xl border border-black/5 bg-white p-6">
            <div>
                <label class="block text-sm font-medium text-ink/80">Email</label>
                <input type="email" wire:model="login_email" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @error('login_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink/80">Mot de passe</label>
                <input type="password" wire:model="login_password" class="mt-1.5 w-full rounded-lg border border-black/10 px-3.5 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                @error('login_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full rounded-lg bg-accent px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition">
                Continuer ma candidature
            </button>
        </form>
    @endif

    <p class="mt-6 text-center text-sm text-ink/60">
        Une candidature déjà déposée ? <a href="{{ route('admissions.track') }}" class="text-primary font-medium hover:underline">Suivre mon dossier</a>
    </p>
</div>
