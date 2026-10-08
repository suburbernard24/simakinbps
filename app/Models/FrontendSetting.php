<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label', 'group'];

    /**
     * Ambil nilai setting berdasarkan key, dengan fallback default.
     */
    public static function get(string $key, $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set / upsert nilai setting.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Ambil semua settings sebagai key-value array.
     */
    public static function allKeyed(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }
}
