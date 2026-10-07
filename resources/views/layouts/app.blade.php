<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'RS Santa Anna')</title>
    <meta name="description" content="@yield('description', 'Website resmi RS Santa Anna. Akses layanan rumah sakit, daftar berobat, cari dokter, dan lihat antrian melalui smartphone.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="page-container" id="app">
        @hasSection('hide-header')
        @else
            @hasSection('header')
                <div class="mobile-only-header">
                    @yield('header')
                </div>
                <div class="desktop-only-header">
                    @include('components.header')
                </div>
            @else
                @include('components.header')
            @endif
        @endif

        <main>
            @yield('content')
        </main>

        @hasSection('hide-footer')
        @else
            @include('components.footer')
        @endif

        @hasSection('hide-bottom-nav')
        @else
            @include('components.bottom-nav')
        @endif
    </div>

    @include('components.mobile-sidebar')

    <script>
        function toggleMobileSidebar(force) {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            if (!sidebar || !overlay) return;
            const isOpen = sidebar.classList.contains('open');
            const shouldOpen = typeof force === 'boolean' ? force : !isOpen;
            if (shouldOpen) {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
