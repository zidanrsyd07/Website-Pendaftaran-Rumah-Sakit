@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - RS Santa Anna')

@section('content')
    <div class="desktop-container narrow" style="padding: 24px 16px 40px;">
    <div class="confirmation-card">
        <div class="confirmation-header">
            <div class="check-icon">
                @include('components.icon', ['name' => 'check', 'size' => 24])
            </div>
            <h2>Pendaftaran Berhasil!</h2>
            <p>{{ $result['message'] }}</p>
        </div>

        <div class="confirmation-number">
            <div class="label">Nomor Pendaftaran</div>
            <div class="number">{{ $result['registration_number'] }}</div>
        </div>

        <div class="confirmation-details">
            <div class="confirmation-row">
                <span class="label">Nama Pasien</span>
                <span class="value">{{ $validated['full_name'] }}</span>
            </div>
            <div class="confirmation-row">
                <span class="label">Jenis Layanan</span>
                <span class="value">{{ $validated['service_type'] === 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}</span>
            </div>

            @if(!empty($result['polyclinic']))
                <div class="confirmation-row">
                    <span class="label">Poliklinik</span>
                    <span class="value">{{ $result['polyclinic']['name'] }}</span>
                </div>
            @endif

            @if(!empty($result['doctor']))
                <div class="confirmation-row">
                    <span class="label">Dokter</span>
                    <span class="value">{{ $result['doctor']['name'] }}</span>
                </div>
            @endif

            @if(!empty($validated['visit_date']))
                <div class="confirmation-row">
                    <span class="label">Tanggal Kunjungan</span>
                    <span class="value">{{ \Carbon\Carbon::parse($validated['visit_date'])->format('d M Y') }}</span>
                </div>
            @endif

            @if(!empty($validated['schedule']))
                <div class="confirmation-row">
                    <span class="label">Jadwal</span>
                    <span class="value">{{ $validated['schedule'] }}</span>
                </div>
            @endif

            <div class="confirmation-row">
                <span class="label">Jenis Penjamin</span>
                <span class="value">
                    @php
                        $paymentMap = ['umum' => 'Umum / Mandiri', 'asuransi' => 'Asuransi Swasta', 'perusahaan' => 'Penjamin Perusahaan'];
                    @endphp
                    {{ $paymentMap[$validated['patient_type']] ?? ucfirst($validated['patient_type']) }}
                </span>
            </div>

            @if($validated['patient_type'] !== 'umum' && !empty($validated['insurance_name']))
                <div class="confirmation-row">
                    <span class="label">Nama Asuransi</span>
                    <span class="value">{{ $validated['insurance_name'] }}</span>
                </div>
            @endif

            @if(!empty($validated['room_class']))
                @php
                    $roomClassMap = ['vip' => 'VIP', 'kelas_1' => 'Kelas 1', 'kelas_2' => 'Kelas 2', 'kelas_3' => 'Kelas 3'];
                @endphp
                <div class="confirmation-row">
                    <span class="label">Kelas Perawatan</span>
                    <span class="value">{{ $roomClassMap[$validated['room_class']] ?? $validated['room_class'] }}</span>
                </div>
            @endif

            @if(!empty($result['queue_number']))
                <div class="confirmation-row">
                    <span class="label">Nomor Antrian</span>
                    <span class="value" style="color: var(--color-primary-500); font-weight: 700; font-size: 16px;">{{ $result['queue_number'] }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Status timeline --}}
    <div style="background: #fff; border: 1px solid var(--color-gray-200); border-radius: 14px; padding: 20px; margin: 16px 0;">
        <h3 style="font-size: 15px; font-weight: 700; color: var(--color-gray-900); margin-bottom: 16px;">Status Pendaftaran</h3>
        <div class="status-timeline">
            <div class="timeline-item completed">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Formulir Dikirim</h4>
                    <p>Data pendaftaran berhasil diterima</p>
                    <time>{{ now()->format('d M Y, H:i') }}</time>
                </div>
            </div>
            <div class="timeline-item active">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Menunggu Verifikasi</h4>
                    <p>Petugas akan memverifikasi data Anda</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Terdaftar</h4>
                    <p>Pendaftaran dikonfirmasi oleh petugas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="notice-banner info" style="margin: 0 0 16px;">
        Simpan nomor pendaftaran Anda. Data ini merupakan data sementara dan belum terintegrasi dengan SIMRS RS Santa Anna.
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="{{ route('home') }}" class="btn btn-outline btn-block">Beranda</a>
        <a href="{{ route('queue.index') }}" class="btn btn-primary btn-block">Lihat Antrian</a>
    </div>
    </div>
@endsection
