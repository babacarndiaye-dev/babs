<?php

namespace App\Livewire\Admin\Documents;

use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Student;
use App\Notifications\DocumentIssued;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public ?int $student_id = null;

    public ?int $document_template_id = null;

    protected function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'document_template_id' => ['required', 'exists:document_templates,id'],
        ];
    }

    public function issue(): void
    {
        $this->validate();

        $template = DocumentTemplate::findOrFail($this->document_template_id);
        $reference = $this->generateReference($template);

        $document = Document::create([
            'document_template_id' => $template->id,
            'reference' => $reference,
            'documentable_type' => Student::class,
            'documentable_id' => $this->student_id,
            'hash' => hash('sha256', $reference.now()->timestamp.Str::random(16)),
            'issued_by' => Auth::id(),
            'issued_at' => now(),
        ]);

        $document->load('template');
        Student::find($this->student_id)?->user?->notify(new DocumentIssued($document));

        $this->reset(['student_id', 'document_template_id']);

        session()->flash('status', "Document {$reference} émis.");
    }

    private function generateReference(DocumentTemplate $template): string
    {
        $year = now()->format('Y');
        $count = Document::where('document_template_id', $template->id)
            ->whereYear('created_at', now()->year)
            ->count() + 1;

        $format = $template->numbering_format ?: '{code}-{year}-{seq}';

        return str_replace(
            ['{year}', '{seq}', '{code}'],
            [$year, str_pad((string) $count, 4, '0', STR_PAD_LEFT), Str::upper($template->code)],
            $format
        );
    }

    public function render()
    {
        return view('livewire.admin.documents.index', [
            'students' => Student::orderBy('first_name')->get(),
            'templates' => DocumentTemplate::where('is_active', true)->orderBy('name')->get(),
            'documents' => Document::with(['template', 'documentable'])->latest()->paginate(15),
        ]);
    }
}
