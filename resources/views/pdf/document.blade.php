<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1F2421; }
        .header { border-bottom: 2px solid {{ setting('design.color_primary', '#0F5132') }}; padding-bottom: 12px; margin-bottom: 30px; }
        .school-name { font-size: 16px; font-weight: bold; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .school-meta { font-size: 10px; color: #666; }
        h1 { font-size: 20px; text-align: center; margin: 20px 0 30px; color: {{ setting('design.color_primary', '#0F5132') }}; text-transform: uppercase; }
        .ref { text-align: center; font-size: 10px; color: #999; margin-bottom: 30px; }
        .body-text { font-size: 13px; line-height: 1.8; padding: 0 20px; text-align: justify; }
        .body-text strong { color: {{ setting('design.color_primary', '#0F5132') }}; }
        .footer { margin-top: 60px; display: table; width: 100%; }
        .footer .qr { display: table-cell; width: 30%; vertical-align: bottom; }
        .footer .qr img { width: 90px; height: 90px; }
        .footer .qr .caption { font-size: 8px; color: #999; margin-top: 4px; width: 90px; }
        .footer .sig { display: table-cell; width: 70%; text-align: right; vertical-align: bottom; font-size: 11px; }
        .footer .sig .line { margin-top: 40px; border-top: 1px solid #999; width: 200px; margin-left: auto; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ setting('identity.name') }}</div>
        <div class="school-meta">{{ setting('identity.address') }} — {{ setting('identity.phone') }} — {{ setting('identity.email') }}</div>
    </div>

    <h1>{{ $document->template->name }}</h1>
    <div class="ref">Référence : {{ $document->reference }} — délivré le {{ $document->issued_at?->format('d/m/Y') }}</div>

    @php
        $student = $document->documentable;
        // Escape every piece of user-influenced data before splicing it into
        // raw HTML below — student names/matricules originate from
        // self-service candidate registration, so they can't be trusted verbatim.
        $formation = e($student?->currentClass?->formation?->title ?? '—');
        $class = e($student?->currentClass?->name ?? '—');
        $year = e($student?->currentClass?->academicYear?->name ?? '—');
        $studentName = e($student?->fullName() ?? '—');
        $matricule = e($student?->matricule ?? '—');
        $schoolName = e(setting('identity.name'));

        $bodies = [
            'certificat_scolarite' => "Le Directeur de {$schoolName} certifie que <strong>{$studentName}</strong>, titulaire du matricule <strong>{$matricule}</strong>, est régulièrement inscrit(e) au titre de l'année académique <strong>{$year}</strong> en <strong>{$formation}</strong>, classe <strong>{$class}</strong>.<br><br>En foi de quoi le présent certificat lui est délivré pour servir et valoir ce que de droit.",
            'attestation_reussite' => "Le Directeur de {$schoolName} atteste que <strong>{$studentName}</strong>, titulaire du matricule <strong>{$matricule}</strong>, a suivi avec succès la formation <strong>{$formation}</strong> au titre de l'année académique <strong>{$year}</strong>.<br><br>En foi de quoi la présente attestation lui est délivrée pour servir et valoir ce que de droit.",
            'certificat_fin_formation' => "Le Directeur de {$schoolName} certifie que <strong>{$studentName}</strong>, titulaire du matricule <strong>{$matricule}</strong>, a suivi et achevé l'intégralité de la formation <strong>{$formation}</strong>.<br><br>En foi de quoi le présent certificat lui est délivré pour servir et valoir ce que de droit.",
        ];
    @endphp

    <div class="body-text">
        {!! $bodies[$document->template->code] ?? "Document délivré à <strong>{$studentName}</strong>." !!}
    </div>

    <div class="footer">
        <div class="qr">
            <img src="{{ $qrDataUri }}" alt="QR">
            <div class="caption">Scannez pour vérifier l'authenticité de ce document sur {{ $verificationUrl }}</div>
        </div>
        <div class="sig">
            <div>Fait à {{ setting('identity.address') ? \Illuminate\Support\Str::before(setting('identity.address'), ',') : '' }}, le {{ $document->issued_at?->format('d/m/Y') }}</div>
            <div class="line">Le Directeur</div>
        </div>
    </div>
</body>
</html>
