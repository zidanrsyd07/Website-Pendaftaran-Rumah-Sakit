@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - RS Santa Anna')
@section('hide-header', true)
@section('hide-bottom-nav', true)
@section('hide-footer', true)

@section('content')
<div class="auth-page">
    <div class="auth-card" style="text-align: center;">
        <div style="width: 68px; height: 68px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#065f46" width="36" height="36"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>

        <h2 style="font-size: 22px; font-weight: 700; color: var(--color-gray-900); margin-bottom: 8px;">Akun Berhasil Dibuat!</h2>
        <p style="font-size: 14px; color: var(--color-gray-600); margin-bottom: 24px; line-height: 1.6;">
            Selamat{{ session('registered_name') ? ', ' . session('registered_name') : '' }}! Akun Anda telah aktif dan terdaftar di sistem RS Santa Anna.
        </p>

        <a href="{{ route('registration.select-type') }}" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 10px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; margin-right: 6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Daftar Berobat Sekarang
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline btn-block">Kembali ke Beranda</a>
    </div>
</div>
@endsection
