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
            'kpis' => [
                'students_total' => Student::count(),
                'students_active' => Student::where('status', 'actif')->count(),
                'applications_new' => Application::where('status', 'nouveau')->count(),
                'applications_review' => Application::where('status', 'en_etude')->count(),
                'teachers_total' => Teacher::count(),
                'formations_published' => Formation::where('is_published', true)->count(),
                'invoices_billed' => Invoice::sum('total_amount'),
                'invoices_outstanding' => Invoice::whereIn('status', ['en_attente', 'partiel', 'en_retard'])->count(),
            ],
        ]);
    }
}
