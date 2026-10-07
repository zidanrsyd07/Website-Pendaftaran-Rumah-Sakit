<footer class="site-footer">
    <div class="footer-inner">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 28px; height: 28px; color: var(--color-primary-400);">
                    <rect width="32" height="32" rx="8" fill="currentColor"/>
                    <path d="M16 7v18M7 16h18" stroke="#fff" stroke-width="2.8" stroke-linecap="round"/>
                </svg>
                <span style="font-size: 16px; font-weight: 700; color: #fff;">RS Santa Anna</span>
            </div>
            <p style="margin-bottom: 12px;">Memberikan pelayanan kesehatan terbaik bagi masyarakat dengan fasilitas modern dan tenaga medis profesional.</p>
            <p>Jl. Santa Anna No. 123, Kota Bandung<br>Jawa Barat, Indonesia 40112</p>
        </div>
        <div>
            <h4>Layanan</h4>
            <p><a href="{{ route('polyclinics.index') }}">Poliklinik</a></p>
            <p><a href="{{ route('doctors.index') }}">Cari Dokter</a></p>
            <p><a href="{{ route('queue.index') }}">Live Antrian</a></p>
            <p><a href="{{ route('registration.select-type') }}">Daftar Berobat</a></p>
        </div>
        <div>
            <h4>Informasi</h4>
            <p><a href="{{ route('about') }}">Tentang Kami</a></p>
            <p><a href="{{ route('about') }}">Fasilitas</a></p>
            <p><a href="{{ route('about') }}">Karir</a></p>
            <p><a href="{{ route('about') }}">FAQ</a></p>
        </div>
        <div>
            <h4>Kontak</h4>
            <p>IGD 24 Jam: <strong style="color: #fff;">(022) 1234-5678</strong></p>
            <p>WhatsApp: <strong style="color: #fff;">0812-3456-7890</strong></p>
            <p>Email: <a href="mailto:info@rssantaanna.id">info@rssantaanna.id</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} RS Santa Anna. Seluruh hak cipta dilindungi.
    </div>
</footer>
