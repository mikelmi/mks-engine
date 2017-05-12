<?php

namespace App\Notifications;


interface ReadableNotification
{
    /**
     * @param $data
     * @return string
     */
    public static function title($data);

    /**
     * @param $data
     * @return string
     */
    public static function details($data);
}