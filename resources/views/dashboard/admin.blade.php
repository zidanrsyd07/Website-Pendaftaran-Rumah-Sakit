@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('role-label', 'Administrator')
@section('breadcrumb', 'Admin Dashboard')

@section('sidebar-nav')
    <div class="dash-nav-section">
        <div class="dash-nav-title">Menu Utama</div>
        <a href="{{ route('dashboard.admin') }}" class="dash-nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            <span class="nav-label">Dashboard Admin</span>
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Monitoring & Kontrol</div>
        <a href="#loketGridSection" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            <span class="nav-label">Status Loket Fisik</span>
        </a>
        <a href="#kanbanSection" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z"/></svg>
            <span class="nav-label">Papan Alur Pasien (Kanban)</span>
        </a>
        <a href="#recentTableSection" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            <span class="nav-label">Rekapitulasi Pendaftaran</span>
        </a>
        <a href="#activitySection" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Log Aktivitas Petugas</span>
        </a>
    </div>
@endsection

@section('content')
    {{-- Header --}}
    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 28px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div class="dash-page-header" style="margin-bottom: 0;">
                <h1>Dashboard Administrator</h1>
                <p>Ringkasan komprehensif alur pasien dan pemantauan loket RS Santa Anna — {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <button onclick="window.location.reload()" class="btn-outline-slate" title="Perbarui Data">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                    Segarkan
                </button>
                <span class="btn-outline-slate" style="cursor: default; display: inline-flex; align-items: center; gap: 8px;" title="Kapasitas Bed SIMRS">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;color:#16A34A;"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    Kamar Ready: <strong>{{ $bedStats['ready_beds'] ?? 0 }} Bed</strong>
                </span>
            </div>
        </div>
    </div>

    {{-- 4 KPI Stat Cards --}}
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Pendaftaran Hari Ini</div>
                    <div class="stat-value">{{ $stats['total_registrations_today'] }}</div>
                </div>
                <div class="stat-card-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                <span>RJ + RI Real-time</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Sedang Dilayani</div>
                    <div class="stat-value">{{ $stats['active_queues'] }}</div>
                </div>
                <div class="stat-card-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #7C3AED;"></span>
                <span>Di loket fisik</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Selesai Hari Ini</div>
                    <div class="stat-value">{{ $stats['completed_today'] }}</div>
                </div>
                <div class="stat-card-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18"/></svg>
                <span>Pelayanan tuntas</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Avg Waktu Tunggu</div>
                    <div class="stat-value">{{ $stats['avg_wait_minutes'] }} <span style="font-size: 16px; font-weight: 600; color: #64748B;">menit</span></div>
                </div>
                <div class="stat-card-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>Target: &lt; 20 menit</span>
            </div>
        </div>
    </div>

    {{-- Loket Monitoring Grid --}}
    <div style="margin-bottom: 24px;" id="loketGridSection">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #0F172A; margin: 0;">Status Loket Layanan Aktif</h3>
            <span style="font-size: 12.5px; color: #64748B;">2 Loket Terdaftar</span>
        </div>
        <div class="loket-card-grid">
            @foreach($loketStatuses as $loket)
                <div class="loket-card">
                    <div class="loket-card-top">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $loket['status'] === 'active' ? '#16A34A' : ($loket['status'] === 'idle' ? '#D97706' : '#94A3B8') }};"></div>
                            <h4>{{ $loket['name'] }}</h4>
                        </div>
                        <span class="status-badge {{ $loket['status'] === 'active' ? 'verified' : ($loket['status'] === 'idle' ? 'pending' : 'draft') }}">
                            {{ $loket['status'] === 'active' ? 'Sedang Melayani' : ($loket['status'] === 'idle' ? 'Menunggu Panggilan' : 'Tutup') }}
                        </span>
                    </div>

                    <div class="loket-card-serving">
                        <div class="loket-queue-num">{{ $loket['current_number'] }}</div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #64748B; letter-spacing: .05em;">Pasien Saat Ini</div>
                            <div class="loket-patient-name">{{ $loket['current_patient'] }}</div>
                            <div class="loket-patient-meta">{{ $loket['name'] }} &bull; Counter 1</div>
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 6px;">
                            <span style="color: #64748B;">Progres Antrean Hari Ini</span>
                            <span style="font-weight: 700; color: #0F172A;">{{ $loket['completed'] }} / {{ $loket['total'] }} ({{ $loket['progress'] }}%)</span>
                        </div>
                        <div class="loket-progress-bar">
                            <div class="loket-progress-fill" style="width: {{ $loket['progress'] }}%;"></div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px solid #F1F5F9; font-size: 12.5px;">
                        <span style="color: #D97706; font-weight: 600;">{{ $loket['waiting'] }} antrean menunggu</span>
                        <span style="display: inline-flex; align-items: center; gap: 5px; color: #64748B; font-weight: 600; font-size: 12px; background: #F1F5F9; padding: 4px 8px; border-radius: 6px;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #3B82F6;"></span>
                            Monitoring Loket
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Charts Section (Donut Chart & Hourly Activity) --}}
    <div class="dash-chart-grid">
        {{-- Donut Chart: Status Distribution --}}
        <div class="dash-chart-card">
            <h3>
                <span>Distribusi Status Pendaftaran</span>
                <span style="font-size: 12px; font-weight: 500; color: #64748B;">Total: {{ array_sum($statusDistribution) }} Pasien</span>
            </h3>

            @php
                $totalStatus = array_sum($statusDistribution) ?: 1;
                $pPct = round((($statusDistribution['pending'] ?? 0) / $totalStatus) * 100);
                $vPct = round((($statusDistribution['verified'] ?? 0) / $totalStatus) * 100);
                $iPct = round((($statusDistribution['in_progress'] ?? 0) / $totalStatus) * 100);
                $cPct = round((($statusDistribution['completed'] ?? 0) / $totalStatus) * 100);
                $xPct = round((($statusDistribution['cancelled'] ?? 0) / $totalStatus) * 100);

                // SVG donut calculation: radius 52, perimeter = 2 * pi * 52 = 326.7
                $circumference = 326.7;
                $pDash = ($statusDistribution['pending'] ?? 0) / $totalStatus * $circumference;
                $vDash = ($statusDistribution['verified'] ?? 0) / $totalStatus * $circumference;
                $iDash = ($statusDistribution['in_progress'] ?? 0) / $totalStatus * $circumference;
                $cDash = ($statusDistribution['completed'] ?? 0) / $totalStatus * $circumference;
                $xDash = ($statusDistribution['cancelled'] ?? 0) / $totalStatus * $circumference;

                $offset1 = 0;
                $offset2 = $offset1 - $cDash;
                $offset3 = $offset2 - $iDash;
                $offset4 = $offset3 - $vDash;
                $offset5 = $offset4 - $pDash;
            @endphp

            <div style="display: flex; flex-direction: column; md:flex-row; align-items: center; justify-content: space-around; gap: 20px; padding: 10px 0;">
                {{-- Inline SVG Donut Chart --}}
                <div style="position: relative; width: 170px; height: 170px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 140 140" style="width: 100%; height: 100%; transform: rotate(-90deg);">
                        <circle cx="70" cy="70" r="52" fill="none" stroke="#F1F5F9" stroke-width="18"/>
                        {{-- Completed (Green) --}}
                        <circle cx="70" cy="70" r="52" fill="none" stroke="#16A34A" stroke-width="18"
                                stroke-dasharray="{{ $cDash }} {{ $circumference }}" stroke-dashoffset="{{ $offset1 }}"/>
                        {{-- In Progress (Purple) --}}
                        <circle cx="70" cy="70" r="52" fill="none" stroke="#7C3AED" stroke-width="18"
                                stroke-dasharray="{{ $iDash }} {{ $circumference }}" stroke-dashoffset="{{ $offset2 }}"/>
                        {{-- Verified (Blue) --}}
                        <circle cx="70" cy="70" r="52" fill="none" stroke="#2563EB" stroke-width="18"
                                stroke-dasharray="{{ $vDash }} {{ $circumference }}" stroke-dashoffset="{{ $offset3 }}"/>
                        {{-- Pending (Amber) --}}
                        <circle cx="70" cy="70" r="52" fill="none" stroke="#D97706" stroke-width="18"
                                stroke-dasharray="{{ $pDash }} {{ $circumference }}" stroke-dashoffset="{{ $offset4 }}"/>
                        {{-- Cancelled/Skipped (Red) --}}
                        <circle cx="70" cy="70" r="52" fill="none" stroke="#DC2626" stroke-width="18"
                                stroke-dasharray="{{ $xDash }} {{ $circumference }}" stroke-dashoffset="{{ $offset5 }}"/>
                    </svg>
                    <div style="position: absolute; text-align: center;">
                        <div style="font-size: 24px; font-weight: 800; color: #0F172A; line-height: 1;">{{ array_sum($statusDistribution) }}</div>
                        <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase;">Pasien</div>
                    </div>
                </div>

                {{-- Donut Legend --}}
                <div style="display: flex; flex-direction: column; gap: 9px; width: 100%; max-width: 240px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; background: #16A34A;"></span>
                            <span style="color: #334155; font-weight: 500;">Selesai</span>
                        </div>
                        <span style="font-weight: 700; color: #0F172A;">{{ $statusDistribution['completed'] ?? 0 }} ({{ $cPct }}%)</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; background: #7C3AED;"></span>
                            <span style="color: #334155; font-weight: 500;">Sedang Dilayani</span>
                        </div>
                        <span style="font-weight: 700; color: #0F172A;">{{ $statusDistribution['in_progress'] ?? 0 }} ({{ $iPct }}%)</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; background: #2563EB;"></span>
                            <span style="color: #334155; font-weight: 500;">Terverifikasi</span>
                        </div>
                        <span style="font-weight: 700; color: #0F172A;">{{ $statusDistribution['verified'] ?? 0 }} ({{ $vPct }}%)</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; background: #D97706;"></span>
                            <span style="color: #334155; font-weight: 500;">Menunggu</span>
                        </div>
                        <span style="font-weight: 700; color: #0F172A;">{{ $statusDistribution['pending'] ?? 0 }} ({{ $pPct }}%)</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 3px; background: #DC2626;"></span>
                            <span style="color: #334155; font-weight: 500;">Dibatalkan / Skip</span>
                        </div>
                        <span style="font-weight: 700; color: #0F172A;">{{ $statusDistribution['cancelled'] ?? 0 }} ({{ $xPct }}%)</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bar Chart: Hourly Activity --}}
        <div class="dash-chart-card">
            <h3>
                <span>Aktivitas Registrasi per Jam</span>
                <span style="font-size: 12px; font-weight: 500; color: #64748B;">Jam Operasional 08:00 - 16:00</span>
            </h3>

            @php
                $maxHourly = max(array_values($hourlyActivity) ?: [1]);
                $maxHourly = $maxHourly > 0 ? $maxHourly : 1;
            @endphp

            <div style="height: 190px; display: flex; align-items: flex-end; gap: 10px; padding: 10px 4px 0 4px; border-bottom: 1px solid #E2E8F0;">
                @foreach($hourlyActivity as $hour => $count)
                    @php
                        $heightPct = round(($count / $maxHourly) * 100);
                        $isPeak = $count === $maxHourly && $count > 0;
                    @endphp
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;" title="{{ $hour }}: {{ $count }} pendaftaran">
                        <span style="font-size: 11px; font-weight: 700; color: {{ $isPeak ? '#2563EB' : '#64748B' }}; margin-bottom: 4px;">{{ $count }}</span>
                        <div style="width: 100%; max-width: 32px; height: {{ max($heightPct, 6) }}%; background: {{ $isPeak ? '#2563EB' : '#DBEAFE' }}; border-radius: 6px 6px 0 0; transition: all .2s ease;"
                             onmouseover="this.style.background='#1D4ED8'"
                             onmouseout="this.style.background='{{ $isPeak ? '#2563EB' : '#DBEAFE' }}'">
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; gap: 10px; padding: 8px 4px 0 4px;">
                @foreach(array_keys($hourlyActivity) as $hour)
                    <div style="flex: 1; text-align: center; font-size: 10.5px; font-weight: 600; color: #94A3B8;">
                        {{ substr($hour, 0, 2) }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Kanban Board (4 Columns - FlowTask Style) --}}
    <div style="margin-bottom: 28px;" id="kanbanSection">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: #0F172A; margin: 0 0 2px 0;">Alur Pelayanan Pasien (Workflow Board)</h3>
                <p style="font-size: 13px; color: #64748B; margin: 0;">Status siklus registrasi dari kedatangan hingga selesai pelayanan</p>
            </div>
            <div style="font-size: 12.5px; color: #64748B;">Auto-refresh aktif</div>
        </div>

        <div class="kanban-board">
            {{-- Column 1: Menunggu --}}
            <div class="kanban-col waiting">
                <div class="kanban-col-header">
                    <div class="kanban-col-title">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #D97706;"></span>
                        Menunggu
                    </div>
                    <span class="kanban-col-badge">{{ count($kanbanData['waiting']) }}</span>
                </div>
                <div style="flex: 1; overflow-y: auto;">
                    @forelse($kanbanData['waiting'] as $reg)
                        <div class="kanban-card">
                            <div class="kanban-card-header">
                                <span class="kanban-ticket-num">{{ $reg->queueTicket?->queue_number ?? $reg->registration_number }}</span>
                                <span class="status-badge pending">Antre</span>
                            </div>
                            <div class="kanban-patient-name">{{ $reg->patient_name }}</div>
                            <div style="font-size: 12px; color: #64748B;">{{ $reg->polyclinic ?? ($reg->room_class ? 'Kamar: '.$reg->room_class : '-') }}</div>
                            <div class="kanban-card-meta">
                                <span>{{ $reg->service_type === 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}</span>
                                <span>{{ $reg->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 32px 12px; color: #94A3B8; font-size: 13px;">
                            Tidak ada pasien menunggu
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 2: Dalam Proses --}}
            <div class="kanban-col in_progress">
                <div class="kanban-col-header">
                    <div class="kanban-col-title">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #2563EB;"></span>
                        Sedang Dilayani
                    </div>
                    <span class="kanban-col-badge">{{ count($kanbanData['in_progress']) }}</span>
                </div>
                <div style="flex: 1; overflow-y: auto;">
                    @forelse($kanbanData['in_progress'] as $reg)
                        <div class="kanban-card" style="border-left: 3px solid #2563EB;">
                            <div class="kanban-card-header">
                                <span class="kanban-ticket-num">{{ $reg->queueTicket?->queue_number ?? $reg->registration_number }}</span>
                                <span class="status-badge serving">Proses</span>
                            </div>
                            <div class="kanban-patient-name">{{ $reg->patient_name }}</div>
                            <div style="font-size: 12px; color: #64748B;">{{ $reg->polyclinic ?? $reg->room_class }}</div>
                            <div class="kanban-card-meta">
                                <span style="color: #2563EB; font-weight: 600;">{{ $reg->queueTicket?->loket_name ?? 'Loket' }}</span>
                                <span>{{ $reg->updated_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 32px 12px; color: #94A3B8; font-size: 13px;">
                            Belum ada yang sedang dilayani
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 3: Tertunda / Dilewati --}}
            <div class="kanban-col on_hold">
                <div class="kanban-col-header">
                    <div class="kanban-col-title">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #7C3AED;"></span>
                        Dilewati / Tertunda
                    </div>
                    <span class="kanban-col-badge">{{ count($kanbanData['on_hold']) }}</span>
                </div>
                <div style="flex: 1; overflow-y: auto;">
                    @forelse($kanbanData['on_hold'] as $reg)
                        <div class="kanban-card">
                            <div class="kanban-card-header">
                                <span class="kanban-ticket-num" style="color: #DC2626;">{{ $reg->queueTicket?->queue_number ?? $reg->registration_number }}</span>
                                <span class="status-badge skipped">Dilewati</span>
                            </div>
                            <div class="kanban-patient-name">{{ $reg->patient_name }}</div>
                            <div style="font-size: 12px; color: #64748B;">{{ $reg->polyclinic ?? $reg->room_class }}</div>
                            <div class="kanban-card-meta">
                                <span>Panggilan terlewat</span>
                                <span>{{ $reg->updated_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 32px 12px; color: #94A3B8; font-size: 13px;">
                            Tidak ada antrean dilewati
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Column 4: Selesai --}}
            <div class="kanban-col completed">
                <div class="kanban-col-header">
                    <div class="kanban-col-title">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #16A34A;"></span>
                        Selesai
                    </div>
                    <span class="kanban-col-badge">{{ count($kanbanData['completed']) }}</span>
                </div>
                <div style="flex: 1; overflow-y: auto;">
                    @forelse($kanbanData['completed'] as $reg)
                        <div class="kanban-card" style="border-left: 3px solid #16A34A;">
                            <div class="kanban-card-header">
                                <span class="kanban-ticket-num" style="color: #16A34A;">{{ $reg->queueTicket?->queue_number ?? $reg->registration_number }}</span>
                                <span class="status-badge completed">Tuntas</span>
                            </div>
                            <div class="kanban-patient-name">{{ $reg->patient_name }}</div>
                            <div style="font-size: 12px; color: #64748B;">{{ $reg->polyclinic ?? $reg->room_class }}</div>
                            <div class="kanban-card-meta">
                                <span>{{ strtoupper($reg->patient_type) }}</span>
                                <span>{{ $reg->updated_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 32px 12px; color: #94A3B8; font-size: 13px;">
                            Belum ada pelayanan tuntas
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Registrations Table --}}
    <div class="dash-table-card" id="recentTableSection">
        <div class="dash-table-header">
            <div>
                <h3>Pendaftaran Pasien Terbaru</h3>
                <p style="font-size: 12.5px; color: #64748B; margin-top: 2px;">Daftar 10 pasien terbaru yang mendaftar hari ini</p>
            </div>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="adminTableSearch" placeholder="Filter nama atau no reg..." style="padding: 7px 12px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 12.5px; font-family: inherit;">
            </div>
        </div>
        <div class="dash-table-wrapper">
            <table class="dash-table" id="adminRegTable">
                <thead>
                    <tr>
                        <th>No. Reg</th>
                        <th>Nama Pasien</th>
                        <th>Layanan</th>
                        <th>Poli / Kelas</th>
                        <th>Penjamin</th>
                        <th>No. Antrean</th>
                        <th>Waktu Daftar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRegistrations as $reg)
                        <tr>
                            <td style="font-weight: 700; color: #2563EB;">{{ $reg->registration_number }}</td>
                            <td style="font-weight: 600; color: #0F172A;">{{ $reg->patient_name }}</td>
                            <td>
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 600; background: {{ $reg->service_type === 'rawat_jalan' ? '#EFF6FF' : '#F3E8FF' }}; color: {{ $reg->service_type === 'rawat_jalan' ? '#2563EB' : '#7C3AED' }};">
                                    {{ $reg->service_type === 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}
                                </span>
                            </td>
                            <td>{{ $reg->polyclinic ?? ($reg->room_class ? 'Kamar '.$reg->room_class : '-') }}</td>
                            <td>
                                <span style="font-weight: 700; font-size: 11px; text-transform: uppercase;">{{ $reg->patient_type }}</span>
                            </td>
                            <td style="font-weight: 800; font-size: 14px; color: #0F172A;">
                                {{ $reg->queueTicket?->queue_number ?? '-' }}
                            </td>
                            <td>{{ $reg->created_at->format('H:i') }} WIB</td>
                            <td>
                                <span class="status-badge {{ $reg->status }}">
                                    {{ match($reg->status) {
                                        'pending' => 'Menunggu',
                                        'verified' => 'Terverifikasi',
                                        'in_progress' => 'Sedang Dilayani',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                        'skipped' => 'Dilewati',
                                        default => $reg->status
                                    } }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: #94A3B8;">
                                Belum ada data pendaftaran hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="dash-table-footer">
            <span>Menampilkan {{ count($recentRegistrations) }} data terbaru</span>
            <span style="font-size: 12px; color: #94A3B8;">RS Santa Anna &bull; SIMRS Core</span>
        </div>
    </div>

    {{-- Activity Log Section --}}
    <div class="dash-table-card" style="margin-top: 24px;" id="activitySection">
        <div class="dash-table-header">
            <div>
                <h3>Aktivitas Pemanggilan & Pelayanan Terkini</h3>
                <p style="font-size: 12.5px; color: #64748B; margin-top: 2px;">Log audit pelayanan petugas loket secara real-time</p>
            </div>
            <span style="font-size: 12px; color: #64748B;">Audit Log Sistem</span>
        </div>
        <div class="dash-table-wrapper">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>No. Antrean</th>
                        <th>Nama Pasien</th>
                        <th>Loket / Counter</th>
                        <th>Petugas</th>
                        <th>Status Layanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLog as $log)
                        <tr>
                            <td style="font-size: 12px; color: #64748B;">{{ $log->updated_at->format('H:i:s') }}</td>
                            <td style="font-weight: 800; color: #2563EB;">{{ $log->queue_number }}</td>
                            <td style="font-weight: 600; color: #0F172A;">{{ $log->registration?->patient_name ?? 'Pasien' }}</td>
                            <td>{{ $log->loket_name }} (Counter {{ $log->counter_number }})</td>
                            <td>{{ $log->servedByUser?->name ?? 'Petugas Loket' }}</td>
                            <td>
                                <span class="status-badge {{ $log->status }}">
                                    {{ match($log->status) {
                                        'serving' => 'Sedang Dilayani',
                                        'completed' => 'Selesai',
                                        'skipped' => 'Dilewati',
                                        default => $log->status
                                    } }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 24px; color: #94A3B8;">
                                Belum ada aktivitas pemanggilan antrean hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Search filter for admin table
    document.getElementById('adminTableSearch')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#adminRegTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
