@extends('layouts.app')

@section('title', $doctor['name'] . ' - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Detail Dokter', 'backUrl' => route('doctors.index')])
@endsection

@section('content')
<div class="desktop-container narrow" style="padding-bottom: 24px;">
    {{-- Doctor Profile --}}
    <div class="detail-header">
        <div class="detail-photo">
            @if(!empty($doctor['photo']))
                <img src="{{ $doctor['photo'] }}" alt="{{ $doctor['name'] }}">
            @else
                @include('components.icon', ['name' => 'user', 'size' => 40])
            @endif
        </div>
        <div class="detail-name">{{ $doctor['name'] }}</div>
        <div class="detail-spec">{{ $doctor['specialization'] }}</div>
        @if(!empty($doctor['polyclinic']))
            <div style="font-size: 13px; color: var(--color-gray-500); margin-top: 4px;">
                {{ $doctor['polyclinic']['name'] }}
            </div>
        @endif
    </div>

    {{-- Bio --}}
    @if(!empty($doctor['bio']))
        <div class="detail-section">
            <h3>Tentang Dokter</h3>
            <p>{{ $doctor['bio'] }}</p>
        </div>
    @endif

    {{-- Jadwal Praktik --}}
    <div class="detail-section">
        <h3>Jadwal Praktik</h3>
        <table class="schedule-table">
            @foreach($doctor['schedules'] as $schedule)
                <tr>
                    <td>{{ $schedule['day'] }}</td>
                    <td>{{ $schedule['time'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    {{-- Status --}}
    <div class="detail-section">
        <h3>Status Jadwal</h3>
        @php
            $today = now()->locale('id')->dayName;
            $isAvailableToday = false;
            $todaySchedule = null;
            foreach ($doctor['schedules'] as $schedule) {
                if (strtolower($schedule['day']) === strtolower($today)) {
                    $isAvailableToday = true;
                    $todaySchedule = $schedule;
                    break;
                }
            }
        @endphp
        @if($isAvailableToday)
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="queue-status-badge serving">Praktik Hari Ini</span>
                <span style="font-size: 13px; color: var(--color-gray-600);">{{ $todaySchedule['time'] }}</span>
            </div>
        @else
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="queue-status-badge waiting">Tidak Praktik Hari Ini</span>
            </div>
        @endif
    </div>

    {{-- Tombol Daftar --}}
    <div style="padding: 8px 16px 24px;">
        <a href="{{ route('registration.select-type', ['doctor_id' => $doctor['id'], 'polyclinic_id' => $doctor['polyclinic_id']]) }}" class="btn btn-primary btn-block btn-lg">
            @include('components.icon', ['name' => 'clipboard', 'size' => 18])
            Daftar Berobat
        </a>
    </div>
</div>
@endsection
