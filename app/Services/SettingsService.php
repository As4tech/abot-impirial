<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected string $cacheKey = 'settings.all';
    protected int $ttlSeconds = 3600; // 1 hour

    public function all(): array
    {
        return Cache::remember($this->cacheKey, $this->ttlSeconds, function () {
            return Setting::query()->pluck('value', 'key')->toArray();
        });
    }

    public function get(string $key, $default = null): mixed
    {
        $all = $this->all();
        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public function setMany(array $data): void
    {
        if (empty($data)) return;

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_scalar($value) || is_null($value) ? (string) $value : json_encode($value)]
            );
        }

        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}
