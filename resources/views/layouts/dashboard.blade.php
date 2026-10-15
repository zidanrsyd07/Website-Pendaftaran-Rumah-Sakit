<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - RS Santa Anna</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&family=inter:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $dashHome = match(Auth::user()?->role) {
        'admin' => route('dashboard.admin'),
        'loket_rawat_jalan' => route('dashboard.loket-rawat-jalan'),
        'loket_rawat_inap' => route('dashboard.loket-rawat-inap'),
        'pasien' => route('dashboard.pasien'),
        default => route('home')
    };
@endphp
<body>
    <div class="dashboard-layout">
        {{-- Sidebar --}}
        <aside class="dash-sidebar" id="dashSidebar">
            <div class="dash-sidebar-header">
                <a href="{{ $dashHome }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                    <div class="dash-logo-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16M4 12h16"/>
                        </svg>
                    </div>
                    <div class="dash-logo-text">
                        <h4>RS Santa Anna</h4>
                        <span>@yield('role-label', Auth::user()->role_label ?? 'Staff')</span>
                    </div>
                </a>
            </div>

            <nav class="dash-sidebar-nav">
                @yield('sidebar-nav')
            </nav>

            <div class="dash-sidebar-footer">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 4px 6px;">
                    <div style="display: flex; align-items: center; gap: 10px; overflow: hidden;">
                        <div class="dash-profile-btn" style="width: 34px; height: 34px; font-size: 12px;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <div style="font-size: 13px; font-weight: 700; color: #0F172A; text-overflow: ellipsis; overflow: hidden;">{{ Auth::user()->name ?? 'User' }}</div>
                            <div style="font-size: 11px; color: #64748B;">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('auth.logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" title="Keluar dari Sistem" style="background: none; border: none; cursor: pointer; color: #94A3B8; padding: 6px; border-radius: 6px; display: flex; align-items: center;" onmouseover="this.style.color='#DC2626'; this.style.background='#FEE2E2'" onmouseout="this.style.color='#94A3B8'; this.style.background='none'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="dash-main">
            {{-- Top Bar --}}
            <header class="dash-topbar">
                <div class="dash-topbar-left">
                    <button class="sidebar-toggle" onclick="toggleDashSidebar()" aria-label="Toggle sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    </button>
                    <div class="dash-breadcrumb">
                        <a href="{{ $dashHome }}">RS Santa Anna</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:13px;height:13px;color:#CBD5E1;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        <span>@yield('breadcrumb', 'Dashboard')</span>
                    </div>
                </div>

                <div class="dash-topbar-right">
                    {{-- System Online Status Indicator --}}
                    <div class="dash-system-status">
                        <div class="pulse-dot"></div>
                        <span>Sistem Aktif</span>
                    </div>

                    {{-- Live Clock --}}
                    <div class="dash-live-clock" id="liveClock">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span id="liveClockText">{{ now()->translatedFormat('l, d M Y') }}</span>
                    </div>

                    {{-- Search Box --}}
                    <div class="dash-search-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" id="globalSearchInput" placeholder="Cari data pasien...">
                    </div>

                    {{-- Notification Bell --}}
                    <div class="dash-notification-btn" title="Notifikasi Sistem" onclick="showToast('Semua layanan berjalan optimal.', 'info')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <div class="notif-dot"></div>
                    </div>

                    {{-- User Profile Pill --}}
                    <a href="#" class="dash-profile-btn" title="{{ Auth::user()->name ?? 'User' }}">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </a>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="dash-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Toast Notification Container --}}
    <div id="toastContainer" class="toast-container"></div>

    <script>
        function toggleDashSidebar() {
            const sidebar = document.getElementById('dashSidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Global Toast Notification Helper
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icons = {
                success: `<svg style="width:18px;height:18px;color:#16A34A;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>`,
                error: `<svg style="width:18px;height:18px;color:#DC2626;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`,
                warning: `<svg style="width:18px;height:18px;color:#D97706;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>`,
                info: `<svg style="width:18px;height:18px;color:#2563EB;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>`
            };

            toast.innerHTML = `
                ${icons[type] || icons.info}
                <div style="flex: 1; font-size: 13px; font-weight: 500; color: #1E293B;">${message}</div>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#94A3B8;padding:2px;">&times;</button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                toast.style.transition = 'all .3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        };

        // CSRF Token Helper
        window.csrfToken = function() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        };
    </script>

    @stack('scripts')
</body>
</html>
