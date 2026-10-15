<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Seed staff accounts for dashboard access.
     */
    public function run(): void
    {
        $staffAccounts = [
            [
                'name' => 'Admin RS Santa Anna',
                'email' => 'admin@rssantaanna.id',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Petugas Loket Rawat Jalan',
                'email' => 'loket.rj@rssantaanna.id',
                'password' => bcrypt('password'),
                'role' => User::ROLE_LOKET_RAWAT_JALAN,
            ],
            [
                'name' => 'Petugas Loket Rawat Inap',
                'email' => 'loket.ri@rssantaanna.id',
                'password' => bcrypt('password'),
                'role' => User::ROLE_LOKET_RAWAT_INAP,
            ],
            [
                'name' => 'Budi Santoso (Pasien)',
                'email' => 'pasien@rssantaanna.id',
                'password' => bcrypt('password'),
                'role' => User::ROLE_PASIEN,
            ],
        ];

        foreach ($staffAccounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                $account
            );
        }
    }
}
