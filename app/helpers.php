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

function captcha_enabled()
{
    return app('app.captcha')->enabled();
}

function captcha_display($withInput = false)
{
    return app('app.captcha')->display($withInput);
}

function captcha_input(array $attr = [])
{
    return app('app.captcha')->inputField($attr);
}

function captcha_has_input()
{
    return app('app.captcha')->hasInput();
}

function captcha_field_name()
{
    return app('app.captcha')->fieldName();
}

function locales()
{
    static $locales;

    if (!isset($locales)) {
        $locales = app(\App\Repositories\LanguageRepository::class)->locales();
    }

    return $locales;
}

function language_icon($iso)
{
    if (!$iso) {
        return;
    }
    
    return sprintf('<img src="%s" alt="" width="12" height="10">', route('lang.icon', $iso));
}