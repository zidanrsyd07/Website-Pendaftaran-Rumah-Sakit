@extends('layouts.app')

@section('title', 'RS Santa Anna - Rumah Sakit Terpercaya')
@section('description', 'Website resmi RS Santa Anna. Layanan kesehatan terbaik dengan fasilitas modern dan tenaga medis profesional.')

@section('content')
    {{-- ════════════════════════════════════════════════
       HERO: Split layout (text left, image right)
       ════════════════════════════════════════════════ --}}
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-label">Selamat Datang di RS Santa Anna</div>
                <h1>Pelayanan<br>Terbaik.<br><span>Setiap Saat.</span></h1>
                <p class="hero-desc">Pelayanan kesehatan berkualitas dengan teknologi modern dan dokter berpengalaman. Kesehatan Anda adalah prioritas utama kami.</p>
                <div class="hero-buttons">
                    <a href="{{ route('registration.select-type') }}" class="btn btn-primary btn-lg">
                        Daftar Berobat
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="{{ route('polyclinics.index') }}" class="btn btn-white btn-lg">
                        Layanan Kami
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="/images/hospital-exterior.jpg" alt="Gedung RS Santa Anna" loading="eager">
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       FEATURE STRIP: 4 value props
       ════════════════════════════════════════════════ --}}
    <section class="feature-strip">
        <div class="container">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="feature-text">
                    <h4>IGD 24 Jam</h4>
                    <p>Layanan gawat darurat siap 24 jam setiap hari.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    @include('components.icon', ['name' => 'stethoscope', 'size' => 22])
                </div>
                <div class="feature-text">
                    <h4>Dokter Spesialis</h4>
                    <p>50+ dokter spesialis berpengalaman di berbagai bidang.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
                </div>
                <div class="feature-text">
                    <h4>Teknologi Modern</h4>
                    <p>Peralatan medis canggih untuk diagnosis akurat.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <div class="feature-text">
                    <h4>Pelayanan Terpercaya</h4>
                    <p>40+ tahun pengalaman melayani masyarakat.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       SPECIALTIES: Poliklinik Cards
       ════════════════════════════════════════════════ --}}
    <section class="section">
        <div class="container">
            <div class="section-header row">
                <div>
                    <div class="section-label">Layanan Kami</div>
                    <h2>Poliklinik Spesialis<br>untuk Kebutuhan Anda</h2>
                </div>
                <a href="{{ route('polyclinics.index') }}" class="view-all">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="specialty-grid">
                @foreach($polyclinics as $poli)
                    <a href="{{ route('polyclinics.show', $poli['id']) }}" class="specialty-card">
                        <div class="spec-icon">
                            @include('components.icon', ['name' => $poli['icon'], 'size' => 24])
                        </div>
                        <h3>{{ $poli['name'] }}</h3>
                        <p>{{ Str::limit($poli['description'], 60) }}</p>
                        <span class="card-link">
                            Selengkapnya
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       ABOUT SPLIT: Image + Text + Stats
       ════════════════════════════════════════════════ --}}
    <section class="section bg-gray">
        <div class="container">
            <div class="about-split">
                <div class="about-image">
                    <img src="/images/medical-team.jpg" alt="Tim Medis RS Santa Anna" loading="lazy">
                </div>
                <div class="about-text">
                    <div class="section-label">Tentang RS Santa Anna</div>
                    <h2>Tangan Terampil.<br>Hati yang Peduli.</h2>
                    <p>RS Santa Anna berkomitmen memberikan pelayanan kesehatan berkualitas dengan pendekatan yang mengutamakan pasien. Tim medis berpengalaman dan infrastruktur modern memastikan hasil terbaik untuk Anda dan keluarga.</p>

                    <div class="stats-row">
                        <div class="stat-box">
                            <div class="stat-number">20+</div>
                            <div class="stat-label">Tahun Melayani</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Dokter Spesialis</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">15+</div>
                            <div class="stat-label">Poliklinik</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">15K+</div>
                            <div class="stat-label">Pasien / Bulan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       FACILITY INFO CARDS
       ════════════════════════════════════════════════ --}}
    <section class="section">
        <div class="container">
            <div class="section-header row">
                <div>
                    <div class="section-label">Fasilitas</div>
                    <h2>Kenali RS Santa Anna</h2>
                </div>
                <a href="{{ route('about') }}" class="view-all">
                    Selengkapnya
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="info-cards">
                <div class="info-card">
                    <img src="/images/hospital-lobby.jpg" alt="Lobby RS Santa Anna" loading="lazy">
                    <div class="info-card-body">
                        <h3>Fasilitas Modern & Nyaman</h3>
                        <p>Dilengkapi lobby luas, ruang tunggu nyaman, dan area parkir yang memadai untuk kenyamanan pasien dan keluarga.</p>
                    </div>
                </div>
                <div class="info-card">
                    <img src="/images/doctor-consultation.jpg" alt="Konsultasi dokter" loading="lazy">
                    <div class="info-card-body">
                        <h3>Dokter Berpengalaman</h3>
                        <p>Tim dokter spesialis berpengalaman siap memberikan konsultasi dan penanganan medis terbaik dengan pendekatan ramah.</p>
                    </div>
                </div>
                <div class="info-card">
                    <img src="/images/hospital-exterior.jpg" alt="Gedung RS Santa Anna" loading="lazy">
                    <div class="info-card-body">
                        <h3>Lokasi Strategis</h3>
                        <p>Terletak di pusat kota Bandung dengan akses mudah dari berbagai penjuru dan didukung area parkir luas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       TESTIMONIALS
       ════════════════════════════════════════════════ --}}
    <section class="section bg-gray">
        <div class="container">
            <div class="section-header row">
                <div>
                    <div class="section-label">Testimoni Pasien</div>
                    <h2>Dipercaya Ribuan Pasien</h2>
                </div>
                <a href="#" class="view-all">Lihat Semua Testimoni →</a>
            </div>

            <div class="testimonial-scroll">
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p>"Pelayanan dokter dan staf sangat ramah dan profesional. Proses pendaftaran mudah, tidak perlu menunggu lama. Sangat merekomendasikan RS Santa Anna."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">S</div>
                        <div>
                            <div class="testimonial-name">Sari Dewi</div>
                            <div class="testimonial-role">Pasien Poliklinik</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p>"Fasilitas rumah sakit sangat bersih dan modern. Ruang rawat inap nyaman. Tim medis memberikan penjelasan yang detail tentang kondisi kesehatan saya."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">B</div>
                        <div>
                            <div class="testimonial-name">Budi Hartono</div>
                            <div class="testimonial-role">Pasien Rawat Inap</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-stars">★★★★★</div>
                    <p>"Dari diagnosa hingga pemulihan, seluruh tim sangat supportif dan profesional. Anak saya ditangani dengan sangat baik. Terima kasih RS Santa Anna!"</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">R</div>
                        <div>
                            <div class="testimonial-name">Rina Susanti</div>
                            <div class="testimonial-role">Orang Tua Pasien</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       CTA BANNER
       ════════════════════════════════════════════════ --}}
    <section class="cta-banner">
        <div class="container">
            <div>
                <h2>Daftar Berobat Sekarang</h2>
                <p>Langkah pertama menuju kesehatan yang lebih baik.</p>
            </div>
            <a href="{{ route('registration.select-type') }}" class="btn btn-white btn-lg">
                Daftar Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════
       DEV NOTICE
       ════════════════════════════════════════════════ --}}
    <div class="notice-banner info" style="margin: 16px;">
        Website ini masih dalam tahap pengembangan. Data yang ditampilkan merupakan data contoh dan belum terhubung ke sistem SIMRS RS Santa Anna.
    </div>
@endsection
