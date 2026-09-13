<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion des Requêtes Étudiantes')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-light: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --accent-purple: #7c3aed;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0284c7;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
        html, body { background-color: var(--bg-light); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; width: 100%; }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 0.9rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-badge {
            background: linear-gradient(135deg, var(--primary), var(--accent-purple));
            color: white;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }

        .brand-text h1 { font-size: 1.15rem; font-weight: 800; color: var(--text-main); }
        .brand-text p { font-size: 0.75rem; color: var(--text-sub); }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info-chip {
            background: #f1f5f9;
            border: 1px solid var(--card-border);
            padding: 6px 14px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        .role-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 12px;
            text-transform: uppercase;
        }
        .role-etudiant { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .role-gestionnaire { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .role-responsable_pedagogique { background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
        .role-admin_systeme { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .btn-logout {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-logout:hover { background: #fca5a5; color: #7f1d1d; }

        .main-container {
            max-width: 1280px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        /* Grid & Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .stat-label { font-size: 0.85rem; color: var(--text-sub); margin-bottom: 6px; font-weight: 500; }
        .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--text-main); }

        .card-panel {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            margin-bottom: 2rem;
            width: 100%;
            overflow: hidden;
        }

        .table-responsive { width: 100%; overflow-x: auto; margin-top: 1rem; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
        th { padding: 12px 16px; color: var(--text-sub); border-bottom: 2px solid #f1f5f9; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; background: #f8fafc; }
        td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: var(--text-main); vertical-align: middle; }
        tr:hover td { background: #f8fafc; }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }

        .status-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .status-en_attente { background: #fef3c7; color: #b45309; }
        .status-en_instruction { background: #e0f2fe; color: #0369a1; }
        .status-avis_pedagogique_requis { background: #f3e8ff; color: #7e22ce; }
        .status-approuvee { background: #d1fae5; color: #047857; }
        .status-rejetee { background: #fee2e2; color: #b91c1c; }

        footer {
            border-top: 1px solid var(--card-border);
            padding: 1.25rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-sub);
            background: #ffffff;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="brand-logo">
            <span class="brand-badge">DEVIA</span>
            <div class="brand-text">
                <h1>Plateforme des Requêtes</h1>
                <p>Gestion Numérique des Demandes Étudiantes</p>
            </div>
        </a>
        @auth
            <div class="user-nav">
                <div class="user-info-chip">
                    <span style="font-weight: 700;">{{ auth()->user()->name }}</span>
                    <span class="role-badge role-{{ auth()->user()->role }}">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Déconnexion</button>
                </form>
            </div>
        @endauth
    </nav>

    <main class="main-container">
        @if(session('success'))
            <div class="alert-success">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        Devia Technologic © {{ date('Y') }} — Plateforme de Gestion des Requêtes Étudiantes. Tous droits réservés.
    </footer>
</body>
</html>
