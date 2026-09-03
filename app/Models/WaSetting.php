<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaSetting extends Model
{
    protected $table = 'wa_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'keterangan',
    ];

    /**
     * Helper static untuk mengambil nilai setting berdasarkan key
     */
    public static function getByKey(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Helper static untuk mengupdate atau membuat nilai setting
     */
    public static function setKey(string $key, $value, string $group = 'general', ?string $keterangan = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'group' => $group,
                'keterangan' => $keterangan,
            ]
        );
    }
}
