<?php

namespace Database\Seeders;

use App\Models\QueueTicket;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationSeeder extends Seeder
{
    /**
     * Seed realistic registration and queue ticket data for today.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        QueueTicket::truncate();
        Registration::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $names = [
            'Ahmad Fauzi', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari',
            'Rizky Pratama', 'Rina Wati', 'Hendra Wijaya', 'Nuraini Putri',
            'Dimas Ardianto', 'Sari Rahayu', 'Agus Setiawan', 'Linda Permata',
            'Bambang Hermanto', 'Yuliana Sari', 'Eko Prasetyo', 'Sri Wahyuni',
            'Rudi Hartono', 'Fitri Handayani', 'Joko Susilo', 'Ani Susilawati',
            'Teguh Prasetya', 'Maya Indah', 'Fajar Nugraha', 'Ratna Dewi',
            'Surya Darma', 'Wulandari', 'Arief Rahman', 'Kartini Sari',
            'Bagus Permadi', 'Lestari Ningrum',
        ];

        $polyclinicDoctors = [
            'Poli Umum' => ['dr. Ahmad Fauzi, Sp.PD', 'dr. Siti Rahmawati'],
            'Poli Gigi' => ['drg. Maria Angelina', 'drg. Budi Santoso, Sp.Ort'],
            'Poli Anak' => ['dr. Dewi Kartika, Sp.A', 'dr. Rizky Pratama, Sp.A'],
            'Poli Ibu Hamil' => ['dr. Indah Permatasari, Sp.OG'],
            'Poli Obgyn' => ['dr. Hendra Wijaya, Sp.OG'],
        ];

        $date = now()->format('Ymd');
        $seq = 1;

        // === RAWAT JALAN: 20 registrations ===
        $rjStatuses = [
            // 5 completed
            'completed', 'completed', 'completed', 'completed', 'completed',
            // 3 in_progress (currently being served)
            'in_progress', 'in_progress', 'in_progress',
            // 2 verified (ready to be called)
            'verified', 'verified',
            // 7 pending (waiting in queue)
            'pending', 'pending', 'pending', 'pending', 'pending', 'pending', 'pending',
            // 2 cancelled
            'cancelled', 'cancelled',
            // 1 skipped
            'skipped',
        ];

        shuffle($names);
        $nameIndex = 0;

        $rjUser = User::where('email', 'loket.rj@rssantaanna.id')->first();
        $riUser = User::where('email', 'loket.ri@rssantaanna.id')->first();
        $pasienUser = User::where('email', 'pasien@rssantaanna.id')->first();

        foreach ($rjStatuses as $i => $status) {
            $poli = array_rand($polyclinicDoctors);
            $doctor = $polyclinicDoctors[$poli][array_rand($polyclinicDoctors[$poli])];
            $regNum = 'REG-'.$date.'-'.str_pad($seq, 4, '0', STR_PAD_LEFT);
            $createdMinutesAgo = rand(20, 420);

            // Assign one active waiting registration to the test patient
            $isTestPatient = ($i === 8 && $pasienUser);
            $patientName = $isTestPatient ? 'Budi Santoso' : $names[$nameIndex % count($names)];
            $assignedUserId = $isTestPatient ? $pasienUser->id : null;

            $reg = Registration::create([
                'user_id' => $assignedUserId,
                'registration_number' => $regNum,
                'patient_name' => $patientName,
                'nik' => '32'.str_pad(rand(10000000000000, 99999999999999), 14, '0', STR_PAD_LEFT),
                'phone' => '08'.rand(100000000, 999999999),
                'service_type' => 'rawat_jalan',
                'polyclinic' => $poli,
                'doctor_name' => $doctor,
                'patient_type' => ['umum', 'bpjs', 'asuransi'][array_rand(['umum', 'bpjs', 'asuransi'])],
                'room_class' => null,
                'status' => $status,
                'visit_date' => today(),
                'notes' => null,
                'created_at' => now()->subMinutes($createdMinutesAgo),
                'updated_at' => now()->subMinutes($createdMinutesAgo),
            ]);

            // Create queue ticket
            $queueNum = 'A-'.str_pad($seq, 3, '0', STR_PAD_LEFT);
            $queueStatus = match ($status) {
                'pending' => 'waiting',
                'verified' => 'waiting',
                'in_progress' => 'serving',
                'completed' => 'completed',
                'cancelled' => 'skipped',
                'skipped' => 'skipped',
            };

            $ticket = [
                'registration_id' => $reg->id,
                'queue_number' => $queueNum,
                'loket_name' => 'Loket Rawat Jalan',
                'status' => $queueStatus,
                'served_by' => null,
                'counter_number' => null,
                'called_at' => null,
                'served_at' => null,
                'completed_at' => null,
                'created_at' => now()->subMinutes($createdMinutesAgo),
                'updated_at' => now()->subMinutes($createdMinutesAgo),
            ];

            if (in_array($queueStatus, ['serving', 'completed'])) {
                $ticket['called_at'] = now()->subMinutes($createdMinutesAgo - 5);
                $ticket['served_at'] = now()->subMinutes($createdMinutesAgo - 8);
                $ticket['served_by'] = $rjUser?->id;
                $ticket['counter_number'] = 1;
            }

            if ($queueStatus === 'completed') {
                $ticket['completed_at'] = now()->subMinutes(rand(5, $createdMinutesAgo - 10));
            }

            QueueTicket::create($ticket);

            $seq++;
            $nameIndex++;
        }

        // === RAWAT INAP: 10 registrations ===
        $riStatuses = [
            'completed', 'completed', 'completed',
            'in_progress', 'in_progress',
            'verified',
            'pending', 'pending', 'pending',
            'cancelled',
        ];

        $roomClasses = ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3'];

        foreach ($riStatuses as $i => $status) {
            $poli = array_rand($polyclinicDoctors);
            $doctor = $polyclinicDoctors[$poli][array_rand($polyclinicDoctors[$poli])];
            $regNum = 'REG-'.$date.'-'.str_pad($seq, 4, '0', STR_PAD_LEFT);
            $createdMinutesAgo = rand(20, 420);

            $isTestPatient = ($i === 0 && $pasienUser);
            $patientName = $isTestPatient ? 'Budi Santoso' : $names[$nameIndex % count($names)];
            $assignedUserId = $isTestPatient ? $pasienUser->id : null;

            $reg = Registration::create([
                'user_id' => $assignedUserId,
                'registration_number' => $regNum,
                'patient_name' => $patientName,
                'nik' => '32'.str_pad(rand(10000000000000, 99999999999999), 14, '0', STR_PAD_LEFT),
                'phone' => '08'.rand(100000000, 999999999),
                'service_type' => 'rawat_inap',
                'polyclinic' => $poli,
                'doctor_name' => $doctor,
                'patient_type' => ['umum', 'bpjs', 'asuransi'][array_rand(['umum', 'bpjs', 'asuransi'])],
                'room_class' => $roomClasses[array_rand($roomClasses)],
                'status' => $status,
                'visit_date' => today(),
                'notes' => null,
                'created_at' => now()->subMinutes($createdMinutesAgo),
                'updated_at' => now()->subMinutes($createdMinutesAgo),
            ]);

            $queueNum = 'B-'.str_pad($seq - 20, 3, '0', STR_PAD_LEFT);
            $queueStatus = match ($status) {
                'pending' => 'waiting',
                'verified' => 'waiting',
                'in_progress' => 'serving',
                'completed' => 'completed',
                'cancelled' => 'skipped',
                'skipped' => 'skipped',
            };

            $ticket = [
                'registration_id' => $reg->id,
                'queue_number' => $queueNum,
                'loket_name' => 'Loket Rawat Inap',
                'status' => $queueStatus,
                'served_by' => null,
                'counter_number' => null,
                'called_at' => null,
                'served_at' => null,
                'completed_at' => null,
                'created_at' => now()->subMinutes($createdMinutesAgo),
                'updated_at' => now()->subMinutes($createdMinutesAgo),
            ];

            if (in_array($queueStatus, ['serving', 'completed'])) {
                $ticket['called_at'] = now()->subMinutes($createdMinutesAgo - 5);
                $ticket['served_at'] = now()->subMinutes($createdMinutesAgo - 8);
                $ticket['served_by'] = $riUser?->id;
                $ticket['counter_number'] = 1;
            }

            if ($queueStatus === 'completed') {
                $ticket['completed_at'] = now()->subMinutes(rand(5, $createdMinutesAgo - 10));
            }

            QueueTicket::create($ticket);

            $seq++;
            $nameIndex++;
        }
    }
}
