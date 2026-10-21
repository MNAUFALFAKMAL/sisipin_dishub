<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunArsip extends Model
{
    protected $fillable = ['tahun'];

    protected $casts = [
        'tahun' => 'integer',
    ];
}
