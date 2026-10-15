@extends('layouts.dashboard')

@section('title', 'Ketersediaan Kamar Ready (SIMRS)')
@section('role-label', Auth::user()->role_label ?? 'Staff Rawat Inap')
@section('breadcrumb', 'Ketersediaan Kamar SIMRS')

@section('sidebar-nav')
    <div class="dash-nav-section">
        <div class="dash-nav-title">Menu Utama</div>
        <a href="{{ route('dashboard.kamar') }}" class="dash-nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
            <span class="nav-label">Kamar Ready SIMRS</span>
            <span class="nav-badge" style="background: #16A34A; color: #FFFFFF;">{{ $bedStats['ready_beds'] ?? 0 }}</span>
        </a>
        <a href="{{ route('dashboard.loket-rawat-inap') }}" class="dash-nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            <span class="nav-label">Kembali ke Loket RI</span>
        </a>
    </div>

    <div class="dash-nav-section">
        <div class="dash-nav-title">Kategori Ruang</div>
        @foreach(['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'Isolasi'] as $navClass)
            @php
                $statCls = $bedStats['by_class'][$navClass] ?? ['ready' => 0, 'total' => 0];
            @endphp
            <a href="{{ route('dashboard.kamar', ['class' => $navClass]) }}" class="dash-nav-item {{ strtolower($classFilter) === strtolower($navClass) ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                <span class="nav-label">{{ $navClass }}</span>
                <span style="font-size: 11px; font-weight: 700; color: {{ $statCls['ready'] > 0 ? '#16A34A' : '#94A3B8' }};">
                    {{ $statCls['ready'] }} ready
                </span>
            </a>
        @endforeach
    </div>
@endsection

@section('content')
    {{-- Header --}}
    <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
            <div class="dash-page-header" style="margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                    <h1 style="margin: 0;">Ketersediaan Kamar Rawat Inap</h1>
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; background: #DCFCE7; color: #16A34A; border: 1px solid #BBF7D0;">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #16A34A; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.2);"></span>
                        Live API SIMRS Terhubung
                    </span>
                </div>
                <p>Pemantauan kapasitas bed, status kamar siap huni (ready), dan alokasi pasien rawat inap real-time</p>
            </div>

            <div style="display: flex; gap: 10px;">
                <button onclick="window.location.reload()" class="btn-outline-slate" title="Sinkronkan dengan SIMRS Core">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                    Sinkronkan SIMRS
                </button>
                <a href="{{ route('dashboard.loket-rawat-inap') }}" class="btn-primary-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Kembali ke Loket RI
                </a>
            </div>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="stat-cards" id="summarySection">
        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Kamar Ready (Siap Huni)</div>
                    <div class="stat-value" style="color: #16A34A;">{{ $bedStats['ready_beds'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Bed</span></div>
                </div>
                <div class="stat-card-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <span style="font-weight: 700;">Siap dialokasikan segera</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Sedang Terisi Pasien</div>
                    <div class="stat-value" style="color: #2563EB;">{{ $bedStats['occupied_beds'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Bed</span></div>
                </div>
                <div class="stat-card-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>BOR (Bed Occupancy): {{ $bedStats['occupancy_rate'] }}%</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Proses Pembersihan</div>
                    <div class="stat-value" style="color: #D97706;">{{ $bedStats['cleaning_beds'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Bed</span></div>
                </div>
                <div class="stat-card-icon orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/></svg>
                </div>
            </div>
            <div class="stat-change neutral">
                <span>Sanitasi & Sterilisasi Ruangan</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div>
                    <div class="stat-title">Total Kapasitas SIMRS</div>
                    <div class="stat-value">{{ $bedStats['total_beds'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Bed</span></div>
                </div>
                <div class="stat-card-icon purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                </div>
            </div>
            <div class="stat-change up">
                <span>6 Kelas Ruang Perawatan</span>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="dash-table-card" style="margin-bottom: 24px; padding: 18px 24px;" id="filterSection">
        <form method="GET" action="{{ route('dashboard.kamar') }}" id="filterForm">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px;">
                {{-- Class Tabs --}}
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;">
                    <span style="font-size: 13px; font-weight: 700; color: #475569; margin-right: 4px;">Kelas Ruang:</span>
                    @php
                        $classesList = [
                            'all' => 'Semua',
                            'VIP' => 'VIP',
                            'Kelas 1' => 'Kelas 1',
                            'Kelas 2' => 'Kelas 2',
                            'Kelas 3' => 'Kelas 3',
                            'ICU' => 'ICU',
                            'Isolasi' => 'Isolasi',
                        ];
                    @endphp
                    @foreach($classesList as $val => $label)
                        <button type="submit" name="class" value="{{ $val }}"
                            style="padding: 6px 14px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all .15s ease;
                            {{ strtolower($classFilter) === strtolower($val) ? 'background: #2563EB; color: #FFFFFF; border: 1px solid #2563EB;' : 'background: #FFFFFF; color: #64748B; border: 1px solid #E2E8F0;' }}">
                            {{ $label }}
                            @if($val !== 'all' && isset($bedStats['by_class'][$val]))
                                <span style="margin-left: 4px; opacity: 0.85;">({{ $bedStats['by_class'][$val]['ready'] }})</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Status Filter & Search --}}
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
                    <select name="status" onchange="document.getElementById('filterForm').submit()" style="padding: 7px 14px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 12.5px; font-family: inherit; color: #0F172A; background: #FFFFFF; cursor: pointer;">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status Bed</option>
                        <option value="ready" {{ $statusFilter === 'ready' ? 'selected' : '' }}>🟢 Ready (Siap Huni)</option>
                        <option value="occupied" {{ $statusFilter === 'occupied' ? 'selected' : '' }}>🔵 Terisi Pasien</option>
                        <option value="cleaning" {{ $statusFilter === 'cleaning' ? 'selected' : '' }}>🟡 Proses Pembersihan</option>
                        <option value="maintenance" {{ $statusFilter === 'maintenance' ? 'selected' : '' }}>🔴 Dalam Perbaikan</option>
                    </select>

                    <div style="position: relative;">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode bed / ruang..." style="padding: 7px 14px 7px 32px; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 12.5px; font-family: inherit; width: 200px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: #94A3B8;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>

                    @if($classFilter !== 'all' || $statusFilter !== 'all' || $search)
                        <a href="{{ route('dashboard.kamar') }}" class="btn-outline-slate" style="padding: 7px 12px; font-size: 12px;">Reset Filter</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Bed Grid --}}
    <div style="margin-bottom: 32px;" id="bedGridSection">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">
                Daftar Bed & Kamar Rawat Inap
                <span style="font-size: 13px; font-weight: 500; color: #64748B;">({{ count($beds) }} bed ditampilkan)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 16px; font-size: 12px; color: #64748B;">
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #16A34A;"></span> Ready (Siap Huni)</span>
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #2563EB;"></span> Terisi Pasien</span>
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #D97706;"></span> Pembersihan</span>
                <span style="display: flex; align-items: center; gap: 6px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #DC2626;"></span> Perbaikan</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(330px, 1fr)); gap: 16px;">
            @forelse($beds as $bed)
                @php
                    $isReady = $bed['status'] === 'ready';
                    $isOccupied = $bed['status'] === 'occupied';
                    $isCleaning = $bed['status'] === 'cleaning';
                    $isMaint = $bed['status'] === 'maintenance';

                    $badgeBg = $isReady ? '#DCFCE7' : ($isOccupied ? '#EFF6FF' : ($isCleaning ? '#FEF3C7' : '#FEE2E2'));
                    $badgeText = $isReady ? '#16A34A' : ($isOccupied ? '#2563EB' : ($isCleaning ? '#D97706' : '#DC2626'));
                    $badgeLabel = $isReady ? 'Ready (Siap Huni)' : ($isOccupied ? 'Terisi Pasien' : ($isCleaning ? 'Sanitasi' : 'Perbaikan'));
                @endphp
                <div class="stat-card" style="padding: 18px 20px; border-radius: 14px; border: 1px solid {{ $isReady ? '#BBF7D0' : '#E2E8F0' }}; background: #FFFFFF; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: transform .15s ease, box-shadow .15s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px -5px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div>
                        {{-- Top Code & Status --}}
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 10px;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 17px; font-weight: 800; color: #0F172A; letter-spacing: -0.01em;">{{ $bed['bed_code'] }}</span>
                                    <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #F1F5F9; color: #475569;">
                                        {{ $bed['room_class'] }}
                                    </span>
                                </div>
                                <div style="font-size: 13.5px; font-weight: 600; color: #334155; margin-top: 2px;">{{ $bed['room_name'] }}</div>
                                <div style="font-size: 11.5px; color: #94A3B8;">{{ $bed['floor'] }} • {{ $bed['building'] }}</div>
                            </div>
                            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 11.5px; font-weight: 700; background: {{ $badgeBg }}; color: {{ $badgeText }};">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $badgeText }};"></span>
                                {{ $badgeLabel }}
                            </span>
                        </div>

                        {{-- Rate Per Night --}}
                        <div style="display: flex; align-items: baseline; gap: 6px; margin-bottom: 10px;">
                            <span style="font-size: 15px; font-weight: 800; color: #0F172A;">Rp {{ number_format($bed['daily_rate'], 0, ',', '.') }}</span>
                            <span style="font-size: 11.5px; color: #94A3B8;">/ malam</span>
                        </div>

                        {{-- Facilities Badges --}}
                        <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 12px;">
                            @foreach(array_slice($bed['facilities'] ?? [], 0, 4) as $fac)
                                <span style="display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 10.5px; font-weight: 500; background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0;">
                                    {{ $fac }}
                                </span>
                            @endforeach
                            @if(count($bed['facilities'] ?? []) > 4)
                                <span style="display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 10.5px; font-weight: 500; background: #F8FAFC; color: #94A3B8; border: 1px solid #E2E8F0;">
                                    +{{ count($bed['facilities']) - 4 }}
                                </span>
                            @endif
                        </div>

                        {{-- Occupant Info if Occupied --}}
                        @if($isOccupied && !empty($bed['patient']))
                            <div style="padding: 10px 12px; background: #F8FAFC; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 4px;">
                                <div style="font-size: 11px; font-weight: 600; color: #64748B; text-transform: uppercase;">Pasien Rawat Inap</div>
                                <div style="font-size: 13px; font-weight: 700; color: #0F172A;">{{ $bed['patient']['name'] }}</div>
                                @if(!empty($bed['patient']['doctor_name']))
                                    <div style="font-size: 11.5px; color: #64748B;">DPJP: {{ $bed['patient']['doctor_name'] }}</div>
                                @endif
                            </div>
                        @elseif($isCleaning)
                            <div style="padding: 8px 12px; background: #FFFBEB; border-radius: 8px; border: 1px solid #FEF3C7; font-size: 11.5px; color: #B45309;">
                                Petugas kebersihan sedang mensterilkan ruangan pasca-pasien pulang.
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; pt: 8px; border-top: 1px solid #F1F5F9;">
                        @if($isReady)
                            <button onclick="openAssignModal({{ json_encode($bed) }})" class="btn-success-green" style="width: 100%; justify-content: center; font-size: 12.5px; padding: 8px 14px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 15px; height: 15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.375 12.375 0 0110.375 21c-2.31 0-4.473-.637-6.375-1.765z"/></svg>
                                Alokasikan Bed Ini
                            </button>
                        @elseif($isCleaning)
                            <button onclick="window.showToast('Notifikasi telah dikirim ke tim sanitasi ruangan.', 'info')" class="btn-outline-slate" style="width: 100%; justify-content: center; font-size: 12px; padding: 7px 12px;">
                                Cek Status Sanitasi
                            </button>
                        @elseif($isOccupied)
                            <button onclick="window.showToast('Bed {{ $bed['bed_code'] }} terisi oleh {{ $bed['patient']['name'] ?? 'Pasien' }}.', 'info')" class="btn-outline-slate" style="width: 100%; justify-content: center; font-size: 12px; padding: 7px 12px;">
                                Info Pasien Terdaftar
                            </button>
                        @else
                            <button onclick="window.showToast('Bed sedang dalam pemeliharaan teknis medis.', 'warning')" class="btn-outline-slate" style="width: 100%; justify-content: center; font-size: 12px; padding: 7px 12px;">
                                Info Maintenance
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 48px; text-align: center; background: #FFFFFF; border-radius: 16px; border: 1px dashed #CBD5E1;">
                    <div style="font-size: 16px; font-weight: 700; color: #0F172A;">Tidak ada kamar yang sesuai kriteria filter</div>
                    <p style="font-size: 13px; color: #64748B; margin-top: 4px;">Coba reset filter atau ubah kata kunci pencarian</p>
                    <a href="{{ route('dashboard.kamar') }}" class="btn-outline-slate" style="margin-top: 12px; display: inline-flex;">Reset Filter</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Interactive Modal: Alokasikan Bed ke Pasien --}}
    <div id="assignBedModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
        <div style="background: #FFFFFF; border-radius: 16px; max-width: 520px; width: 100%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; animation: popIn .2s ease-out;">
            {{-- Modal Header --}}
            <div style="padding: 20px 24px; border-bottom: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0F172A;">Alokasikan Bed Rawat Inap (SIMRS)</h3>
                    <p style="margin: 2px 0 0; font-size: 12px; color: #64748B;">Konfirmasi pemesanan bed ke sistem SIMRS Core Rumah Sakit</p>
                </div>
                <button onclick="closeAssignModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94A3B8; padding: 4px;" title="Tutup">&times;</button>
            </div>

            {{-- Modal Body --}}
            <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                {{-- Selected Bed Summary --}}
                <div style="padding: 14px 16px; background: #F0FDF4; border: 1px solid #DCFCE7; border-radius: 10px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #16A34A; text-transform: uppercase;">Bed Terpilih</div>
                        <div style="font-size: 16px; font-weight: 800; color: #0F172A;" id="modalBedCode">VIP-101</div>
                        <div style="font-size: 12px; color: #475569;" id="modalBedRoom">Paviliun Santa Anna 101</div>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700; background: #DCFCE7; color: #16A34A;" id="modalBedClass">VIP</span>
                        <div style="font-size: 11.5px; color: #64748B; margin-top: 2px;" id="modalBedRate">Rp 850.000 / malam</div>
                    </div>
                </div>

                {{-- Patient Selector --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Pilih Pasien Rawat Inap:</label>
                    @if(count($waitingPatients) > 0)
                        <select id="modalSelectPatient" style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; font-family: inherit; color: #0F172A; background: #FFFFFF;">
                            <option value="">-- Pilih dari pasien antrean rawat inap --</option>
                            @foreach($waitingPatients as $wp)
                                <option value="{{ $wp->id }}">
                                    [{{ $wp->queueTicket?->queue_number ?? 'Antrean' }}] {{ $wp->patient_name }} ({{ $wp->registration_number }}) - Pilihan Kelas: {{ $wp->room_class ?? 'Kelas 1' }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div style="padding: 12px; background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 8px; font-size: 12.5px; color: #92400E;">
                            Belum ada pasien rawat inap dalam status antrean/menunggu saat ini.
                        </div>
                    @endif
                </div>

                <div style="font-size: 12px; color: #64748B; line-height: 1.5; padding: 10px 12px; background: #F8FAFC; border-radius: 8px;">
                    ℹ️ Mengalokasikan bed ini akan langsung memperbarui status di SIMRS Core menjadi <strong>Occupied</strong> dan mengaitkannya dengan nomor rekam medis pasien.
                </div>
            </div>

            {{-- Modal Footer --}}
            <div style="padding: 16px 24px; border-top: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                <button onclick="closeAssignModal()" class="btn-outline-slate">Batal</button>
                <button onclick="submitAssignBed()" id="btnSubmitAssign" class="btn-success-green" style="padding: 9px 18px;">
                    Konfirmasi Alokasi Bed
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let activeSelectedBed = null;

    function openAssignModal(bed) {
        activeSelectedBed = bed;
        document.getElementById('modalBedCode').innerText = bed.bed_code;
        document.getElementById('modalBedRoom').innerText = bed.room_name + ' (' + bed.floor + ')';
        document.getElementById('modalBedClass').innerText = bed.room_class;
        document.getElementById('modalBedRate').innerText = 'Rp ' + Number(bed.daily_rate).toLocaleString('id-ID') + ' / malam';

        const modal = document.getElementById('assignBedModal');
        modal.style.display = 'flex';
    }

    function closeAssignModal() {
        document.getElementById('assignBedModal').style.display = 'none';
        activeSelectedBed = null;
    }

    async function submitAssignBed() {
        const patientSelect = document.getElementById('modalSelectPatient');
        if (!patientSelect || !patientSelect.value) {
            window.showToast('Silakan pilih pasien yang akan dialokasikan bed ini.', 'warning');
            return;
        }

        if (!activeSelectedBed) {
            window.showToast('Data bed tidak valid.', 'error');
            return;
        }

        const regId = patientSelect.value;
        const btn = document.getElementById('btnSubmitAssign');
        if (btn) btn.disabled = true;

        try {
            const res = await fetch(`/dashboard/api/registration/${regId}/assign-bed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    bed_id: activeSelectedBed.id,
                    bed_code: activeSelectedBed.bed_code,
                    room_name: activeSelectedBed.room_name,
                    room_class: activeSelectedBed.room_class
                })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                window.showToast(data.message, 'success');
                closeAssignModal();
                setTimeout(() => window.location.reload(), 900);
            } else {
                window.showToast(data.message || 'Gagal mengalokasikan bed.', 'error');
            }
        } catch(err) {
            window.showToast('Terjadi kesalahan jaringan.', 'error');
        } finally {
            if (btn) btn.disabled = false;
        }
    }
</script>
@endpush
