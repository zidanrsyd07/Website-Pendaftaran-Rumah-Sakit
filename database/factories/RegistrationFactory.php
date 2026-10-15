<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceType = fake()->randomElement(['rawat_jalan', 'rawat_inap']);
        $polyclinics = ['Poli Umum', 'Poli Gigi', 'Poli Anak', 'Poli Ibu Hamil', 'Poli Obgyn'];
        $doctors = [
            'Poli Umum' => ['dr. Ahmad Fauzi, Sp.PD', 'dr. Siti Rahmawati'],
            'Poli Gigi' => ['drg. Maria Angelina', 'drg. Budi Santoso, Sp.Ort'],
            'Poli Anak' => ['dr. Dewi Kartika, Sp.A', 'dr. Rizky Pratama, Sp.A'],
            'Poli Ibu Hamil' => ['dr. Indah Permatasari, Sp.OG'],
            'Poli Obgyn' => ['dr. Hendra Wijaya, Sp.OG'],
        ];
        $roomClasses = ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3'];

        $polyclinic = fake()->randomElement($polyclinics);
        $doctorName = fake()->randomElement($doctors[$polyclinic]);

        return [
            'registration_number' => Registration::generateRegistrationNumber(),
            'patient_name' => fake()->randomElement([
                'Ahmad Fauzi', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari',
                'Rizky Pratama', 'Rina Wati', 'Hendra Wijaya', 'Nuraini Putri',
                'Dimas Ardianto', 'Sari Rahayu', 'Agus Setiawan', 'Linda Permata',
                'Bambang Hermanto', 'Yuliana Sari', 'Eko Prasetyo', 'Sri Wahyuni',
                'Rudi Hartono', 'Fitri Handayani', 'Joko Widodo', 'Ani Susilawati',
            ]),
            'nik' => '32'.fake()->numerify('##############'),
            'phone' => '08'.fake()->numerify('##########'),
            'service_type' => $serviceType,
            'polyclinic' => $polyclinic,
            'doctor_name' => $doctorName,
            'patient_type' => fake()->randomElement(['umum', 'bpjs', 'asuransi']),
            'room_class' => $serviceType === 'rawat_inap' ? fake()->randomElement($roomClasses) : null,
            'status' => fake()->randomElement(['pending', 'verified', 'in_progress', 'completed', 'cancelled']),
            'visit_date' => today(),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Mark as pending status.
     */
    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    /**
     * Mark as verified status.
     */
    public function verified(): static
    {
        return $this->state(fn () => ['status' => 'verified']);
    }

    /**
     * Mark as in_progress status.
     */
    public function inProgress(): static
    {
        return $this->state(fn () => ['status' => 'in_progress']);
    }

    /**
     * Mark as completed status.
     */
    public function completed(): static
    {
        return $this->state(fn () => ['status' => 'completed']);
    }

    /**
     * Set as outpatient (rawat jalan).
     */
    public function outpatient(): static
    {
        return $this->state(fn () => [
            'service_type' => 'rawat_jalan',
            'room_class' => null,
        ]);
    }

    /**
     * Set as inpatient (rawat inap).
     */
    public function inpatient(): static
    {
        return $this->state(fn () => [
            'service_type' => 'rawat_inap',
            'room_class' => fake()->randomElement(['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3']),
        ]);
    }
}
