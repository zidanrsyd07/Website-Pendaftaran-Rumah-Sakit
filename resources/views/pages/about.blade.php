@extends('layouts.app')

@section('title', 'Tentang RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Tentang Kami', 'backUrl' => route('home')])
@endsection

@section('content')
    {{-- Hero Image --}}
    <div class="about-hero">
        <img src="/images/hospital-exterior.jpg" alt="Gedung RS Santa Anna" loading="eager">
        <div class="about-hero-overlay">
            <h1>RS Santa Anna</h1>
        </div>
    </div>

    <div class="about-content">
        <h2>Tentang RS Santa Anna</h2>
        <p>
            RS Santa Anna adalah rumah sakit swasta yang berlokasi di Kota Bandung, Jawa Barat. Didirikan pada tahun 1985, rumah sakit ini telah melayani masyarakat selama lebih dari 40 tahun dengan komitmen untuk memberikan pelayanan kesehatan berkualitas tinggi.
        </p>
        <p>
            Dengan visi menjadi rumah sakit pilihan utama masyarakat Bandung dan sekitarnya, RS Santa Anna terus berinovasi dalam meningkatkan kualitas layanan, fasilitas, dan teknologi kesehatan yang digunakan.
        </p>

        <img src="/images/hospital-lobby.jpg" alt="Lobby RS Santa Anna" loading="lazy">

        <h2>Visi & Misi</h2>
        <p>
            <strong>Visi:</strong> Menjadi rumah sakit terdepan yang memberikan pelayanan kesehatan berkualitas, terjangkau, dan berkesinambungan bagi seluruh lapisan masyarakat.
        </p>
        <p>
            <strong>Misi:</strong>
        </p>
        <ul style="padding-left: 20px; font-size: 14px; color: var(--color-gray-600); line-height: 1.8; margin-bottom: 12px;">
            <li>Memberikan pelayanan medis yang profesional dan berpusat pada pasien</li>
            <li>Mengembangkan sumber daya manusia yang kompeten dan berdedikasi</li>
            <li>Menyediakan fasilitas dan teknologi kesehatan yang modern</li>
            <li>Membangun budaya kerja yang berintegritas dan inovatif</li>
            <li>Menjalin kerjasama dengan berbagai pihak untuk peningkatan kualitas layanan</li>
        </ul>

        <img src="/images/doctor-consultation.jpg" alt="Konsultasi dokter" loading="lazy">

        <h2>Fasilitas Unggulan</h2>
        <p>RS Santa Anna dilengkapi dengan berbagai fasilitas modern untuk menunjang pelayanan kesehatan yang optimal:</p>
        <ul style="padding-left: 20px; font-size: 14px; color: var(--color-gray-600); line-height: 1.8; margin-bottom: 12px;">
            <li>Instalasi Gawat Darurat (IGD) 24 Jam</li>
            <li>Ruang Operasi dengan peralatan modern</li>
            <li>Laboratorium lengkap</li>
            <li>Radiologi & CT Scan</li>
            <li>Farmasi 24 Jam</li>
            <li>Rawat Inap VIP, Kelas 1, 2, dan 3</li>
            <li>Poliklinik Spesialis lengkap</li>
            <li>ICU & NICU</li>
            <li>Rehabilitasi Medik</li>
            <li>Ambulans 24 Jam</li>
        </ul>

        <img src="/images/medical-team.jpg" alt="Tim medis RS Santa Anna" loading="lazy">

        <h2>Tim Medis Kami</h2>
        <p>
            RS Santa Anna memiliki lebih dari 50 dokter spesialis dan 200 tenaga medis profesional yang berpengalaman di bidangnya masing-masing. Seluruh tim medis kami berkomitmen untuk memberikan pelayanan terbaik dengan pendekatan yang ramah dan penuh perhatian.
        </p>

        <h2>Jam Operasional</h2>
        <div style="background: var(--color-gray-50); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            <table style="width: 100%; font-size: 14px;">
                <tr style="border-bottom: 1px solid var(--color-gray-200);">
                    <td style="padding: 8px 0; font-weight: 600; color: var(--color-gray-700);">Senin – Jumat</td>
                    <td style="padding: 8px 0; color: var(--color-gray-600); text-align: right;">07.00 – 21.00</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-gray-200);">
                    <td style="padding: 8px 0; font-weight: 600; color: var(--color-gray-700);">Sabtu</td>
                    <td style="padding: 8px 0; color: var(--color-gray-600); text-align: right;">07.00 – 17.00</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-gray-200);">
                    <td style="padding: 8px 0; font-weight: 600; color: var(--color-gray-700);">Minggu</td>
                    <td style="padding: 8px 0; color: var(--color-gray-600); text-align: right;">08.00 – 14.00</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600; color: var(--color-success);">IGD & Farmasi</td>
                    <td style="padding: 8px 0; color: var(--color-success); font-weight: 600; text-align: right;">24 Jam</td>
                </tr>
            </table>
        </div>

        <h2>Kontak & Lokasi</h2>
        <div style="background: var(--color-gray-50); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            <p style="margin-bottom: 8px;"><strong>Alamat:</strong> Jl. Santa Anna No. 123, Kota Bandung, Jawa Barat 40112</p>
            <p style="margin-bottom: 8px;"><strong>Telepon:</strong> (022) 1234-5678</p>
            <p style="margin-bottom: 8px;"><strong>IGD:</strong> (022) 1234-5679</p>
            <p style="margin-bottom: 8px;"><strong>WhatsApp:</strong> 0812-3456-7890</p>
            <p><strong>Email:</strong> info@rssantaanna.id</p>
        </div>

        <div style="padding-bottom: 16px;">
            <a href="{{ route('registration.select-type') }}" class="btn btn-primary btn-block btn-lg">
                Daftar Berobat Sekarang
            </a>
        </div>
    </div>
@endsection
