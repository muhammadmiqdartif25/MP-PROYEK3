<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Pelapor hanya boleh melihat laporannya sendiri.
     */
    public function view(User $user, Report $report): bool
    {
        if ($user->role === 'pelapor') {
            return $report->user_id === $user->id;
        }

        return in_array($user->role, ['relawan', 'koordinator'], true);
    }

    /**
     * Hanya pelapor yang boleh membuat laporan.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pelapor';
    }

    /**
     * BR-01: laporan hanya bisa diubah pemiliknya dan hanya saat status masih "baru".
     */
    public function update(User $user, Report $report): bool
    {
        return $report->user_id === $user->id && $report->status === 'baru';
    }
}
