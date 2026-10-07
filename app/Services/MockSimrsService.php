<?php

namespace App\Services;

/**
 * Mock implementation dari SIMRS Service.
 * Menggunakan data dummy untuk pengembangan.
 * Akan diganti dengan implementasi API ketika SIMRS tersedia.
 */
class MockSimrsService implements SimrsServiceInterface
{
    private array $polyclinics;

    private array $doctors;

    private array $queues;

    public function __construct()
    {
        $this->polyclinics = $this->loadPolyclinics();
        $this->doctors = $this->loadDoctors();
        $this->queues = $this->loadQueues();
    }

    public function getPolyclinics(): array
    {
        return $this->polyclinics;
    }

    public function getPolyclinic(int $id): ?array
    {
        return collect($this->polyclinics)->firstWhere('id', $id);
    }

    public function getDoctors(array $filters = []): array
    {
        $doctors = collect($this->doctors);

        if (! empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $doctors = $doctors->filter(function ($doctor) use ($search) {
                return str_contains(strtolower($doctor['name']), $search);
            });
        }

        if (! empty($filters['polyclinic_id'])) {
            $doctors = $doctors->where('polyclinic_id', (int) $filters['polyclinic_id']);
        }

        if (! empty($filters['day'])) {
            $day = strtolower($filters['day']);
            $doctors = $doctors->filter(function ($doctor) use ($day) {
                foreach ($doctor['schedules'] as $schedule) {
                    if (str_contains(strtolower($schedule['day']), $day)) {
                        return true;
                    }
                }

                return false;
            });
        }

        return $doctors->values()->all();
    }

    public function getDoctor(int $id): ?array
    {
        $doctor = collect($this->doctors)->firstWhere('id', $id);

        if ($doctor) {
            $doctor['polyclinic'] = $this->getPolyclinic($doctor['polyclinic_id']);
        }

        return $doctor;
    }

    public function getDoctorsByPolyclinic(int $polyclinicId): array
    {
        return collect($this->doctors)
            ->where('polyclinic_id', $polyclinicId)
            ->values()
            ->all();
    }

    public function submitRegistration(array $data): array
    {
        // Simulasi pendaftaran berhasil
        $registrationNumber = 'REG-'.strtoupper(substr(md5(uniqid()), 0, 8));
        $queueNumber = 'A-'.str_pad(rand(1, 50), 3, '0', STR_PAD_LEFT);

        return [
            'success' => true,
            'registration_number' => $registrationNumber,
            'queue_number' => $queueNumber,
            'data' => $data,
            'message' => 'Pendaftaran berhasil disimpan. Data ini merupakan data sementara dan belum terhubung ke sistem SIMRS.',
        ];
    }

    public function getQueueStatus(int $polyclinicId, ?int $doctorId = null): array
    {
        $queues = collect($this->queues);

        if ($polyclinicId) {
            $queues = $queues->where('polyclinic_id', $polyclinicId);
        }

        if ($doctorId) {
            $queues = $queues->where('doctor_id', $doctorId);
        }

        return $queues->map(function ($queue) {
            $queue['polyclinic'] = $this->getPolyclinic($queue['polyclinic_id']);
            $queue['doctor'] = $this->getDoctor($queue['doctor_id']);

            return $queue;
        })->values()->all();
    }

    public function getQueueByPatient(string $registrationNumber): ?array
    {
        // Simulasi pencarian antrian berdasarkan nomor pendaftaran
        return [
            'registration_number' => $registrationNumber,
            'queue_number' => 'A-027',
            'current_number' => 'A-023',
            'waiting_count' => 4,
            'status' => 'Menunggu',
            'polyclinic' => $this->getPolyclinic(1),
            'doctor' => $this->getDoctor(1),
        ];
    }

    // ──────────────────────────────────────────
    // Data Dummy
    // ──────────────────────────────────────────

