<?php

namespace Tests\Feature\Security;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Receipt;
use App\Models\ReportCard;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\CreatesSchoolData;
use Tests\TestCase;

/**
 * Every PDF/document route serves sensitive personal data (grades, payment
 * amounts, identity documents). Each must be reachable by its owner and by
 * school staff, and denied to every other authenticated user.
 */
class DocumentAuthorizationTest extends TestCase
{
    use CreatesSchoolData, RefreshDatabase;

    public function test_report_card_pdf_is_denied_to_a_student_who_does_not_own_it(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $class = $this->createSchoolClass(null, $year);
        [, $owner] = $this->createStudent($class);
        [$strangerUser] = $this->createStudent($class);

        $reportCard = ReportCard::create([
            'student_id' => $owner->id,
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'period' => 'Trimestre 1',
            'published_at' => now(),
        ]);

        $this->actingAs($strangerUser)
            ->get("/bulletins/{$reportCard->id}")
            ->assertForbidden();
    }

    public function test_report_card_pdf_is_denied_to_its_owner_before_publication(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $class = $this->createSchoolClass(null, $year);
        [$ownerUser, $owner] = $this->createStudent($class);

        $reportCard = ReportCard::create([
            'student_id' => $owner->id,
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'period' => 'Trimestre 1',
            'published_at' => null,
        ]);

        $this->actingAs($ownerUser)
            ->get("/bulletins/{$reportCard->id}")
            ->assertForbidden();
    }

    public function test_report_card_pdf_is_reachable_by_its_owner_once_published(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        $class = $this->createSchoolClass(null, $year);
        [$ownerUser, $owner] = $this->createStudent($class);

        $reportCard = ReportCard::create([
            'student_id' => $owner->id,
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'period' => 'Trimestre 1',
            'published_at' => now(),
        ]);

        $this->actingAs($ownerUser)
            ->get("/bulletins/{$reportCard->id}")
            ->assertOk();
    }

    public function test_receipt_pdf_is_denied_to_a_student_who_does_not_own_the_invoice(): void
    {
        $this->seedBase();

        $year = $this->createAcademicYear();
        [, $owner] = $this->createStudent();
        [$strangerUser] = $this->createStudent();

        $invoice = Invoice::create([
            'student_id' => $owner->id,
            'academic_year_id' => $year->id,
            'invoice_number' => 'FAC-TEST-0001',
            'total_amount' => 100000,
            'status' => 'en_attente',
        ]);

        $method = PaymentMethod::create(['name' => 'Espèces', 'code' => 'especes']);
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_method_id' => $method->id,
            'amount' => 50000,
            'status' => 'valide',
            'paid_at' => now(),
        ]);
        Receipt::create(['payment_id' => $payment->id, 'receipt_number' => 'REC-TEST-0001', 'issued_at' => now()]);

        $this->actingAs($strangerUser)
            ->get("/recus/{$payment->id}")
            ->assertForbidden();
    }

    public function test_document_pdf_is_denied_to_a_student_who_does_not_own_it(): void
    {
        $this->seedBase();

        [, $owner] = $this->createStudent();
        [$strangerUser] = $this->createStudent();

        $template = DocumentTemplate::create([
            'code' => 'certificat_scolarite',
            'name' => 'Certificat de scolarité',
            'blade_view' => 'pdf.document',
        ]);

        $document = Document::create([
            'document_template_id' => $template->id,
            'reference' => 'CERT-TEST-0001',
            'documentable_type' => Student::class,
            'documentable_id' => $owner->id,
            'hash' => hash('sha256', 'test-hash'),
            'issued_at' => now(),
        ]);

        $this->actingAs($strangerUser)
            ->get("/documents/{$document->id}")
            ->assertForbidden();
    }

    public function test_candidate_application_document_is_denied_to_a_different_candidate(): void
    {
        $this->seedBase();
        Storage::fake('local');

        [, $owner] = $this->createCandidate();
        [$strangerUser] = $this->createCandidate();

        $formation = $this->createFormation();
        $year = $this->createAcademicYear();

        $application = Application::create([
            'application_number' => 'CAND-TEST-0001',
            'candidate_id' => $owner->id,
            'formation_id' => $formation->id,
            'academic_year_id' => $year->id,
            'status' => 'nouveau',
            'submitted_at' => now(),
        ]);

        Storage::disk('local')->put('candidatures/CAND-TEST-0001/piece.pdf', 'fake-pdf-content');

        $document = ApplicationDocument::create([
            'application_id' => $application->id,
            'document_type' => "Pièce d'identité",
            'file_path' => 'candidatures/CAND-TEST-0001/piece.pdf',
            'status' => 'en_attente',
        ]);

        $this->actingAs($strangerUser)
            ->get("/candidatures-pieces/{$document->id}")
            ->assertForbidden();
    }

    public function test_candidate_application_document_is_reachable_by_its_owner(): void
    {
        $this->seedBase();
        Storage::fake('local');

        [$ownerUser, $owner] = $this->createCandidate();

        $formation = $this->createFormation();
        $year = $this->createAcademicYear();

        $application = Application::create([
            'application_number' => 'CAND-TEST-0002',
            'candidate_id' => $owner->id,
            'formation_id' => $formation->id,
            'academic_year_id' => $year->id,
            'status' => 'nouveau',
            'submitted_at' => now(),
        ]);

        Storage::disk('local')->put('candidatures/CAND-TEST-0002/piece.pdf', 'fake-pdf-content');

        $document = ApplicationDocument::create([
            'application_id' => $application->id,
            'document_type' => "Pièce d'identité",
            'file_path' => 'candidatures/CAND-TEST-0002/piece.pdf',
            'status' => 'en_attente',
        ]);

        $this->actingAs($ownerUser)
            ->get("/candidatures-pieces/{$document->id}")
            ->assertOk();
    }

    public function test_candidate_application_documents_are_not_served_from_the_public_disk(): void
    {
        $this->seedBase();

        [, $owner] = $this->createCandidate();
        $formation = $this->createFormation();
        $year = $this->createAcademicYear();

        $application = Application::create([
            'application_number' => 'CAND-TEST-0003',
            'candidate_id' => $owner->id,
            'formation_id' => $formation->id,
            'academic_year_id' => $year->id,
            'status' => 'nouveau',
            'submitted_at' => now(),
        ]);

        $document = ApplicationDocument::create([
            'application_id' => $application->id,
            'document_type' => "Pièce d'identité",
            'file_path' => 'candidatures/CAND-TEST-0003/piece.pdf',
            'status' => 'en_attente',
        ]);

        $this->assertFalse(Storage::disk('public')->exists($document->file_path));
    }
}
