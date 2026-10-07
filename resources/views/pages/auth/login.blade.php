@extends('layouts.app')

@section('title', 'Masuk - RS Santa Anna')
@section('hide-header', true)
@section('hide-bottom-nav', true)
@section('hide-footer', true)

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="48" height="48" rx="12" fill="currentColor"/>
                <path d="M24 10v28M10 24h28" stroke="#fff" stroke-width="3.5" stroke-linecap="round"/>
            </svg>
            <h2>Masuk ke Akun</h2>
            <p>Akses layanan RS Santa Anna</p>
        </div>

        <form>
            <div class="form-group">
                <label class="form-label">Email atau No. Telepon</label>
                <input type="text" class="form-input" placeholder="contoh@email.com atau 08xx" inputmode="email">
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <input type="password" class="form-input" placeholder="Masukkan kata sandi">
            </div>

            <div style="text-align: right; margin-bottom: 16px;">
                <a href="#" style="font-size: 13px; color: var(--color-primary-500); text-decoration: none;">Lupa kata sandi?</a>
            </div>

            <button type="button" class="btn btn-primary btn-block btn-lg" onclick="alert('Fitur login akan tersedia setelah integrasi dengan sistem SIMRS.')">Masuk</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('auth.register') }}">Daftar sekarang</a>
        </div>
    </div>

    <a href="{{ route('home') }}" style="margin-top: 16px; font-size: 14px; color: var(--color-gray-500); text-decoration: none;">← Kembali ke Beranda</a>
</div>
@endsection
