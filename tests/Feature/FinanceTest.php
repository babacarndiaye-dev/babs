<?php

namespace Tests\Feature;

use App\Livewire\Admin\Finance\Invoices\Form as InvoiceForm;
use App\Livewire\Admin\Finance\Invoices\Show as InvoiceShow;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Feature\Concerns\CreatesSchoolData;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use CreatesSchoolData, RefreshDatabase;

    public function test_an_admin_can_create_an_invoice_with_lines(): void
    {
        $this->seedBase();
        $this->createAcademicYear();
        $admin = $this->createAdminUser('comptable');
        [, $student] = $this->createStudent();
        $feeType = FeeType::create(['name' => 'Scolarité', 'code' => 'scolarite']);

        Livewire::actingAs($admin)
            ->test(InvoiceForm::class)
            ->set('student_id', $student->id)
            ->set('lines.0.label', 'Frais de scolarité')
            ->set('lines.0.amount', 250000)
            ->set('lines.0.fee_type_id', $feeType->id)
            ->call('save');

        $this->assertDatabaseHas('invoices', [
            'student_id' => $student->id,
            'total_amount' => 250000,
            'status' => 'en_attente',
        ]);

        $this->assertDatabaseHas('invoice_lines', [
            'label' => 'Frais de scolarité',
            'amount' => 250000,
        ]);
    }

    public function test_recording_a_full_payment_marks_the_invoice_as_paid_and_issues_a_receipt(): void
    {
        $this->seedBase();
        $year = $this->createAcademicYear();
        $admin = $this->createAdminUser('comptable');
        [, $student] = $this->createStudent();

        $invoice = Invoice::create([
            'student_id' => $student->id,
            'academic_year_id' => $year->id,
            'invoice_number' => 'FAC-TEST-0099',
            'total_amount' => 100000,
            'status' => 'en_attente',
        ]);

        $method = PaymentMethod::create(['name' => 'Espèces', 'code' => 'especes']);

        Livewire::actingAs($admin)
            ->test(InvoiceShow::class, ['invoice' => $invoice])
            ->set('amount', 100000)
            ->set('payment_method_id', $method->id)
            ->call('recordPayment');

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 100000,
            'status' => 'valide',
        ]);

        $this->assertSame('paye', $invoice->fresh()->status);
        $this->assertDatabaseHas('receipts', [
            'payment_id' => Payment::where('invoice_id', $invoice->id)->value('id'),
        ]);
    }

    public function test_a_teacher_cannot_access_the_finance_screens(): void
    {
        $this->seedBase();
        [$user] = $this->createTeacher();

        $this->actingAs($user)
            ->get('/admin/finance')
            ->assertForbidden();
    }
}
