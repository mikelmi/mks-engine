<?php
/**
 * Author: mike
 * Date: 02.04.17
 * Time: 22:39
 */

namespace App\Contracts;


use Illuminate\Support\Collection;

interface RouteCollector
{
    /**
     * @return array
     */
    public function map(): array;

    /**
     * @return array
     */
    public function params(): array;
}