@extends('layouts.app')

@section('title', 'Masuk - RS Santa Anna')

@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo">
                <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: var(--color-primary-500); margin: 0 auto 12px; display: block;">
                    <rect width="36" height="36" rx="9" fill="currentColor"/>
                    <path d="M18 8v20M8 18h20" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                </svg>
                <h2>Masuk ke Akun Anda</h2>
                <p>Akses dashboard dan kelola data pendaftaran RS Santa Anna</p>
            </div>

            @if($errors->any())
                <div style="background: var(--color-danger-light); border: 1px solid #fecaca; border-radius: 10px; padding: 12px 14px; margin-bottom: 20px; font-size: 13px; color: var(--color-danger);">
                    @foreach($errors->all() as $error)
                        <p style="margin: 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>Password</span>
                    </label>
                    <input type="password" name="password" class="form-input @error('password') error @enderror" required placeholder="Masukkan password">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="checkbox-label" style="font-size: 13px;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Masuk</button>
            </form>

            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('auth.register') }}">Daftar akun</a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('home') }}" style="font-size: 13px; color: var(--color-gray-500); display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
