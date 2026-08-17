<?php

namespace App\Livewire\Public\Documents;

use App\Models\Document;
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
