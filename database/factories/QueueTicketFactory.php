<?php

namespace Database\Factories;

use App\Models\QueueTicket;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QueueTicket>
 */
class QueueTicketFactory extends Factory
{
    protected $model = QueueTicket::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'queue_number' => 'A-'.str_pad(fake()->numberBetween(1, 99), 3, '0', STR_PAD_LEFT),
            'loket_name' => 'Loket Rawat Jalan',
            'status' => fake()->randomElement(['waiting', 'called', 'serving', 'completed', 'skipped']),
            'served_by' => null,
            'counter_number' => null,
            'called_at' => null,
            'served_at' => null,
            'completed_at' => null,
        ];
    }

    public function waiting(): static
    {
        return $this->state(fn () => ['status' => 'waiting']);
    }

    public function called(): static
    {
        return $this->state(fn () => [
            'status' => 'called',
            'called_at' => now()->subMinutes(fake()->numberBetween(1, 5)),
        ]);
    }

    public function serving(): static
    {
        return $this->state(fn () => [
            'status' => 'serving',
            'called_at' => now()->subMinutes(fake()->numberBetween(10, 30)),
            'served_at' => now()->subMinutes(fake()->numberBetween(1, 10)),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'called_at' => now()->subMinutes(fake()->numberBetween(30, 120)),
            'served_at' => now()->subMinutes(fake()->numberBetween(15, 60)),
            'completed_at' => now()->subMinutes(fake()->numberBetween(1, 15)),
        ]);
    }

    public function forOutpatient(): static
    {
        return $this->state(fn () => [
            'loket_name' => 'Loket Rawat Jalan',
            'queue_number' => 'A-'.str_pad(fake()->numberBetween(1, 99), 3, '0', STR_PAD_LEFT),
        ]);
    }

    public function forInpatient(): static
    {
        return $this->state(fn () => [
            'loket_name' => 'Loket Rawat Inap',
            'queue_number' => 'B-'.str_pad(fake()->numberBetween(1, 99), 3, '0', STR_PAD_LEFT),
        ]);
    }
}
