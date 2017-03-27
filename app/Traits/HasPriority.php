<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 11:38
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class HasPriority
 * @package App\Traits
 *
 * @property int $priority
 *
 * @method static Builder orderPriority()
 * @method static Builder ordered()
 */
trait HasPriority
{
    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeOrderPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * @param Builder $query
     */
    public function scopeOrdered($query)
    {
        return $this->scopeOrderPriority($query)
            ->orderBy('created_at', 'desc');
    }

    /**
     * @param $value
     * @return int
     */
    public function getPriorityAttribute($value)
    {
        return intval($value);
    }

    /**
     * @param $value
     */
    public function setPriorityAttribute($value)
    {
        $this->attributes['priority'] = intval($value);
    }
}