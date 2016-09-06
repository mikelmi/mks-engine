<?php

function admin_prefix()
{
    return config('admin.url', 'admin');
}

function settings($key, $default = null)
{
    return app(\App\Services\Settings::class)->get($key, $default);
}