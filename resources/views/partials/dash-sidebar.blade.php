@php
    $currentRoute = Route::currentRouteName();
    $isMasterData = in_array($currentRoute, ['users.index', 'siswa.index', 'guru.index', 'kelas.index', 'mapel.index', 'ruangan.index']) || request()->is('users*', 'siswa*', 'guru*', 'kelas*', 'mapel*', 'ruangan*');
    $isAkademik = in_array($currentRoute, ['jam.index', 'jadwal.index', 'jadwal-piket.index', 'jurnal.index']) || request()->is('jam*', 'jadwal*', 'jadwal-piket*', 'jurnal*');
    $isPengaturanWa = request()->is('pengaturan-wa*');
@endphp

<!-- Sidebar Navigation -->
<aside class="dash-sidebar">
    @include('partials.dash-brand')

    <ul class="dash-menu">
        @if(Auth::check() && Auth::user()->isAdmin())
            <li class="dash-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="dash-menu-link" @if(request()->routeIs('dashboard')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Master Data Category -->
            <li class="dash-menu-category">
                <button type="button" class="dash-category-btn" onclick="toggleSubmenu('masterDataSub')">
                    <div class="dash-category-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="width: 18px; height: 18px; flex-shrink: 0;">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span>Master Data</span>
                    </div>
                    <svg class="dash-category-chevron" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <ul class="dash-sub-menu" id="masterDataSub" style="{{ $isMasterData ? 'display: flex;' : '' }}">
                    <li>
                        <a href="{{ route('users.index') }}" class="dash-sub-link" @if(request()->routeIs('users.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('users.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.index') }}" class="dash-sub-link" @if(request()->routeIs('siswa.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('siswa.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                            <span>Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.index') }}" class="dash-sub-link" @if(request()->routeIs('guru.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('guru.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                            <span>Guru</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelas.index') }}" class="dash-sub-link" @if(request()->routeIs('kelas.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('kelas.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                <path d="M3 9h18M9 21V9"></path>
                            </svg>
                            <span>Kelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mapel.index') }}" class="dash-sub-link" @if(request()->routeIs('mapel.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('mapel.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span>Mapel</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ruangan.index') }}" class="dash-sub-link" @if(request()->routeIs('ruangan.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('ruangan.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Ruangan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Akademik Category -->
            <li class="dash-menu-category">
                <button type="button" class="dash-category-btn" onclick="toggleSubmenu('akademikSub')">
                    <div class="dash-category-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="width: 18px; height: 18px; flex-shrink: 0;">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span>Akademik</span>
                    </div>
                    <svg class="dash-category-chevron" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <ul class="dash-sub-menu" id="akademikSub" style="{{ $isAkademik ? 'display: flex;' : '' }}">
                    <li>
                        <a href="{{ route('jam.index') }}" class="dash-sub-link" @if(request()->routeIs('jam.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('jam.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>Jam Pelajaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jadwal.index') }}" class="dash-sub-link" @if(request()->routeIs('jadwal.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('jadwal.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Jadwal Pelajaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jadwal-piket.index') }}" class="dash-sub-link" @if(request()->routeIs('jadwal-piket.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('jadwal-piket.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                            <span>Jadwal Guru Piket</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jurnal.index') }}" class="dash-sub-link" @if(request()->routeIs('jurnal.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" style="{{ request()->routeIs('jurnal.*') ? 'color: #ffffff;' : '' }} width: 18px; height: 18px; flex-shrink: 0;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                            <span>Jurnal Mengajar</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Pengaturan / Notifikasi WhatsApp Menu Item -->
            <li class="dash-menu-item {{ $isPengaturanWa ? 'active' : '' }}">
                <a href="{{ route('pengaturan-wa.index') }}" class="dash-menu-link" @if($isPengaturanWa) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span>Bot Konfigurasi</span>
                </a>
            </li>
        @else
            {{-- Non-Admin Roles (Guru Mengajar, Guru Piket, Wali Kelas) --}}
            <li class="dash-menu-item {{ request()->routeIs('role.dashboard', 'guru-mengajar.dashboard', 'guru-piket.dashboard', 'wali-kelas.dashboard') ? 'active' : '' }}">
                <a href="{{ route('role.dashboard') }}" class="dash-menu-link" @if(request()->routeIs('role.dashboard', 'guru-mengajar.dashboard', 'guru-piket.dashboard', 'wali-kelas.dashboard')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                    <span>Dashboard Saya</span>
                </a>
            </li>

            <li class="dash-menu-item {{ request()->routeIs('jurnal.*') ? 'active' : '' }}">
                <a href="{{ route('jurnal.index') }}" class="dash-menu-link" @if(request()->routeIs('jurnal.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span>Jurnal Mengajar Saya</span>
                </a>
            </li>

            <li class="dash-menu-item {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                <a href="{{ route('jadwal.index') }}" class="dash-menu-link" @if(request()->routeIs('jadwal.*')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Jadwal Pelajaran Saya</span>
                </a>
            </li>

            @if(Auth::check() && Auth::user()->isWaliKelas())
                <li class="dash-menu-item {{ request()->routeIs('wali-kelas.perwalian') ? 'active' : '' }}">
                    <a href="{{ route('wali-kelas.perwalian') }}" class="dash-menu-link" @if(request()->routeIs('wali-kelas.perwalian')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Kelas Perwalian</span>
                    </a>
                </li>
            @endif

            @if(Auth::check() && Auth::user()->isGuruPiket())
                <li class="dash-menu-item {{ request()->routeIs('guru-piket.digital-surat') ? 'active' : '' }}">
                    <a href="{{ route('guru-piket.digital-surat') }}" class="dash-menu-link" @if(request()->routeIs('guru-piket.digital-surat')) style="background-color: var(--dash-navy); color: #ffffff; font-weight: 700;" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="width: 20px; height: 20px; flex-shrink: 0;">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Surat Piket Digital</span>
                    </a>
                </li>
            @endif
        @endif
    </ul>

    @include('partials.dash-sidebar-footer')
</aside>
