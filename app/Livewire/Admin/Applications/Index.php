<?php

namespace App\Livewire\Admin\Applications;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $status = '';

    #[Url]
    public string $search = '';

    public array $statuses = [
        'nouveau' => 'Nouveau',
        'en_etude' => 'En étude',
        'incomplet' => 'Incomplet',
        'pieces_complementaires_demandees' => 'Pièces complémentaires demandées',
        'admis' => 'Admis',
        'refuse' => 'Refusé',
        'inscrit' => 'Inscrit',
    ];

    public function render()
    {
        return view('livewire.admin.applications.index', [
            'applications' => Application::with(['candidate', 'formation'])
                ->whereNotNull('submitted_at')
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->when($this->search, fn ($q) => $q->where('application_number', 'like', "%{$this->search}%")
                    ->orWhereHas('candidate', fn ($c) => $c->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")))
                ->latest('submitted_at')
                ->paginate(15),
        ]);
    }
}
