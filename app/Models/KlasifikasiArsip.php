<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiArsip extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'parent_id',
        'retensi_aktif_tahun',
        'retensi_inaktif_tahun',
        'nasib_akhir',
        'keamanan',
        'hak_akses',
        'dasar_pertimbangan',
        'unit_pengolah',
    ];

    public function parent()
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(KlasifikasiArsip::class, 'parent_id');
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class, 'klasifikasi_id');
    }

    /**
     * Get the keamanan label
     */
    public function getKeamananLabelAttribute(): string
    {
        $labels = [
            'sangat_rahasia' => 'Sangat Rahasia',
            'rahasia' => 'Rahasia',
            'terbatas' => 'Terbatas',
            'umum' => 'Umum',
        ];

        return $labels[$this->keamanan] ?? $this->keamanan;
    }

    /**
     * Get the nasib akhir label
     */
    public function getNasibAkhirLabelAttribute(): string
    {
        $labels = [
            'musnah' => 'Musnah',
            'permanen' => 'Permanen',
            'dinilai' => 'Dinilai',
        ];

        return $labels[$this->nasib_akhir] ?? ucfirst($this->nasib_akhir);
    }

    /**
     * Get the nasib akhir badge class
     */
    public function getNasibAkhirBadgeClassAttribute(): string
    {
        $classes = [
            'musnah' => 'badge-danger',
            'permanen' => 'badge-success',
            'dinilai' => 'badge-warning',
        ];

        return $classes[$this->nasib_akhir] ?? 'badge-neutral';
    }

    /**
     * Get the tingkat based on kode segments
     */
    public function getTingkatAttribute(): int
    {
        return count(explode('.', $this->kode));
    }

    /**
     * Get the tingkat label
     */
    public function getTingkatLabelAttribute(): string
    {
        $labels = [
            1 => 'Utama',
            2 => 'Sub',
            3 => 'Detail',
            4 => 'Sub-Detail',
        ];

        return $labels[$this->tingkat] ?? "Tingkat {$this->tingkat}";
    }

    /**
     * Get total masa simpan
     */
    public function getTotalMasaSimpanAttribute(): int
    {
        return $this->retensi_aktif_tahun + $this->retensi_inaktif_tahun;
    }
}