<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function view(User $user, Report $report): bool
    {
        if ($user->role === 'pelapor') {
            return $report->user_id === $user->id;
        }

        return in_array($user->role, ['relawan', 'koordinator'], true);
    }

    public function create(User $user): bool
    {
        return $user->role === 'pelapor';
    }

    public function update(User $user, Report $report): bool
    {
        return $report->user_id === $user->id && $report->status === 'baru';
    }
}
