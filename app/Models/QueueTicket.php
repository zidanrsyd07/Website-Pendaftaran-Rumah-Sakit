<?php

namespace App\Models;

use Database\Factories\QueueTicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueTicket extends Model
{
    /** @use HasFactory<QueueTicketFactory> */
    use HasFactory;

    public const STATUS_WAITING = 'waiting';

    public const STATUS_CALLED = 'called';

    public const STATUS_SERVING = 'serving';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_SKIPPED = 'skipped';

    public const STATUS_TRANSFERRED = 'transferred';

    protected $fillable = [
        'registration_id',
        'queue_number',
        'loket_name',
        'status',
        'served_by',
        'counter_number',
        'called_at',
        'served_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'called_at' => 'datetime',
            'served_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function servedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by');
    }

    /**
     * Generate the next queue number for a given loket today.
     */
    public static function generateQueueNumber(string $loketName): string
    {
        $prefix = match ($loketName) {
            'Loket Rawat Jalan' => 'A',
            'Loket Rawat Inap' => 'B',
            default => 'X',
        };

        $todayCount = static::where('loket_name', $loketName)
            ->whereDate('created_at', today())
            ->count();

        return $prefix.'-'.str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_WAITING => 'Menunggu',
            self::STATUS_CALLED => 'Dipanggil',
            self::STATUS_SERVING => 'Dilayani',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_SKIPPED => 'Dilewati',
            self::STATUS_TRANSFERRED => 'Dialihkan',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Calculate wait time in minutes from creation to being served.
     */
    public function waitTimeMinutes(): ?int
    {
        if (! $this->served_at) {
            return $this->created_at ? (int) $this->created_at->diffInMinutes(now()) : null;
        }

        return (int) $this->created_at->diffInMinutes($this->served_at);
    }
}
