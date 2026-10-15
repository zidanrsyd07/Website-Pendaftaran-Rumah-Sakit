@extends('layouts.dashboard')

@section('title', 'Loket Rawat Jalan')
@section('role-label', 'Petugas Loket RJ')
@section('breadcrumb', 'Loket Rawat Jalan')

@section('sidebar-nav')
    <div class="dash-nav-section">
        <div class="dash-nav-title">Menu Utama</div>
        <a href="{{ route('dashboard.loket-rawat-jalan') }}" class="dash-nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.375 12.375 0 0110.375 21c-2.31 0-4.473-.637-6.375-1.765z"/></svg>
            <span class="nav-label">Loket Rawat Jalan</span>
            @if(($stats['waiting_queue'] ?? 0) > 0)
                <span class="nav-badge">{{ $stats['waiting_queue'] }}</span>
            @endif
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Fitur Loket RJ</div>
        <a href="#activeCallingCard" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.216-1.614.597-2.32a2.003 2.003 0 011.938-1.43h2.25z"/></svg>
            <span class="nav-label">Pemanggilan Pasien Seri A</span>
        </a>
        <a href="#queueTableSectionRJ" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            <span class="nav-label">Verifikasi & Check-In Berkas</span>
        </a>
        <a href="#statCardsRJ" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            <span class="nav-label">Statistik Layanan RJ Hari Ini</span>
        </a>
    </div>
@endsection

