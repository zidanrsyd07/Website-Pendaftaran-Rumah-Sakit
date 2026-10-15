<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** Available roles */
    public const ROLE_ADMIN = 'admin';

    public const ROLE_LOKET_RAWAT_JALAN = 'loket_rawat_jalan';

    public const ROLE_LOKET_RAWAT_INAP = 'loket_rawat_inap';

    public const ROLE_PASIEN = 'pasien';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isLoketRawatJalan(): bool
    {
        return $this->role === self::ROLE_LOKET_RAWAT_JALAN;
    }

    public function isLoketRawatInap(): bool
    {
        return $this->role === self::ROLE_LOKET_RAWAT_INAP;
    }

    public function isPasien(): bool
    {
        return $this->role === self::ROLE_PASIEN;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_LOKET_RAWAT_JALAN,
            self::ROLE_LOKET_RAWAT_INAP,
        ]);
    }

    /**
     * Get user role display label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_LOKET_RAWAT_JALAN => 'Petugas Loket Rawat Jalan',
            self::ROLE_LOKET_RAWAT_INAP => 'Petugas Loket Rawat Inap',
            self::ROLE_PASIEN => 'Pasien Mandiri',
            default => 'Pengguna',
        };
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the dashboard route for the user's role.
     */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'dashboard.admin',
            self::ROLE_LOKET_RAWAT_JALAN => 'dashboard.loket-rawat-jalan',
            self::ROLE_LOKET_RAWAT_INAP => 'dashboard.loket-rawat-inap',
            self::ROLE_PASIEN => 'dashboard.pasien',
            default => 'home',
        };
    }
}
