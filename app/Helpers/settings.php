<?php

use App\Services\SettingsService;

if (! function_exists('setting')) {
    function setting(string $key, $default = null) {
        /** @var SettingsService $svc */
        $svc = app(SettingsService::class);
        return $svc->get($key, $default);
    }
}
