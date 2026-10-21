<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
        'catatan',
        'tanggal_disposisi',
        'tanggal_baca',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_disposisi' => 'datetime',
        'tanggal_baca' => 'datetime',
        'tanggal_selesai' => 'datetime',
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