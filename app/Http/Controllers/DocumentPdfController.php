<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentPdfController extends Controller
{
    public function show(Request $request, Document $document)
    {
        $user = $request->user();
        $student = $document->documentable;
        $isOwner = $user->student && $student && $user->student->id === $student->id;

        abort_unless($user->hasAnyRole(['super-admin', 'directeur', 'administrateur', 'responsable-academique', 'scolarite']) || $isOwner, 403);

        $document->load(['template', 'documentable']);
        $document->documentable?->load('currentClass.formation');

        $verificationUrl = route('documents.verify', ['reference' => $document->reference]);

        // No Imagick extension available, so the PNG backend is out. DomPDF
        // doesn't reliably rasterize inline <svg> markup, but it does decode
        // an <img> whose src is an SVG data URI, so go through that instead.
        $qrSvg = QrCode::format('svg')->size(140)->margin(1)->generate($verificationUrl);
        $qrDataUri = 'data:image/svg+xml;base64,'.base64_encode($qrSvg);

        $pdf = Pdf::loadView('pdf.document', [
            'document' => $document,
            'qrDataUri' => $qrDataUri,
            'verificationUrl' => $verificationUrl,
        ]);

        return $pdf->stream("{$document->reference}.pdf");
    }
}
