<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisis';

    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'kepada_user_id',
        'kepada_bidang_id',
        'instruksi',
        'prioritas',
        'sifat',
        'status',
        'catatan',
        'batas_waktu',
        'tanggal_disposisi',
        'tanggal_baca',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_disposisi' => 'datetime',
        'tanggal_baca' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'batas_waktu' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function dariUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    public function kepadaUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }

    public function kepadaBidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'kepada_bidang_id');
    }

    // ── Accessors ─────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'dibaca' => 'Dibaca',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getPrioritasLabelAttribute(): string
    {
        return match ($this->prioritas) {
            'biasa' => 'Biasa',
            'segera' => 'Segera',
            'mendesak' => 'Mendesak',
            default => ucfirst($this->prioritas),
        };
    }

    public function getSifatLabelAttribute(): string
    {
        return match ($this->sifat) {
            'biasa' => 'Biasa',
            'rahasia' => 'Rahasia',
            'terbatas' => 'Terbatas',
            default => ucfirst($this->sifat),
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->batas_waktu) {
            return false;
        }
        return $this->status !== 'selesai' && Carbon::parse($this->batas_waktu)->isPast();
    }

    public function getProgressPercentageAttribute(): int
    {
        return match ($this->status) {
            'baru' => 0,
            'dibaca' => 25,
            'diproses' => 50,
            'selesai' => 100,
            'ditolak' => 100,
            default => 0,
        };
    }

    public function getRemainingTimeAttribute(): ?string
    {
        if (!$this->batas_waktu) {
            return null;
        }
        if ($this->status === 'selesai' || $this->status === 'ditolak') {
            return 'Selesai';
        }
        $diff = now()->diffForHumans(Carbon::parse($this->batas_waktu), true);
        return Carbon::parse($this->batas_waktu)->isPast() ? "Terlambat {$diff}" : "Tersisa {$diff}";
    }

    // ── Helper Methods ─────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === 'baru';
    }

    public function isRead(): bool
    {
        return in_array($this->status, ['dibaca', 'diproses', 'selesai']);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'selesai';
    }

    public function markAsRead(): void
    {
        $this->update([
            'status' => 'dibaca',
            'tanggal_baca' => now(),
        ]);
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => 'diproses']);
    }

    public function markAsCompleted(string $catatan = null): void
    {
        $this->update([
            'status' => 'selesai',
            'tanggal_selesai' => now(),
            'catatan' => $catatan ?? $this->catatan,
        ]);
    }

    public function markAsRejected(string $catatan = null): void
    {
        $this->update([
            'status' => 'ditolak',
            'tanggal_selesai' => now(),
            'catatan' => $catatan ?? $this->catatan,
        ]);
    }
}