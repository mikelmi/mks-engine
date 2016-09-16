<?php

function admin_prefix()
{
    return config('admin.url', 'admin');
}

function settings($key, $default = null)
{
    return app(\App\Services\Settings::class)->get($key, $default);
}

function html_attr($attributes)
{
    if (!is_array($attributes)) {
        return $attributes;
    }

    return array_reduce(
        array_keys($attributes),
        function ($result, $key) use ($attributes) {
            return $result . ' ' . $key . '="' . e($attributes[$key]) . '"';
        },
        ''
    );
}