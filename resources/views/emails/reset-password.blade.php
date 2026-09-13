<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de votre mot de passe — Devia Technologic</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f6f8fc; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 5px 0 0; font-size: 0.85rem; opacity: 0.9; }
        .content { padding: 30px 25px; line-height: 1.6; }
        .btn { display: inline-block; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; margin: 20px 0; text-align: center; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 15px 20px; text-align: center; font-size: 0.78rem; color: #64748b; }
        .note { font-size: 0.82rem; color: #64748b; background: #f1f5f9; padding: 12px 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #7c3aed; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Devia Technologic</h1>
            <p>Plateforme de Gestion des Requêtes Académiques</p>
        </div>
        <div class="content">
            <h2>Bonjour {{ $userName }},</h2>
            <p>Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte <strong>{{ $userEmail }}</strong>.</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="btn">Réinitialiser Mon Mot de Passe</a>
            </div>

            <p>Ce lien de réinitialisation expirera dans 60 minutes.</p>

            <div class="note">
                Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action supplémentaire n'est requise de votre part. Votre mot de passe actuel reste inchangé.
            </div>
        </div>
        <div class="footer">
            Devia Technologic © {{ date('Y') }} — Tous droits réservés.
        </div>
    </div>
</body>
</html>
