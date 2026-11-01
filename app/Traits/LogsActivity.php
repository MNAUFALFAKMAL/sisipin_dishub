<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('created', $model);
        });

        static::updated(function ($model) {
            static::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            static::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($activity, $model)
    {
        $oldValues = null;
        $newValues = null;

        $sensitiveFields = ['password', 'remember_token', 'updated_at', 'created_at'];

        if ($activity === 'updated') {
            $changed = $model->getChanges();
            $newValues = [];
            $oldValues = [];
            foreach ($changed as $key => $newValue) {
                if (in_array($key, $sensitiveFields)) {
                    continue;
                }
                $newValues[$key] = $newValue;
                $oldValues[$key] = $model->getOriginal($key);
            }
            if (empty($newValues)) {
                return;
            }

            // Model dapat memberi label aktivitas yang lebih spesifik
            // (mis. perubahan role pengguna dicatat sebagai "role_changed").
            $activity = static::activityName($activity, $model);
        } elseif ($activity === 'created') {
            $newValues = $model->getAttributes();
            foreach ($sensitiveFields as $field) {
                unset($newValues[$field]);
            }
        } elseif ($activity === 'deleted') {
            $oldValues = $model->getOriginal();
            foreach ($sensitiveFields as $field) {
                unset($oldValues[$field]);
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Tentukan label aktivitas yang dicatat. Model dapat meng-override
     * method ini untuk memberi label khusus (mis. "role_changed").
     */
    protected static function activityName(string $activity, $model): string
    {
        return $activity;
    }
}
