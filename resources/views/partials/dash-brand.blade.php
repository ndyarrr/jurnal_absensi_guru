<div class="dash-sidebar-brand-wrap">
    <a href="{{ Auth::check() && Auth::user()->isAdmin() ? route('dashboard') : route('role.dashboard') }}" class="dash-brand" style="padding: 0;">
        <img src="{{ asset('assets/image/logo/logo-brand.svg') }}" alt="Jurnal & Absensi Guru" class="dash-brand-logo">
    </a>
    <button type="button" class="dash-sidebar-close-btn" onclick="toggleSidebar()" title="Sembunyikan Sidebar" aria-label="Tutup sidebar">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>
