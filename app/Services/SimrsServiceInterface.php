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

    // Inpatient Rooms & Beds (SIMRS Core)
    public function getRooms(array $filters = []): array;

    public function getReadyBeds(?string $class = null): array;

    public function getBedStatistics(): array;

    public function assignBed(int $bedId, array $patientData): array;
}
