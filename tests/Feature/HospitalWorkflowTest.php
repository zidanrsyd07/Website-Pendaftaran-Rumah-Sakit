<?php

use App\Models\QueueTicket;
use App\Models\Registration;
use App\Models\User;

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
        'consent' => '1',
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

test('staff admin can log in and redirect to admin dashboard', function () {
    $response = $this->post('/masuk', [
        'email' => 'admin@rssantaanna.id',
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard.admin'));
    $this->assertAuthenticated();

    $dashResponse = $this->get('/dashboard/admin');
    $dashResponse->assertStatus(200);
    $dashResponse->assertSee('Dashboard Administrator');
    $dashResponse->assertSee('Status Loket Layanan Aktif');
    $dashResponse->assertSee('Alur Pelayanan Pasien (Workflow Board)');
});

test('staff loket rawat jalan can access outpatient dashboard', function () {
    $this->post('/masuk', [
        'email' => 'loket.rj@rssantaanna.id',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response = $this->get(route('dashboard.loket-rawat-jalan'));
    $response->assertStatus(200);
    $response->assertSee('Loket Pelayanan Rawat Jalan');
    $response->assertSee('Daftar Antrean');
});

test('staff loket rawat inap can access inpatient dashboard', function () {
    $this->post('/masuk', [
        'email' => 'loket.ri@rssantaanna.id',
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response = $this->get(route('dashboard.loket-rawat-inap'));
    $response->assertStatus(200);
    $response->assertSee('Loket Pelayanan Rawat Inap');
    $response->assertSee('Ketersediaan Bed per Kelas Kamar');
    $response->assertSee('Sebaran Penjamin Pasien Rawat Inap');
});

test('queue api allows calling, completing, skipping tickets and verifying registrations by authorized staff', function () {
    $rjStaff = User::where('email', 'loket.rj@rssantaanna.id')->first();
    $this->actingAs($rjStaff);

    // Test verify outpatient registration
    $pendingReg = Registration::where('status', 'pending')->where('service_type', 'rawat_jalan')->first();
    if ($pendingReg) {
        $verifyRes = $this->postJson(route('dashboard.registration.verify', $pendingReg->id));
        $verifyRes->assertStatus(200);
        $verifyRes->assertJson(['success' => true]);
        expect($pendingReg->fresh()->status)->toBe('verified');
    }

    // Complete any existing serving tickets from seeder before calling next
    QueueTicket::where('served_by', $rjStaff->id)->where('status', 'serving')->update(['status' => 'completed']);

    // Test call next queue for Loket Rawat Jalan
    $callRes = $this->postJson(route('dashboard.queue.call-next'), [
        'loket_name' => 'Loket Rawat Jalan',
        'counter_number' => 1,
    ]);
    $callRes->assertStatus(200);
    $callRes->assertJson(['success' => true]);
    $ticketId = $callRes->json('ticket.id');

    if ($ticketId) {
        // Test complete ticket
        $completeRes = $this->postJson(route('dashboard.queue.complete', $ticketId));
        $completeRes->assertStatus(200);
        $completeRes->assertJson(['success' => true]);

        $ticket = QueueTicket::find($ticketId);
        expect($ticket->status)->toBe('completed');
    }

    // Test skip ticket
    $waitingTicket = QueueTicket::where('status', 'waiting')->where('loket_name', 'Loket Rawat Jalan')->first();
    if ($waitingTicket) {
        $skipRes = $this->postJson(route('dashboard.queue.skip', $waitingTicket->id));
        $skipRes->assertStatus(200);
        $skipRes->assertJson(['success' => true]);
        expect($waitingTicket->fresh()->status)->toBe('skipped');
    }
});

test('dashboard sidebars do not contain cross-role switcher navigation links', function () {
    $admin = User::where('email', 'admin@rssantaanna.id')->first();
    $this->actingAs($admin);

    $adminView = $this->get(route('dashboard.admin'));
    $adminView->assertStatus(200);
    // Admin must NOT see links to open Loket RJ, Loket RI, or Kamar
    $adminView->assertDontSee('href="'.route('dashboard.loket-rawat-jalan').'"', false);
    $adminView->assertDontSee('href="'.route('dashboard.loket-rawat-inap').'"', false);
    $adminView->assertDontSee('href="'.route('dashboard.kamar').'"', false);
    $adminView->assertDontSee('Buka Loket');

    $rjStaff = User::where('email', 'loket.rj@rssantaanna.id')->first();
    $this->actingAs($rjStaff);

    $rjView = $this->get(route('dashboard.loket-rawat-jalan'));
    $rjView->assertStatus(200);
    // Loket RJ must NOT see links to Admin, Loket RI, or Kamar
    $rjView->assertDontSee('href="'.route('dashboard.admin').'"', false);
    $rjView->assertDontSee('href="'.route('dashboard.loket-rawat-inap').'"', false);
    $rjView->assertDontSee('href="'.route('dashboard.kamar').'"', false);

    $riStaff = User::where('email', 'loket.ri@rssantaanna.id')->first();
    $this->actingAs($riStaff);

    $riView = $this->get(route('dashboard.loket-rawat-inap'));
    $riView->assertStatus(200);
    // Loket RI must NOT see links to Admin or Loket RJ
    $riView->assertDontSee('href="'.route('dashboard.admin').'"', false);
    $riView->assertDontSee('href="'.route('dashboard.loket-rawat-jalan').'"', false);
    $riView->assertSee(route('dashboard.kamar'));
});

test('each role is strictly isolated and cannot access other roles dashboards', function () {
    $admin = User::where('email', 'admin@rssantaanna.id')->first();
    $rjStaff = User::where('email', 'loket.rj@rssantaanna.id')->first();
    $riStaff = User::where('email', 'loket.ri@rssantaanna.id')->first();
    $pasien = User::where('email', 'pasien@rssantaanna.id')->first();

    // 1. Admin cannot access Loket RJ, Loket RI, Kamar, or Pasien
    $this->actingAs($admin);
    $this->get(route('dashboard.loket-rawat-jalan'))->assertStatus(403);
    $this->get(route('dashboard.loket-rawat-inap'))->assertStatus(403);
    $this->get(route('dashboard.kamar'))->assertStatus(403);
    $this->get(route('dashboard.pasien'))->assertStatus(403);

    // 2. Loket RJ cannot access Admin, Loket RI, Kamar, or Pasien
    $this->actingAs($rjStaff);
    $this->get(route('dashboard.admin'))->assertStatus(403);
    $this->get(route('dashboard.loket-rawat-inap'))->assertStatus(403);
    $this->get(route('dashboard.kamar'))->assertStatus(403);
    $this->get(route('dashboard.pasien'))->assertStatus(403);

    // 3. Loket RI cannot access Admin, Loket RJ, or Pasien
    $this->actingAs($riStaff);
    $this->get(route('dashboard.admin'))->assertStatus(403);
    $this->get(route('dashboard.loket-rawat-jalan'))->assertStatus(403);
    $this->get(route('dashboard.pasien'))->assertStatus(403);

    // 4. Pasien cannot access Admin, Loket RJ, Loket RI, or Kamar
    $this->actingAs($pasien);
    $this->get(route('dashboard.admin'))->assertStatus(403);
    $this->get(route('dashboard.loket-rawat-jalan'))->assertStatus(403);
    $this->get(route('dashboard.loket-rawat-inap'))->assertStatus(403);
    $this->get(route('dashboard.kamar'))->assertStatus(403);
});

test('patient can access patient portal, see active ticket, and cancel registration', function () {
    $pasien = User::where('email', 'pasien@rssantaanna.id')->first();
    $this->actingAs($pasien);

    $response = $this->get(route('dashboard.pasien'));
    $response->assertStatus(200);
    $response->assertSee('Portal Pasien');
    $response->assertSee('Selamat Datang, '.$pasien->name);
    $response->assertSee('Riwayat Pendaftaran Pasien');

    // Test patient cancelling their own pending registration
    $pendingPatientReg = Registration::where('user_id', $pasien->id)
        ->whereIn('status', ['pending', 'verified'])
        ->first();

    if ($pendingPatientReg) {
        $cancelRes = $this->post(route('dashboard.registration.cancel', $pendingPatientReg->id), [
            'reason' => 'Ada keperluan mendadak',
        ]);
        $cancelRes->assertRedirect();
        expect($pendingPatientReg->fresh()->status)->toBe('cancelled');
    }
});

test('inpatient staff can access dedicated SIMRS kamar ready view and filters', function () {
    $riStaff = User::where('email', 'loket.ri@rssantaanna.id')->first();
    $this->actingAs($riStaff);

    $response = $this->get(route('dashboard.kamar'));
    $response->assertStatus(200);
    $response->assertSee('Ketersediaan Kamar Rawat Inap');
    $response->assertSee('Live API SIMRS Terhubung');
    $response->assertSee('Kamar Ready (Siap Huni)');

    // Test filter by class
    $vipResponse = $this->get(route('dashboard.kamar', ['class' => 'VIP']));
    $vipResponse->assertStatus(200);
    $vipResponse->assertSee('VIP');
});

test('api simrs beds returns json list of ready beds', function () {
    $riStaff = User::where('email', 'loket.ri@rssantaanna.id')->first();
    $this->actingAs($riStaff);

    $response = $this->getJson(route('dashboard.api.simrs.beds'));
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'beds',
        'total_ready',
    ]);
    expect($response->json('total_ready'))->toBeGreaterThan(0);
});

test('inpatient staff can assign a ready bed to a patient via API', function () {
    $riStaff = User::where('email', 'loket.ri@rssantaanna.id')->first();
    $this->actingAs($riStaff);

    $inpatientReg = Registration::where('service_type', 'rawat_inap')->first();
    expect($inpatientReg)->not->toBeNull();

    $response = $this->postJson(route('dashboard.registration.assign-bed', $inpatientReg->id), [
        'bed_id' => 1,
        'bed_code' => 'VIP-101',
        'room_name' => 'Paviliun Santa Anna 101',
        'room_class' => 'VIP',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $fresh = $inpatientReg->fresh();
    expect($fresh->notes)->toContain('Alokasi Bed SIMRS: VIP-101');
    expect($fresh->room_class)->toBe('VIP');
});
