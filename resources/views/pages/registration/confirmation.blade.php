@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - RS Santa Anna')

@section('content')
    <div class="desktop-container narrow" style="padding: 20px 16px 40px;">
    <div class="confirmation-card">
        <div class="confirmation-header">
            <div class="check-icon">
                @include('components.icon', ['name' => 'check', 'size' => 24])
            </div>
            <h2>Pendaftaran Berhasil</h2>
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
                <span class="label">Jenis Pasien</span>
                <span class="value">{{ $validated['patient_type'] === 'asuransi' ? 'Asuransi' : 'Pasien Umum' }}</span>
            </div>

            @if($validated['patient_type'] === 'asuransi' && !empty($validated['insurance_name']))
                <div class="confirmation-row">
                    <span class="label">Nama Asuransi</span>
                    <span class="value">{{ $validated['insurance_name'] }}</span>
                </div>
            @endif

            @if(!empty($validated['room_class']))
                @php
                    $roomClassMap = [
                        'vip' => 'VIP',
                        'kelas_1' => 'Kelas 1',
                        'kelas_2' => 'Kelas 2',
                        'kelas_3' => 'Kelas 3',
                    ];
                @endphp
                <div class="confirmation-row">
                    <span class="label">Kelas Perawatan</span>
                    <span class="value">{{ $roomClassMap[$validated['room_class']] ?? $validated['room_class'] }}</span>
                </div>
            @endif

            @if(!empty($result['queue_number']))
                <div class="confirmation-row">
                    <span class="label">Nomor Antrian</span>
                    <span class="value" style="color: var(--color-primary-500); font-weight: 700;">{{ $result['queue_number'] }}</span>
                </div>
            @endif
        </div>
    </div>

    <div class="notice-banner info">
        Data ini merupakan data sementara dan belum terintegrasi dengan SIMRS RS Santa Anna. Simpan nomor pendaftaran Anda untuk referensi.
    </div>

    <div style="padding: 0 16px 24px; display: flex; gap: 10px;">
        <a href="{{ route('home') }}" class="btn btn-outline btn-block">Beranda</a>
        <a href="{{ route('queue.index') }}" class="btn btn-primary btn-block">Lihat Antrian</a>
    </div>
    </div>
@endsection
