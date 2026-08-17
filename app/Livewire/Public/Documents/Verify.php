<?php

namespace App\Livewire\Public\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Verify extends Component
{
    #[Url]
    public string $reference = '';

    public ?Document $result = null;

    public bool $searched = false;

    public function mount(): void
    {
        if ($this->reference) {
            $this->search();
        }
    }

    public function search(): void
    {
        $this->validate(['reference' => ['required', 'string']]);

        $key = 'verify-document|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 20)) {
            throw ValidationException::withMessages([
                'reference' => 'Trop de tentatives. Réessayez dans quelques minutes.',
            ]);
        }

        RateLimiter::hit($key, 300);

        $this->searched = true;
        $this->result = Document::with(['template', 'documentable'])
            ->where('reference', $this->reference)
            ->first();
    }

    public function render()
    {
        return view('livewire.public.documents.verify');
    }
}
