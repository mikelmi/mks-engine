<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 13:13
 */

namespace App\Contracts;


use Lavary\Menu\Builder as MenuBuilder;

interface AdminMenuBuilder
{
    /**
     * @param MenuBuilder $menu
     */
    public function build(MenuBuilder $menu);
}