<?php

namespace App\Http\Controllers;

use App\Models\ApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationDocumentController extends Controller
{
    public function show(Request $request, ApplicationDocument $document)
    {
        $user = $request->user();
        $application = $document->application()->with('candidate')->first();
        $isOwner = $user->candidate && $user->candidate->id === $application->candidate_id;

        abort_unless($user->hasAnyRole(['super-admin', 'directeur', 'administrateur', 'responsable-academique', 'scolarite']) || $isOwner, 403);

        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->response($document->file_path);
    }
}
