<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthenticationActivity
{
    /**
     * Catat aktivitas login/logout pengguna ke tabel audit_logs.
     */
    public function handle(Login|Logout $event): void
    {
        $user = $event->user;

        if (! $user) {
            return;
        }

        $activity = $event instanceof Login ? 'login' : 'logout';

        AuditLog::create([
            'user_id' => $user->getAuthIdentifier(),
            'activity' => $activity,
            'model_type' => $user::class,
            'model_id' => $user->getAuthIdentifier(),
            'old_values' => null,
            'new_values' => $activity === 'login' ? ['email' => $user->email] : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
