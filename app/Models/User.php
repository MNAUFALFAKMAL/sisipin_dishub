<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'peran_id',
        'bidang_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class);
    }

    // ── Role Helper Methods ────────────────────────────────────────────────

    public function getRoleLabelAttribute(): string
    {
        return $this->peran ? str_replace('_', ' ', $this->peran->slug) : '';
    }

    public function getRoleSlugAttribute(): ?string
    {
        return $this->peran?->slug;
    }

    public function isSuperAdmin(): bool
    {
        return $this->peran?->slug === 'super_admin';
    }

    public function isKepalaDinas(): bool
    {
        return $this->peran?->slug === 'kepala_dinas';
    }

    public function isKepalaBidang(): bool
    {
        return $this->peran?->slug === 'kepala_bidang';
    }

    public function isStaff(): bool
    {
        return $this->peran?->slug === 'staff';
    }

    public function isTataUsaha(): bool
    {
        return $this->peran?->slug === 'tata_usaha';
    }

    public function isPegawai(): bool
    {
        return $this->peran?->slug === 'pegawai';
    }

    /**
     * Cek apakah user memiliki salah satu dari role yang diberikan.
     */
    public function hasRole(string|array $roles): bool
    {
        if (! $this->peran) {
            return false;
        }

        return in_array($this->peran->slug, (array) $roles);
    }

    /**
     * Catat perubahan role sebagai aktivitas tersendiri agar mudah diaudit.
     */
    protected static function activityName(string $activity, $model): string
    {
        if ($activity === 'updated' && $model->wasChanged('peran_id')) {
            return 'role_changed';
        }

        return $activity;
    }
}
