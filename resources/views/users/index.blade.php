<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Pengguna | Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Modular Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/modules/dashboard.css') }}">
</head>
<body class="dashboard-body">

    <div class="dash-layout">

        <!-- ===================================================================
             Left Sidebar Navigation
             =================================================================== -->
        <aside class="dash-sidebar">
            <a href="{{ route('dashboard') }}" class="dash-brand">
                <svg width="42" height="42" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 75C20 70 30 65 50 65C70 65 80 70 80 75V80H20V75Z" fill="#252B3E"/>
                    <path d="M50 20L15 40L50 60L85 40L50 20Z" fill="#D97706"/>
                    <path d="M50 20L25 34.2857V60L50 45.7143V20Z" fill="#F59E0B"/>
                    <path d="M35 65L20 50V70L35 80V65Z" fill="#252B3E"/>
                    <path d="M65 65L80 50V70L65 80V65Z" fill="#252B3E"/>
                    <circle cx="50" cy="50" r="12" fill="#252B3E"/>
                    <path d="M46 50L49 53L55 47" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <ul class="dash-menu">
                <!-- Dashboard Item -->
                <li class="dash-menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="dash-menu-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Master Data Category (Expanded) -->
                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('masterDataSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <span>Master Data</span>
                        </div>
                        <svg class="dash-category-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="masterDataSub" style="display: flex;">
                        <!-- Pengguna Active Sub Link -->
                        <li>
                            <a href="{{ route('users.index') }}" class="dash-sub-link" style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #ffffff;">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('siswa.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg>
                                <span>Siswa</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                                <span>Guru</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('kelas.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <path d="M3 9h18M9 21V9"></path>
                                </svg>
                                <span>Kelas</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('mapel.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>Mapel</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Akademik Category -->
                <li class="dash-menu-category">
                    <button type="button" class="dash-category-btn" onclick="toggleSubmenu('akademikSub')">
                        <div class="dash-category-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <span>Akademik</span>
                        </div>
                        <svg class="dash-category-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <ul class="dash-sub-menu" id="akademikSub">
                        <li>
                            <a href="{{ route('jadwal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('jurnal.index') }}" class="dash-sub-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1"></rect>
                                </svg>
                                <span>Jurnal Mengajar</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </aside>

        <!-- ===================================================================
             Main Content Region
             =================================================================== -->
        <main class="dash-main">

            <!-- Top Header Bar -->
            <header class="dash-top-bar">
                <div>
                    <h1 class="dash-header-title">Master Data - Pengguna</h1>
                    <p class="dash-header-subtitle">Lihat pengguna dan kelola mereka</p>
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

                    <div class="dash-user-widget">
                        <div style="display: flex; flex-direction: column; align-items: flex-end; line-height: 1.2;">
                            <span style="font-size: 0.875rem; font-weight: 700; color: #1e2538;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            <span style="font-size: 0.725rem; font-weight: 600; color: #847e73;">{{ Auth::user()->role_label ?? 'Admin' }}</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="dash-user-avatar">
                        
                        <form action="{{ route('logout') }}" method="POST" style="margin-left: 6px;">
                            @csrf
                            <button type="submit" title="Keluar dari Akun" style="background: none; border: none; cursor: pointer; color: #dc2626; padding: 4px; display: flex; align-items: center;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="flash-alert" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="flash-alert" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 12px; font-size: 0.9rem; font-weight: 600;">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <!-- ---------------------------------------------------------------
                 Top 4 Summary Cards Grid (Matching Mockup)
                 --------------------------------------------------------------- -->
            <section class="dash-summary-row">
                <!-- Card 1: Admin -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box" style="background-color: #e0f2fe;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <span>Admin</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $adminCount }}
                    </div>
                </div>

                <!-- Card 2: Guru Piket -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box" style="background-color: #fef3c7;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span>Guru Piket</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $guruPiketCount }}
                    </div>
                </div>

                <!-- Card 3: Guru Mapel -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box" style="background-color: #dcfce7;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span>Guru Mapel</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $guruMapelCount }}
                    </div>
                </div>

                <!-- Card 4: Wali Kelas -->
                <div class="dash-summary-card">
                    <div class="dash-card-header">
                        <div class="dash-card-icon-box" style="background-color: #f1ebd9;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#334155" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span>Wali Kelas</span>
                    </div>
                    <div class="dash-card-value">
                        {{ $waliKelasCount }}
                    </div>
                </div>
            </section>

            <!-- ---------------------------------------------------------------
                 Controls Bar: Search & Action Buttons
                 --------------------------------------------------------------- -->
            <div class="pengguna-controls-row">
                <!-- Search Form -->
                <form action="{{ route('users.index') }}" method="GET" class="pengguna-search-box">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                    <svg class="pengguna-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" id="userSearchInput" class="pengguna-search-input" placeholder="Cari Pengguna...." value="{{ request('search') }}" autocomplete="off">
                </form>

                <!-- Right Action Buttons -->
                <div class="pengguna-action-group">
                    <button type="button" class="btn-tambah-pengguna" onclick="openCreateModal()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Tambah Pengguna</span>
                    </button>

                    <!-- Filter Dropdown -->
                    <div style="position: relative; display: inline-block;">
                        <button type="button" class="btn-filter-dropdown" onclick="toggleFilterMenu()">
                            <span>Filter</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div id="filterMenuDropdown" style="display: none; position: absolute; right: 0; top: 48px; background: #ffffff; border: 1px solid var(--dash-cream-border); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 8px; width: 180px; z-index: 50;">
                            <a href="{{ route('users.index') }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Semua Role</a>
                            <a href="{{ route('users.index', ['role' => 'super_admin']) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Admin Super</a>
                            <a href="{{ route('users.index', ['role' => 'admin']) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Admin Biasa</a>
                            <a href="{{ route('users.index', ['role' => 'guru_mengajar']) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Guru Mapel</a>
                            <a href="{{ route('users.index', ['role' => 'wali_kelas']) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Wali Kelas</a>
                            <a href="{{ route('users.index', ['role' => 'guru_piket']) }}" style="display: block; padding: 8px 12px; font-size: 0.825rem; font-weight: 600; color: #334155; text-decoration: none; border-radius: 6px;">Guru Piket</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---------------------------------------------------------------
                 Data Table Card Component
                 --------------------------------------------------------------- -->
            <div class="pengguna-table-card">
                <div class="table-responsive-clean">
                    <table class="pengguna-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama</th>
                                <th style="width: 25%;">Password</th>
                                <th style="width: 20%;">Role</th>
                                <th style="width: 15%;">Dibuat Pada</th>
                                <th style="width: 10%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                                <tr>
                                    <td class="td-no">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                    <td class="td-nama">{{ $user->name }}</td>
                                    <td>
                                        <div style="display: inline-flex; align-items: center; gap: 8px;">
                                            <button type="button" onclick="toggleTablePassword({{ $user->id }})" style="background: none; border: none; cursor: pointer; padding: 2px; color: #94a3b8; display: inline-flex; align-items: center;" title="Tampilkan / Sembunyikan Password">
                                                <svg id="table_eye_open_{{ $user->id }}" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                </svg>
                                                <svg id="table_eye_closed_{{ $user->id }}" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                                </svg>
                                            </button>
                                            <span id="table_pwd_{{ $user->id }}" class="pwd-dots" style="letter-spacing: 2px;">••••••••••••</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="role-badge-cell">
                                            <span>{{ $user->role_label }}</span>
                                            @if($user->role === 'super_admin')
                                                <span class="badge-tag-super">Super</span>
                                            @elseif($user->role === 'admin')
                                                <span class="badge-tag-super" style="background: #e0f2fe; color: #0284c7;">Biasa</span>
                                            @elseif($user->role === 'guru_mengajar')
                                                <span class="badge-tag-guru">Mapel</span>
                                            @elseif($user->role === 'wali_kelas')
                                                <span class="badge-tag-wali">Wali</span>
                                            @elseif($user->role === 'guru_piket')
                                                <span class="badge-tag-piket">Piket</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="color: #475569; font-weight: 600;">
                                        {{ $user->created_at ? $user->created_at->format('d-m-Y') : '11-02-2026' }}
                                    </td>
                                    <td>
                                        <div class="action-icons-cell">
                                            <!-- View Action -->
                                            <button type="button" class="action-btn-icon view" title="Lihat Detail" onclick="openViewModal({{ $user->id }})">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                </svg>
                                            </button>

                                            <!-- Edit Action -->
                                            <button type="button" class="action-btn-icon edit" title="Edit Pengguna" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->id_guru }}')">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <!-- Delete Action -->
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn-icon delete" title="Hapus Pengguna">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #847e73;">
                                        Belum ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination Row -->
                <div class="table-pagination-row">
                    <span class="pagination-summary-text">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data
                    </span>

                    <div class="pagination-nav-group">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- ===================================================================
         Create User Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="createModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Tambah Pengguna Baru</h3>
                <button type="button" class="btn-close-modal" onclick="closeCreateModal()">&times;</button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="modal-form-grid">
                @csrf
                <div class="form-field-group">
                    <label for="create_name">Nama Pengguna / Username</label>
                    <input type="text" name="name" id="create_name" class="form-field-input" placeholder="Masukkan nama pengguna" required>
                </div>

                <div class="form-field-group">
                    <label for="create_email">Email</label>
                    <input type="email" name="email" id="create_email" class="form-field-input" placeholder="contoh@gmail.com" required>
                </div>

                <div class="form-field-group">
                    <label for="create_password">Password</label>
                    <input type="password" name="password" id="create_password" class="form-field-input" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="form-field-group">
                    <label for="create_role">Role Hak Akses</label>
                    <select name="role" id="create_role" class="form-field-input" onchange="handleRoleChange('create')" required>
                        <option value="super_admin">Admin (Super Admin)</option>
                        <option value="admin">Admin (Admin Biasa)</option>
                        <option value="guru_mengajar">Guru Mengajar</option>
                        <option value="wali_kelas">Wali Kelas</option>
                        <option value="guru_piket">Guru Piket</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="waka">Waka</option>
                        <option value="waka_sdm">Waka SDM</option>
                        <option value="satpam">Satpam</option>
                    </select>
                </div>

                <!-- Relasi Profil Guru (Hidden by default for Admin/Kepsek/Waka/Satpam) -->
                <div class="form-field-group" id="create_guru_group" style="display: none;">
                    <label>Relasi Profil Guru</label>
                    <input type="hidden" name="id_guru" id="create_id_guru" value="">
                    <div class="searchable-select" id="create_guru_ss">
                        <input type="text" class="form-field-input ss-input" id="create_guru_input" placeholder="Ketik nama guru atau NUPTK..." autocomplete="off" onclick="openDropdown('create')" onkeyup="filterDropdown('create')">
                        <div class="ss-dropdown" id="create_guru_dropdown">
                            <div class="ss-option" data-value="" onclick="pickGuru('create','','-- Hapus Relasi --')">-- Hapus Relasi --</div>
                            @foreach($guruList as $g)
                                <div class="ss-option" data-value="{{ $g->id_guru }}" onclick="pickGuru('create','{{ $g->id_guru }}','{{ addslashes($g->nama_guru) }} ({{ $g->nuptk }})')">
                                    <strong>{{ $g->nama_guru }}</strong>
                                    <small style="color:#64748b;">NUPTK: {{ $g->nuptk }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeCreateModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         Edit User Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="editModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Edit Data Pengguna</h3>
                <button type="button" class="btn-close-modal" onclick="closeEditModal()">&times;</button>
            </div>

            <form id="editForm" method="POST" class="modal-form-grid">
                @csrf
                @method('PUT')

                <div class="form-field-group">
                    <label for="edit_name">Nama Pengguna / Username</label>
                    <input type="text" name="name" id="edit_name" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label for="edit_email">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-field-input" required>
                </div>

                <div class="form-field-group">
                    <label for="edit_password">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" id="edit_password" class="form-field-input" placeholder="Opsional">
                </div>

                <div class="form-field-group">
                    <label for="edit_role">Role Hak Akses</label>
                    <select name="role" id="edit_role" class="form-field-input" onchange="handleRoleChange('edit')" required>
                        <option value="super_admin">Admin (Super Admin)</option>
                        <option value="admin">Admin (Admin Biasa)</option>
                        <option value="guru_mengajar">Guru Mengajar</option>
                        <option value="wali_kelas">Wali Kelas</option>
                        <option value="guru_piket">Guru Piket</option>
                        <option value="kepala_sekolah">Kepala Sekolah</option>
                        <option value="waka">Waka</option>
                        <option value="waka_sdm">Waka SDM</option>
                        <option value="satpam">Satpam</option>
                    </select>
                </div>

                <!-- Relasi Profil Guru (Hidden for non-teacher roles) -->
                <div class="form-field-group" id="edit_guru_group" style="display: none;">
                    <label>Relasi Profil Guru</label>
                    <input type="hidden" name="id_guru" id="edit_id_guru" value="">
                    <div class="searchable-select" id="edit_guru_ss">
                        <input type="text" class="form-field-input ss-input" id="edit_guru_input" placeholder="Ketik nama guru atau NUPTK..." autocomplete="off" onclick="openDropdown('edit')" onkeyup="filterDropdown('edit')">
                        <div class="ss-dropdown" id="edit_guru_dropdown">
                            <div class="ss-option" data-value="" onclick="pickGuru('edit','','-- Hapus Relasi --')">-- Hapus Relasi --</div>
                            @foreach($guruList as $g)
                                <div class="ss-option" data-value="{{ $g->id_guru }}" onclick="pickGuru('edit','{{ $g->id_guru }}','{{ addslashes($g->nama_guru) }} ({{ $g->nuptk }})')">
                                    <strong>{{ $g->nama_guru }}</strong>
                                    <small style="color:#64748b;">NUPTK: {{ $g->nuptk }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-actions-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-modal-submit">Update Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================
         View User Modal Popup
         =================================================================== -->
    <div class="modal-overlay" id="viewModal" style="display: none;">
        <div class="modal-content-card">
            <div class="modal-header-bar">
                <h3 class="modal-title-text">Detail Data Pengguna</h3>
                <button type="button" class="btn-close-modal" onclick="closeViewModal()">&times;</button>
            </div>

            <div class="modal-form-grid">
                <div class="form-field-group">
                    <label>Nama Pengguna:</label>
                    <div id="view_name" style="font-weight: 700; font-size: 1rem; color: #1e2538;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Email:</label>
                    <div id="view_email" style="font-weight: 600; color: #334155;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Password Akun:</label>
                    <div style="display: flex; align-items: center; justify-content: space-between; background-color: #f7f3eb; padding: 10px 14px; border-radius: 12px; border: 1px solid var(--dash-cream-border);">
                        <span id="view_password" style="font-family: monospace; font-size: 1rem; font-weight: 700; color: #1e2538; letter-spacing: 2px;">••••••••••••</span>
                        <button type="button" id="btn_toggle_view_pwd" onclick="toggleViewPassword()" style="background: none; border: none; cursor: pointer; padding: 4px; color: #64748b; display: flex; align-items: center;" title="Tampilkan / Sembunyikan Password">
                            <svg id="icon_eye_open" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            </svg>
                            <svg id="icon_eye_closed" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="form-field-group">
                    <label>Role:</label>
                    <div id="view_role" style="font-weight: 700; color: var(--dash-navy);">-</div>
                </div>
                <div class="form-field-group">
                    <label>Profil Guru Terelasi:</label>
                    <div id="view_guru" style="font-weight: 600; color: #475569;">-</div>
                </div>
                <div class="form-field-group">
                    <label>Dibuat Pada:</label>
                    <div id="view_created_at" style="font-weight: 600; color: #64748b;">-</div>
                </div>
            </div>

            <div class="modal-actions-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeViewModal()" style="width: 100%;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Toggle & Modal Scripts -->
    <script>
        /* ---- Guru data for JS lookup ---- */
        const GURU_DATA = [
            @foreach($guruList as $g)
                { id: '{{ $g->id_guru }}', label: '{{ addslashes($g->nama_guru) }} ({{ $g->nuptk }})' },
            @endforeach
        ];

        const TEACHER_ROLES = ['guru_mengajar', 'wali_kelas', 'guru_piket'];

        /* ---- Role change: show/hide guru field ---- */
        function handleRoleChange(prefix) {
            const roleVal = document.getElementById(prefix + '_role').value;
            const guruGroup = document.getElementById(prefix + '_guru_group');
            if (TEACHER_ROLES.includes(roleVal)) {
                guruGroup.style.display = 'flex';
            } else {
                guruGroup.style.display = 'none';
                document.getElementById(prefix + '_id_guru').value = '';
                document.getElementById(prefix + '_guru_input').value = '';
            }
        }

        /* ---- Searchable Select: open dropdown with auto-flip ---- */
        function openDropdown(prefix) {
            const input = document.getElementById(prefix + '_guru_input');
            const dd = document.getElementById(prefix + '_guru_dropdown');
            const rect = input.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;
            const dropdownHeight = 228; // max-height 220 + padding

            // Remove old direction classes
            dd.classList.remove('ss-up', 'ss-down');

            // Pick direction: if not enough space below AND more space above → flip up
            if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                dd.classList.add('ss-up');
            } else {
                dd.classList.add('ss-down');
            }

            dd.classList.add('ss-open');
            filterDropdown(prefix);
        }

        /* ---- Searchable Select: filter items ---- */
        function filterDropdown(prefix) {
            const query = document.getElementById(prefix + '_guru_input').value.toLowerCase();
            const dd = document.getElementById(prefix + '_guru_dropdown');
            const items = dd.querySelectorAll('.ss-option');
            let visibleCount = 0;
            items.forEach(item => {
                const val = item.getAttribute('data-value');
                const txt = item.textContent.toLowerCase();
                if (val === '' || txt.includes(query)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            dd.classList.add('ss-open');
        }

        /* ---- Searchable Select: pick option ---- */
        function pickGuru(prefix, value, label) {
            document.getElementById(prefix + '_id_guru').value = value;
            document.getElementById(prefix + '_guru_input').value = value ? label : '';
            document.getElementById(prefix + '_guru_dropdown').classList.remove('ss-open');
        }

        /* ---- Close dropdown on outside click ---- */
        document.addEventListener('click', function(e) {
            ['create', 'edit'].forEach(prefix => {
                const ss = document.getElementById(prefix + '_guru_ss');
                if (ss && !ss.contains(e.target)) {
                    document.getElementById(prefix + '_guru_dropdown').classList.remove('ss-open');
                }
            });
        });

        /* ---- Sidebar toggle ---- */
        function toggleSubmenu(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        }

        function toggleFilterMenu() {
            const el = document.getElementById('filterMenuDropdown');
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        /* ---- Modal CRUD Functions ---- */
        function openCreateModal() {
            document.getElementById('create_guru_input').value = '';
            document.getElementById('create_id_guru').value = '';
            document.getElementById('createModal').style.display = 'flex';
            handleRoleChange('create');
        }

        function closeCreateModal() {
            document.getElementById('createModal').style.display = 'none';
        }

        function openEditModal(id, name, email, role, idGuru) {
            document.getElementById('editForm').action = '/users/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_id_guru').value = idGuru || '';

            // Set guru input display text from id
            const guru = GURU_DATA.find(g => g.id == idGuru);
            document.getElementById('edit_guru_input').value = guru ? guru.label : '';

            handleRoleChange('edit');
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function toggleTablePassword(userId) {
            const pwdEl = document.getElementById('table_pwd_' + userId);
            const eyeOpen = document.getElementById('table_eye_open_' + userId);
            const eyeClosed = document.getElementById('table_eye_closed_' + userId);

            if (pwdEl.getAttribute('data-shown') === 'true') {
                pwdEl.innerText = '••••••••••••';
                pwdEl.style.letterSpacing = '2px';
                pwdEl.setAttribute('data-shown', 'false');
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            } else {
                pwdEl.innerText = 'password';
                pwdEl.style.letterSpacing = 'normal';
                pwdEl.setAttribute('data-shown', 'true');
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            }
        }

        let isPasswordVisible = false;

        function toggleViewPassword() {
            isPasswordVisible = !isPasswordVisible;
            const pwdEl = document.getElementById('view_password');
            const eyeOpen = document.getElementById('icon_eye_open');
            const eyeClosed = document.getElementById('icon_eye_closed');

            if (isPasswordVisible) {
                pwdEl.innerText = 'password';
                pwdEl.style.letterSpacing = 'normal';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                pwdEl.innerText = '••••••••••••';
                pwdEl.style.letterSpacing = '2px';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }

        function openViewModal(id) {
            isPasswordVisible = false;
            document.getElementById('view_password').innerText = '••••••••••••';
            document.getElementById('view_password').style.letterSpacing = '2px';
            document.getElementById('icon_eye_open').style.display = 'block';
            document.getElementById('icon_eye_closed').style.display = 'none';

            fetch('/users/' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('view_name').innerText = data.name;
                    document.getElementById('view_email').innerText = data.email;
                    document.getElementById('view_role').innerText = data.role_label;
                    document.getElementById('view_guru').innerText = data.nama_guru;
                    document.getElementById('view_created_at').innerText = data.created_at;
                    document.getElementById('viewModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        function updateLiveClock() {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const now = new Date();
            const dayName = days[now.getDay()];
            const dateNum = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const dateEl = document.getElementById('live_date_str');
            const timeEl = document.getElementById('live_time_str');

            if (dateEl) dateEl.innerText = `${dayName}, ${dateNum} ${monthName} ${year}`;
            if (timeEl) timeEl.innerText = `${hours}:${minutes}:${seconds} WIB`;
        }

        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        /* ---- Real-time Client-side Search (No Refresh) ---- */
        (function() {
            const input = document.getElementById('userSearchInput');
            if (!input) return;

            const tbody = document.querySelector('.pengguna-table tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));

            input.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();

                rows.forEach(function(row) {
                    const name = (row.querySelector('.td-nama') || {}).textContent || '';
                    const role = (row.querySelector('.role-badge-cell') || {}).textContent || '';
                    const text = (name + ' ' + role).toLowerCase();

                    if (q === '' || text.includes(q)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Prevent form submit on Enter key
            input.closest('form').addEventListener('submit', function(e) {
                e.preventDefault();
            });
        })();

        /* ---- Auto-fade Flash Feedback Alerts after 3 seconds ---- */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert').forEach(function(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>
</body>
</html>
