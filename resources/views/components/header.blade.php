@php
    $currentRoute = Route::currentRouteName();
@endphp

{{-- Top Info Bar (Desktop only) --}}
<div class="top-bar">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 16px;">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: inline; vertical-align: -2px; width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                Kesehatan Anda, Prioritas Kami
            </span>
            <span>IGD 24 Jam</span>
        </div>
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="tel:02212345678">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: inline; vertical-align: -2px; width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                (022) 1234-5678
            </a>
        </div>
    </div>
</div>

{{-- Main Header --}}
<header class="site-header">
    <div class="container">
        <a href="{{ route('home') }}" class="site-logo">
            <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="36" height="36" rx="9" fill="currentColor"/>
                <path d="M18 8v20M8 18h20" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <div>
                <div class="logo-text">RS Santa Anna</div>
                <div class="logo-sub">Rumah Sakit</div>
            </div>
        </a>

        <nav class="main-nav">
            <a href="{{ route('home') }}" class="{{ $currentRoute === 'home' ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('about') }}" class="{{ $currentRoute === 'about' ? 'active' : '' }}">Tentang</a>
            <a href="{{ route('polyclinics.index') }}" class="{{ str_starts_with($currentRoute ?? '', 'polyclinics') ? 'active' : '' }}">Layanan</a>
            <a href="{{ route('doctors.index') }}" class="{{ str_starts_with($currentRoute ?? '', 'doctors') ? 'active' : '' }}">Dokter</a>
            <a href="{{ route('queue.index') }}" class="{{ $currentRoute === 'queue.index' ? 'active' : '' }}">Antrian</a>
        </nav>

        <div class="header-actions">
            <a href="{{ route('auth.login') }}" class="btn btn-ghost btn-sm btn-login">Masuk</a>
            <a href="{{ route('registration.select-type') }}" class="btn btn-primary btn-sm">Daftar Berobat</a>
            <button type="button" class="mobile-menu-btn" onclick="toggleMobileSidebar(true)" aria-label="Buka Menu Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>
</header>
