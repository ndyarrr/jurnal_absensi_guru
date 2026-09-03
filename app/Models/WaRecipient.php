<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaRecipient extends Model
{
    protected $table = 'wa_recipients';

    protected $fillable = [
        'nama',
        'nomor_wa',
        'peran',
        'terima_notifikasi',
        'catatan',
    ];

    protected $casts = [
        'terima_notifikasi' => 'boolean',
    ];
}
