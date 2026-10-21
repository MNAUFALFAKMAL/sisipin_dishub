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
}
