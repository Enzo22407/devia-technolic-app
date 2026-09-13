<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notification de Requête — Devia Technologic</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f6f8fc; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff; padding: 25px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 1.4rem; font-weight: 800; }
        .header p { margin: 4px 0 0; font-size: 0.85rem; opacity: 0.9; }
        .content { padding: 25px 20px; line-height: 1.6; }
        .badge-box { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 15px; margin: 15px 0; }
        .btn { display: inline-block; background: #4f46e5; color: #ffffff !important; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 700; margin-top: 15px; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 20px; text-align: center; font-size: 0.78rem; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Devia Technologic</h1>
            <p>Notification de Requête Académique</p>
        </div>
        <div class="content">
            <h2>Bonjour {{ $recipientName }},</h2>

            @if($isForStudent)
                <p>Votre requête a bien été enregistrée et transmise au service de la scolarité de l'établissement.</p>
            @else
                <p>Une nouvelle requête académique a été enregistrée sur la plateforme et nécessite votre attention.</p>
            @endif

            <div class="badge-box">
                <p style="margin: 0 0 6px;"><strong>Référence Dossier :</strong> <span style="color: #4f46e5; font-weight: 800;">{{ $studentRequest->reference_code }}</span></p>
                <p style="margin: 0 0 6px;"><strong>Demandeur :</strong> {{ $studentRequest->student->name ?? 'N/A' }} ({{ $studentRequest->student->matricule ?? 'N/A' }})</p>
                <p style="margin: 0 0 6px;"><strong>Type de Requête :</strong> {{ $studentRequest->requestType->title ?? 'Générale' }}</p>
                <p style="margin: 0;"><strong>Objet :</strong> {{ $studentRequest->subject }}</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('requests.show', $studentRequest->id) }}" class="btn">Consulter la Requête</a>
            </div>
        </div>
        <div class="footer">
            Devia Technologic © {{ date('Y') }} — Notification Automatique.
        </div>
    </div>
</body>
</html>
