<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Persetujuan - {{ auth()->user()->role_label }}</title>

    <!-- Font & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
    <script src="/js/sidebar-toggle.js"></script>

    <style>
        :root {
            --app-bg: #f8fafc;
            --app-navy: #0f172a;
            --app-card: #ffffff;
            --app-border: #e2e8f0;
            --app-primary: #2563eb;
            --app-primary-dark: #1d4ed8;
            --app-text: #1e293b;
            --app-subtext: #64748b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--app-bg); color: var(--app-text); min-height: 100vh; }

        .app-layout { display: flex; flex-direction: column; min-height: 100vh; }
        
        /* Top Navigation Header */
        .app-header {
            background: var(--app-navy);
            color: #ffffff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .app-brand { display: flex; align-items: center; gap: 12px; }
        .app-brand img { height: 36px; width: auto; }
        .app-brand-title { font-size: 1.1rem; font-weight: 800; color: #ffffff; letter-spacing: -0.3px; }
        .app-brand-sub { font-size: 0.75rem; color: #94a3b8; }

        .main-container { max-width: 1350px; margin: 0 auto; width: 100%; padding: 24px; }

        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.2);
            position: relative;
            overflow: hidden;
        }
        .welcome-card::after {
            content: '';
            position: absolute;
            right: -30px; bottom: -30px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .welcome-title { font-size: 1.35rem; font-weight: 800; margin-bottom: 4px; }
        .welcome-sub { font-size: 0.875rem; color: #94a3b8; }

        /* Summary Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--app-border);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; flex-shrink: 0;
        }
        .stat-val { font-size: 1.5rem; font-weight: 800; color: var(--app-text); line-height: 1.2; }
        .stat-label { font-size: 0.775rem; font-weight: 700; color: var(--app-subtext); text-transform: uppercase; letter-spacing: 0.5px; }

        /* Main Content Container */
        .content-card {
            background: #ffffff;
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        /* Tabs Header */
        .tabs-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--app-border);
            background: #f8fafc;
            padding: 0 16px;
        }
        .tab-btn {
            padding: 16px 20px;
            font-size: 0.9rem;
            font-weight: 800;
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

        /* Filter Controls */
        .filter-bar {
            padding: 16px 20px;
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
            padding: 6px 14px;
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
            background: var(--app-navy);
            color: #ffffff;
            border-color: var(--app-navy);
        }

        .search-form { display: flex; align-items: center; gap: 8px; }
        .search-input {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid var(--app-border);
            font-size: 0.85rem;
            width: 240px;
            outline: none;
        }
        .search-input:focus { border-color: var(--app-primary); }

        /* Table Styling */
        .table-responsive { width: 100%; overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; }
        .data-table th {
            background: #f8fafc;
            padding: 12px 16px;
            font-size: 0.725rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--app-subtext);
            border-bottom: 1px solid var(--app-border);
        }
        .data-table td { padding: 14px 16px; border-bottom: 1px solid var(--app-border); vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: #f8fafc; }

        /* Track Approval Badges */
        .approval-track-list { display: flex; flex-direction: column; gap: 4px; font-size: 0.75rem; }
        .track-step { display: flex; align-items: center; gap: 6px; }
        .track-step.done { color: #16a34a; font-weight: 700; }
        .track-step.pending { color: #ca8a04; font-weight: 600; }
        .track-step.rejected { color: #dc2626; font-weight: 700; }

        /* Badges */
        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 12px; font-weight: 800; font-size: 0.75rem;
        }
        .badge-pending { background: #fefce8; color: #b45309; border: 1px solid #fef08a; }
        .badge-approved { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-rejected { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Action Buttons */
        .btn-sm-approve {
            background: #16a34a; color: #ffffff; border: none; padding: 6px 12px;
            border-radius: 6px; font-size: 0.775rem; font-weight: 800; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
        }
        .btn-sm-approve:hover { background: #15803d; }
        .btn-sm-reject {
            background: #dc2626; color: #ffffff; border: none; padding: 6px 12px;
            border-radius: 6px; font-size: 0.775rem; font-weight: 800; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
        }
        .btn-sm-reject:hover { background: #b91c1c; }
        .btn-sm-reset {
            background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 5px 10px;
            border-radius: 6px; font-size: 0.75rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px; text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-sm-reset:hover { background: #e2e8f0; color: #0f172a; }

        .pagination-container { padding: 16px 20px; border-top: 1px solid var(--app-border); }
    </style>
</head>
<body>

<div class="app-layout">
    <!-- Header Navigation -->
    <header class="app-header">
        <div class="app-brand">
            <img src="{{ asset('assets/image/logo/logo-only.svg') }}" alt="Logo">
            <div>
                <div class="app-brand-title">Persetujuan Bertingkat (Multi-Approval)</div>
                <div class="app-brand-sub">Panel Khusus {{ auth()->user()->role_label }}</div>
            </div>
        </div>

        <div>
            @include('partials.dash-user-widget')
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Alert Flash Messages -->
        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 14px 18px; border-radius: 12px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="welcome-card">
            <div>
                <h1 class="welcome-title">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="welcome-sub">Anda masuk sebagai <strong>{{ auth()->user()->role_label }}</strong>. Alur Persetujuan 3 Pihak: <strong>Guru Piket → Waka → Kepala Sekolah</strong>. Anda dapat menyetujui, menolak, atau membatalkan (reset) keputusan kapan saja.</p>
            </div>
        </div>

        <!-- Summary Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fefce8; color: #ca8a04;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $izinStats['pending'] + $dispensasiStats['pending'] }}</div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $izinStats['total'] }}</div>
                    <div class="stat-label">Total Izin Guru</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $dispensasiStats['total'] }}</div>
                    <div class="stat-label">Total Dispensasi Siswa</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #faf5ff; color: #9333ea;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $izinStats['disetujui'] + $dispensasiStats['disetujui'] }}</div>
                    <div class="stat-label">Disetujui Lengkap</div>
                </div>
            </div>
        </div>

        <!-- Main Card with Tabs & Data Table -->
        <div class="content-card">
            <!-- Tabs Navigation -->
            <div class="tabs-header">
                <a href="{{ route('approver.dashboard', ['tab' => 'izin', 'status' => $statusFilter]) }}" class="tab-btn {{ $activeTab === 'izin' ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Izin Guru</span>
                    @if($izinStats['pending'] > 0)
                        <span style="background: #ef4444; color: #ffffff; font-size: 0.7rem; padding: 2px 7px; border-radius: 10px;">{{ $izinStats['pending'] }}</span>
                    @endif
                </a>
                <a href="{{ route('approver.dashboard', ['tab' => 'dispensasi', 'status' => $statusFilter]) }}" class="tab-btn {{ $activeTab === 'dispensasi' ? 'active' : '' }}">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Dispensasi Siswa</span>
                    @if($dispensasiStats['pending'] > 0)
                        <span style="background: #ef4444; color: #ffffff; font-size: 0.7rem; padding: 2px 7px; border-radius: 10px;">{{ $dispensasiStats['pending'] }}</span>
                    @endif
                </a>
            </div>

            <!-- Filter Controls -->
            <div class="filter-bar">
                <div class="filter-status-group">
                    <span style="font-size: 0.75rem; font-weight: 800; color: var(--app-subtext); margin-right: 4px;">STATUS:</span>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'all', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'all' ? 'active' : '' }}">Semua</a>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'pending', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'pending' ? 'active' : '' }}">Menunggu</a>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'disetujui', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'disetujui' ? 'active' : '' }}">Disetujui Lengkap</a>
                    <a href="{{ route('approver.dashboard', ['tab' => $activeTab, 'status' => 'ditolak', 'search' => $search]) }}" class="filter-chip {{ $statusFilter === 'ditolak' ? 'active' : '' }}">Ditolak</a>
                </div>

                <form action="{{ route('approver.dashboard') }}" method="GET" class="search-form">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <input type="text" name="search" class="search-input" placeholder="Cari data..." value="{{ $search }}">
                    <button type="submit" class="btn-sm-approve" style="background: var(--app-navy);">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari
                    </button>
                </form>
            </div>

            <!-- TAB CONTENT: IZIN GURU -->
            @if($activeTab === 'izin')
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Guru Pemohon</th>
                                <th>Jenis & Waktu Izin</th>
                                <th>Keterangan / Alasan</th>
                                <th>Persetujuan</th>
                                <th style="text-align: right;">Aksi Anda ({{ auth()->user()->role_label }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($izinList as $idx => $item)
                                @php
                                    $isWakaOk = ($item->status_waka === 'disetujui');
                                    $isWakaNo = ($item->status_waka === 'ditolak');
                                    $isKepsekOk = ($item->status_kepsek === 'disetujui');
                                    $isKepsekNo = ($item->status_kepsek === 'ditolak');

                                    $isMyWaka = in_array(auth()->user()->role, ['waka', 'waka_sdm'], true);
                                    $isMyKepsek = (auth()->user()->role === 'kepala_sekolah');

                                    $myApproved = ($isMyWaka && $isWakaOk) || ($isMyKepsek && $isKepsekOk);
                                    $myRejected = ($isMyWaka && $isWakaNo) || ($isMyKepsek && $isKepsekNo);
                                @endphp
                                <tr>
                                    <td>{{ $izinList->firstItem() + $idx }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--app-text);">{{ $item->guru->nama_guru ?? 'Guru' }}</div>
                                        <small style="color: var(--app-subtext);">NUPTK: {{ $item->guru->nuptk ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--app-primary);">{{ $item->kategori_label }}</div>
                                        <small style="color: #64748b;">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                            @if($item->tanggal_mulai != $item->tanggal_selesai)
                                                - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </td>
                                    <td style="max-width: 220px;">
                                        <div style="font-size: 0.85rem; color: #334155;">{{ $item->alasan_izin }}</div>
                                    </td>

                                    <td>
                                        @if($isWakaOk && $isKepsekOk)
                                            <span class="status-badge badge-approved"><i class="fa-solid fa-check-double"></i> Disetujui Lengkap</span>
                                        @elseif($isWakaNo && $isKepsekNo)
                                            <span class="status-badge badge-rejected"><i class="fa-solid fa-xmark"></i> Ditolak Semua</span>
                                        @else
                                            <div style="display:flex; flex-direction:column; gap:3px; min-width:130px;">
                                                <div style="font-size:0.7rem; font-weight:800; color:var(--app-subtext); letter-spacing:.04em; margin-bottom:1px;">VOTE PERSETUJUAN</div>
                                                <div style="display:flex; align-items:center; gap:5px; font-size:0.78rem;">
                                                    <span style="width:16px; height:16px; border-radius:50%; background:#16a34a; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-check"></i></span>
                                                    <span style="color:#374151;">Piket Setuju</span>
                                                </div>
                                                <div style="display:flex; align-items:center; gap:5px; font-size:0.78rem;">
                                                    @if($isWakaOk)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#16a34a; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-check"></i></span>
                                                        <span style="color:#374151;">Waka <b style="color:#16a34a;">Setuju</b></span>
                                                    @elseif($isWakaNo)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#dc2626; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-xmark"></i></span>
                                                        <span style="color:#374151;">Waka <b style="color:#dc2626;">Tolak</b></span>
                                                    @else
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#e2e8f0; color:#94a3b8; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-clock"></i></span>
                                                        <span style="color:#94a3b8;">Waka Menunggu</span>
                                                    @endif
                                                </div>
                                                <div style="display:flex; align-items:center; gap:5px; font-size:0.78rem;">
                                                    @if($isKepsekOk)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#16a34a; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-check"></i></span>
                                                        <span style="color:#374151;">Kepsek <b style="color:#16a34a;">Setuju</b></span>
                                                    @elseif($isKepsekNo)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#dc2626; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-xmark"></i></span>
                                                        <span style="color:#374151;">Kepsek <b style="color:#dc2626;">Tolak</b></span>
                                                    @else
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#e2e8f0; color:#94a3b8; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-clock"></i></span>
                                                        <span style="color:#94a3b8;">Kepsek Menunggu</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        @if($myApproved)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #16a34a; background: #f0fdf4; padding: 3px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                                                    <i class="fa-solid fa-check-double"></i> Anda Sudah Setuju
                                                </span>
                                                <form action="{{ route('approver.izin.reset', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset persetujuan Anda untuk permohonan ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($myRejected)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #dc2626; background: #fef2f2; padding: 3px 8px; border-radius: 6px; border: 1px solid #fecaca;">
                                                    <i class="fa-solid fa-xmark"></i> Anda Menolak
                                                </span>
                                                <form action="{{ route('approver.izin.reset', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset penolakan Anda untuk permohonan ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div style="display: inline-flex; gap: 4px;">
                                                <form action="{{ route('approver.izin.approve', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-approve" onclick="return confirm('Setujui permohonan izin ini?')">
                                                        <i class="fa-solid fa-check"></i> Setujui
                                                    </button>
                                                </form>

                                                <form action="{{ route('approver.izin.reject', $item->id_izin_guru) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reject" onclick="return confirm('Tolak permohonan izin ini?')">
                                                        <i class="fa-solid fa-xmark"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--app-subtext);">
                                        <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; color: #cbd5e1; display: block;"></i>
                                        Tidak ada data permohonan izin guru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($izinList->hasPages())
                    <div class="pagination-container">
                        {{ $izinList->links() }}
                    </div>
                @endif

            <!-- TAB CONTENT: DISPENSASI SISWA -->
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Surat</th>
                                <th>Nama Siswa & Kelas</th>
                                <th>Kegiatan</th>
                                <th>Persetujuan</th>
                                <th style="text-align: right;">Aksi Anda ({{ auth()->user()->role_label }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dispensasiList as $idx => $item)
                                @php
                                    $isWakaOk = ($item->status_waka === 'disetujui');
                                    $isWakaNo = ($item->status_waka === 'ditolak');
                                    $isKepsekOk = ($item->status_kepsek === 'disetujui');
                                    $isKepsekNo = ($item->status_kepsek === 'ditolak');

                                    $isMyWaka = in_array(auth()->user()->role, ['waka', 'waka_sdm'], true);
                                    $isMyKepsek = (auth()->user()->role === 'kepala_sekolah');

                                    $myApproved = ($isMyWaka && $isWakaOk) || ($isMyKepsek && $isKepsekOk);
                                    $myRejected = ($isMyWaka && $isWakaNo) || ($isMyKepsek && $isKepsekNo);
                                @endphp
                                <tr>
                                    <td>{{ $dispensasiList->firstItem() + $idx }}</td>
                                    <td>
                                        <span style="font-weight: 700; font-family: monospace; color: var(--app-primary);">{{ $item->nomor_surat }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--app-text);">{{ optional($item->siswa)->nama_siswa ?? 'Siswa' }}</div>
                                        <small style="color: var(--app-subtext);">
                                            {{ optional(optional($item->siswa)->kelas)->tingkat }} 
                                            {{ optional(optional(optional($item->siswa)->kelas)->jurusan)->kode_jurusan }} 
                                            {{ optional(optional($item->siswa)->kelas)->rombel }}
                                        </small>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700;">{{ $item->nama_kegiatan }}</div>
                                        <small style="color: var(--app-subtext);">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                            @if($item->jam_mulai && $item->jam_selesai)
                                                ({{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }})
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="approval-track-list">
                                            <div class="track-step done">
                                                <i class="fa-solid fa-check-circle"></i> 1. Guru Piket: {{ optional($item->approver)->name ?? 'Disetujui' }}
                                            </div>
                                            <div class="track-step {{ $isWakaOk ? 'done' : ($isWakaNo ? 'rejected' : 'pending') }}">
                                                <i class="fa-solid {{ $isWakaOk ? 'fa-check-circle' : ($isWakaNo ? 'fa-circle-xmark' : 'fa-clock') }}"></i>
                                                2. Waka: {{ $isWakaOk ? (optional($item->approverWaka)->name ?? 'Disetujui') : ($isWakaNo ? 'Ditolak' : 'Menunggu') }}
                                            </div>
                                            <div class="track-step {{ $isKepsekOk ? 'done' : ($isKepsekNo ? 'rejected' : 'pending') }}">
                                                <i class="fa-solid {{ $isKepsekOk ? 'fa-check-circle' : ($isKepsekNo ? 'fa-circle-xmark' : 'fa-clock') }}"></i>
                                                3. Kepsek: {{ $isKepsekOk ? (optional($item->approverKepsek)->name ?? 'Disetujui') : ($isKepsekNo ? 'Ditolak' : 'Menunggu') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($isWakaOk && $isKepsekOk)
                                            <span class="status-badge badge-approved"><i class="fa-solid fa-check-double"></i> Disetujui Lengkap</span>
                                        @elseif($isWakaNo && $isKepsekNo)
                                            <span class="status-badge badge-rejected"><i class="fa-solid fa-xmark"></i> Ditolak Semua</span>
                                        @else
                                            <div style="display:flex; flex-direction:column; gap:3px; min-width:130px;">
                                                <div style="font-size:0.7rem; font-weight:800; color:var(--app-subtext); letter-spacing:.04em; margin-bottom:1px;">VOTE PERSETUJUAN</div>
                                                <div style="display:flex; align-items:center; gap:5px; font-size:0.78rem;">
                                                    <span style="width:16px; height:16px; border-radius:50%; background:#16a34a; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-check"></i></span>
                                                    <span style="color:#374151;">Piket Setuju</span>
                                                </div>
                                                <div style="display:flex; align-items:center; gap:5px; font-size:0.78rem;">
                                                    @if($isWakaOk)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#16a34a; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-check"></i></span>
                                                        <span style="color:#374151;">Waka <b style="color:#16a34a;">Setuju</b></span>
                                                    @elseif($isWakaNo)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#dc2626; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-xmark"></i></span>
                                                        <span style="color:#374151;">Waka <b style="color:#dc2626;">Tolak</b></span>
                                                    @else
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#e2e8f0; color:#94a3b8; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-clock"></i></span>
                                                        <span style="color:#94a3b8;">Waka Menunggu</span>
                                                    @endif
                                                </div>
                                                <div style="display:flex; align-items:center; gap:5px; font-size:0.78rem;">
                                                    @if($isKepsekOk)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#16a34a; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-check"></i></span>
                                                        <span style="color:#374151;">Kepsek <b style="color:#16a34a;">Setuju</b></span>
                                                    @elseif($isKepsekNo)
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#dc2626; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-xmark"></i></span>
                                                        <span style="color:#374151;">Kepsek <b style="color:#dc2626;">Tolak</b></span>
                                                    @else
                                                        <span style="width:16px; height:16px; border-radius:50%; background:#e2e8f0; color:#94a3b8; display:inline-flex; align-items:center; justify-content:center; font-size:0.6rem;"><i class="fa-solid fa-clock"></i></span>
                                                        <span style="color:#94a3b8;">Kepsek Menunggu</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        @if($myApproved)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #16a34a; background: #f0fdf4; padding: 3px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                                                    <i class="fa-solid fa-check-double"></i> Anda Sudah Setuju
                                                </span>
                                                <form action="{{ route('approver.dispensasi.reset', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset persetujuan Anda untuk surat dispensasi ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($myRejected)
                                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                                <span style="font-size: 0.775rem; font-weight: 800; color: #dc2626; background: #fef2f2; padding: 3px 8px; border-radius: 6px; border: 1px solid #fecaca;">
                                                    <i class="fa-solid fa-xmark"></i> Anda Menolak
                                                </span>
                                                <form action="{{ route('approver.dispensasi.reset', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reset" onclick="return confirm('Batalkan / reset penolakan Anda untuk surat dispensasi ini?')">
                                                        <i class="fa-solid fa-rotate-left"></i> Batalkan / Reset
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div style="display: inline-flex; gap: 4px;">
                                                <form action="{{ route('approver.dispensasi.approve', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-approve" onclick="return confirm('Setujui surat dispensasi ini?')">
                                                        <i class="fa-solid fa-check"></i> Setujui
                                                    </button>
                                                </form>

                                                <form action="{{ route('approver.dispensasi.reject', $item->id_dispen) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn-sm-reject" onclick="return confirm('Tolak surat dispensasi ini?')">
                                                        <i class="fa-solid fa-xmark"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--app-subtext);">
                                        <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; color: #cbd5e1; display: block;"></i>
                                        Tidak ada data surat dispensasi siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($dispensasiList->hasPages())
                    <div class="pagination-container">
                        {{ $dispensasiList->links() }}
                    </div>
                @endif
            @endif
        </div>
    </main>
</div>

</body>
</html>
