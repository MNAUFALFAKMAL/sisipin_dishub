<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'no_agenda', 'klasifikasi_id', 'jenis_surat', 'tahun',
        'no_surat', 'tanggal_surat', 'tanggal_terima',
        'pengirim', 'perihal', 'isi_ringkas', 'file_surat',
        'bidang_tujuan_id', 'diinput_oleh',
        'status_disposisi', 'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
        'tahun' => 'integer',
    ];

    /**
     * Simpan daftar path file (JSON array); kompatibel dengan data lama berupa path tunggal.
     */
    public function getFileSuratAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return array_values($value);
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values($decoded);
        }

        // Legacy: string path tunggal
        return [$value];
    }

    /**
     * Simpan daftar path file (JSON array); kompatibel dengan data lama berupa path tunggal.
     */
    public function setFileSuratAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['file_surat'] = null;

            return;
        }

        if (is_array($value)) {
            $clean = array_values(array_filter($value, fn ($v) => $v !== null && $v !== ''));
            $this->attributes['file_surat'] = $clean ? json_encode($clean) : null;

            return;
        }

        // String path tunggal
        $this->attributes['file_surat'] = json_encode([$value]);
    }

    public function bidangTujuan(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_tujuan_id');
    }

    public function bidangTujuans(): BelongsToMany
    {
        return $this->belongsToMany(Bidang::class, 'surat_masuk_bidang', 'surat_masuk_id', 'bidang_id');
    }

    public function diinputOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'klasifikasi_id');
    }

    public function disposisis(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id');
    }

    public function latestDisposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'surat_masuk_id')->latest('tanggal_disposisi');
    }
}
