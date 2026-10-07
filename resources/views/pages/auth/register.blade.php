@extends('layouts.app')

@section('title', 'Daftar Akun - RS Santa Anna')
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
            <h2>Buat Akun Baru</h2>
            <p>Daftar untuk akses layanan RS Santa Anna</p>
        </div>

        @if($errors->any())
            <div class="notice-banner" style="margin: 0 0 16px;">
                <strong>Mohon perbaiki kesalahan berikut:</strong>
                <ul style="margin: 4px 0 0 16px; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.register.submit') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="name" class="form-input @error('name') error @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Aktif <span class="required">*</span></label>
                <input type="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" placeholder="contoh@email.com" inputmode="email" required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-help">Gunakan email aktif untuk menerima informasi penting</div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon <span class="required">*</span></label>
                <input type="tel" name="phone" class="form-input @error('phone') error @enderror" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx" inputmode="tel" required>
                @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-help">Nomor WhatsApp aktif untuk notifikasi antrian</div>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Sandi <span class="required">*</span></label>
                <input type="password" name="password" class="form-input @error('password') error @enderror" placeholder="Minimal 8 karakter" required>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Kata Sandi <span class="required">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 4px;">Daftar Akun</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('auth.login') }}">Masuk di sini</a>
        </div>
    </div>

    <a href="{{ route('home') }}" style="margin-top: 16px; font-size: 14px; color: var(--color-gray-500); text-decoration: none;">← Kembali ke Beranda</a>
</div>
@endsection
