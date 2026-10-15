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

    private array $beds;

    public function __construct()
    {
        $this->polyclinics = $this->loadPolyclinics();
        $this->doctors = $this->loadDoctors();
        $this->queues = $this->loadQueues();
        $this->beds = $this->loadBeds();
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

    public function getRooms(array $filters = []): array
    {
        $beds = collect($this->beds);

        if (! empty($filters['class'])) {
            $class = strtolower($filters['class']);
            $beds = $beds->filter(fn ($b) => strtolower($b['room_class']) === $class);
        }

        if (! empty($filters['status'])) {
            $status = strtolower($filters['status']);
            $beds = $beds->filter(fn ($b) => strtolower($b['status']) === $status);
        }

        if (! empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $beds = $beds->filter(function ($b) use ($search) {
                return str_contains(strtolower($b['room_name']), $search) ||
                       str_contains(strtolower($b['bed_code']), $search) ||
                       str_contains(strtolower($b['room_class']), $search);
            });
        }

        return $beds->values()->all();
    }

    public function getReadyBeds(?string $class = null): array
    {
        $beds = collect($this->beds)->where('status', 'ready');

        if ($class && strtolower($class) !== 'all') {
            $classLower = strtolower($class);
            $beds = $beds->filter(fn ($b) => strtolower($b['room_class']) === $classLower);
        }

        return $beds->values()->all();
    }

    public function getBedStatistics(): array
    {
        $beds = collect($this->beds);
        $total = $beds->count();
        $ready = $beds->where('status', 'ready')->count();
        $occupied = $beds->where('status', 'occupied')->count();
        $cleaning = $beds->where('status', 'cleaning')->count();
        $maintenance = $beds->where('status', 'maintenance')->count();

        $classes = ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'Isolasi'];
        $byClass = [];

        foreach ($classes as $cls) {
            $classBeds = $beds->where('room_class', $cls);
            $byClass[$cls] = [
                'total' => $classBeds->count(),
                'ready' => $classBeds->where('status', 'ready')->count(),
                'occupied' => $classBeds->where('status', 'occupied')->count(),
                'cleaning' => $classBeds->where('status', 'cleaning')->count(),
            ];
        }

        return [
            'total_beds' => $total,
            'ready_beds' => $ready,
            'occupied_beds' => $occupied,
            'cleaning_beds' => $cleaning,
            'maintenance_beds' => $maintenance,
            'occupancy_rate' => $total > 0 ? round(($occupied / $total) * 100) : 0,
            'by_class' => $byClass,
        ];
    }

    public function assignBed(int $bedId, array $patientData): array
    {
        foreach ($this->beds as &$bed) {
            if ($bed['id'] === $bedId) {
                if ($bed['status'] !== 'ready') {
                    return [
                        'success' => false,
                        'message' => 'Bed '.$bed['bed_code'].' tidak berstatus Ready (status saat ini: '.$bed['status'].').',
                    ];
                }

                $bed['status'] = 'occupied';
                $bed['patient'] = [
                    'name' => $patientData['patient_name'] ?? 'Pasien',
                    'registration_number' => $patientData['registration_number'] ?? null,
                    'doctor_name' => $patientData['doctor_name'] ?? null,
                    'assigned_at' => now()->toIso8601String(),
                ];

                return [
                    'success' => true,
                    'message' => 'Bed '.$bed['bed_code'].' ('.$bed['room_name'].') berhasil dialokasikan ke pasien.',
                    'bed' => $bed,
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Bed tidak ditemukan di SIMRS.',
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

    private function loadBeds(): array
    {
        return [
            // === VIP (Paviliun Santa Anna - Lt. 2) ===
            [
                'id' => 1,
                'bed_code' => 'VIP-201-A',
                'room_name' => 'Paviliun Santa Anna 201',
                'room_class' => 'VIP',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 750000,
                'status' => 'ready',
                'facilities' => ['AC', 'Smart TV 43"', 'Kamar Mandi Dalam', 'Kulkas', 'Sofa Bed Penunggu', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 2,
                'bed_code' => 'VIP-202-A',
                'room_name' => 'Paviliun Santa Anna 202',
                'room_class' => 'VIP',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 750000,
                'status' => 'ready',
                'facilities' => ['AC', 'Smart TV 43"', 'Kamar Mandi Dalam', 'Kulkas', 'Sofa Bed Penunggu', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 3,
                'bed_code' => 'VIP-203-A',
                'room_name' => 'Paviliun Santa Anna 203',
                'room_class' => 'VIP',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 750000,
                'status' => 'occupied',
                'facilities' => ['AC', 'Smart TV 43"', 'Kamar Mandi Dalam', 'Kulkas', 'Sofa Bed Penunggu', 'Oksigen Central', 'Nurse Call'],
                'patient' => ['name' => 'Bambang Hermanto', 'doctor_name' => 'dr. Ahmad Fauzi, Sp.PD'],
            ],
            [
                'id' => 4,
                'bed_code' => 'VIP-204-A',
                'room_name' => 'Paviliun Santa Anna 204',
                'room_class' => 'VIP',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 750000,
                'status' => 'cleaning',
                'facilities' => ['AC', 'Smart TV 43"', 'Kamar Mandi Dalam', 'Kulkas', 'Sofa Bed Penunggu', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 5,
                'bed_code' => 'VIP-205-A',
                'room_name' => 'Paviliun Santa Anna 205',
                'room_class' => 'VIP',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 750000,
                'status' => 'ready',
                'facilities' => ['AC', 'Smart TV 43"', 'Kamar Mandi Dalam', 'Kulkas', 'Sofa Bed Penunggu', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],

            // === KELAS 1 (Paviliun Maria - Lt. 3) ===
            [
                'id' => 6,
                'bed_code' => 'K1-301-A',
                'room_name' => 'Paviliun Maria 301',
                'room_class' => 'Kelas 1',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 450000,
                'status' => 'ready',
                'facilities' => ['AC', 'TV LED 32"', 'Kamar Mandi Dalam', 'Tirai Pembatas', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 7,
                'bed_code' => 'K1-301-B',
                'room_name' => 'Paviliun Maria 301',
                'room_class' => 'Kelas 1',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 450000,
                'status' => 'occupied',
                'facilities' => ['AC', 'TV LED 32"', 'Kamar Mandi Dalam', 'Tirai Pembatas', 'Oksigen Central', 'Nurse Call'],
                'patient' => ['name' => 'Siti Nurhaliza', 'doctor_name' => 'dr. Hendra Wijaya, Sp.OG'],
            ],
            [
                'id' => 8,
                'bed_code' => 'K1-302-A',
                'room_name' => 'Paviliun Maria 302',
                'room_class' => 'Kelas 1',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 450000,
                'status' => 'ready',
                'facilities' => ['AC', 'TV LED 32"', 'Kamar Mandi Dalam', 'Tirai Pembatas', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 9,
                'bed_code' => 'K1-302-B',
                'room_name' => 'Paviliun Maria 302',
                'room_class' => 'Kelas 1',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 450000,
                'status' => 'ready',
                'facilities' => ['AC', 'TV LED 32"', 'Kamar Mandi Dalam', 'Tirai Pembatas', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 10,
                'bed_code' => 'K1-303-A',
                'room_name' => 'Paviliun Maria 303',
                'room_class' => 'Kelas 1',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 450000,
                'status' => 'ready',
                'facilities' => ['AC', 'TV LED 32"', 'Kamar Mandi Dalam', 'Tirai Pembatas', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 11,
                'bed_code' => 'K1-303-B',
                'room_name' => 'Paviliun Maria 303',
                'room_class' => 'Kelas 1',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Utama',
                'daily_rate' => 450000,
                'status' => 'cleaning',
                'facilities' => ['AC', 'TV LED 32"', 'Kamar Mandi Dalam', 'Tirai Pembatas', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],

            // === KELAS 2 (Ruang Santo Yosef - Lt. 3) ===
            [
                'id' => 12,
                'bed_code' => 'K2-304-A',
                'room_name' => 'Ruang Santo Yosef 304',
                'room_class' => 'Kelas 2',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Barat',
                'daily_rate' => 300000,
                'status' => 'ready',
                'facilities' => ['AC', 'Kamar Mandi Dalam', 'Tirai Sekat', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 13,
                'bed_code' => 'K2-304-B',
                'room_name' => 'Ruang Santo Yosef 304',
                'room_class' => 'Kelas 2',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Barat',
                'daily_rate' => 300000,
                'status' => 'occupied',
                'facilities' => ['AC', 'Kamar Mandi Dalam', 'Tirai Sekat', 'Oksigen Central', 'Nurse Call'],
                'patient' => ['name' => 'Dewi Lestari', 'doctor_name' => 'dr. Ahmad Fauzi, Sp.PD'],
            ],
            [
                'id' => 14,
                'bed_code' => 'K2-304-C',
                'room_name' => 'Ruang Santo Yosef 304',
                'room_class' => 'Kelas 2',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Barat',
                'daily_rate' => 300000,
                'status' => 'ready',
                'facilities' => ['AC', 'Kamar Mandi Dalam', 'Tirai Sekat', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 15,
                'bed_code' => 'K2-305-A',
                'room_name' => 'Ruang Santo Yosef 305',
                'room_class' => 'Kelas 2',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Barat',
                'daily_rate' => 300000,
                'status' => 'ready',
                'facilities' => ['AC', 'Kamar Mandi Dalam', 'Tirai Sekat', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 16,
                'bed_code' => 'K2-305-B',
                'room_name' => 'Ruang Santo Yosef 305',
                'room_class' => 'Kelas 2',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Barat',
                'daily_rate' => 300000,
                'status' => 'ready',
                'facilities' => ['AC', 'Kamar Mandi Dalam', 'Tirai Sekat', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 17,
                'bed_code' => 'K2-305-C',
                'room_name' => 'Ruang Santo Yosef 305',
                'room_class' => 'Kelas 2',
                'floor' => 'Lantai 3',
                'building' => 'Gedung Rawat Inap Barat',
                'daily_rate' => 300000,
                'status' => 'maintenance',
                'facilities' => ['AC', 'Kamar Mandi Dalam', 'Tirai Sekat', 'Oksigen Central', 'Nurse Call'],
                'patient' => null,
            ],

            // === KELAS 3 (Ruang Santa Elisabeth - Lt. 4) ===
            [
                'id' => 18,
                'bed_code' => 'K3-401-A',
                'room_name' => 'Ruang Santa Elisabeth 401',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'ready',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 19,
                'bed_code' => 'K3-401-B',
                'room_name' => 'Ruang Santa Elisabeth 401',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'ready',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 20,
                'bed_code' => 'K3-401-C',
                'room_name' => 'Ruang Santa Elisabeth 401',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'occupied',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => ['name' => 'Agus Setiawan', 'doctor_name' => 'dr. Dewi Kartika, Sp.A'],
            ],
            [
                'id' => 21,
                'bed_code' => 'K3-401-D',
                'room_name' => 'Ruang Santa Elisabeth 401',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'ready',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 22,
                'bed_code' => 'K3-402-A',
                'room_name' => 'Ruang Santa Elisabeth 402',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'ready',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 23,
                'bed_code' => 'K3-402-B',
                'room_name' => 'Ruang Santa Elisabeth 402',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'ready',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => null,
            ],
            [
                'id' => 24,
                'bed_code' => 'K3-402-C',
                'room_name' => 'Ruang Santa Elisabeth 402',
                'room_class' => 'Kelas 3',
                'floor' => 'Lantai 4',
                'building' => 'Gedung Rawat Inap Timur',
                'daily_rate' => 180000,
                'status' => 'cleaning',
                'facilities' => ['AC Sentral', 'Kamar Mandi Luar/Bersama', 'Tirai Sekat', 'Oksigen Tabung/Wall', 'Nurse Call'],
                'patient' => null,
            ],

            // === ICU (Unit Perawatan Intensif - Lt. 2) ===
            [
                'id' => 25,
                'bed_code' => 'ICU-01',
                'room_name' => 'ICU Sentral Bed 1',
                'room_class' => 'ICU',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Medis Sentral',
                'daily_rate' => 1500000,
                'status' => 'occupied',
                'facilities' => ['Ventilator', 'Bedside Monitor Hemodinamik', 'Defibrillator Ready', 'Syringe Pump', 'Oksigen Central Presisi'],
                'patient' => ['name' => 'Rudi Hartono', 'doctor_name' => 'dr. Ahmad Fauzi, Sp.PD'],
            ],
            [
                'id' => 26,
                'bed_code' => 'ICU-02',
                'room_name' => 'ICU Sentral Bed 2',
                'room_class' => 'ICU',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Medis Sentral',
                'daily_rate' => 1500000,
                'status' => 'ready',
                'facilities' => ['Ventilator', 'Bedside Monitor Hemodinamik', 'Defibrillator Ready', 'Syringe Pump', 'Oksigen Central Presisi'],
                'patient' => null,
            ],
            [
                'id' => 27,
                'bed_code' => 'ICU-03',
                'room_name' => 'ICU Sentral Bed 3',
                'room_class' => 'ICU',
                'floor' => 'Lantai 2',
                'building' => 'Gedung Medis Sentral',
                'daily_rate' => 1500000,
                'status' => 'ready',
                'facilities' => ['Ventilator', 'Bedside Monitor Hemodinamik', 'Defibrillator Ready', 'Syringe Pump', 'Oksigen Central Presisi'],
                'patient' => null,
            ],

            // === ISOLASI (Ruang Isolasi Khusus - Lt. 1) ===
            [
                'id' => 28,
                'bed_code' => 'ISO-101',
                'room_name' => 'Isolasi Tekanan Negatif 1',
                'room_class' => 'Isolasi',
                'floor' => 'Lantai 1',
                'building' => 'Gedung Perawatan Khusus',
                'daily_rate' => 950000,
                'status' => 'ready',
                'facilities' => ['Tekanan Negatif', 'Filter HEPA', 'Anteroom Disinfeksi', 'Kamar Mandi Dalam', 'CCTV Monitor'],
                'patient' => null,
            ],
            [
                'id' => 29,
                'bed_code' => 'ISO-102',
                'room_name' => 'Isolasi Tekanan Negatif 2',
                'room_class' => 'Isolasi',
                'floor' => 'Lantai 1',
                'building' => 'Gedung Perawatan Khusus',
                'daily_rate' => 950000,
                'status' => 'occupied',
                'facilities' => ['Tekanan Negatif', 'Filter HEPA', 'Anteroom Disinfeksi', 'Kamar Mandi Dalam', 'CCTV Monitor'],
                'patient' => ['name' => 'Fajar Nugraha', 'doctor_name' => 'dr. Ahmad Fauzi, Sp.PD'],
            ],
        ];
    }
}
