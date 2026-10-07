<?php

namespace App\Services;

/**
 * Contract untuk semua service SIMRS.
 * Implementasi mock menggunakan data dummy.
 * Ketika SIMRS API tersedia, buat implementasi baru
 * yang mengimplementasikan interface ini.
 */
interface SimrsServiceInterface
{
    // Polyclinics
    public function getPolyclinics(): array;

    public function getPolyclinic(int $id): ?array;

    // Doctors
    public function getDoctors(array $filters = []): array;

    public function getDoctor(int $id): ?array;

    public function getDoctorsByPolyclinic(int $polyclinicId): array;

    // Registration
    public function submitRegistration(array $data): array;

    // Queue
    public function getQueueStatus(int $polyclinicId, ?int $doctorId = null): array;

    public function getQueueByPatient(string $registrationNumber): ?array;
}
