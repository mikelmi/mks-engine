<?php
/**
 * Author: mike
 * Date: 21.03.17
 * Time: 10:23
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class OrderByName
 * @package App\Traits
 *
 * @method static Builder|Collection ordered()
 */
trait OrderByName
{
    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }
}