<?php

namespace App\Http\Controllers;

use App\Models\ReportCard;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportCardPdfController extends Controller
{
    public function show(Request $request, ReportCard $reportCard)
    {
        $user = $request->user();
        $isOwner = $user->student && $user->student->id === $reportCard->student_id;

        abort_unless($user->hasAnyRole(['super-admin', 'directeur', 'administrateur', 'responsable-academique']) || ($isOwner && $reportCard->published_at), 403);

        $reportCard->load(['student.currentClass.formation', 'schoolClass', 'lines.subject']);

        $pdf = Pdf::loadView('pdf.report-card', ['reportCard' => $reportCard]);

        return $pdf->stream("bulletin-{$reportCard->student->matricule}-{$reportCard->period}.pdf");
    }
}
