<?php

namespace App\Notifications;


use Illuminate\Database\Eloquent\Model;

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