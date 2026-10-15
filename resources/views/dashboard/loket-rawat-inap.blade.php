@extends('layouts.dashboard')

@section('title', 'Loket Rawat Inap')
@section('role-label', 'Petugas Loket RI')
@section('breadcrumb', 'Loket Rawat Inap')

@section('sidebar-nav')
    <div class="dash-nav-section">
        <div class="dash-nav-title">Menu Utama</div>
        <a href="{{ route('dashboard.loket-rawat-inap') }}" class="dash-nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <span class="nav-label">Loket Rawat Inap</span>
            @if(($stats['waiting_queue'] ?? 0) > 0)
                <span class="nav-badge">{{ $stats['waiting_queue'] }}</span>
            @endif
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Kapasitas & Fasilitas</div>
        <a href="{{ route('dashboard.kamar') }}" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            <span class="nav-label">Kamar Ready (SIMRS)</span>
            <span class="nav-badge" style="background: #16A34A; color: #FFFFFF;">{{ $bedStats['ready_beds'] ?? 0 }}</span>
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Navigasi Loket RI</div>
        <a href="#activeCallingCardRI" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.216-1.614.597-2.32a2.003 2.003 0 011.938-1.43h2.25z"/></svg>
            <span class="nav-label">Pemanggilan Antrean RI</span>
        </a>
        <a href="#kamarReadySectionRI" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <span class="nav-label">Kamar Ready (Live SIMRS)</span>
        </a>
        <a href="#queueTableCardRI" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            <span class="nav-label">Daftar Pasien & Alokasi Bed</span>
        </a>
        <a href="#occupancySectionRI" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/></svg>
            <span class="nav-label">Okupansi Ruang Perawatan</span>
        </a>
    </div>
@endsection

