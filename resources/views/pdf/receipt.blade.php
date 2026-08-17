<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1F2421; }
        .header { border-bottom: 2px solid {{ setting('design.color_primary', '#0F5132') }}; padding-bottom: 12px; margin-bottom: 20px; }
        .school-name { font-size: 16px; font-weight: bold; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .school-meta { font-size: 10px; color: #666; }
        h1 { font-size: 18px; text-align: center; margin: 10px 0 24px; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .ref { text-align: center; font-size: 11px; color: #666; margin-bottom: 24px; }
        table.info { width: 100%; margin-bottom: 20px; }
        table.info td { padding: 4px 0; font-size: 12px; }
        table.info td.label { color: #666; width: 160px; }
        .amount-box { text-align: center; padding: 20px; border: 2px solid {{ setting('design.color_primary', '#0F5132') }}; margin: 20px 0; }
        .amount-box .value { font-size: 28px; font-weight: bold; color: {{ setting('design.color_primary', '#0F5132') }}; }
        .footer { margin-top: 50px; display: table; width: 100%; }
        .footer .sig { display: table-cell; width: 50%; text-align: center; font-size: 11px; padding-top: 30px; border-top: 1px solid #999; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ setting('identity.name') }}</div>
        <div class="school-meta">{{ setting('identity.address') }} — {{ setting('identity.phone') }} — {{ setting('identity.email') }}</div>
    </div>

    <h1>Reçu de paiement</h1>
    <div class="ref">N° {{ $payment->receipt->receipt_number }} — délivré le {{ $payment->receipt->issued_at?->format('d/m/Y') }}</div>

    <table class="info">
        <tr>
            <td class="label">Étudiant</td>
            <td>{{ $payment->invoice->student->fullName() }} ({{ $payment->invoice->student->matricule }})</td>
        </tr>
        <tr>
            <td class="label">Facture</td>
            <td>{{ $payment->invoice->invoice_number }}</td>
        </tr>
        <tr>
            <td class="label">Moyen de paiement</td>
            <td>{{ $payment->paymentMethod->name }}</td>
        </tr>
        @if ($payment->reference)
            <tr>
                <td class="label">Référence</td>
                <td>{{ $payment->reference }}</td>
            </tr>
        @endif
        <tr>
            <td class="label">Date de paiement</td>
            <td>{{ $payment->paid_at?->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="amount-box">
        <div class="value">{{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ setting('finance.currency') }}</div>
    </div>

    <div class="footer">
        <div class="sig">Le Comptable</div>
        <div class="sig">Cachet de l'établissement</div>
    </div>
</body>
</html>
