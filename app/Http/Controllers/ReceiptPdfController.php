<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReceiptPdfController extends Controller
{
    public function show(Request $request, Payment $payment)
    {
        $user = $request->user();
        $invoice = $payment->invoice()->with('student')->first();
        $isOwner = $user->student && $user->student->id === $invoice->student_id;

        abort_unless($user->hasAnyRole(['super-admin', 'directeur', 'administrateur', 'responsable-academique', 'comptable']) || $isOwner, 403);

        $payment->load(['invoice.student', 'paymentMethod', 'receipt']);

        $pdf = Pdf::loadView('pdf.receipt', ['payment' => $payment]);

        return $pdf->stream("recu-{$payment->receipt->receipt_number}.pdf");
    }
}
