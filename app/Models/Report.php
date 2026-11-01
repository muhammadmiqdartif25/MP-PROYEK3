<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'user_id',
    'area_id',
    'title',
    'description',
    'incident_at',
    'address',
    'latitude',
    'longitude',
    'status',
    'forwarded_to',
    'forwarding_note',
])]
class Report extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'incident_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Area, $this>
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return HasOne<Evidence, $this>
     */
    public function evidence(): HasOne
    {
        return $this->hasOne(Evidence::class);
    }

    /**
     * @return HasMany<ReportReview, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ReportReview::class);
    }

    /**
     * @return HasMany<ReportStatusLog, $this>
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class);
    }

    /**
     * Transisi status yang sah: hanya maju satu langkah.
     *
     * @return array<string, array{next: string, roles: array<int, string>}>
     */
    public static function transitions(): array
    {
        return [
            'baru' => ['next' => 'diperiksa', 'roles' => ['relawan', 'koordinator']],
            'diperiksa' => ['next' => 'diteruskan', 'roles' => ['koordinator']],
            'diteruskan' => ['next' => 'selesai', 'roles' => ['koordinator']],
        ];
    }

    /**
     * Batasi kueri ke laporan milik satu pengguna.
     */
    public function scopeOwnedBy(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }
}