    private function loadPolyclinics(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Poli Umum',
                'slug' => 'poli-umum',
                'description' => 'Layanan pemeriksaan kesehatan umum untuk semua usia',
                'icon' => 'stethoscope',
            ],
            [
                'id' => 2,
                'name' => 'Poli Gigi',
                'slug' => 'poli-gigi',
                'description' => 'Layanan perawatan dan pemeriksaan kesehatan gigi dan mulut',
                'icon' => 'tooth',
            ],
            [
                'id' => 3,
                'name' => 'Poli Anak',
                'slug' => 'poli-anak',
                'description' => 'Layanan kesehatan khusus untuk bayi, anak, dan remaja',
                'icon' => 'baby',
            ],
            [
                'id' => 4,
                'name' => 'Poli Ibu Hamil',
                'slug' => 'poli-ibu-hamil',
                'description' => 'Layanan pemeriksaan kehamilan dan konsultasi prenatal',
                'icon' => 'pregnant',
            ],
            [
                'id' => 5,
                'name' => 'Poli Obgyn',
                'slug' => 'poli-obgyn',
                'description' => 'Layanan obstetri dan ginekologi untuk kesehatan reproduksi wanita',
                'icon' => 'heart-pulse',
            ],
        ];
    }

    private function loadDoctors(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'dr. Ahmad Fauzi, Sp.PD',
                'polyclinic_id' => 1,
                'specialization' => 'Dokter Umum',
                'photo' => null,
                'bio' => 'Berpengalaman lebih dari 10 tahun dalam pelayanan kesehatan umum.',
                'schedules' => [
                    ['day' => 'Senin', 'time' => '08.00 - 12.00'],
                    ['day' => 'Selasa', 'time' => '08.00 - 12.00'],
                    ['day' => 'Rabu', 'time' => '08.00 - 12.00'],
                    ['day' => 'Kamis', 'time' => '08.00 - 12.00'],
                    ['day' => 'Jumat', 'time' => '08.00 - 11.00'],
                ],
            ],
            [
                'id' => 2,
                'name' => 'dr. Siti Rahmawati',
                'polyclinic_id' => 1,
                'specialization' => 'Dokter Umum',
                'photo' => null,
                'bio' => 'Dokter umum dengan keahlian di bidang penyakit dalam dan kesehatan masyarakat.',
                'schedules' => [
                    ['day' => 'Senin', 'time' => '13.00 - 17.00'],
                    ['day' => 'Selasa', 'time' => '13.00 - 17.00'],
                    ['day' => 'Rabu', 'time' => '13.00 - 17.00'],
                    ['day' => 'Kamis', 'time' => '13.00 - 17.00'],
                ],
            ],
            [
                'id' => 3,
                'name' => 'drg. Maria Angelina',
                'polyclinic_id' => 2,
                'specialization' => 'Dokter Gigi',
                'photo' => null,
                'bio' => 'Spesialis perawatan gigi dengan pengalaman 8 tahun.',
                'schedules' => [
                    ['day' => 'Senin', 'time' => '09.00 - 14.00'],
                    ['day' => 'Rabu', 'time' => '09.00 - 14.00'],
                    ['day' => 'Jumat', 'time' => '09.00 - 12.00'],
                ],
            ],
            [
                'id' => 4,
                'name' => 'drg. Budi Santoso, Sp.Ort',
                'polyclinic_id' => 2,
                'specialization' => 'Dokter Gigi Spesialis Ortodonti',
                'photo' => null,
                'bio' => 'Spesialis ortodonti untuk perawatan kawat gigi dan koreksi susunan gigi.',
                'schedules' => [
                    ['day' => 'Selasa', 'time' => '10.00 - 15.00'],
                    ['day' => 'Kamis', 'time' => '10.00 - 15.00'],
                ],
            ],
            [
                'id' => 5,
                'name' => 'dr. Dewi Kartika, Sp.A',
                'polyclinic_id' => 3,
                'specialization' => 'Dokter Spesialis Anak',
                'photo' => null,
                'bio' => 'Dokter spesialis anak yang berpengalaman menangani tumbuh kembang anak.',
                'schedules' => [
                    ['day' => 'Senin', 'time' => '08.00 - 13.00'],
                    ['day' => 'Rabu', 'time' => '08.00 - 13.00'],
                    ['day' => 'Jumat', 'time' => '08.00 - 12.00'],
                ],
            ],
            [
                'id' => 6,
                'name' => 'dr. Rizky Pratama, Sp.A',
                'polyclinic_id' => 3,
                'specialization' => 'Dokter Spesialis Anak',
                'photo' => null,
                'bio' => 'Fokus pada kesehatan anak dan imunisasi dengan pendekatan yang ramah.',
                'schedules' => [
                    ['day' => 'Selasa', 'time' => '09.00 - 14.00'],
                    ['day' => 'Kamis', 'time' => '09.00 - 14.00'],
                ],
            ],
            [
                'id' => 7,
                'name' => 'dr. Indah Permatasari, Sp.OG',
                'polyclinic_id' => 4,
                'specialization' => 'Dokter Spesialis Kandungan',
                'photo' => null,
                'bio' => 'Spesialis pemeriksaan kehamilan dan persalinan dengan pengalaman 12 tahun.',
                'schedules' => [
                    ['day' => 'Senin', 'time' => '09.00 - 14.00'],
                    ['day' => 'Rabu', 'time' => '09.00 - 14.00'],
                    ['day' => 'Jumat', 'time' => '09.00 - 12.00'],
                ],
            ],
            [
                'id' => 8,
                'name' => 'dr. Hendra Wijaya, Sp.OG',
                'polyclinic_id' => 5,
                'specialization' => 'Dokter Spesialis Obgyn',
                'photo' => null,
                'bio' => 'Spesialis obstetri dan ginekologi untuk layanan kesehatan reproduksi wanita.',
                'schedules' => [
                    ['day' => 'Selasa', 'time' => '08.00 - 13.00'],
                    ['day' => 'Kamis', 'time' => '08.00 - 13.00'],
                    ['day' => 'Sabtu', 'time' => '09.00 - 12.00'],
                ],
            ],
        ];
    }

    private function loadQueues(): array
    {
        return [
            [
                'id' => 1,
                'polyclinic_id' => 1,
                'doctor_id' => 1,
                'date' => now()->format('Y-m-d'),
                'current_number' => 'A-023',
                'total_patients' => 30,
                'served_patients' => 23,
                'waiting_count' => 7,
                'status' => 'Sedang Dilayani',
            ],
            [
                'id' => 2,
                'polyclinic_id' => 1,
                'doctor_id' => 2,
                'date' => now()->format('Y-m-d'),
                'current_number' => 'B-015',
                'total_patients' => 25,
                'served_patients' => 15,
                'waiting_count' => 10,
                'status' => 'Sedang Dilayani',
            ],
            [
                'id' => 3,
                'polyclinic_id' => 2,
                'doctor_id' => 3,
                'date' => now()->format('Y-m-d'),
                'current_number' => 'C-008',
                'total_patients' => 15,
                'served_patients' => 8,
                'waiting_count' => 7,
                'status' => 'Sedang Dilayani',
            ],
            [
                'id' => 4,
                'polyclinic_id' => 3,
                'doctor_id' => 5,
                'date' => now()->format('Y-m-d'),
                'current_number' => 'D-012',
                'total_patients' => 20,
                'served_patients' => 12,
                'waiting_count' => 8,
                'status' => 'Sedang Dilayani',
            ],
            [
                'id' => 5,
                'polyclinic_id' => 4,
                'doctor_id' => 7,
                'date' => now()->format('Y-m-d'),
                'current_number' => 'E-005',
                'total_patients' => 12,
                'served_patients' => 5,
                'waiting_count' => 7,
                'status' => 'Sedang Dilayani',
            ],
            [
                'id' => 6,
                'polyclinic_id' => 5,
                'doctor_id' => 8,
                'date' => now()->format('Y-m-d'),
                'current_number' => 'F-010',
                'total_patients' => 18,
                'served_patients' => 10,
                'waiting_count' => 8,
                'status' => 'Sedang Dilayani',
            ],
        ];
    }
}
