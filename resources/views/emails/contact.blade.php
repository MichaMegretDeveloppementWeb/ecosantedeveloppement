<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle demande de contact</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #2b2521; line-height: 1.6; max-width: 640px; margin: 0 auto; padding: 24px; background: #fefcf7; }
        h1 { font-size: 22px; color: #2b2521; border-bottom: 2px solid #efb1a3; padding-bottom: 8px; }
        h2 { font-size: 16px; color: #4a423d; margin-top: 24px; }
        .field { margin: 8px 0; }
        .label { font-weight: 700; color: #7a6f68; font-size: 13px; text-transform: uppercase; letter-spacing: 0.04em; }
        .value { font-size: 15px; }
        .message { background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #ebe5dd; white-space: pre-wrap; }
        .muted { color: #7a6f68; font-size: 13px; }
    </style>
</head>
<body>
    <h1>Nouvelle demande de contact</h1>
    <p class="muted">Reçue depuis le formulaire du site {{ config('eco-sante.legal.site_url') }}.</p>

    <h2>Demandeur</h2>
    <div class="field">
        <span class="label">Nom complet</span><br>
        <span class="value">{{ $data['firstName'] }} {{ $data['lastName'] }}</span>
    </div>
    <div class="field">
        <span class="label">Email</span><br>
        <span class="value"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></span>
    </div>
    @if (! empty($data['phone']))
        <div class="field">
            <span class="label">Téléphone</span><br>
            <span class="value">{{ $data['phone'] }}</span>
        </div>
    @endif

    <h2>Demande</h2>
    <div class="field">
        <span class="label">Crèche d'intérêt</span><br>
        <span class="value">{{ $crecheLabel }}</span>
    </div>
    @if (! empty($data['entryDate']))
        <div class="field">
            <span class="label">Date d'entrée souhaitée</span><br>
            <span class="value">{{ $data['entryDate'] }}</span>
        </div>
    @endif

    <h2>Message</h2>
    <div class="message">{{ $data['message'] }}</div>

    @if ($attachments ?? false)
        <p class="muted" style="margin-top: 24px;">📎 Une fiche de préinscription est jointe à ce message.</p>
    @endif
</body>
</html>
