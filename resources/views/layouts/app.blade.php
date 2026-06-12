<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lord Of Wraps')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css">
    <style>
        /* ===== SLATE PROFESSIONAL LIGHT THEME ===== */

        /* --- Color Tokens --- */
        :root {
            --color-page-bg:           #f1f5f9;
            --color-sidebar-bg:         #1e293b;
            --color-sidebar-text:       #94a3b8;
            --color-sidebar-active-bg:  #6366f1;
            --color-sidebar-active-text:#ffffff;
            --color-sidebar-hover-bg:   #273549;

            --color-card-bg:            #ffffff;
            --color-card-border:        #e2e8f0;

            --color-text-primary:       #1e293b;
            --color-text-secondary:     #64748b;
            --color-text-muted:         #94a3b8;

            --color-accent:             #6366f1;
            --color-accent-hover:       #4f46e5;
            --color-accent-light:       #eef2ff;

            --color-success:            #16a34a;
            --color-success-light:       #f0fdf4;
            --color-warning:            #d97706;
            --color-warning-light:       #fffbeb;
            --color-danger:             #dc2626;
            --color-danger-light:        #fef2f2;

            --color-border:             #e2e8f0;
            --color-input-bg:           #f8fafc;

            --radius-sm:  6px;
            --radius-md:  8px;
            --radius-lg:  12px;
            --shadow-sm:  0 1px 2px rgba(0,0,0,0.05);
            --shadow-md:  0 4px 6px rgba(0,0,0,0.07);
        }

        /* --- Base --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: var(--color-page-bg);
            color: var(--color-text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* --- Layout Shell --- */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 230px;
            min-height: 100vh;
            flex-shrink: 0;
            background: var(--color-sidebar-bg);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .brand-section {
            padding: 22px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 8px;
        }
        .brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }
        .brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            margin-top: 3px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-sidebar-text);
            text-decoration: none;
            border-radius: var(--radius-md);
            margin: 1px 10px;
            width: calc(100% - 20px);
            transition: all 0.15s ease;
        }
        .nav-item:hover {
            color: #e2e8f0;
            background: var(--color-sidebar-hover-bg);
        }
        .nav-item.active {
            color: var(--color-sidebar-active-text);
            background: var(--color-sidebar-active-bg);
        }
        .nav-icon { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 14px 26px 4px;
        }

        /* Sidebar footer / user */
        .sidebar-user-section {
            margin-top: auto;
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .user-info {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }
        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
        }
        .user-role {
            font-size: 10px;
            color: var(--color-sidebar-active-bg);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
        }
        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: transparent;
            border: none;
            border-radius: var(--radius-md);
            color: #f87171;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
        }
        .logout-btn:hover {
            background: rgba(220,38,38,0.12);
            color: #fca5a5;
        }

        .sidebar-footer {
            padding: 12px 20px;
            font-size: 10px;
            color: rgba(255,255,255,0.2);
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        /* --- Main Content --- */
        .main-wrap {
            flex: 1;
            padding: 28px 32px;
            overflow-y: auto;
            min-height: 100vh;
        }
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--color-text-primary);
            letter-spacing: -0.3px;
        }
        .page-subtitle {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin-top: 3px;
        }

        /* --- Cards --- */
        .card {
            background: var(--color-card-bg);
            border: 1px solid var(--color-card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 22px;
            margin-bottom: 16px;
            transition: box-shadow 0.2s;
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--color-text-muted);
            margin-bottom: 16px;
        }

        /* Stat cards */
        .stat-card {
            border-radius: var(--radius-lg);
            padding: 20px;
            background: var(--color-card-bg);
            border: 1px solid var(--color-card-border);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-text-muted);
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 600;
            color: var(--color-text-primary);
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .stat-sub {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin-top: 5px;
        }

        /* Stat accent colors for dashboard cards */
        .stat-card.accent-blue { border-left: 4px solid #6366f1; }
        .stat-card.accent-red  { border-left: 4px solid #dc2626; }
        .stat-card.accent-green{ border-left: 4px solid #16a34a; }
        .stat-card.accent-amber{ border-left: 4px solid #d97706; }
        .stat-card.accent-cyan { border-left: 4px solid #0891b2; }
        .stat-card.accent-purple{ border-left: 4px solid #9333ea; }

        /* --- Tables --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table thead tr {
            background: #f8fafc;
        }
        .data-table th {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--color-text-muted);
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--color-border);
        }
        .data-table td {
            padding: 12px 14px;
            font-size: 13px;
            color: var(--color-text-primary);
            border-bottom: 1px solid var(--color-border);
            background: var(--color-card-bg);
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #f8fafc; }

        /* --- Buttons --- */
        .btn-primary {
            background: var(--color-accent);
            color: #ffffff;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
            display: inline-block;
            text-decoration: none;
        }
        .btn-primary:hover { background: var(--color-accent-hover); color: #ffffff; }

        .btn-secondary {
            background: var(--color-card-bg);
            color: var(--color-text-primary);
            border: 1px solid var(--color-border);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            padding: 9px 16px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-danger {
            background: var(--color-danger);
            color: #ffffff;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            padding: 9px 16px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger:hover { background: #b91c1c; }

        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .btn-xs { padding: 3px 8px; font-size: 11px; border-radius: var(--radius-sm); }

        /* --- Form Inputs --- */
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            background: var(--color-input-bg);
            border: 1px solid var(--color-border);
            color: var(--color-text-primary);
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 2px rgba(99,102,241,0.15);
            background: #ffffff;
        }
        .form-input::placeholder { color: var(--color-text-muted); }
        .form-select option { background: #ffffff; color: var(--color-text-primary); }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-secondary);
            margin-bottom: 6px;
        }
        .form-group { margin-bottom: 18px; }
        .form-error {
            color: var(--color-danger);
            font-size: 12px;
            margin-top: 4px;
        }

        /* Stock entry inputs */
        .stock-input {
            padding: 7px 8px;
            font-size: 13px;
            background: var(--color-input-bg);
            border: 1px solid var(--color-border);
            color: var(--color-text-primary);
            border-radius: var(--radius-sm);
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.15s;
            width: 100%;
        }
        .stock-input:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 2px rgba(99,102,241,0.1);
            background: #ffffff;
        }
        .stock-input.edited {
            background: #fef9ec;
            border-color: #fbbf24;
        }
        .stock-input[readonly] {
            background: #f1f5f9;
            color: var(--color-text-muted);
            cursor: default;
        }
        /* Opening color coding */
        .opening-auto {
            background: #eff6ff !important;
            border-color: #93c5fd !important;
            color: #1e40af !important;
        }
        .opening-manual {
            background: #fef9ec !important;
            border-color: #fbbf24 !important;
            color: #92400e !important;
        }

        /* --- Badges / Pills --- */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
        }
        .badge-cash      { background: #dbeafe; color: #1d4ed8; }
        .badge-gp        { background: #dcfce7; color: #15803d; }
        .badge-swiggy    { background: #ffedd5; color: #c2410c; }
        .badge-zomato    { background: #fee2e2; color: #b91c1c; }
        .badge-ok        { background: #dcfce7; color: #15803d; }
        .badge-low       { background: #fee2e2; color: #b91c1c; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-paid      { background: #dcfce7; color: #15803d; }
        .badge-unpaid    { background: #fee2e2; color: #b91c1c; }
        .badge-active    { background: #dcfce7; color: #15803d; }
        .badge-inactive  { background: #f1f5f9; color: #64748b; }
        .badge-received  { background: #dcfce7; color: #15803d; }
        .badge-disputed  { background: #ffedd5; color: #c2410c; }
        .badge-mayo      { background: #fef3c7; color: #92400e; }
        .badge-other     { background: #f3e8ff; color: #6b21a8; }

        /* --- Alerts --- */
        .alert-success {
            background: var(--color-success-light);
            border: 1px solid #bbf7d0;
            color: var(--color-success);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            font-size: 13px;
        }
        .alert-error {
            background: var(--color-danger-light);
            border: 1px solid #fecaca;
            color: var(--color-danger);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            font-size: 13px;
        }
        .alert-warning {
            background: var(--color-warning-light);
            border: 1px solid #fde68a;
            color: var(--color-warning);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 16px;
            font-size: 13px;
        }

        /* --- Date Badge --- */
        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--color-card-bg);
            border: 1px solid var(--color-border);
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 13px;
            color: var(--color-text-secondary);
            margin-bottom: 20px;
        }
        .today-pill {
            background: var(--color-accent-light);
            color: var(--color-accent);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* --- Section divider --- */
        .section-divider {
            height: 1px;
            background: var(--color-border);
            margin: 20px 0;
        }

        /* --- Page header bar (top of content) --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* --- Scrollbar --- */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* --- Links --- */
        a { text-decoration: none; }
        .link { color: var(--color-accent); font-size: 13px; font-weight: 500; }
        .link:hover { color: var(--color-accent-hover); }
        .link-danger { color: var(--color-danger); font-size: 13px; font-weight: 500; }
        .link-danger:hover { color: #b91c1c; }

        /* --- WhatsApp Share Button --- */
        .whatsapp-share-btn {
            position: fixed;
            top: 20px;
            right: 24px;
            background: var(--color-accent);
            color: #ffffff;
            border: none;
            border-radius: 999px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(99,102,241,0.35);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .whatsapp-share-btn:hover {
            background: var(--color-accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99,102,241,0.4);
        }
        .whatsapp-share-btn:disabled {
            opacity: 0.7;
            cursor: wait;
        }

        /* --- Modal overlay --- */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.5);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(2px);
        }
        .modal-box {
            background: var(--color-card-bg);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            max-width: 560px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text-primary);
        }
        .modal-close {
            background: #f1f5f9;
            border: none;
            color: var(--color-text-secondary);
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }
        .modal-close:hover { background: #e2e8f0; color: var(--color-text-primary); }
        .modal-body { padding: 20px 24px; }
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--color-border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* --- Empty state --- */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--color-text-muted);
        }
        .empty-state-icon { font-size: 40px; margin-bottom: 12px; }
        .empty-state h3 { font-size: 15px; font-weight: 600; color: var(--color-text-secondary); margin-bottom: 6px; }
        .empty-state p { font-size: 13px; }

        /* --- Grid helpers --- */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
        @media (max-width: 1024px) {
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .grid-3, .grid-2, .grid-4 { grid-template-columns: 1fr; }
            .main-wrap { padding: 16px; }
        }

        /* --- Misc utilities --- */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: ui-monospace, monospace; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body>
<div class="app-shell">

    <!-- ======= SIDEBAR ======= -->
    <aside class="sidebar">
        <div class="brand-section">
            <div class="brand-name">Lord Of Wraps</div>
            <div class="brand-sub">Restaurant Management</div>
        </div>

        <nav>
            @if(Auth::check() && in_array(Auth::user()->role, ['admin']))
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-layout-dashboard"></i></span> Dashboard
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin']))
            <a href="{{ route('suppliers.index') }}"
               class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-building-store"></i></span> Suppliers
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'manager']))
            <a href="{{ route('inventory.daily') }}"
               class="nav-item {{ request()->routeIs('inventory*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-package"></i></span> Inventory
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'accounts']))
            <a href="{{ route('expenses.index') }}"
               class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-receipt"></i></span> Expenses
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'accounts']))
            <a href="{{ route('payments.index') }}"
               class="nav-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-credit-card"></i></span> Payments
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'accounts']))
            <a href="{{ route('sales.index') }}"
               class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-shopping-cart"></i></span> Sales
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin']))
            <a href="{{ route('staff.index') }}"
               class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-users"></i></span> Staff
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin']))
            <a href="{{ route('payroll.index') }}"
               class="nav-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-cash"></i></span> Payroll
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'accounts']))
            <a href="{{ route('settlements.index') }}"
               class="nav-item {{ request()->routeIs('settlements.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-building-bank"></i></span> Settlements
            </a>
            @endif

            @if(Auth::check() && in_array(Auth::user()->role, ['admin']))
            <a href="{{ route('backup.index') }}"
               class="nav-item {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="ti ti-database"></i></span> Backup
            </a>
            @endif
        </nav>

        @auth
        <div class="sidebar-user-section">
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="nav-icon"><i class="ti ti-logout"></i></span> Logout
                </button>
            </form>
        </div>
        @endauth

        <div class="sidebar-footer">Lord Of Wraps v1.0</div>
    </aside>

    <!-- ======= MAIN ======= -->
    <main class="main-wrap">
        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">❌ {{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</div>

<!-- WhatsApp Share Button -->
<button class="whatsapp-share-btn" onclick="shareViaWhatsApp()" id="whatsappBtn">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    Share
</button>

<script>
function shareViaWhatsApp() {
    var btn = document.getElementById('whatsappBtn');
    var originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Capturing...';

    var inputs = document.querySelectorAll('input[type="text"], input[type="number"], input:not([type])');
    var replacements = [];
    inputs.forEach(function(input) {
        if (input.style.display === 'none') return;
        var div = document.createElement('div');
        div.textContent = input.value || input.placeholder || '0';
        var cs = window.getComputedStyle(input);
        div.style.cssText = cs.cssText;
        div.style.display = 'flex'; div.style.alignItems = 'center';
        div.style.justifyContent = 'center'; div.style.height = cs.height;
        div.style.borderRadius = cs.borderRadius; div.style.fontSize = cs.fontSize;
        div.style.padding = cs.padding;
        input.parentNode.insertBefore(div, input);
        input.style.display = 'none';
        replacements.push({ input: input, div: div });
    });

    html2canvas(document.body, { scale: 1, useCORS: true, scrollY: -window.scrollY })
    .then(function(canvas) {
        replacements.forEach(function(r) { r.input.style.display = ''; r.div.remove(); });
        var pageName = window.location.pathname.replace(/\//g,'').replace(/-/g,'') || 'dashboard';
        var date = new Date().toISOString().split('T')[0];
        var link = document.createElement('a');
        link.download = 'flavordesk-' + pageName + '-' + date + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        window.open('https://web.whatsapp.com', '_blank');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    })
    .catch(function(err) {
        replacements.forEach(function(r) { r.input.style.display = ''; r.div.remove(); });
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    });
}
</script>

@stack('scripts')
</body>
</html>
