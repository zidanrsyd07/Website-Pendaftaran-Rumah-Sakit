<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PolyclinicController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Home / Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tentang RS
Route::get('/tentang', function () {
    return view('pages.about');
})->name('about');

// Auth
Route::get('/masuk', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/masuk', [AuthController::class, 'login'])->name('auth.login.submit');
Route::post('/keluar', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/daftar-akun', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/daftar-akun', [AuthController::class, 'register'])->name('auth.register.submit');
Route::get('/daftar-akun/berhasil', [AuthController::class, 'registerSuccess'])->name('auth.register.success');

// Poliklinik
Route::get('/poliklinik', [PolyclinicController::class, 'index'])->name('polyclinics.index');
Route::get('/poliklinik/{id}', [PolyclinicController::class, 'show'])->name('polyclinics.show');

// Dokter
Route::get('/dokter', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/dokter/{id}', [DoctorController::class, 'show'])->name('doctors.show');

// Pendaftaran Berobat
Route::get('/daftar', [RegistrationController::class, 'selectType'])->name('registration.select-type');
Route::get('/daftar/rawat-jalan', [RegistrationController::class, 'outpatientForm'])->name('registration.outpatient');
Route::get('/daftar/rawat-inap', [RegistrationController::class, 'inpatientForm'])->name('registration.inpatient');
Route::post('/daftar/submit', [RegistrationController::class, 'submit'])->name('registration.submit');

// Antrian
Route::get('/antrian', [QueueController::class, 'index'])->name('queue.index');

// ═══════════════════════════════════════════════════════
// Dashboard Routes (Protected by auth + role middleware)
// ═══════════════════════════════════════════════════════

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    // Admin Dashboard (STRICT: only role:admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });

    // Loket Rawat Jalan Dashboard (STRICT: only role:loket_rawat_jalan)
    Route::middleware(['role:loket_rawat_jalan'])->group(function () {
        Route::get('/loket-rawat-jalan', [DashboardController::class, 'loketRawatJalan'])->name('dashboard.loket-rawat-jalan');
    });

    // Loket Rawat Inap & SIMRS Kamar Dashboard (STRICT: only role:loket_rawat_inap)
    Route::middleware(['role:loket_rawat_inap'])->group(function () {
        Route::get('/loket-rawat-inap', [DashboardController::class, 'loketRawatInap'])->name('dashboard.loket-rawat-inap');
        Route::get('/kamar', [DashboardController::class, 'kamarReady'])->name('dashboard.kamar');
    });

    // Patient Portal Dashboard (STRICT: only role:pasien)
    Route::middleware(['role:pasien'])->group(function () {
        Route::get('/pasien', [DashboardController::class, 'pasien'])->name('dashboard.pasien');
    });

    // Cancel Registration (Patient cancelling own registration, or Admin override)
    Route::middleware(['role:pasien,admin'])->group(function () {
        Route::post('/registration/{registrationId}/cancel', [DashboardController::class, 'cancelRegistration'])->name('dashboard.registration.cancel');
    });

    // Queue & SIMRS Action API Routes (strictly authorized counter staff)
    Route::middleware(['role:loket_rawat_jalan,loket_rawat_inap'])->prefix('api')->group(function () {
        Route::post('/queue/call-next', [DashboardController::class, 'callNext'])->name('dashboard.queue.call-next');
        Route::post('/queue/{ticketId}/complete', [DashboardController::class, 'completeTicket'])->name('dashboard.queue.complete');
        Route::post('/queue/{ticketId}/skip', [DashboardController::class, 'skipTicket'])->name('dashboard.queue.skip');
        Route::post('/registration/{registrationId}/verify', [DashboardController::class, 'verifyRegistration'])->name('dashboard.registration.verify');
        Route::get('/simrs/beds', [DashboardController::class, 'apiSimrsBeds'])->name('dashboard.api.simrs.beds');
        Route::post('/registration/{registrationId}/assign-bed', [DashboardController::class, 'assignBed'])->name('dashboard.registration.assign-bed');
    });
});

// API endpoints (untuk integrasi SIMRS nantinya)
Route::prefix('api')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('api.doctors');
    Route::get('/polyclinics', [PolyclinicController::class, 'index'])->name('api.polyclinics');
});
