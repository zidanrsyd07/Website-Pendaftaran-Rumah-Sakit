@extends('layouts.app')

@section('title', 'Daftar Poliklinik - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Daftar Poliklinik', 'backUrl' => route('home')])
@endsection

@section('content')
    <div class="desktop-container" style="padding: 16px 0;">
        <div class="poli-list">
            @forelse($polyclinics as $poli)
                <a href="{{ route('polyclinics.show', $poli['id']) }}" class="poli-card">
                    <div class="poli-icon">
                        @include('components.icon', ['name' => $poli['icon'], 'size' => 22])
                    </div>
                    <div class="poli-info">
                        <h3>{{ $poli['name'] }}</h3>
                        <p>{{ $poli['description'] }}</p>
                    </div>
                    <div class="poli-arrow">
                        @include('components.icon', ['name' => 'chevron-right', 'size' => 18])
                    </div>
                </a>
            @empty
                <div class="state-container">
                    @include('components.icon', ['name' => 'empty', 'size' => 48])
                    <h3>Tidak ada poliklinik</h3>
                    <p>Data poliklinik belum tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