@section('content')
    {{-- Header --}}
    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div class="dash-page-header" style="margin-bottom: 0;">
                <h1>Loket Pelayanan Rawat Inap</h1>
                <p>Kelola alokasi kamar, verifikasi jaminan, dan antrean pasien rawat inap — {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('dashboard.kamar') }}" class="btn-outline-slate" style="font-size: 13px; padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    Kamar Ready SIMRS ({{ $bedStats['ready_beds'] ?? 0 }})
                </a>
                <button onclick="callNextInpatientQueue()" class="btn-primary-blue" id="btnCallNextRI" style="font-size: 14px; padding: 10px 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.216-1.614.597-2.32a2.003 2.003 0 011.938-1.43h2.25z"/></svg>
                    Panggil Antrean Rawat Inap
                </button>
            </div>
        </div>
    </div>

    {{-- Active Calling Hero Card --}}
    <div class="active-calling-card" id="activeCallingCardRI" style="{{ $currentlyServing ? '' : 'border-color: #E2E8F0; background: #FAFAFC;' }}">
        @if($currentlyServing)
            <div class="active-calling-left">
                <div class="active-calling-badge" id="activeBadgeRI">
                    <div class="label">Sedang Dipanggil</div>
                    <div class="number" id="activeQueueNumberRI">{{ $currentlyServing->queue_number }}</div>
                </div>
                <div class="active-calling-info">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span class="status-badge serving">Sedang Dilayani</span>
                        <span style="font-size: 12px; color: #64748B;">Loket Rawat Inap &bull; {{ $currentlyServing->served_at?->format('H:i') ?? now()->format('H:i') }} WIB</span>
                    </div>
                    <h2 id="activePatientNameRI">{{ $currentlyServing->registration?->patient_name ?? 'Pasien' }}</h2>
                    <p id="activePatientMetaRI">
                        No. Reg: <strong style="color: #2563EB;">{{ $currentlyServing->registration?->registration_number ?? '-' }}</strong> &bull;
                        Kamar: <strong style="color: #7C3AED;">{{ $currentlyServing->registration?->room_class ?? 'Kelas 1' }}</strong> &bull;
                        Penjamin: <span style="text-transform: uppercase; font-weight: 700;">{{ $currentlyServing->registration?->patient_type ?? 'Umum' }}</span>
                    </p>
                </div>
            </div>

            <div class="active-calling-actions">
                <button onclick="recallCurrentRI()" class="btn-primary-blue" id="btnRecallRI" title="Panggil Ulang Suara">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.216-1.614.597-2.32a2.003 2.003 0 011.938-1.43h2.25z"/></svg>
                    Panggil Ulang
                </button>
                <button onclick="completeCurrentRI({{ $currentlyServing->id }})" class="btn-success-green" id="btnCompleteRI" title="Selesaikan & Alokasikan Kamar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Selesaikan Pelayanan
                </button>
                <button onclick="skipCurrentRI({{ $currentlyServing->id }})" class="btn-warning-amber" id="btnSkipRI" title="Lewati">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062A1.125 1.125 0 013 16.81V8.688zM12.75 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062a1.125 1.125 0 01-1.683-.977V8.688z"/></svg>
                    Lewati
                </button>
            </div>
        @else
            <div class="active-calling-left" style="padding: 12px 0;">
                <div style="width: 54px; height: 54px; border-radius: 14px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                </div>
                <div class="active-calling-info">
                    <h2>Tidak Ada Pasien Rawat Inap Sedang Dilayani</h2>
                    <p>Klik tombol <strong>"Panggil Antrean Rawat Inap"</strong> untuk memanggil pasien urutan pertama yang menunggu konfirmasi kamar.</p>
                </div>
            </div>
            <div class="active-calling-actions">
                <button onclick="callNextInpatientQueue()" class="btn-primary-blue">
                    Panggil Antrean Pertama
                </button>
            </div>
        @endif
    </div>

    {{-- 4 Stat Cards --}}
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Antrean Menunggu</div>
                    <div class="stat-value" id="statWaitingRI">{{ $stats['waiting_queue'] }}</div>
                </div>
                <div class="stat-card-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>Antre Loket RI</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Antrean Saat Ini</div>
                    <div class="stat-value" id="statCurrentQueueRI" style="color: #2563EB;">{{ $stats['current_queue'] }}</div>
                </div>
                <div class="stat-card-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <span style="width:6px;height:6px;border-radius:50%;background:#16A34A;display:inline-block;"></span>
                <span>Loket Rawat Inap</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Bed Tersedia</div>
                    <div class="stat-value" id="statBedAvailable" style="color: #16A34A;">{{ $stats['bed_available'] }}</div>
                </div>
                <div class="stat-card-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <span>Siap diisi pasien baru</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Total Pasien RI Hari Ini</div>
                    <div class="stat-value" id="statTotalRI">{{ $stats['total_today'] }}</div>
                </div>
                <div class="stat-card-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>{{ $stats['completed_today'] }} telah selesai</span>
            </div>
        </div>
    </div>

    {{-- Kamar Ready (Live SIMRS Core) Section --}}
    <div class="dash-table-card" style="margin-bottom: 24px;" id="kamarReadySectionRI">
        <div class="dash-table-header" style="flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <h3 style="margin: 0;">Kamar Ready & Siap Huni &bull; Live SIMRS RS Santa Anna</h3>
                    <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #16A34A;"></span>
                        {{ count($readyBeds) }} Bed Ready Terkoneksi API SIMRS
                    </span>
                </div>
                <p style="font-size: 12.5px; color: #64748B; margin-top: 3px;">Kamar kosong steril yang siap dialokasikan ke pasien rawat inap baru</p>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <a href="{{ route('dashboard.kamar') }}" class="btn-primary-blue" style="font-size: 12.5px; padding: 7px 14px;">
                    Buka Manajemen Kamar Lengkap &rarr;
                </a>
            </div>
        </div>

        {{-- Class filter pills --}}
        <div style="padding: 10px 24px; border-bottom: 1px solid #F1F5F9; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; background: #FAFAFC;">
            <span style="font-size: 12px; font-weight: 700; color: #64748B; margin-right: 4px;">Kelas:</span>
            <button type="button" onclick="filterReadyBedsClass('all', this)" class="ready-bed-filter active" style="padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 600; cursor: pointer; background: #2563EB; color: #FFFFFF; border: 1px solid #2563EB;">Semua ({{ count($readyBeds) }})</button>
            @foreach(['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'Isolasi'] as $rbClass)
                @php
                    $rbCount = collect($readyBeds)->where('room_class', $rbClass)->count();
                @endphp
                <button type="button" onclick="filterReadyBedsClass('{{ $rbClass }}', this)" class="ready-bed-filter" style="padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 600; cursor: pointer; background: #FFFFFF; color: #64748B; border: 1px solid #E2E8F0;">
                    {{ $rbClass }} ({{ $rbCount }})
                </button>
            @endforeach
        </div>

        {{-- Ready Beds Grid --}}
        <div style="padding: 20px 24px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 14px;" id="readyBedsGrid">
                @forelse($readyBeds as $rBed)
                    <div class="ready-bed-card" data-class="{{ $rBed['room_class'] }}" style="padding: 16px; border: 1px solid #DCFCE7; background: #F0FDF4; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between; gap: 12px; transition: transform .15s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div>
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; margin-bottom: 6px;">
                                <div>
                                    <span style="font-size: 16px; font-weight: 800; color: #0F172A;">{{ $rBed['bed_code'] }}</span>
                                    <span style="margin-left: 6px; display: inline-block; padding: 2px 7px; border-radius: 5px; font-size: 10.5px; font-weight: 700; background: #DCFCE7; color: #16A34A;">
                                        {{ $rBed['room_class'] }}
                                    </span>
                                </div>
                                <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 7px; border-radius: 999px; font-size: 10.5px; font-weight: 700; background: #DCFCE7; color: #16A34A;">
                                    <span style="width: 5px; height: 5px; border-radius: 50%; background: #16A34A;"></span> Ready
                                </span>
                            </div>
                            <div style="font-size: 13px; font-weight: 600; color: #334155;">{{ $rBed['room_name'] }}</div>
                            <div style="font-size: 11px; color: #64748B; margin-top: 2px;">{{ $rBed['floor'] }} &bull; {{ $rBed['building'] }}</div>
                            <div style="font-size: 13.5px; font-weight: 800; color: #0F172A; margin-top: 8px;">
                                Rp {{ number_format($rBed['daily_rate'], 0, ',', '.') }} <span style="font-size: 11px; font-weight: 500; color: #64748B;">/ malam</span>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px;">
                                @foreach(array_slice($rBed['facilities'] ?? [], 0, 3) as $fItem)
                                    <span style="padding: 1px 6px; border-radius: 4px; font-size: 10px; background: #FFFFFF; color: #475569; border: 1px solid #E2E8F0;">{{ $fItem }}</span>
                                @endforeach
                            </div>
                        </div>
                        <button onclick="openAssignModalFromBed({{ json_encode($rBed) }})" class="btn-success-green" style="width: 100%; justify-content: center; font-size: 12px; padding: 7px 12px;">
                            Alokasikan Bed Ini
                        </button>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 32px; color: #64748B;">
                        Saat ini tidak ada bed berstatus Ready di SIMRS.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Room Availability & Payment Breakdown --}}
    <div class="dash-chart-grid" style="margin-bottom: 24px;" id="occupancySectionRI">
        {{-- Room Availability --}}
        <div class="dash-chart-card">
            <h3>
                <span>Ketersediaan Bed per Kelas Kamar</span>
                <span style="font-size: 12px; font-weight: 600; color: #16A34A;">Total: {{ $stats['bed_available'] }} Kosong</span>
            </h3>

            <div style="display: flex; flex-direction: column; gap: 14px; margin-top: 6px;">
                @foreach($roomOccupancy as $room)
                    @php
                        $pct = round(($room['used'] / $room['total']) * 100);
                        $remaining = $room['total'] - $room['used'];
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px;">
                            <span style="font-weight: 700; color: #0F172A;">{{ $room['name'] }}</span>
                            <span style="color: #64748B; font-size: 12.5px;">
                                <strong style="color: #0F172A;">{{ $room['used'] }}</strong> / {{ $room['total'] }} terisi
                                &bull; <span style="color: {{ $remaining > 3 ? '#16A34A' : '#DC2626' }}; font-weight: 600;">{{ $remaining }} sisa</span>
                            </span>
                        </div>
                        <div style="height: 8px; background: #F1F5F9; border-radius: 6px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $pct }}%; background: {{ $pct > 85 ? '#DC2626' : ($pct > 60 ? '#D97706' : '#2563EB') }}; border-radius: 6px; transition: width .4s ease;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Payment Distribution --}}
        <div class="dash-chart-card">
            <h3>
                <span>Sebaran Penjamin Pasien Rawat Inap</span>
                <span style="font-size: 12px; font-weight: 500; color: #64748B;">Data Hari Ini</span>
            </h3>

            @php
                $bpjsCount = $paymentDistribution['bpjs'] ?? 0;
                $umumCount = $paymentDistribution['umum'] ?? 0;
                $asuransiCount = $paymentDistribution['asuransi'] ?? 0;
                $totalPayment = $bpjsCount + $umumCount + $asuransiCount ?: 1;
            @endphp

            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 6px;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #DCFCE7; color: #16A34A; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                            BPJS
                        </div>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: #0F172A;">BPJS Kesehatan</div>
                            <div style="font-size: 11.5px; color: #64748B;">Pasien JKN-KIS / PBI / Non-PBI</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 18px; font-weight: 800; color: #16A34A;">{{ $bpjsCount }}</span>
                        <div style="font-size: 11px; color: #64748B;">{{ round(($bpjsCount / $totalPayment) * 100) }}%</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #EFF6FF; border: 1px solid #DBEAFE; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #DBEAFE; color: #2563EB; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                            UMUM
                        </div>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: #0F172A;">Umum / Bayar Mandiri</div>
                            <div style="font-size: 11.5px; color: #64748B;">Biaya pribadi tanpa asuransi</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 18px; font-weight: 800; color: #2563EB;">{{ $umumCount }}</span>
                        <div style="font-size: 11px; color: #64748B;">{{ round(($umumCount / $totalPayment) * 100) }}%</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #FDE68A; color: #D97706; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px;">
                            ASR
                        </div>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: #0F172A;">Asuransi Swasta & Perusahaan</div>
                            <div style="font-size: 11.5px; color: #64748B;">Klaim cashless & reimbursement</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 18px; font-weight: 800; color: #D97706;">{{ $asuransiCount }}</span>
                        <div style="font-size: 11px; color: #64748B;">{{ round(($asuransiCount / $totalPayment) * 100) }}%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inpatient Table --}}
    <div class="dash-table-card" id="queueTableCardRI">
        <div class="dash-table-header">
            <div>
                <h3>Pendaftaran & Alokasi Kamar Rawat Inap</h3>
                <p style="font-size: 12.5px; color: #64748B; margin-top: 2px;">Daftar lengkap pendaftaran rawat inap hari ini</p>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                {{-- Tabs Filter --}}
                <div class="tabs" style="border-bottom: none; gap: 4px;">
                    <button class="tab-item active" onclick="filterTabRI('all', this)" style="border-radius: 8px; border: 1px solid transparent; background: #2563EB; color: #FFFFFF; font-size: 12px; padding: 6px 12px;">Semua</button>
                    <button class="tab-item" onclick="filterTabRI('waiting', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Menunggu</button>
                    <button class="tab-item" onclick="filterTabRI('serving', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Sedang Dilayani</button>
                    <button class="tab-item" onclick="filterTabRI('completed', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Selesai</button>
                </div>

                {{-- Search Box --}}
                <div style="position: relative;">
                    <input type="text" id="tableSearchRI" placeholder="Cari nama/kamar..." style="padding: 6px 12px 6px 30px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 12.5px; font-family: inherit; width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: #94A3B8;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
            </div>
        </div>

        <div class="dash-table-wrapper">
            <table class="dash-table" id="queueTableRI">
                <thead>
                    <tr>
                        <th>Antrean</th>
                        <th>No. Reg</th>
                        <th>Nama Pasien</th>
                        <th>Kelas Diminta</th>
                        <th>Status Bed SIMRS</th>
                        <th>Dokter Pengampu</th>
                        <th>Penjamin</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        @php
                            $ticket = $reg->queueTicket;
                            $rowStatus = match($reg->status) {
                                'pending' => 'waiting',
                                'verified' => 'waiting',
                                'in_progress' => 'serving',
                                'completed' => 'completed',
                                'cancelled', 'skipped' => 'skipped',
                                default => 'other'
                            };
                        @endphp
                        <tr data-status="{{ $rowStatus }}" id="row-ri-{{ $reg->id }}">
                            <td style="font-weight: 800; font-size: 15px; color: #7C3AED;">
                                {{ $ticket?->queue_number ?? '-' }}
                            </td>
                            <td style="font-weight: 600; color: #64748B; font-size: 12px;">{{ $reg->registration_number }}</td>
                            <td>
                                <div style="font-weight: 700; color: #0F172A;">{{ $reg->patient_name }}</div>
                                <div style="font-size: 11px; color: #94A3B8;">NIK: {{ $reg->nik }}</div>
                            </td>
                            <td>
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 12px; font-weight: 700; background: #F3E8FF; color: #7C3AED;">
                                    {{ $reg->room_class ?? 'Kelas 1' }}
                                </span>
                            </td>
                            <td>
                                @if(str_contains($reg->notes ?? '', 'Alokasi Bed SIMRS:'))
                                    <span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0;" title="{{ $reg->notes }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        {{ str_replace('Alokasi Bed SIMRS: ', '', explode('(', $reg->notes)[0] ?? 'Bed Ready') }}
                                    </span>
                                @else
                                    <button onclick="openAssignForPatient({{ $reg->id }}, '{{ addslashes($reg->patient_name) }}', '{{ $reg->room_class ?? 'Kelas 1' }}')" class="btn-outline-slate" style="padding: 3px 8px; font-size: 11px; color: #2563EB; border-color: #BFDBFE; background: #EFF6FF;" title="Pilih bed ready dari SIMRS">
                                        + Pilih Bed Ready
                                    </button>
                                @endif
                            </td>
                            <td><span style="font-size: 12px; color: #475569;">{{ $reg->doctor_name }}</span></td>
                            <td>
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: {{ $reg->patient_type === 'bpjs' ? '#DCFCE7' : ($reg->patient_type === 'asuransi' ? '#FEF3C7' : '#EFF6FF') }}; color: {{ $reg->patient_type === 'bpjs' ? '#16A34A' : ($reg->patient_type === 'asuransi' ? '#D97706' : '#2563EB') }};">
                                    {{ $reg->patient_type }}
                                </span>
                            </td>
                            <td>{{ $reg->created_at->format('H:i') }}</td>
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
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    @if($reg->status === 'pending')
                                        <button onclick="verifyRegistrationRI({{ $reg->id }})" class="btn-warning-amber" style="padding: 5px 10px; font-size: 11.5px;" title="Verifikasi">
                                            Verifikasi
                                        </button>
                                    @endif

                                    @if($ticket && in_array($ticket->status, ['waiting', 'skipped']))
                                        <button onclick="callSpecificTicketRI({{ $ticket->id }}, '{{ $ticket->queue_number }}')" class="btn-primary-blue" style="padding: 5px 11px; font-size: 11.5px;" title="Panggil Antrean RI">
                                            Panggil
                                        </button>
                                    @elseif($ticket && $ticket->status === 'serving')
                                        <button onclick="completeCurrentRI({{ $ticket->id }})" class="btn-success-green" style="padding: 5px 10px; font-size: 11.5px;" title="Selesaikan">
                                            Selesai
                                        </button>
                                        <button onclick="skipCurrentRI({{ $ticket->id }})" class="btn-outline-slate" style="padding: 5px 8px; font-size: 11.5px;" title="Lewati">
                                            Lewati
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 48px; color: #94A3B8;">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                    </div>
                                    <h3>Tidak Ada Pasien</h3>
                                    <p>Belum ada pendaftaran rawat inap hari ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dash-table-footer">
            <span id="tableCountInfoRI">Menampilkan {{ count($registrations) }} pendaftaran rawat inap</span>
            <span style="font-size: 12px; color: #94A3B8;">Loket Rawat Inap RS Santa Anna</span>
        </div>
    </div>

    {{-- Interactive Modal: Alokasi Kamar & Bed SIMRS --}}
    <div id="assignBedModalRI" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 16px; max-width: 520px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; animation: popIn .2s ease-out;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0F172A;">Alokasikan Kamar Rawat Inap (SIMRS)</h3>
                    <p style="margin: 2px 0 0; font-size: 12px; color: #64748B;">Konfirmasi ketersediaan dan penempatan bed pasien ke sistem SIMRS</p>
                </div>
                <button onclick="closeAssignModalRI()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94A3B8; padding: 4px;" title="Tutup">&times;</button>
            </div>

            <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                {{-- Patient Information --}}
                <div style="padding: 12px 14px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">Pasien Terpilih</div>
                    <div style="font-size: 15px; font-weight: 800; color: #0F172A;" id="modalPatientNameRI">-</div>
                    <div style="font-size: 12px; color: #475569;" id="modalPatientMetaRI">Pilihan Kamar: Kelas 1</div>
                </div>

                {{-- Bed Selection --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Pilih Bed Ready dari SIMRS:</label>
                    <select id="modalSelectBedRI" style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; font-family: inherit; color: #0F172A; background: #FFFFFF;">
                        <option value="">-- Pilih Kamar / Bed Siap Huni --</option>
                        @foreach($readyBeds as $rb)
                            <option value="{{ $rb['id'] }}" data-code="{{ $rb['bed_code'] }}" data-room="{{ $rb['room_name'] }}" data-class="{{ $rb['room_class'] }}">
                                [{{ $rb['bed_code'] }} - {{ $rb['room_class'] }}] {{ $rb['room_name'] }} ({{ $rb['floor'] }}) &bull; Rp {{ number_format($rb['daily_rate'], 0, ',', '.') }}/malam
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="font-size: 12px; color: #64748B; line-height: 1.5; padding: 10px 12px; background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 8px;">
                    🟢 Bed yang dipilih akan langsung diubah statusnya menjadi <strong>Terisi (Occupied)</strong> di SIMRS rumah sakit dan dicatat pada rekam medis registrasi pasien.
                </div>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                <button onclick="closeAssignModalRI()" class="btn-outline-slate">Batal</button>
                <button onclick="submitAssignBedRI()" id="btnSubmitAssignRI" class="btn-success-green" style="padding: 9px 18px;">
                    Konfirmasi Alokasi Bed
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function playChimeRI() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, now);
            gain1.gain.setValueAtTime(0.3, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.6);

            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(440.00, now + 0.3);
            gain2.gain.setValueAtTime(0.35, now + 0.3);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 1.2);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.3);
            osc2.stop(now + 1.2);
        } catch(e) {
            console.log('AudioContext error:', e);
        }
    }

    // Call Next Inpatient FIFO
    async function callNextInpatientQueue() {
        const btn = document.getElementById('btnCallNextRI');
        if (btn) btn.disabled = true;

        try {
            const res = await fetch("{{ route('dashboard.queue.call-next') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    loket_name: 'Loket Rawat Inap',
                    counter_number: 1
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                playChimeRI();
                window.showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 900);
            } else {
                window.showToast(data.message || 'Gagal memanggil antrean.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    // Call Specific Inpatient Ticket
    async function callSpecificTicketRI(ticketId, queueNum) {
        try {
            const res = await fetch("{{ route('dashboard.queue.call-next') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    loket_name: 'Loket Rawat Inap',
                    ticket_id: ticketId,
                    counter_number: 1
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                playChimeRI();
                window.showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 900);
            } else {
                window.showToast(data.message || 'Gagal memanggil antrean.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        }
    }

    // Recall
    function recallCurrentRI() {
        playChimeRI();
        const num = document.getElementById('activeQueueNumberRI')?.innerText || '';
        window.showToast('Memanggil ulang nomor ' + num + ' menuju Loket Rawat Inap', 'info');
    }

    // Complete
    async function completeCurrentRI(ticketId) {
        if (!confirm('Tandai pelayanan rawat inap ini telah selesai dan kamar dialokasikan?')) return;

        try {
            const res = await fetch(`/dashboard/api/queue/${ticketId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(data.message || 'Gagal menyelesaikan antrean.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        }
    }

    // Skip
    async function skipCurrentRI(ticketId) {
        if (!confirm('Pasien tidak hadir atau ingin dilewati?')) return;

        try {
            const res = await fetch(`/dashboard/api/queue/${ticketId}/skip`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'warning');
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(data.message || 'Gagal melewati antrean.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        }
    }

    // Verify
    async function verifyRegistrationRI(regId) {
        try {
            const res = await fetch(`/dashboard/api/registration/${regId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 700);
            } else {
                window.showToast(data.message || 'Gagal verifikasi.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        }
    }

    // Filter Tabs
    function filterTabRI(status, btn) {
        document.querySelectorAll('.tab-item').forEach(b => {
            b.style.background = '#FFFFFF';
            b.style.color = '#64748B';
            b.style.borderColor = '#E2E8F0';
        });
        btn.style.background = '#2563EB';
        btn.style.color = '#FFFFFF';
        btn.style.borderColor = 'transparent';

        const rows = document.querySelectorAll('#queueTableRI tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const countEl = document.getElementById('tableCountInfoRI');
        if (countEl) countEl.innerText = `Menampilkan ${visibleCount} pendaftaran`;
    }

    // Table Search
    document.getElementById('tableSearchRI')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#queueTableRI tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const countEl = document.getElementById('tableCountInfoRI');
        if (countEl) countEl.innerText = `Menampilkan ${visibleCount} pendaftaran`;
    });

    // Filter Ready Beds by Class
    function filterReadyBedsClass(cls, btn) {
        document.querySelectorAll('.ready-bed-filter').forEach(b => {
            b.style.background = '#FFFFFF';
            b.style.color = '#64748B';
            b.style.borderColor = '#E2E8F0';
        });
        btn.style.background = '#2563EB';
        btn.style.color = '#FFFFFF';
        btn.style.borderColor = '#2563EB';

        const cards = document.querySelectorAll('#readyBedsGrid .ready-bed-card');
        cards.forEach(card => {
            const cardClass = card.getAttribute('data-class');
            if (cls === 'all' || cardClass === cls) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    let activeAssignPatientId = null;

    // Open Modal when clicking from Ready Bed card
    function openAssignModalFromBed(bed) {
        const modal = document.getElementById('assignBedModalRI');
        const selectBed = document.getElementById('modalSelectBedRI');
        if (selectBed) {
            selectBed.value = bed.id;
        }

        @php
            $firstCandidate = $currentlyServing?->registration ?? $registrations->firstWhere('status', 'verified') ?? $registrations->firstWhere('status', 'pending');
        @endphp
        @if($firstCandidate)
            activeAssignPatientId = {{ $firstCandidate->id }};
            document.getElementById('modalPatientNameRI').innerText = "{{ addslashes($firstCandidate->patient_name) }} ({{ $firstCandidate->registration_number }})";
            document.getElementById('modalPatientMetaRI').innerText = "Pilihan Kamar: {{ $firstCandidate->room_class ?? 'Kelas 1' }} • NIK: {{ $firstCandidate->nik }}";
        @else
            document.getElementById('modalPatientNameRI').innerText = "Pasien Rawat Inap";
            document.getElementById('modalPatientMetaRI').innerText = "Silakan klik '+ Pilih Bed Ready' pada baris pasien di tabel antrean";
        @endif

        modal.style.display = 'flex';
    }

    // Open Modal when clicking from Patient Table row
    function openAssignForPatient(regId, patientName, roomClass) {
        activeAssignPatientId = regId;
        document.getElementById('modalPatientNameRI').innerText = patientName;
        document.getElementById('modalPatientMetaRI').innerText = "Pilihan Kamar Diminta: " + roomClass;

        const selectBed = document.getElementById('modalSelectBedRI');
        if (selectBed) {
            let matched = false;
            for (let i = 0; i < selectBed.options.length; i++) {
                const opt = selectBed.options[i];
                if (opt.getAttribute('data-class') && opt.getAttribute('data-class').toLowerCase() === roomClass.toLowerCase()) {
                    selectBed.selectedIndex = i;
                    matched = true;
                    break;
                }
            }
            if (!matched && selectBed.options.length > 1) {
                selectBed.selectedIndex = 1;
            }
        }

        const modal = document.getElementById('assignBedModalRI');
        modal.style.display = 'flex';
    }

    function closeAssignModalRI() {
        document.getElementById('assignBedModalRI').style.display = 'none';
        activeAssignPatientId = null;
    }

    async function submitAssignBedRI() {
        if (!activeAssignPatientId) {
            window.showToast('Silakan pilih pasien dari tabel terlebih dahulu.', 'warning');
            return;
        }

        const selectBed = document.getElementById('modalSelectBedRI');
        if (!selectBed || !selectBed.value) {
            window.showToast('Silakan pilih bed ready yang akan dialokasikan.', 'warning');
            return;
        }

        const opt = selectBed.options[selectBed.selectedIndex];
        const bedId = selectBed.value;
        const bedCode = opt.getAttribute('data-code');
        const roomName = opt.getAttribute('data-room');
        const roomClass = opt.getAttribute('data-class');

        const btn = document.getElementById('btnSubmitAssignRI');
        if (btn) btn.disabled = true;

        try {
            const res = await fetch(`/dashboard/api/registration/${activeAssignPatientId}/assign-bed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    bed_id: bedId,
                    bed_code: bedCode,
                    room_name: roomName,
                    room_class: roomClass
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                closeAssignModalRI();
                setTimeout(() => window.location.reload(), 900);
            } else {
                window.showToast(data.message || 'Gagal mengalokasikan kamar.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        } finally {
            if (btn) btn.disabled = false;
        }
    }
</script>
@endpush