@section('content')
    {{-- Header --}}
    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div class="dash-page-header" style="margin-bottom: 0;">
                <h1>Loket Pelayanan Rawat Jalan</h1>
                <p>Kelola pemanggilan antrean, verifikasi data, dan proses pelayanan pasien rawat jalan — {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <button onclick="callNextQueue()" class="btn-primary-blue" id="btnCallNext" style="font-size: 14px; padding: 10px 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.216-1.614.597-2.32a2.003 2.003 0 011.938-1.43h2.25z"/></svg>
                    Panggil Nomor Berikutnya
                </button>
            </div>
        </div>
    </div>

    {{-- Active Calling Hero Card --}}
    <div class="active-calling-card" id="activeCallingCard" style="{{ $currentlyServing ? '' : 'border-color: #E2E8F0; background: #FAFAFC;' }}">
        @if($currentlyServing)
            <div class="active-calling-left">
                <div class="active-calling-badge" id="activeBadge">
                    <div class="label">Sedang Dipanggil</div>
                    <div class="number" id="activeQueueNumber">{{ $currentlyServing->queue_number }}</div>
                </div>
                <div class="active-calling-info">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span class="status-badge serving">Sedang Dilayani</span>
                        <span style="font-size: 12px; color: #64748B;">Counter 1 &bull; {{ $currentlyServing->served_at?->format('H:i') ?? now()->format('H:i') }} WIB</span>
                    </div>
                    <h2 id="activePatientName">{{ $currentlyServing->registration?->patient_name ?? 'Pasien' }}</h2>
                    <p id="activePatientMeta">
                        No. Reg: <strong style="color: #2563EB;">{{ $currentlyServing->registration?->registration_number ?? '-' }}</strong> &bull;
                        Poli: <strong>{{ $currentlyServing->registration?->polyclinic ?? '-' }}</strong> &bull;
                        Penjamin: <span style="text-transform: uppercase; font-weight: 700;">{{ $currentlyServing->registration?->patient_type ?? 'Umum' }}</span>
                    </p>
                </div>
            </div>

            <div class="active-calling-actions">
                <button onclick="recallCurrent()" class="btn-primary-blue" id="btnRecall" title="Panggil Ulang Suara">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.216-1.614.597-2.32a2.003 2.003 0 011.938-1.43h2.25z"/></svg>
                    Panggil Ulang
                </button>
                <button onclick="completeCurrent({{ $currentlyServing->id }})" class="btn-success-green" id="btnComplete" title="Selesaikan Pelayanan">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Selesaikan Pelayanan
                </button>
                <button onclick="skipCurrent({{ $currentlyServing->id }})" class="btn-warning-amber" id="btnSkip" title="Lewati / Pasien Tidak Hadir">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062A1.125 1.125 0 013 16.81V8.688zM12.75 8.688c0-.864.933-1.405 1.683-.977l7.108 4.062a1.125 1.125 0 010 1.953l-7.108 4.062a1.125 1.125 0 01-1.683-.977V8.688z"/></svg>
                    Lewati
                </button>
            </div>
        @else
            <div class="active-calling-left" style="padding: 12px 0;">
                <div style="width: 54px; height: 54px; border-radius: 14px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:28px;height:28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="active-calling-info">
                    <h2>Tidak Ada Pasien Sedang Dilayani</h2>
                    <p>Klik tombol <strong>"Panggil Nomor Berikutnya"</strong> untuk memanggil pasien urutan pertama yang menunggu.</p>
                </div>
            </div>
            <div class="active-calling-actions">
                <button onclick="callNextQueue()" class="btn-primary-blue">
                    Panggil Antrean Pertama
                </button>
            </div>
        @endif
    </div>

    {{-- 4 Stat Cards --}}
    <div class="stat-cards" id="statCardsRJ">
        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Antrean Menunggu</div>
                    <div class="stat-value" id="statWaiting">{{ $stats['waiting_queue'] }}</div>
                </div>
                <div class="stat-card-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>Siap dipanggil loket</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Nomor Saat Ini</div>
                    <div class="stat-value" id="statCurrentQueue" style="color: #2563EB;">{{ $stats['current_queue'] }}</div>
                </div>
                <div class="stat-card-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <span style="width:6px;height:6px;border-radius:50%;background:#16A34A;display:inline-block;"></span>
                <span>Counter 1</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Selesai Hari Ini</div>
                    <div class="stat-value" id="statCompleted">{{ $stats['completed_today'] }}</div>
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
                    <div class="stat-title">Total Pendaftaran RJ</div>
                    <div class="stat-value" id="statTotal">{{ $stats['total_today'] }}</div>
                </div>
                <div class="stat-card-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>Semua status RJ</span>
            </div>
        </div>
    </div>

    {{-- Interactive Queue Table --}}
    <div class="dash-table-card" id="queueTableSectionRJ">
        <div class="dash-table-header">
            <div>
                <h3>Daftar Antrean & Pendaftaran Pasien</h3>
                <p style="font-size: 12.5px; color: #64748B; margin-top: 2px;">Daftar lengkap pendaftaran rawat jalan hari ini</p>
            </div>

            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                {{-- Tabs Filter --}}
                <div class="tabs" style="border-bottom: none; gap: 4px;">
                    <button class="tab-item active" onclick="filterTab('all', this)" style="border-radius: 8px; border: 1px solid transparent; background: #2563EB; color: #FFFFFF; font-size: 12px; padding: 6px 12px;">Semua</button>
                    <button class="tab-item" onclick="filterTab('waiting', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Menunggu</button>
                    <button class="tab-item" onclick="filterTab('serving', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Sedang Dilayani</button>
                    <button class="tab-item" onclick="filterTab('completed', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Selesai</button>
                    <button class="tab-item" onclick="filterTab('skipped', this)" style="border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #64748B; font-size: 12px; padding: 6px 12px;">Dilewati</button>
                </div>

                {{-- Search Box --}}
                <div style="position: relative;">
                    <input type="text" id="tableSearch" placeholder="Cari nomor/nama..." style="padding: 6px 12px 6px 30px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 12.5px; font-family: inherit; width: 180px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: #94A3B8;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
            </div>
        </div>

        <div class="dash-table-wrapper">
            <table class="dash-table" id="queueTable">
                <thead>
                    <tr>
                        <th>Antrean</th>
                        <th>No. Reg</th>
                        <th>Nama Pasien</th>
                        <th>Poliklinik</th>
                        <th>Dokter</th>
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
                        <tr data-status="{{ $rowStatus }}" id="row-{{ $reg->id }}">
                            <td style="font-weight: 800; font-size: 15px; color: #2563EB;">
                                {{ $ticket?->queue_number ?? '-' }}
                            </td>
                            <td style="font-weight: 600; color: #64748B; font-size: 12px;">{{ $reg->registration_number }}</td>
                            <td>
                                <div style="font-weight: 700; color: #0F172A;">{{ $reg->patient_name }}</div>
                                <div style="font-size: 11px; color: #94A3B8;">NIK: {{ $reg->nik }}</div>
                            </td>
                            <td>{{ $reg->polyclinic }}</td>
                            <td><span style="font-size: 12px; color: #475569;">{{ $reg->doctor_name }}</span></td>
                            <td>
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; background: {{ $reg->patient_type === 'bpjs' ? '#DCFCE7' : ($reg->patient_type === 'asuransi' ? '#FEF3C7' : '#EFF6FF') }}; color: {{ $reg->patient_type === 'bpjs' ? '#16A34A' : ($reg->patient_type === 'asuransi' ? '#D97706' : '#2563EB') }};">
                                    {{ $reg->patient_type }}
                                </span>
                            </td>
                            <td>{{ $reg->created_at->format('H:i') }}</td>
                            <td>
                                <span class="status-badge {{ $reg->status }}" id="badge-{{ $reg->id }}">
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
                                <div style="display: inline-flex; gap: 6px;" id="actions-{{ $reg->id }}">
                                    @if($reg->status === 'pending')
                                        <button onclick="verifyRegistration({{ $reg->id }})" class="btn-warning-amber" style="padding: 5px 10px; font-size: 11.5px;" title="Verifikasi Pendaftaran">
                                            Verifikasi
                                        </button>
                                    @endif

                                    @if($ticket && in_array($ticket->status, ['waiting', 'skipped']))
                                        <button onclick="callSpecificTicket({{ $ticket->id }}, '{{ $ticket->queue_number }}')" class="btn-primary-blue" style="padding: 5px 11px; font-size: 11.5px;" title="Panggil Pasien Ini">
                                            Panggil
                                        </button>
                                    @elseif($ticket && $ticket->status === 'serving')
                                        <button onclick="completeCurrent({{ $ticket->id }})" class="btn-success-green" style="padding: 5px 10px; font-size: 11.5px;" title="Selesaikan">
                                            Selesai
                                        </button>
                                        <button onclick="skipCurrent({{ $ticket->id }})" class="btn-outline-slate" style="padding: 5px 8px; font-size: 11.5px;" title="Lewati">
                                            Lewati
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 48px; color: #94A3B8;">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    </div>
                                    <h3>Tidak Ada Pasien</h3>
                                    <p>Belum ada pendaftaran rawat jalan hari ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dash-table-footer">
            <span id="tableCountInfo">Menampilkan {{ count($registrations) }} pendaftaran rawat jalan</span>
            <span style="font-size: 12px; color: #94A3B8;">Loket Rawat Jalan RS Santa Anna</span>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Audio Synthesizer for Hospital Call Ding-Dong
    function playChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;

            // First chime tone (high note: 587 Hz - D5)
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

            // Second chime tone (low note: 440 Hz - A4)
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
            console.log('AudioContext not allowed yet:', e);
        }
    }

    // Call Next FIFO
    async function callNextQueue() {
        const btn = document.getElementById('btnCallNext');
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
                    loket_name: 'Loket Rawat Jalan',
                    counter_number: 1
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                playChime();
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

    // Call Specific Ticket from table
    async function callSpecificTicket(ticketId, queueNum) {
        try {
            const res = await fetch("{{ route('dashboard.queue.call-next') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    loket_name: 'Loket Rawat Jalan',
                    ticket_id: ticketId,
                    counter_number: 1
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                playChime();
                window.showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 900);
            } else {
                window.showToast(data.message || 'Gagal memanggil antrean.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        }
    }

    // Re-call current active ticket
    function recallCurrent() {
        playChime();
        const num = document.getElementById('activeQueueNumber')?.innerText || '';
        window.showToast('Memanggil ulang nomor ' + num + ' menuju Loket Rawat Jalan', 'info');
    }

    // Complete Current Ticket
    async function completeCurrent(ticketId) {
        if (!confirm('Tandai antrean ini telah selesai dilayani?')) return;

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

    // Skip Ticket
    async function skipCurrent(ticketId) {
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

    // Verify Registration
    async function verifyRegistration(regId) {
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

    // Tab Filtering
    function filterTab(status, btn) {
        document.querySelectorAll('.tab-item').forEach(b => {
            b.style.background = '#FFFFFF';
            b.style.color = '#64748B';
            b.style.borderColor = '#E2E8F0';
        });
        btn.style.background = '#2563EB';
        btn.style.color = '#FFFFFF';
        btn.style.borderColor = 'transparent';

        const rows = document.querySelectorAll('#queueTable tbody tr');
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

        const countEl = document.getElementById('tableCountInfo');
        if (countEl) countEl.innerText = `Menampilkan ${visibleCount} pendaftaran`;
    }

    // Table Search Filter
    document.getElementById('tableSearch')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#queueTable tbody tr');
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

        const countEl = document.getElementById('tableCountInfo');
        if (countEl) countEl.innerText = `Menampilkan ${visibleCount} pendaftaran`;
    });
</script>
@endpush
