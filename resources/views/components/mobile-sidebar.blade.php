@php
    $currentRoute = Route::currentRouteName();
@endphp

{{-- Mobile Sidebar Drawer Overlay --}}
<div class="mobile-sidebar-overlay" id="mobileSidebarOverlay" onclick="toggleMobileSidebar(false)"></div>

{{-- Mobile Sidebar Drawer --}}
<aside class="mobile-sidebar" id="mobileSidebar" role="dialog" aria-modal="true" aria-label="Menu Navigasi Mobile">
    {{-- Drawer Header --}}
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-logo" onclick="toggleMobileSidebar(false)">
            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px; color: var(--color-primary-500); flex-shrink: 0;">
                <rect width="32" height="32" rx="8" fill="currentColor"/>
                <path d="M16 7v18M7 16h18" stroke="#fff" stroke-width="2.8" stroke-linecap="round"/>
            </svg>
            <div>
                <div class="sidebar-logo-title">RS Santa Anna</div>
                <div class="sidebar-logo-sub">Rumah Sakit Terpercaya</div>
            </div>
        </a>
        <button type="button" class="sidebar-close-btn" onclick="toggleMobileSidebar(false)" aria-label="Tutup Menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="sidebar-body">
        {{-- Quick Account Card --}}
        <div class="sidebar-account-box">
            <div class="account-box-badge">Portal Pasien</div>
            <div class="account-box-title">Akses Cepat Layanan Kesehatan</div>
            <p class="account-box-desc">Daftar akun untuk notifikasi jadwal periksa dan riwayat antrian Anda.</p>
            <div class="account-box-actions">
                <a href="{{ route('auth.login') }}" class="btn btn-outline btn-sm" style="background: #fff; flex: 1;" onclick="toggleMobileSidebar(false)">Masuk</a>
                <a href="{{ route('auth.register') }}" class="btn btn-primary btn-sm" style="flex: 1;" onclick="toggleMobileSidebar(false)">Buat Akun</a>
            </div>
        </div>

        {{-- Primary Action: Daftar Berobat --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ route('registration.select-type') }}" class="sidebar-cta-btn" onclick="toggleMobileSidebar(false)">
                <div class="cta-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 14px; color: #fff;">Daftar Berobat Online</div>
                    <div style="font-size: 11px; color: rgba(255,255,255,.8); margin-top: 1px;">Poli Rawat Jalan & Kamar Rawat Inap</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px; color: #fff; margin-left: auto;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </a>
        </div>

        {{-- Menu Group: Layanan Utama --}}
        <div class="sidebar-group-title">Layanan & Fasilitas</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}" class="{{ $currentRoute === 'home' ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                    </span>
                    <span>Beranda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('polyclinics.index') }}" class="{{ str_starts_with($currentRoute ?? '', 'polyclinics') ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                    </span>
                    <span>Poliklinik & Spesialis</span>
                </a>
            </li>
            <li>
                <a href="{{ route('doctors.index') }}" class="{{ str_starts_with($currentRoute ?? '', 'doctors') ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </span>
                    <span>Jadwal & Cari Dokter</span>
                </a>
            </li>
            <li>
                <a href="{{ route('queue.index') }}" class="{{ in_array($currentRoute, ['queue.index']) ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                        </svg>
                    </span>
                    <span>Live Antrian Real-time</span>
                </a>
            </li>
        </ul>

        {{-- Menu Group: Pendaftaran Rawat --}}
        <div class="sidebar-group-title">Pendaftaran Mandiri</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('registration.outpatient') }}" class="{{ $currentRoute === 'registration.outpatient' ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.375 12.375 0 0110.375 21c-2.31 0-4.473-.637-6.375-1.765z"/>
                        </svg>
                    </span>
                    <span>Daftar Rawat Jalan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('registration.inpatient') }}" class="{{ $currentRoute === 'registration.inpatient' ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                    </span>
                    <span>Daftar Rawat Inap</span>
                </a>
            </li>
        </ul>

        {{-- Menu Group: Informasi RS --}}
        <div class="sidebar-group-title">Informasi Rumah Sakit</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('about') }}" class="{{ $currentRoute === 'about' ? 'active' : '' }}" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                        </svg>
                    </span>
                    <span>Tentang RS Santa Anna</span>
                </a>
            </li>
            <li>
                <a href="{{ route('about') }}#fasilitas" onclick="toggleMobileSidebar(false)">
                    <span class="menu-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.75a1.5 1.5 0 011.5-1.5h1.5a1.5 1.5 0 011.5 1.5V21m6-9h.75m-.75 3h.75m-.75 3h.75"/>
                        </svg>
                    </span>
                    <span>Fasilitas & Kamar Inap</span>
                </a>
            </li>
        </ul>

        {{-- Emergency Box --}}
        <div class="sidebar-emergency-card">
            <div class="emergency-badge">IGD 24 Jam Siap Melayani</div>
            <div class="emergency-phone">(022) 1234-5678</div>
            <p class="emergency-note">Layanan gawat darurat & ambulans siaga 24 jam setiap hari.</p>
            <div style="display: flex; gap: 8px; margin-top: 10px;">
                <a href="tel:02212345678" class="btn btn-sm" style="background: #dc3545; color: #fff; flex: 1; border-radius: 8px; font-size: 12px; padding: 8px 10px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    Hubungi IGD
                </a>
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="btn btn-sm" style="background: #25d366; color: #fff; flex: 1; border-radius: 8px; font-size: 12px; padding: 8px 10px;">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width: 14px; height: 14px; margin-right: 4px;"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                    WhatsApp RS
                </a>
            </div>
        </div>

        {{-- Address & Copyright Footer in Drawer --}}
        <div class="sidebar-footer">
            <p><strong>RS Santa Anna</strong></p>
            <p>Jl. Santa Anna No. 123, Kota Bandung, Jawa Barat</p>
            <p style="margin-top: 6px; color: var(--color-gray-400); font-size: 11px;">&copy; {{ date('Y') }} RS Santa Anna. All rights reserved.</p>
        </div>
    </div>
</aside>
