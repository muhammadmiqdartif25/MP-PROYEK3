<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    'evidence_path',
])]
class Report extends Model
{
    protected function casts(): array
    {
        return [
            'incident_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ReportReview::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ReportStatusLog::class);
    }

    public function hasEvidence(): bool
    {
        return $this->evidence_path !== null;
    }

    public static function transitions(): array
    {
        return [
            'baru' => ['next' => 'diperiksa', 'roles' => ['relawan', 'koordinator']],
            'diperiksa' => ['next' => 'diteruskan', 'roles' => ['koordinator']],
            'diteruskan' => ['next' => 'selesai', 'roles' => ['koordinator']],
        ];
    }

    public function scopeOwnedBy(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }
}
