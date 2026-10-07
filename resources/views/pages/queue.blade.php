@extends('layouts.app')

@section('title', 'Live Antrian - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Live Antrian', 'backUrl' => route('home')])
@endsection

@section('content')
    <div class="desktop-container">
        {{-- Filter chips --}}
        <div class="filter-chips" style="padding-top: 12px;">
            <a href="{{ route('queue.index') }}"
               class="filter-chip {{ empty($selectedPolyclinicId) ? 'active' : '' }}">
                Semua Poli
            </a>
            @foreach($polyclinics as $poli)
                <a href="{{ route('queue.index', ['polyclinic_id' => $poli['id']]) }}"
                   class="filter-chip {{ $selectedPolyclinicId == $poli['id'] ? 'active' : '' }}">
                    {{ $poli['name'] }}
                </a>
            @endforeach
        </div>

        {{-- Notice --}}
        <div class="notice-banner info" style="margin-top: 4px;">
            Data antrian ini merupakan data contoh. Antrian real-time akan tersedia setelah integrasi dengan SIMRS.
        </div>

        {{-- Queue Cards --}}
        <div style="padding: 0 16px 24px;">
            <div class="queue-grid">
                @forelse($queues as $queue)
                    <div class="queue-card">
                        <div class="queue-header">
                            <div>
                                <div class="queue-poli">{{ $queue['polyclinic']['name'] ?? 'Poliklinik' }}</div>
                                <div class="queue-doctor">{{ $queue['doctor']['name'] ?? 'Dokter' }}</div>
                            </div>
                            @php
                                $statusClass = match($queue['status']) {
                                    'Sedang Dilayani' => 'serving',
                                    'Selesai' => 'done',
                                    default => 'waiting',
                                };
                            @endphp
                            <span class="queue-status-badge {{ $statusClass }}">{{ $queue['status'] }}</span>
                        </div>

                        <div class="queue-numbers">
                            <div class="queue-number-box current">
                                <div class="label">Nomor Saat Ini</div>
                                <div class="number">{{ $queue['current_number'] }}</div>
                            </div>
                            <div class="queue-number-box">
                                <div class="label">Total Pasien</div>
                                <div class="number">{{ $queue['total_patients'] }}</div>
                            </div>
                        </div>

                        <div class="queue-waiting">
                            <strong>{{ $queue['waiting_count'] }}</strong> pasien menunggu
                        </div>

                        {{-- Progress bar --}}
                        <div style="background: var(--color-gray-100); border-radius: 4px; height: 6px; overflow: hidden; margin-top: 8px;">
                            @php
                                $progress = $queue['total_patients'] > 0 ? ($queue['served_patients'] / $queue['total_patients']) * 100 : 0;
                            @endphp
                            <div style="background: var(--color-success); height: 100%; border-radius: 4px; width: {{ $progress }}%; transition: width 0.3s ease;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                            <span style="font-size: 11px; color: var(--color-gray-500);">{{ $queue['served_patients'] }} dilayani</span>
                            <span style="font-size: 11px; color: var(--color-gray-500);">{{ $queue['total_patients'] }} total</span>
                        </div>
                    </div>
                @empty
                    <div class="state-container">
                        @include('components.icon', ['name' => 'queue', 'size' => 48])
                        <h3>Belum ada antrian</h3>
                        <p>Tidak ada data antrian untuk poliklinik yang dipilih saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Refresh Button --}}
        @if(count($queues) > 0)
            <div style="padding: 0 16px 24px; max-width: 300px; margin: 0 auto;">
                <button type="button" class="btn btn-outline btn-block" onclick="location.reload()">
                    @include('components.icon', ['name' => 'refresh', 'size' => 18])
                    Refresh Antrian
                </button>
            </div>
        @endif
    </div>
@endsection
