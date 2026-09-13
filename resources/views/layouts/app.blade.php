<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Requêtes Étudiantes - IME Douala')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-500: #4338ca;
            --bg-slate: #0f172a;
            --surface-card: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-light: rgba(255, 255, 255, 0.1);
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-slate); color: var(--text-main); margin: 0; }
        .app-header { background: rgba(15,23,42,0.9); padding: 1rem 2rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1300px; margin: 2rem auto; padding: 0 1rem; }
        .btn-primary { background: var(--primary-500); color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; }
    </style>
    @stack('styles')
</head>
<body>
    <header class="app-header">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="background: linear-gradient(135deg, #4f46e5, #9333ea); width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: white;">IME</div>
            <div>
                <h1 style="font-size: 1.1rem; margin: 0;">Gestion des Requêtes Étudiantes</h1>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">Institut des Managers Experts — Douala</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span>{{ auth()->user()->name ?? 'Utilisateur' }} ({{ ucfirst(auth()->user()->role ?? 'invité') }})</span>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #6ee7b7; padding: 12px 16px; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
