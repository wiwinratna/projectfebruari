<?php

namespace App\Policies;

use App\Models\AccessCard;
use App\Models\User;

class AccessCardPolicy
{
    public function view(User $user, AccessCard $accessCard): bool
    {
        // admin boleh semua (sesuaikan field role kamu)
        if (strtolower($user->role ?? '') === 'admin') return true;

        // pelamar: hanya miliknya
        return (int)$accessCard->user_id === (int)$user->id;
    }
}
