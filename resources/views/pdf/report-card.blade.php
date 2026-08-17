<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1F2421; }
        .header { display: table; width: 100%; margin-bottom: 20px; border-bottom: 2px solid {{ setting('design.color_primary', '#0F5132') }}; padding-bottom: 12px; }
        .header .school { display: table-cell; vertical-align: middle; }
        .school-name { font-size: 16px; font-weight: bold; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .school-meta { font-size: 10px; color: #666; }
        h1 { font-size: 18px; text-align: center; margin: 10px 0 20px; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .info-table { width: 100%; margin-bottom: 16px; }
        .info-table td { padding: 3px 0; font-size: 11px; }
        .info-table td.label { color: #666; width: 140px; }
        table.grades { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.grades th, table.grades td { border: 1px solid #ddd; padding: 6px 8px; font-size: 11px; text-align: left; }
        table.grades th { background: #F7F7F5; }
        table.grades td.num { text-align: center; }
        .summary { margin-top: 20px; display: table; width: 100%; }
        .summary .box { display: table-cell; width: 33%; text-align: center; padding: 10px; }
        .summary .box .value { font-size: 20px; font-weight: bold; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .summary .box .label { font-size: 10px; color: #666; }
        .comment { margin-top: 16px; padding: 10px; border: 1px solid #ddd; font-size: 11px; }
        .footer { margin-top: 40px; display: table; width: 100%; }
        .footer .sig { display: table-cell; width: 50%; text-align: center; font-size: 11px; padding-top: 30px; border-top: 1px solid #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school">
            <div class="school-name">{{ setting('identity.name') }}</div>
            <div class="school-meta">{{ setting('identity.address') }} — {{ setting('identity.phone') }} — {{ setting('identity.email') }}</div>
        </div>
    </div>

    <h1>Bulletin de notes — {{ $reportCard->period }}</h1>

    <table class="info-table">
        <tr>
            <td class="label">Étudiant</td><td>{{ $reportCard->student->fullName() }} ({{ $reportCard->student->matricule }})</td>
            <td class="label">Classe</td><td>{{ $reportCard->schoolClass->name }}</td>
        </tr>
        <tr>
            <td class="label">Formation</td><td>{{ $reportCard->student->currentClass?->formation?->title }}</td>
            <td class="label">Année académique</td><td>{{ $reportCard->schoolClass->academicYear->name ?? '' }}</td>
        </tr>
    </table>

    <table class="grades">
        <thead>
            <tr>
                <th>Matière</th>
                <th class="num">Moyenne /20</th>
                <th class="num">Coefficient</th>
                <th>Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reportCard->lines as $line)
                <tr>
                    <td>{{ $line->subject->name }}</td>
                    <td class="num">{{ $line->average ?? '—' }}</td>
                    <td class="num">{{ $line->coefficient }}</td>
                    <td>{{ $line->teacher_comment ?? '' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Aucune note enregistrée pour cette période.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="box">
            <div class="value">{{ $reportCard->general_average ?? '—' }}/20</div>
            <div class="label">Moyenne générale</div>
        </div>
        <div class="box">
            <div class="value">{{ $reportCard->rank ?? '—' }}/{{ $reportCard->class_size ?? '—' }}</div>
            <div class="label">Rang</div>
        </div>
        <div class="box">
            <div class="value">{{ $reportCard->decision ?? '—' }}</div>
            <div class="label">Décision</div>
        </div>
    </div>

    @if ($reportCard->comment)
        <div class="comment"><strong>Observations :</strong> {{ $reportCard->comment }}</div>
    @endif

    <div class="footer">
        <div class="sig">Le Directeur</div>
        <div class="sig">Cachet de l'établissement</div>
    </div>
</body>
</html>
