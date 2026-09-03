<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaTemplate extends Model
{
    protected $table = 'wa_templates';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'format_pesan',
        'variabel_tersedia',
        'is_active',
    ];

    protected $casts = [
        'variabel_tersedia' => 'array',
        'is_active' => 'boolean',
    ];
}
