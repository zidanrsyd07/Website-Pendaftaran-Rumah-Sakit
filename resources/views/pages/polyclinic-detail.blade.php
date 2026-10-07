@extends('layouts.app')

@section('title', $polyclinic['name'] . ' - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => $polyclinic['name'], 'backUrl' => route('polyclinics.index')])
@endsection

@section('content')
<div class="desktop-container" style="padding-bottom: 24px;">
    {{-- Poli Info --}}
    <div style="padding: 24px 16px; text-align: center;">
        <div style="width: 60px; height: 60px; background: var(--color-primary-50); color: var(--color-primary-500); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
            @include('components.icon', ['name' => $polyclinic['icon'], 'size' => 30])
        </div>
        <h2 style="font-size: 22px; font-weight: 700; color: var(--color-gray-900); margin-bottom: 6px;">{{ $polyclinic['name'] }}</h2>
        <p style="font-size: 14px; color: var(--color-gray-500); max-width: 500px; margin: 0 auto;">{{ $polyclinic['description'] }}</p>
    </div>

    <div class="divider"></div>

    {{-- Dokter Tersedia --}}
    <section style="padding: 20px 0;">
        <div style="padding: 0 16px; margin-bottom: 14px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--color-gray-800);">Dokter Spesialis Tersedia</h3>
        </div>

        <div class="doctor-grid">
            @forelse($doctors as $doctor)
                <div style="padding: 0 16px; margin-bottom: 12px;">
                    <div class="doctor-card">
                        <div class="doctor-photo">
                            @if($doctor['photo'])
                                <img src="{{ $doctor['photo'] }}" alt="{{ $doctor['name'] }}">
                            @else
                                @include('components.icon', ['name' => 'user', 'size' => 28])
                            @endif
                        </div>
                        <div class="doctor-info">
                            <h3>{{ $doctor['name'] }}</h3>
                            <div class="doctor-spec">{{ $doctor['specialization'] }}</div>
                            <div class="doctor-schedule">
                                @foreach($doctor['schedules'] as $schedule)
                                    {{ $schedule['day'] }}: {{ $schedule['time'] }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                            <div class="doctor-actions">
                                <a href="{{ route('doctors.show', $doctor['id']) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                                <a href="{{ route('registration.select-type', ['doctor_id' => $doctor['id'], 'polyclinic_id' => $polyclinic['id']]) }}" class="btn btn-primary btn-sm">Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="state-container" style="grid-column: 1 / -1;">
                    @include('components.icon', ['name' => 'user', 'size' => 48])
                    <h3>Belum ada dokter</h3>
                    <p>Belum ada dokter terdaftar untuk poliklinik ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Tombol Daftar --}}
    <div style="padding: 12px 16px 24px; max-width: 500px; margin: 0 auto;">
        <a href="{{ route('registration.select-type', ['polyclinic_id' => $polyclinic['id']]) }}" class="btn btn-primary btn-block btn-lg">
            @include('components.icon', ['name' => 'clipboard', 'size' => 18])
            Daftar Berobat di {{ $polyclinic['name'] }}
        </a>
    </div>
</div>
@endsection
