<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kepala Sekolah') - Jurnal & Absensi Guru</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        :root {
            --app-bg: #f8fafc;
            --app-card: #ffffff;
            --app-border: #e5e7eb;
            --app-primary: #2563eb;
            --app-text: #111827;
            --app-subtext: #6b7280;
        }


        
        /* Top Navigation Header Bar */



        /* Welcome Card */

        /* Summary Stats Cards Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--app-border);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; flex-shrink: 0;
        }
        .stat-val { font-size: 1.65rem; font-weight: 800; color: var(--app-text); line-height: 1.1; }
        .stat-label { font-size: 0.675rem; font-weight: 800; color: var(--app-subtext); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        /* Main Content Container */
        .content-card {
            background: #ffffff;
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        /* Tabs Header */
        .tabs-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--app-border);
            background: #ffffff;
            padding: 0 16px;
        }
        .tab-btn {
            padding: 14px 20px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--app-subtext);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .tab-btn:hover { color: var(--app-primary); }
        .tab-btn.active {
            color: var(--app-primary);
            border-bottom-color: var(--app-primary);
            background: #ffffff;
        }

        /* Filter Controls Bar */
        .filter-bar {
            padding: 14px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid var(--app-border);
            background: #ffffff;
        }
        .filter-status-group { display: flex; align-items: center; gap: 6px; }
        .filter-chip {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--app-subtext);
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .filter-chip.active {
            background: #e2e8f0;
            color: #111827;
            border-color: #cbd5e1;
        }

        .search-container { position: relative; display: flex; align-items: center; }
        .search-input {
            padding: 7px 34px 7px 14px;
            border-radius: 8px;
            border: 1px solid var(--app-border);
            font-size: 0.85rem;
            width: 230px;
            outline: none;
        }
        .search-input:focus { border-color: var(--app-primary); }
        .search-icon-inside {
            position: absolute;
            right: 12px;
            color: #9ca3af;
            font-size: 0.85rem;
            pointer-events: none;
        }

        /* Data Table Styling */
        .table-responsive { width: 100%; overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; }
        .data-table th {
            background: #f9fafb;
            padding: 12px 16px;
            font-size: 0.725rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #4b5563;
            border-bottom: 1px solid var(--app-border);
        }
        .data-table td { padding: 14px 16px; border-bottom: 1px solid var(--app-border); vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f8fafc; }

        /* Approval Dot Track */
        .vote-section { display: flex; flex-direction: column; gap: 3px; min-width: 140px; }
        .vote-title { font-size: 0.65rem; font-weight: 800; color: #6b7280; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 2px; }
        .vote-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #374151; }
        .dot-icon { font-size: 0.5rem; line-height: 1; }
        .dot-green { color: #10b981; }
        .dot-red { color: #ef4444; }
        .dot-gray { color: #9ca3af; }

        /* Action Buttons */
        .btn-sm-approve {
            background: #10b981; color: #ffffff; border: none; padding: 6px 14px;
            border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: background 0.2s ease;
        }
        .btn-sm-approve:hover { background: #059669; }
        .btn-sm-reject {
            background: #ef4444; color: #ffffff; border: none; padding: 6px 14px;
            border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: background 0.2s ease;
        }
        .btn-sm-reject:hover { background: #dc2626; }
        .btn-sm-reset {
            background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 5px 10px;
            border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-sm-reset:hover { background: #e2e8f0; color: #0f172a; }

        .pagination-container { padding: 16px 20px; border-top: 1px solid var(--app-border); }

        /* ===== Tambahan khusus halaman Kepala Sekolah ===== */
        .nav-badge { margin-left: auto; background: #ef4444; color: #fff; font-size: .68rem; padding: 1px 7px; border-radius: 10px; font-weight: 800; }

        .page-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .page-title { font-size: 1.3rem; font-weight: 800; }
        .page-sub { font-size: .85rem; color: var(--app-subtext); margin-top: 2px; }

        .grid-2 { display: grid; grid-template-columns: 1.4fr 1fr; gap: 16px; align-items: start; }
        .grid-2-eq { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
        @media (max-width: 960px) { .grid-2, .grid-2-eq { grid-template-columns: 1fr; } }

        .card-head { padding: 14px 20px; border-bottom: 1px solid var(--app-border); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .card-title { font-size: .95rem; font-weight: 800; display: flex; align-items: center; gap: 8px; }
        .card-link { font-size: .8rem; font-weight: 700; color: var(--app-primary); text-decoration: none; }
        .card-link:hover { text-decoration: underline; }
        .card-body { padding: 16px 20px; }
        .empty-note { text-align: center; padding: 28px 16px; color: var(--app-subtext); font-size: .85rem; }
        .empty-note i { display: block; font-size: 1.6rem; color: #cbd5e1; margin-bottom: 8px; }

        .list-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 20px; border-bottom: 1px solid var(--app-border); }
        .list-row:last-child { border-bottom: none; }
        .list-main { font-weight: 700; font-size: .9rem; color: #111827; }
        .list-sub { font-size: .76rem; color: var(--app-subtext); margin-top: 2px; }

        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 800; border: 1px solid transparent; white-space: nowrap; }
        .badge-green { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .badge-red { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .badge-amber { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        .badge-blue { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .badge-gray { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }

        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: .85rem; font-weight: 700; cursor: pointer; border: 1px solid transparent; text-decoration: none; font-family: inherit; transition: all .15s ease; }
        .btn-primary { background: var(--app-primary); color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-light { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
        .btn-light:hover { background: #e2e8f0; }
        .btn-sm { padding: 5px 12px; font-size: .78rem; }
        .btn[disabled] { opacity: .5; cursor: not-allowed; }

        .meter { height: 10px; background: #e5e7eb; border-radius: 999px; overflow: hidden; display: flex; }
        .meter > span { display: block; height: 100%; }
        .meter-legend { display: flex; flex-wrap: wrap; gap: 6px 16px; margin-top: 10px; font-size: .78rem; color: #374151; }
        .meter-legend i { font-size: .55rem; margin-right: 5px; }

        .kpi-big { font-size: 2.2rem; font-weight: 800; line-height: 1; }

        .chart { display: flex; align-items: flex-end; gap: 14px; height: 170px; padding-top: 8px; }
        .chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; gap: 6px; min-width: 0; }
        .chart-bars { display: flex; align-items: flex-end; gap: 3px; width: 100%; justify-content: center; height: 100%; }
        .chart-bar { width: 38%; max-width: 22px; border-radius: 4px 4px 0 0; min-height: 2px; position: relative; }
        .chart-bar.total { background: #93c5fd; }
        .chart-bar.ok { background: #10b981; }
        .chart-label { font-size: .7rem; font-weight: 700; color: var(--app-subtext); white-space: nowrap; }
        .chart-val { font-size: .68rem; font-weight: 800; color: #374151; }

        /* Stepper persetujuan */
        .stepper { display: flex; flex-direction: column; gap: 0; }
        .step { display: flex; gap: 14px; position: relative; padding-bottom: 18px; }
        .step:last-child { padding-bottom: 0; }
        .step:not(:last-child)::before { content: ''; position: absolute; left: 15px; top: 32px; bottom: 0; width: 2px; background: #e5e7eb; }
        .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0; z-index: 1; }
        .step-dot.ok { background: #10b981; color: #fff; border-color: #10b981; }
        .step-dot.no { background: #ef4444; color: #fff; border-color: #ef4444; }
        .step-dot.now { background: #fff; color: var(--app-primary); border-color: var(--app-primary); }
        .step-title { font-weight: 800; font-size: .88rem; }
        .step-meta { font-size: .76rem; color: var(--app-subtext); margin-top: 2px; }

        .mini-steps { display: flex; align-items: center; gap: 4px; }
        .mini-step { width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: .6rem; background: #f1f5f9; color: #94a3b8; border: 1.5px solid #e2e8f0; }
        .mini-step.ok { background: #10b981; border-color: #10b981; color: #fff; }
        .mini-step.no { background: #ef4444; border-color: #ef4444; color: #fff; }
        .mini-step.now { background: #fff; border-color: var(--app-primary); color: var(--app-primary); }
        .mini-line { width: 10px; height: 2px; background: #e5e7eb; }

        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 24px; }
        @media (max-width: 640px) { .detail-grid { grid-template-columns: 1fr; } }
        .detail-label { font-size: .68rem; font-weight: 800; letter-spacing: .05em; text-transform: uppercase; color: var(--app-subtext); margin-bottom: 3px; }
        .detail-value { font-size: .92rem; font-weight: 600; color: #111827; line-height: 1.5; }

        .note-box { background: #f8fafc; border: 1px solid var(--app-border); border-left: 4px solid #94a3b8; border-radius: 8px; padding: 10px 14px; font-size: .86rem; color: #374151; line-height: 1.5; }
        .note-box.red { background: #fef2f2; border-color: #fecaca; border-left-color: #ef4444; color: #7f1d1d; }
        .note-box.green { background: #f0fdf4; border-color: #bbf7d0; border-left-color: #10b981; color: #14532d; }
        .note-box.amber { background: #fffbeb; border-color: #fde68a; border-left-color: #f59e0b; color: #78350f; }

        .bukti-img { max-width: 100%; max-height: 360px; border-radius: 10px; border: 1px solid var(--app-border); display: block; }

        /* Modal */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(15,23,42,.5); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 16px; }
        .modal-backdrop.open { display: flex; }
        .modal-box { background: #fff; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 20px 50px rgba(0,0,0,.25); overflow: hidden; }
        .modal-head { padding: 16px 20px; border-bottom: 1px solid var(--app-border); font-weight: 800; display: flex; align-items: center; justify-content: space-between; }
        .modal-close { background: none; border: none; font-size: 1.1rem; cursor: pointer; color: var(--app-subtext); }
        .modal-body { padding: 18px 20px; }
        .modal-foot { padding: 14px 20px; border-top: 1px solid var(--app-border); display: flex; justify-content: flex-end; gap: 8px; background: #f9fafb; }
        .field-label { display: block; font-size: .8rem; font-weight: 800; margin-bottom: 6px; color: #374151; }
        .field-textarea { width: 100%; border: 1px solid var(--app-border); border-radius: 8px; padding: 10px 12px; font-size: .88rem; font-family: inherit; resize: vertical; min-height: 90px; outline: none; }
        .field-textarea:focus { border-color: var(--app-primary); }
        .field-error { color: #dc2626; font-size: .78rem; font-weight: 700; margin-top: 4px; }
        .alert-ok { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 10px; }

        /* ===== Sidebar Kepala Sekolah (gaya sama dengan sidebar peran lain) ===== */
        .kp-nav-menu { list-style: none; display: flex; flex-direction: column; gap: 4px; margin: 0; padding: 0; }
        .kp-nav-link {
            display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 12px;
            color: #64748b; text-decoration: none; font-weight: 700; font-size: 0.875rem; transition: all 0.2s ease;
        }
        .kp-nav-link:hover { color: var(--dash-navy); background: var(--dash-cream-light); }
        .kp-nav-link.active { background-color: var(--dash-navy); color: #ffffff; box-shadow: 0 4px 12px rgba(35, 41, 59, 0.18); }
        .kp-nav-link i { width: 18px; text-align: center; }
    </style>
    @stack('head')
</head>
<body class="dashboard-body">
@php
    $menungguKepsek = \App\Models\IzinGuru::menungguKepsek()->count();
@endphp

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="dash-layout">
        <aside class="dash-sidebar">
            <div>
                @include('partials.dash-brand')

                <ul class="kp-nav-menu">
                    <li>
                        <a href="{{ route('kepsek.dashboard') }}" class="kp-nav-link {{ request()->routeIs('kepsek.dashboard') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect></svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepsek.izin.index') }}" class="kp-nav-link {{ request()->routeIs('kepsek.izin.*') ? 'active' : '' }}">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            <span>Persetujuan Izin</span>
                            @if($menungguKepsek > 0)
                                <span class="nav-badge">{{ $menungguKepsek }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>

            <div class="dash-sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;" data-confirm-type="logout">
                    @csrf
                    <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; width: 100%; padding: 10px; border-radius: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.85rem;">
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="dash-main">

            <header class="dash-top-bar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button type="button" class="dash-hamburger-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    <div>
                        <h1 class="dash-header-title">@yield('page-title', 'Dashboard')</h1>
                        <div class="dash-header-subtitle">@yield('page-subtitle', \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y'))</div>
                    </div>
                </div>

                <div class="dash-top-right">
                    <div class="dash-date-widget">
                        <svg class="dash-date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <rect x="7" y="14" width="3" height="3" fill="currentColor"></rect>
                        </svg>
                        <div class="dash-date-info">
                            <span class="date-str" id="live_date_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span>
                            <span class="time-str" id="live_time_str">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }} WIB</span>
                        </div>
                    </div>

                    @include('partials.dash-user-widget')
                </div>
            </header>

            @if(session('success'))
                <div class="alert-ok"><i class="fa-solid fa-circle-check" style="font-size: 1.1rem;"></i><span>{{ session('success') }}</span></div>
            @endif
            @if(session('error'))
                <div class="alert-err"><i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem;"></i><span>{{ session('error') }}</span></div>
            @endif
            @if($errors->any())
                <div class="alert-err"><i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem;"></i><span>{{ $errors->first() }}</span></div>
            @endif

            @yield('content')

        </main>
    </div>

    @include('partials.keputusan-modal')
    <script src="/js/live-clock.js"></script>
    @stack('scripts')
    @include('partials.confirm-modal')
</body>
</html>