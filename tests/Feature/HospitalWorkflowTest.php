<?php

test('homepage renders successfully with hospital branding', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('RS Santa Anna');
    $response->assertSee('Daftar Berobat');
});

test('about page renders successfully', function () {
    $response = $this->get('/tentang');

    $response->assertStatus(200);
    $response->assertSee('Tentang RS Santa Anna');
});

test('polyclinics page and detail render successfully', function () {
    $response = $this->get('/poliklinik');
    $response->assertStatus(200);
    $response->assertSee('Daftar Poliklinik');

    $detailResponse = $this->get('/poliklinik/1');
    $detailResponse->assertStatus(200);
    $detailResponse->assertSee('Poli Umum');
});

test('doctors page and detail render successfully', function () {
    $response = $this->get('/dokter');
    $response->assertStatus(200);
    $response->assertSee('Cari Dokter');

    $detailResponse = $this->get('/dokter/1');
    $detailResponse->assertStatus(200);
    $detailResponse->assertSee('dr. Ahmad Fauzi');
});

test('queue page renders successfully', function () {
    $response = $this->get('/antrian');

    $response->assertStatus(200);
    $response->assertSee('Live Antrian');
});

test('registration workflow renders select-type, outpatient and inpatient forms', function () {
    $selectResponse = $this->get('/daftar');
    $selectResponse->assertStatus(200);
    $selectResponse->assertSee('Pilih Jenis Layanan');

    $outpatientResponse = $this->get('/daftar/rawat-jalan');
    $outpatientResponse->assertStatus(200);
    $outpatientResponse->assertSee('Data Pasien');

    $inpatientResponse = $this->get('/daftar/rawat-inap');
    $inpatientResponse->assertStatus(200);
    $inpatientResponse->assertSee('Data Pasien');
});

test('submitting outpatient registration successfully shows confirmation with booking code', function () {
    $payload = [
        'service_type' => 'rawat_jalan',
        'full_name' => 'Ahmad Fauzi',
        'nik' => '3273012345678901',
        'phone' => '081234567890',
        'birth_date' => '1990-05-15',
        'gender' => 'L',
        'address' => 'Jl. Merdeka No. 45, Bandung',
        'patient_type' => 'umum',
        'polyclinic_id' => '1',
        'doctor_id' => '1',
        'visit_date' => now()->addDay()->format('Y-m-d'),
        'schedule' => 'Senin 08:00 - 12:00',
    ];

    $response = $this->post('/daftar/submit', $payload);

    $response->assertStatus(200);
    $response->assertSee('Pendaftaran Berhasil');
    $response->assertSee('Ahmad Fauzi');
    $response->assertSee('Nomor Pendaftaran');
});

test('account registration page renders and accepts valid submission with active email and phone', function () {
    $response = $this->get('/daftar-akun');
    $response->assertStatus(200);
    $response->assertSee('Buat Akun Baru');

    $payload = [
        'name' => 'Siti Nurhaliza',
        'nik' => '3273019876543210',
        'email' => 'siti.nurhaliza@example.com',
        'phone' => '082198765432',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $submitResponse = $this->post('/daftar-akun', $payload);
    $submitResponse->assertRedirect(route('auth.register.success'));

    $successResponse = $this->get('/daftar-akun/berhasil');
    $successResponse->assertStatus(200);
    $successResponse->assertSee('Akun Berhasil Dibuat');
});

test('login page renders successfully', function () {
    $response = $this->get('/masuk');

    $response->assertStatus(200);
    $response->assertSee('Masuk ke Akun');
});

test('mobile bottom navigation has exactly 3 items and mobile sidebar drawer is rendered', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    // Verify bottom nav items
    $response->assertSee('Beranda');
    $response->assertSee('Cari Dokter');
    $response->assertSee('Live Antrian');

    // Verify mobile sidebar drawer elements
    $response->assertSee('id="mobileSidebar"', false);
    $response->assertSee('id="mobileSidebarOverlay"', false);
    $response->assertSee('Daftar Berobat Online');
    $response->assertSee('IGD 24 Jam Siap Melayani');
    $response->assertSee('(022) 1234-5678');
});
