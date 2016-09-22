<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 22.09.16
 * Time: 15:46
 */

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