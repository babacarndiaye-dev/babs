<?php

namespace App\Livewire\Student;

use App\Models\Document;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.student')]
class Documents extends Component
{
    public function render()
    {
        $student = auth()->user()->student;

        $documents = $student
            ? Document::where('documentable_type', $student::class)
                ->where('documentable_id', $student->id)
                ->with('template')
                ->latest('issued_at')
                ->get()
            : collect();

        return view('livewire.student.documents', [
            'documents' => $documents,
        ]);
    }
}
