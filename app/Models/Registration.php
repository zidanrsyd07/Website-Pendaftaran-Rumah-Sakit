<?php

namespace App\Models;

use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_VERIFIED = 'verified';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_SKIPPED = 'skipped';

    protected $fillable = [
        'user_id',
        'registration_number',
        'patient_name',
        'nik',
        'phone',
        'service_type',
        'polyclinic',
        'doctor_name',
        'patient_type',
        'room_class',
        'status',
        'visit_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function queueTicket(): HasOne
    {
        return $this->hasOne(QueueTicket::class);
    }

    /**
     * Generate a unique registration number for today.
     */
    public static function generateRegistrationNumber(): string
    {
        $date = now()->format('Ymd');
        $lastReg = static::where('registration_number', 'like', "REG-{$date}-%")
            ->orderByDesc('registration_number')
            ->value('registration_number');

        $nextSeq = 1;
        if ($lastReg) {
            $nextSeq = (int) substr($lastReg, -4) + 1;
        }

        return 'REG-'.$date.'-'.str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_IN_PROGRESS => 'Dalam Proses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_SKIPPED => 'Dilewati',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Scope: filter by today's visit date.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', today());
    }

    /**
     * Scope: filter by service type.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
    }
}
