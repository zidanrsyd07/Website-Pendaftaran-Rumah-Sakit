@extends('layouts.app')

@section('title', 'Cari Dokter - RS Santa Anna')

@section('header')
    {{-- Search bar replaces header on this page --}}
    <form action="{{ route('doctors.index') }}" method="GET" id="searchForm">
        <div class="search-bar">
            <div class="search-input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="search" placeholder="Cari nama dokter..." value="{{ $filters['search'] ?? '' }}" autocomplete="off">
            </div>
        </div>

        {{-- Filter chips --}}
        <div class="filter-chips">
            <a href="{{ route('doctors.index', array_merge($filters, ['polyclinic_id' => ''])) }}"
               class="filter-chip {{ empty($filters['polyclinic_id']) ? 'active' : '' }}">
                Semua
            </a>
            @foreach($polyclinics as $poli)
                <a href="{{ route('doctors.index', array_merge($filters, ['polyclinic_id' => $poli['id']])) }}"
                   class="filter-chip {{ ($filters['polyclinic_id'] ?? '') == $poli['id'] ? 'active' : '' }}">
                    {{ $poli['name'] }}
                </a>
            @endforeach
        </div>
    </form>
@endsection

@section('content')
    <div class="desktop-container" style="padding: 12px 0 24px;">
        <div class="doctor-grid">
            @forelse($doctors as $doctor)
                <div style="padding: 0 16px; margin-bottom: 10px;">
                    <div class="doctor-card">
                        <div class="doctor-photo">
                            @if(!empty($doctor['photo']))
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
                                    {{ $schedule['day'] }}: {{ $schedule['time'] }}@if(!$loop->last)<br>@endif
                                @endforeach
                            </div>
                            <div class="doctor-actions">
                                <a href="{{ route('doctors.show', $doctor['id']) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                                <a href="{{ route('registration.select-type', ['doctor_id' => $doctor['id'], 'polyclinic_id' => $doctor['polyclinic_id']]) }}" class="btn btn-primary btn-sm">Daftar</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="state-container">
                    @include('components.icon', ['name' => 'search', 'size' => 48])
                    <h3>Dokter tidak ditemukan</h3>
                    <p>Coba ubah kata kunci pencarian atau filter poliklinik.</p>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-submit search after typing stops
        let searchTimer;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        }
    </script>
    @endpush
@endsection
