<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 12:51
 */

namespace App\Contracts;


interface CategoryType
{
    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function title(): string;
}