<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur Devia Technologic</title>
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
            <p>Bienvenue sur la Plateforme des Requêtes</p>
        </div>
        <div class="content">
            <h2>Bonjour {{ $user->name }},</h2>
            <p>Votre compte a été créé avec succès sur le portail Devia Technologic.</p>

            <div class="badge-box">
                <p style="margin: 0 0 6px;"><strong>Identifiant (Email) :</strong> {{ $user->email }}</p>
                <p style="margin: 0 0 6px;"><strong>Matricule / Code :</strong> {{ $user->matricule }}</p>
                <p style="margin: 0;"><strong>Rôle :</strong> {{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
            </div>

            <p>Vous pouvez dès à présent vous connecter pour accéder à votre espace de travail.</p>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Se Connecter à la Plateforme</a>
            </div>
        </div>
        <div class="footer">
            Devia Technologic © {{ date('Y') }} — Message Automatique de Bienvenue.
        </div>
    </div>
</body>
</html>
