<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Devia Technologic — Management des Requêtes')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #f6f8fc;
            --surface-white: #ffffff;
            --border-subtle: #e2e8f0;
            --border-hover: #cbd5e1;
            
            /* Curated Color Palette */
            --brand-primary: #4f46e5;
            --brand-violet: #7c3aed;
            --brand-pink: #ec4899;
            --brand-emerald: #10b981;
            --brand-amber: #f59e0b;
            --brand-rose: #f43f5e;
            
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            
            --shadow-sm: 0 2px 4px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 8px 24px rgba(15, 23, 42, 0.06);
            --shadow-lg: 0 16px 40px rgba(15, 23, 42, 0.08);
            --shadow-glow: 0 8px 25px rgba(79, 70, 229, 0.25);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; }
        html, body { background-color: var(--bg-page); color: var(--text-body); min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; width: 100%; padding-top: 72px; }

        h1, h2, h3, h4, .brand-font { font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif; }

        /* Fixed Navbar pinned to top */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.85);
            padding: 0.85rem 2.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--text-heading);
        }

        .brand-icon-box {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-violet) 50%, var(--brand-pink) 100%);
            color: white;
            font-weight: 800;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-glow);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .brand-logo:hover .brand-icon-box { transform: scale(1.06) rotate(-3deg); }

        .brand-text h1 { font-size: 1.2rem; font-weight: 800; color: var(--text-heading); letter-spacing: -0.3px; }
        .brand-text p { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info-chip {
            background: var(--surface-white);
            border: 1px solid var(--border-subtle);
            padding: 6px 14px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            box-shadow: var(--shadow-sm);
        }

        .role-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .role-etudiant { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .role-gestionnaire { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .role-responsable_pedagogique { background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
        .role-admin_systeme { background: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }

        .btn-settings {
            background: #f1f5f9;
            color: var(--text-heading);
            border: 1px solid var(--border-subtle);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-settings:hover { background: #e2e8f0; transform: translateY(-1px); }

        .btn-logout {
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-logout:hover { background: #ffe4e6; transform: translateY(-1px); }

        /* General Back Button Style */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1.5px solid var(--border-subtle);
            color: var(--text-heading);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            background: #f8fafc;
            border-color: var(--brand-primary);
            color: var(--brand-primary);
            transform: translateX(-3px);
        }

        /* Mobile Burger Toggle & Menu */
        .burger-toggle {
            display: none;
            background: #f1f5f9;
            border: 1px solid var(--border-subtle);
            padding: 8px 12px;
            border-radius: 10px;
            cursor: pointer;
            color: var(--text-heading);
        }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 68px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-subtle);
            padding: 1.25rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            z-index: 999;
            flex-direction: column;
            gap: 0.75rem;
        }
        .mobile-menu.open { display: flex; }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            color: var(--text-heading);
            font-weight: 700;
            font-size: 0.92rem;
            text-decoration: none;
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
        }

        @media (max-width: 768px) {
            .user-nav { display: none; }
            .burger-toggle { display: inline-flex; align-items: center; justify-content: center; }
            .navbar { padding: 0.85rem 1.25rem; }
        }

        .main-container {
            max-width: 1280px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            padding: 1rem 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            padding: 1rem 1.25rem;
            border-radius: 14px;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        /* Buttons & Actions */
        .btn-primary {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-violet) 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.92rem;
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-glow);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.35);
        }

        /* Responsive Grid & Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
            width: 100%;
        }

        .stat-card {
            background: var(--surface-white);
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            padding: 1.35rem 1.5rem;
            box-shadow: var(--shadow-md);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: var(--border-hover); }
        .stat-label { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 2.1rem; font-weight: 800; color: var(--text-heading); font-family: 'Outfit', sans-serif; }

        .card-panel {
            background: var(--surface-white);
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            width: 100%;
            overflow: hidden;
        }

        .table-responsive { width: 100%; overflow-x: auto; margin-top: 1rem; }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
        th { padding: 14px 18px; color: var(--text-muted); border-bottom: 2px solid #f1f5f9; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; background: #fafcfb; }
        td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; color: var(--text-body); vertical-align: middle; }
        tr:hover td { background: #fafcfb; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .status-en_attente { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .status-en_instruction { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
        .status-avis_pedagogique_requis { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
        .status-approuvee { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .status-rejetee { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }

        footer {
            border-top: 1px solid var(--border-subtle);
            padding: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            background: var(--surface-white);
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="brand-logo">
            <div class="brand-icon-box">D</div>
            <div class="brand-text">
                <h1>Devia Technologic</h1>
                <p>Plateforme de Gestion des Requêtes</p>
            </div>
        </a>
        @auth
            <div class="user-nav">
                <div class="user-info-chip">
                    <span style="font-weight: 700; color: var(--text-heading);">{{ auth()->user()->name }}</span>
                    <span class="role-badge role-{{ auth()->user()->role }}">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn-settings">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Paramètres
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Déconnexion</button>
                </form>
            </div>

            <button class="burger-toggle" onclick="document.getElementById('mobileMenu').classList.toggle('open')" aria-label="Menu Mobile">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
        @endauth
    </nav>

    @auth
        <div class="mobile-menu" id="mobileMenu">
            <div style="padding: 6px 12px; margin-bottom: 4px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; border-radius: 10px; border: 1px solid var(--border-subtle);">
                <span style="font-weight: 700; color: var(--text-heading); font-size: 0.88rem;">{{ auth()->user()->name }}</span>
                <span class="role-badge role-{{ auth()->user()->role }}">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
            </div>

            <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Tableau de Bord
            </a>

            @if(auth()->user()->isEtudiant())
                <a href="{{ route('requests.create') }}" class="mobile-nav-link" style="color: var(--brand-primary); border-color: #c7d2fe;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Nouvelle Requête
                </a>
            @endif

            @if(auth()->user()->isAdminSysteme())
                <a href="{{ route('admin.users.index') }}" class="mobile-nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                    Gérer les Utilisateurs
                </a>
                <a href="{{ route('admin.request-types.index') }}" class="mobile-nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
                    Types de Requêtes
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="mobile-nav-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                Paramètres du Compte
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin-top: 4px;">
                @csrf
                <button type="submit" class="mobile-nav-link" style="width: 100%; color: #e11d48; border-color: #fecdd3; background: #fff1f2; justify-content: center; cursor: pointer;">
                    Déconnexion
                </button>
            </form>
        </div>
    @endauth
    </nav>

    <main class="main-container">
        @if(session('success'))
            <div class="alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>{{ session('success') }}</span>
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
        Devia Technologic © {{ date('Y') }} — Système de Gestion des Requêtes. Tous droits réservés.
    </footer>
</body>
</html>
