<!DOCTYPE html>
<html lang="pt-BR" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel') — DFS</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.x/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --teal:       #1B4A52;
            --teal-light: #245266;
            --teal-dark:  #122e34;
            --gold:       #8B6914;
            --gold-light: #b8935a;
            --dark:       #0f1923;
            --dark2:      #111c27;
            --card:       #162230;
            --card2:      #1a2a3a;
            --border:     rgba(255,255,255,0.07);
            --border2:    rgba(255,255,255,0.12);
            --text:       #e2e8f0;
            --text2:      #cbd5e1;
            --muted:      #64748b;
            --sidebar-w:  240px;

            /* Status colors */
            --green:  #22c55e;
            --red:    #ef4444;
            --yellow: #f59e0b;
            --blue:   #3b82f6;
        }

        html, body { height: 100%; }

        body {
            font-family: sans-serif;
            background: var(--dark);
            color: var(--text);
            display: flex;
        }

        /* ── Sidebar ──────────────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--teal);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.25s ease;
        }

        .sidebar-logo {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo .monogram {
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: bold;
            font-family: Georgia, serif;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo .brand {
            font-family: Georgia, serif;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.9);
            line-height: 1.3;
        }

        .sidebar-logo .subbrand {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 0.65rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 0.75rem 1.25rem 0.3rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.875rem;
            transition: background 0.15s, color 0.15s;
            border-radius: 0;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
            font-weight: 500;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--gold-light);
            border-radius: 0 2px 2px 0;
        }

        .nav-item .icon {
            width: 18px;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--red);
            color: #fff;
            font-size: 0.65rem;
            padding: 1px 6px;
            border-radius: 9999px;
            min-width: 18px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .user-details { min-width: 0; }

        .user-name {
            font-size: 0.8rem;
            color: #fff;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0.05em;
        }

        /* ── Main ──────────────────────────────────────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ─────────────────────────────────────────────────────────── */
        .topbar {
            background: var(--dark2);
            border-bottom: 1px solid var(--border);
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .breadcrumb {
            font-size: 0.85rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .breadcrumb .current {
            color: var(--text);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-btn {
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 6px;
            transition: color 0.15s, background 0.15s;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .topbar-btn:hover {
            color: var(--text);
            background: rgba(255,255,255,0.05);
        }

        .notif-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background: var(--red);
            border-radius: 50%;
        }

        /* ── Content ────────────────────────────────────────────────────────── */
        .content {
            padding: 1.75rem;
            flex: 1;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-family: Georgia, serif;
            font-size: 1.4rem;
            color: var(--text);
            font-weight: normal;
        }

        .page-subtitle {
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }

        /* ── Cards ──────────────────────────────────────────────────────────── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .card-title {
            font-size: 0.8rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }

        .kpi-value {
            font-family: Georgia, serif;
            font-size: 1.8rem;
            color: var(--text);
            font-weight: normal;
        }

        .kpi-sub {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }

        .kpi-up   { color: var(--green); }
        .kpi-down { color: var(--red); }
        .kpi-warn { color: var(--yellow); }

        /* ── Grid ───────────────────────────────────────────────────────────── */
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }

        @media (max-width: 1200px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px)  {
            .grid-4, .grid-3, .grid-2 { grid-template-columns: 1fr; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
        }

        /* ── Tables ─────────────────────────────────────────────────────────── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--muted);
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.1s;
        }

        tbody tr:hover { background: rgba(255,255,255,0.02); }

        tbody td {
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
        }

        /* ── Badges ─────────────────────────────────────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.03em;
        }

        .badge-green  { background: rgba(34,197,94,0.15);  color: var(--green); }
        .badge-red    { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-yellow { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-blue   { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .badge-teal   { background: rgba(27,74,82,0.4);    color: #7dd3dc; }
        .badge-gold   { background: rgba(139,105,20,0.25); color: var(--gold-light); }
        .badge-gray   { background: rgba(100,116,139,0.2); color: var(--muted); }

        /* ── Buttons ────────────────────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-primary   { background: var(--teal);    color: #fff; }
        .btn-primary:hover { background: var(--teal-light); }
        .btn-gold      { background: var(--gold);    color: #fff; }
        .btn-gold:hover { background: #a07820; }
        .btn-ghost     { background: rgba(255,255,255,0.06); color: var(--text); }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); }
        .btn-danger    { background: rgba(239,68,68,0.15); color: #f87171; }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .btn-sm        { padding: 0.3rem 0.75rem; font-size: 0.8rem; }

        /* ── Forms ──────────────────────────────────────────────────────────── */
        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: block;
            font-size: 0.78rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border2);
            border-radius: 8px;
            padding: 0.65rem 0.875rem;
            color: var(--text);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.15s;
        }

        .form-control:focus {
            border-color: var(--teal-light);
            background: rgba(27,74,82,0.08);
        }

        select.form-control option { background: var(--card); }

        .form-error {
            font-size: 0.78rem;
            color: #f87171;
            margin-top: 0.3rem;
        }

        /* ── Alerts ─────────────────────────────────────────────────────────── */
        .alert { padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.875rem; }
        .alert-success { background: rgba(34,197,94,0.1);  border: 1px solid rgba(34,197,94,0.25);  color: #86efac; }
        .alert-error   { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.25);  color: #fca5a5; }
        .alert-warn    { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.25); color: #fde68a; }
        .alert-info    { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd; }

        /* ── Misc ───────────────────────────────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.25rem 0;
        }

        .text-muted { color: var(--muted); font-size: 0.85rem; }

        /* ── Modals ─────────────────────────────────────────────────────────── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 200;
            backdrop-filter: blur(2px);
        }

        .modal {
            background: var(--card);
            border: 1px solid var(--border2);
            border-radius: 16px;
            padding: 1.75rem;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-title {
            font-family: Georgia, serif;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            color: var(--text);
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ──────────────────────────────────────────────────────── --}}
<aside class="sidebar" :class="{ 'open': sidebarOpen }">
    <div class="sidebar-logo">
        <div class="monogram">DFS</div>
        <div class="brand">Dalacorte Financial<br>Solutions</div>
        <div class="subbrand">Sistema Interno</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>

        <a href="{{ route('painel.dashboard') }}"
           class="nav-item {{ request()->routeIs('painel.dashboard') ? 'active' : '' }}">
            <span class="icon">⊞</span> Dashboard
        </a>

        <a href="{{ route('painel.empresas.index') }}"
           class="nav-item {{ request()->routeIs('painel.empresas*') ? 'active' : '' }}">
            <span class="icon">🏢</span> Empresas
        </a>

        <a href="{{ route('painel.tarefas.index') }}"
           class="nav-item {{ request()->routeIs('painel.tarefas*') ? 'active' : '' }}">
            <span class="icon">✓</span> Tarefas e Obrigações
        </a>

        <div class="nav-section-label">Financeiro</div>

        <a href="{{ route('painel.financeiro.index') }}"
           class="nav-item {{ request()->routeIs('painel.financeiro*') ? 'active' : '' }}">
            <span class="icon">$</span> Financeiro
        </a>

        <div class="nav-section-label">Documentos</div>

        <a href="{{ route('painel.documentos.index') }}"
           class="nav-item {{ request()->routeIs('painel.documentos*') ? 'active' : '' }}">
            <span class="icon">📄</span> Documentos
        </a>

        <a href="{{ route('painel.certidoes.index') }}"
           class="nav-item {{ request()->routeIs('painel.certidoes*') ? 'active' : '' }}">
            <span class="icon">📋</span> Certidões
        </a>

        <a href="{{ route('painel.certificados.index') }}"
           class="nav-item {{ request()->routeIs('painel.certificados*') ? 'active' : '' }}">
            <span class="icon">🔑</span> Certificados Digitais
        </a>

        <div class="nav-section-label">Gestão</div>

        <a href="{{ route('painel.demandas.index') }}"
           class="nav-item {{ request()->routeIs('painel.demandas*') ? 'active' : '' }}">
            <span class="icon">📌</span> Demandas
        </a>

        <a href="{{ route('painel.projetos.index') }}"
           class="nav-item {{ request()->routeIs('painel.projetos*') ? 'active' : '' }}">
            <span class="icon">🗂</span> Projetos Internos
        </a>

        <a href="{{ route('painel.notificacoes.index') }}"
           class="nav-item {{ request()->routeIs('painel.notificacoes*') ? 'active' : '' }}">
            <span class="icon">🔔</span> Notificações
            @php $naoLidas = auth()->user()->notificacoesNaoLidas()->count(); @endphp
            @if ($naoLidas > 0)
                <span class="nav-badge">{{ $naoLidas > 99 ? '99+' : $naoLidas }}</span>
            @endif
        </a>

        <a href="{{ route('painel.indicadores.index') }}"
           class="nav-item {{ request()->routeIs('painel.indicadores*') ? 'active' : '' }}">
            <span class="icon">📊</span> Indicadores
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">Administração</div>

        <a href="{{ route('painel.usuarios.index') }}"
           class="nav-item {{ request()->routeIs('painel.usuarios*') ? 'active' : '' }}">
            <span class="icon">👥</span> Usuários
        </a>
        @endif

        @if(auth()->user()->isGestor())
        <a href="{{ route('painel.configuracoes.index') }}"
           class="nav-item {{ request()->routeIs('painel.configuracoes*') ? 'active' : '' }}">
            <span class="icon">⚙</span> Configurações
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-details">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
    </div>
</aside>

{{-- ── Main ──────────────────────────────────────────────────────────── --}}
<div class="main-wrap">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <button class="topbar-btn" @click="sidebarOpen = !sidebarOpen" style="display:none" id="hamburger">
                ☰
            </button>
            <div class="breadcrumb">
                <span>DFS</span>
                <span>/</span>
                <span class="current">@yield('breadcrumb', 'Painel')</span>
            </div>
        </div>

        <div class="topbar-right">
            <a href="{{ route('painel.notificacoes.index') }}" class="topbar-btn" title="Notificações">
                🔔
                @php $naoLidas = auth()->user()->notificacoesNaoLidas()->count(); @endphp
                @if($naoLidas > 0)
                    <span class="notif-badge"></span>
                @endif
            </a>

            <span style="font-size:0.85rem; color:var(--muted);">{{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="topbar-btn" title="Sair">
                    ↗ Sair
                </button>
            </form>
        </div>
    </header>

    {{-- Content --}}
    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">✕ {{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
