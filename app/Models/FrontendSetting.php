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

    /**
     * Menu navigasi default untuk landing page (home)
     */
    public static function defaultNavMenu(): array
    {
        return [
            ['label' => 'Publikasi', 'url' => '/publikasi', 'target' => '_self', 'is_active' => true],
            ['label' => 'Pengaduan', 'url' => '/pengaduan', 'target' => '_self', 'is_active' => true],
            ['label' => 'Antrian', 'url' => '/antrian', 'target' => '_self', 'is_active' => true],
            ['label' => 'SKM', 'url' => '/#buku-tamu', 'target' => '_self', 'is_active' => true],
        ];
    }

    /**
     * Ambil menu navigasi landing page (dikelola langsung dari kode)
     */
    public static function getNavMenu(): array
    {
        return static::defaultNavMenu();
    }
}
