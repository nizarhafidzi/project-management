<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return \Modules\System\Models\Setting
     */
    public static function set($key, $value)
    {
        Cache::forget("setting.{$key}");
        
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
