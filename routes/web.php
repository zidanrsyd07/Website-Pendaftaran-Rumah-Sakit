<?php

use App\Http\Controllers\AuthController;
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

// API endpoints (untuk integrasi SIMRS nantinya)
Route::prefix('api')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('api.doctors');
    Route::get('/polyclinics', [PolyclinicController::class, 'index'])->name('api.polyclinics');
});
