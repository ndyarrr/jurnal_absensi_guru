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

    /**
     * Render template pesan berdasarkan kode dan array variabel replacement.
     */
    public static function renderMessage(string $kode, array $variables = [], ?string $defaultText = null): string
    {
        $template = static::where('kode', $kode)->where('is_active', true)->first();
        if (!$template || empty($template->format_pesan)) {
            $pesan = $defaultText ?? '';
        } else {
            $pesan = $template->format_pesan;
        }

        // Map alias variable names for proof link and approval link
        if (isset($variables['link_dokumen_bukti_izin'])) {
            $variables['link_bukti'] = $variables['link_dokumen_bukti_izin'];
        } elseif (isset($variables['link_bukti'])) {
            $variables['link_dokumen_bukti_izin'] = $variables['link_bukti'];
        }

        if (isset($variables['link_persetujuan_waka_kepsek'])) {
            $variables['link_persetujuan'] = $variables['link_persetujuan_waka_kepsek'];
        } elseif (isset($variables['link_persetujuan'])) {
            $variables['link_persetujuan_waka_kepsek'] = $variables['link_persetujuan'];
        }

        foreach ($variables as $key => $val) {
            $cleanKey = '{' . trim($key, '{}') . '}';
            $pesan = str_replace($cleanKey, $val ?? '-', $pesan);
        }

        return $pesan;
    }
}
