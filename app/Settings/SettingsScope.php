<?php

namespace App\Settings;


abstract class SettingsScope implements \App\Contracts\SettingsScope
{
    /**
     * @return array
     */
    abstract public function fields(): array;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @param array $old
     * @param array $new
     */
    public function afterSave(array $old, array $new)
    {

    }

    public function input(array $data): array
    {
        return $data;
    }

}