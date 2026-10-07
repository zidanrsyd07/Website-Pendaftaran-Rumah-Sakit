@extends('layouts.app')

@section('title', 'Daftar Berobat - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Daftar Berobat', 'backUrl' => route('home')])
@endsection

@section('content')
    <div class="desktop-container narrow" style="padding: 28px 16px 40px;">
        {{-- Pre-selected info --}}
        @if($doctor || $polyclinic)
            <div style="margin-bottom: 24px; padding: 14px 18px; background: var(--color-primary-50); border: 1px solid var(--color-primary-100); border-radius: 12px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--color-primary-500); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <div>
                    @if($doctor)
                        <div style="font-size: 14px; font-weight: 700; color: var(--color-gray-800);">{{ $doctor['name'] }}</div>
                        <div style="font-size: 13px; color: var(--color-primary-500); font-weight: 500;">{{ $doctor['specialization'] }}</div>
                    @elseif($polyclinic)
                        <div style="font-size: 14px; font-weight: 700; color: var(--color-gray-800);">{{ $polyclinic['name'] }}</div>
                        <div style="font-size: 13px; color: var(--color-gray-500);">{{ $polyclinic['description'] }}</div>
                    @endif
                </div>
            </div>
        @endif

        <div style="margin-bottom: 24px;">
            <span style="display: inline-block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-primary-500); background: var(--color-primary-50); padding: 4px 10px; border-radius: 6px; margin-bottom: 8px;">Pendaftaran Online</span>
            <h2 style="font-size: 22px; font-weight: 700; color: var(--color-gray-900); margin: 0 0 6px;">Pilih Jenis Layanan</h2>
            <p style="font-size: 14px; color: var(--color-gray-500); margin: 0;">Silakan pilih jenis pelayanan rumah sakit yang Anda butuhkan.</p>
        </div>

        {{-- Rawat Jalan --}}
        <a href="{{ route('registration.outpatient', array_filter(['doctor_id' => request('doctor_id'), 'polyclinic_id' => request('polyclinic_id')])) }}" class="option-card" style="text-decoration: none; padding: 18px 20px; margin-bottom: 14px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--color-primary-50); color: var(--color-primary-500); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.375 12.375 0 0110.375 21c-2.31 0-4.473-.637-6.375-1.765z"/></svg>
            </div>
            <div class="option-content">
                <h4 style="font-size: 16px; font-weight: 700; color: var(--color-gray-900);">Rawat Jalan (Poliklinik)</h4>
                <p style="font-size: 13px; color: var(--color-gray-500); margin-top: 3px;">Pemeriksaan dokter spesialis, konsultasi kesehatan, & penanganan tanpa menginap.</p>
            </div>
            <div class="poli-arrow" style="color: var(--color-primary-500);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>

        {{-- Rawat Inap --}}
        <a href="{{ route('registration.inpatient', array_filter(['doctor_id' => request('doctor_id'), 'polyclinic_id' => request('polyclinic_id')])) }}" class="option-card" style="text-decoration: none; padding: 18px 20px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            </div>
            <div class="option-content">
                <h4 style="font-size: 16px; font-weight: 700; color: var(--color-gray-900);">Rawat Inap</h4>
                <p style="font-size: 13px; color: var(--color-gray-500); margin-top: 3px;">Perawatan intensif dan pemulihan dengan fasilitas kamar inap VIP, Kelas 1, 2, atau 3.</p>
            </div>
            <div class="poli-arrow" style="color: #0284c7;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            </div>
        </a>
    </div>
@endsection
