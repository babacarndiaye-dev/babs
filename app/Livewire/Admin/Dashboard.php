<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Formation;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'studentsActive' => Student::where('status', 'actif')->count(),
            'studentsTotal' => Student::count(),
            'studentsWatch' => Student::whereIn('status', ['suspendu', 'abandonne'])->count(),
            'teachersTotal' => Teacher::count(),
            'teachersActive' => Teacher::where('is_active', true)->count(),
            'formationsPublished' => Formation::where('is_published', true)->count(),
            'formationsTotal' => Formation::count(),
            'invoicesBilled' => Invoice::sum('total_amount'),
            'invoicesOutstanding' => Invoice::whereIn('status', ['en_attente', 'partiel', 'en_retard'])->count(),
            'invoicesOverdue' => Invoice::where('status', 'en_retard')->count(),
            'applicationsNew' => Application::where('status', 'nouveau')->count(),
            'applicationsReview' => Application::where('status', 'en_etude')->count(),
            'applicationsPending' => Application::whereIn('status', ['nouveau', 'en_etude'])->count(),
            'recentApplications' => Application::with(['candidate', 'formation'])
                ->latest('submitted_at')
                ->take(6)
                ->get(),
        ]);
    }
}
