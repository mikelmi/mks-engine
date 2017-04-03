<?php
/**
 * Author: mike
 * Date: 02.04.17
 * Time: 22:39
 */

namespace App\Contracts;


interface RouteCollector
{
    /**
     * @return array
     */
    public function map(): array;
}