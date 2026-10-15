@extends('layouts.dashboard')

@section('title', 'Portal Pasien')
@section('role-label', 'Pasien Mandiri')
@section('breadcrumb', 'Portal Pasien')

@section('sidebar-nav')
    <div class="dash-nav-section">
        <div class="dash-nav-title">Menu Utama</div>
        <a href="{{ route('dashboard.pasien') }}" class="dash-nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
            <span class="nav-label">Dashboard Pasien</span>
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Fitur Pasien</div>
        <a href="#activeTicketSection" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
            <span class="nav-label">Tiket Antrean Aktif</span>
            @if($stats['active_ticket_exists'])
                <span class="nav-badge" style="background:#2563EB;color:#FFF;">1</span>
            @endif
        </a>
        <a href="#historySection" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Riwayat Pendaftaran</span>
            <span class="nav-badge">{{ $stats['total_registrations'] }}</span>
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Layanan Publik RS</div>
        <a href="{{ route('registration.select-type') }}" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            <span class="nav-label">Daftar Berobat Baru</span>
        </a>
        <a href="{{ route('doctors.index') }}" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            <span class="nav-label">Jadwal Praktik Dokter</span>
        </a>
        <a href="{{ route('polyclinics.index') }}" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <span class="nav-label">Layanan Poliklinik</span>
        </a>
    </div>
@endsection

