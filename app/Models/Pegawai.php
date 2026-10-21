<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'pangkat',
        'golongan',
        'jabatan',
        'unit_bidang',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
