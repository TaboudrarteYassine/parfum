<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting()
    {
        return Cache::remember('site_setting', 3600, function () {
            return Setting::first() ?? new Setting();
        });
    }
}