@section('content')
    {{-- Header --}}
    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div class="dash-page-header" style="margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                    <h1 style="margin: 0;">Selamat Datang, {{ Auth::user()->name }}</h1>
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE;">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #2563EB;"></span>
                        Akun Pasien Terverifikasi
                    </span>
                </div>
                <p>Pantau tiket antrean aktif, estimasi panggilan poliklinik/admisi, dan riwayat pendaftaran Anda di RS Santa Anna</p>
            </div>
            <div>
                <a href="{{ route('registration.select-type') }}" class="btn-calling btn-call-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 18px; font-size: 13.5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Daftar Berobat Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="padding: 12px 16px; border-radius: 8px; background: #DCFCE7; border: 1px solid #86EFAC; color: #166534; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->has('cancel'))
            <div style="padding: 12px 16px; border-radius: 8px; background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <svg style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ $errors->first('cancel') }}</span>
            </div>
        @endif
    </div>

    {{-- Stats Cards Row --}}
    <div class="dash-stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); margin-bottom: 24px;">
        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #EFF6FF; color: #2563EB;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
            </div>
            <div>
                <div class="dash-stat-label">Tiket Antrean Hari Ini</div>
                <div class="dash-stat-value" style="font-size: 22px;">{{ $stats['active_ticket_exists'] ? '1 Aktif' : 'Tidak Ada' }}</div>
            </div>
        </div>

        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #DCFCE7; color: #16A34A;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="dash-stat-label">Kunjungan Selesai</div>
                <div class="dash-stat-value" style="font-size: 22px;">{{ $stats['completed_visits'] }}</div>
            </div>
        </div>

        <div class="dash-stat-card">
            <div class="dash-stat-icon" style="background: #F3E8FF; color: #7C3AED;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
            </div>
            <div>
                <div class="dash-stat-label">Total Pendaftaran</div>
                <div class="dash-stat-value" style="font-size: 22px;">{{ $stats['total_registrations'] }}</div>
            </div>
        </div>
    </div>

    {{-- Active Ticket Hero Section --}}
    <div id="activeTicketSection" style="margin-bottom: 28px;">
        @if($activeRegistration && $queueInfo)
            @php
                $ticket = $queueInfo['ticket'];
                $isServing = $ticket->status === 'serving';
            @endphp
            <div class="dash-card" style="border: 2px solid {{ $isServing ? '#16A34A' : '#3B82F6' }}; box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.1);">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; background: {{ $isServing ? '#DCFCE7' : '#EFF6FF' }}; color: {{ $isServing ? '#16A34A' : '#2563EB' }}; margin-bottom: 8px;">
                            <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $isServing ? '#16A34A' : '#2563EB' }};"></span>
                            {{ $isServing ? 'NOMOR ANDA SEDANG DIPANGGIL!' : 'ANTREAN AKTIF HARI INI' }}
                        </div>
                        <h2 style="margin: 0 0 4px; font-size: 20px; font-weight: 800; color: #0F172A;">Tiket Pendaftaran Layanan Berobat</h2>
                        <p style="margin: 0; font-size: 13px; color: #64748B;">No. Registrasi: <strong style="color: #0F172A;">{{ $activeRegistration->registration_number }}</strong> &bull; {{ $ticket->loket_name }}</p>
                    </div>

                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="openCancelModal({{ $activeRegistration->id }}, '{{ $activeRegistration->registration_number }}')" class="btn-calling btn-call-outline" style="color: #DC2626; border-color: #FCA5A5; padding: 8px 14px; font-size: 12.5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Batalkan Pendaftaran
                        </button>
                    </div>
                </div>

                {{-- Ticket Details Grid --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                    {{-- Big Number Badge --}}
                    <div style="text-align: center; padding: 12px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; display: flex; flex-direction: column; justify-content: center;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Nomor Antrean Anda</span>
                        <div style="font-size: 42px; font-weight: 900; color: #2563EB; line-height: 1.1; margin: 6px 0;">
                            {{ $ticket->queue_number }}
                        </div>
                        <span class="badge-status {{ $activeRegistration->status === 'verified' ? 'verified' : ($activeRegistration->status === 'in_progress' ? 'in-progress' : 'pending') }}" style="align-self: center;">
                            {{ $activeRegistration->statusLabel() }}
                        </span>
                    </div>

                    {{-- Live Wait Status --}}
                    <div style="padding: 12px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">Status Antrean Loket Saat Ini</span>
                            <div style="margin-top: 6px; font-size: 24px; font-weight: 800; color: #0F172A;">
                                Sedang Dipanggil: <span style="color: #16A34A;">{{ $queueInfo['current_serving'] }}</span>
                            </div>
                        </div>
                        <div style="margin-top: 10px; font-size: 12.5px; color: #475569; display: flex; flex-direction: column; gap: 4px;">
                            <span>Sisa Antrean di Depan: <strong style="color: #0F172A;">{{ $queueInfo['waiting_ahead'] }} orang</strong></span>
                            <span>Estimasi Waktu Tunggu: <strong style="color: #2563EB;">~{{ $queueInfo['estimated_wait_minutes'] }} menit</strong></span>
                        </div>
                    </div>

                    {{-- Clinic / Room Details --}}
                    <div style="padding: 12px; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; display: flex; flex-direction: column; gap: 8px;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase;">Tujuan Pemeriksaan</span>
                        <div>
                            <div style="font-size: 14px; font-weight: 800; color: #0F172A;">{{ $activeRegistration->polyclinic }}</div>
                            <div style="font-size: 12.5px; color: #64748B;">{{ $activeRegistration->doctor_name ?? 'Dokter Jaga / Admisi' }}</div>
                        </div>
                        <div style="margin-top: auto; font-size: 11.5px; color: #64748B; border-top: 1px solid #F1F5F9; padding-top: 6px;">
                            Penjamin: <strong style="color: #0F172A; text-transform: uppercase;">{{ $activeRegistration->patient_type }}</strong> &bull; {{ $activeRegistration->service_type === 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}
                        </div>
                    </div>
                </div>

                <div style="background: #EFF6FF; border-left: 4px solid #2563EB; padding: 12px 16px; border-radius: 6px; font-size: 12.5px; color: #1E40AF; display: flex; align-items: center; gap: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:20px;height:20px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    <span>Harap standby di ruang tunggu lobi RS Santa Anna 15 menit sebelum nomor antrean Anda dipanggil petugas loket.</span>
                </div>
            </div>
        @else
            {{-- Empty State Card --}}
            <div class="dash-card" style="text-align: center; padding: 36px 20px;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #EFF6FF; color: #2563EB; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
                </div>
                <h3 style="margin: 0 0 6px; font-size: 17px; font-weight: 700; color: #0F172A;">Tidak Ada Antrean Aktif Hari Ini</h3>
                <p style="margin: 0 auto 18px; max-width: 460px; font-size: 13px; color: #64748B;">Anda belum memiliki pendaftaran berobat yang dijadwalkan untuk hari ini. Silakan buat pendaftaran baru untuk mengambil nomor antrean online.</p>
                <a href="{{ route('registration.select-type') }}" class="btn-calling btn-call-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; padding: 10px 18px; font-size: 13px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Daftar Rawat Jalan / Rawat Inap
                </a>
            </div>
        @endif
    </div>

    {{-- Registration History Section --}}
    <div id="historySection" class="dash-card">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div>
                <h3 style="margin: 0 0 4px; font-size: 16px; font-weight: 800; color: #0F172A;">Riwayat Pendaftaran Pasien</h3>
                <p style="margin: 0; font-size: 12.5px; color: #64748B;">Catatan pendaftaran rawat jalan dan rawat inap yang terdaftar pada akun Anda</p>
            </div>
            <span style="font-size: 12px; font-weight: 600; color: #64748B; background: #F1F5F9; padding: 4px 10px; border-radius: 6px;">
                Total: {{ $registrations->count() }} Pendaftaran
            </span>
        </div>

        <div class="dash-table-wrapper">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>No. Registrasi</th>
                        <th>Layanan</th>
                        <th>Poli / Kamar</th>
                        <th>Dokter</th>
                        <th>Tgl Kunjungan</th>
                        <th>Penjamin</th>
                        <th>Tiket Antrean</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            <td>
                                <strong style="color: #0F172A; font-size: 13px;">{{ $reg->registration_number }}</strong>
                                <div style="font-size: 11px; color: #94A3B8;">{{ $reg->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <span class="service-badge {{ $reg->service_type === 'rawat_jalan' ? 'badge-rj' : 'badge-ri' }}">
                                    {{ $reg->service_type === 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1E293B;">{{ $reg->polyclinic }}</div>
                                @if($reg->room_class)
                                    <div style="font-size: 11px; color: #64748B;">Kelas: {{ $reg->room_class }}</div>
                                @endif
                            </td>
                            <td>{{ $reg->doctor_name ?? '-' }}</td>
                            <td>
                                <div>{{ $reg->visit_date ? $reg->visit_date->format('d M Y') : '-' }}</div>
                            </td>
                            <td>
                                <span style="text-transform: uppercase; font-size: 11.5px; font-weight: 700; color: #475569;">
                                    {{ $reg->patient_type }}
                                </span>
                            </td>
                            <td>
                                @if($reg->queueTicket)
                                    <span style="font-weight: 800; font-size: 13px; color: #2563EB; background: #EFF6FF; padding: 3px 8px; border-radius: 4px; border: 1px solid #DBEAFE;">
                                        {{ $reg->queueTicket->queue_number }}
                                    </span>
                                @else
                                    <span style="color: #94A3B8;">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($reg->status) {
                                        'completed' => 'completed',
                                        'in_progress' => 'in-progress',
                                        'verified' => 'verified',
                                        'cancelled' => 'cancelled',
                                        'skipped' => 'skipped',
                                        default => 'pending'
                                    };
                                @endphp
                                <span class="badge-status {{ $badgeClass }}">
                                    {{ $reg->statusLabel() }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                @if(in_array($reg->status, ['pending', 'verified']))
                                    <button type="button" onclick="openCancelModal({{ $reg->id }}, '{{ $reg->registration_number }}')" style="background: none; border: 1px solid #FCA5A5; color: #DC2626; border-radius: 6px; padding: 4px 8px; font-size: 11.5px; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='none'">
                                        Batalkan
                                    </button>
                                @else
                                    <span style="font-size: 11px; color: #94A3B8;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 36px 16px; color: #64748B;">
                                Belum ada riwayat pendaftaran berobat. Silakan klik tombol "Daftar Berobat Baru" di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Cancel Modal --}}
    <div id="cancelModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: #FFFFFF; border-radius: 12px; max-width: 440px; width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #FEE2E2; color: #DC2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0F172A;">Batalkan Pendaftaran Berobat</h3>
                    <p style="margin: 0; font-size: 12.5px; color: #64748B;" id="cancelModalSubtext">Nomor Registrasi: -</p>
                </div>
            </div>

            <form id="cancelForm" method="POST" action="">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label for="cancelReasonInput" style="display: block; font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Alasan Pembatalan (Opsional)</label>
                    <textarea id="cancelReasonInput" name="reason" rows="3" placeholder="Contoh: Berhalangan hadir, jadwal bentrok, atau salah pilih poliklinik..." style="width: 100%; border: 1px solid #CBD5E1; border-radius: 8px; padding: 10px; font-size: 13px; font-family: inherit; resize: vertical; box-sizing: border-box;"></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="button" onclick="closeCancelModal()" style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 6px; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="background: #DC2626; border: none; color: #FFFFFF; font-size: 13px; font-weight: 600; padding: 8px 16px; border-radius: 6px; cursor: pointer;">
                        Ya, Batalkan Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openCancelModal(registrationId, regNumber) {
        const modal = document.getElementById('cancelModal');
        const form = document.getElementById('cancelForm');
        const subtext = document.getElementById('cancelModalSubtext');

        form.action = `/dashboard/registration/${registrationId}/cancel`;
        subtext.textContent = `Nomor Registrasi: ${regNumber}`;
        modal.style.display = 'flex';
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    // Close on outside click
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('cancelModal');
        if (e.target === modal) {
            closeCancelModal();
        }
    });
</script>
@endpush
