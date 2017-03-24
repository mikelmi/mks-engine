<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 13:22
 */

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;


/**
 * Class HasActive
 * @package App\Traits
 *
 * @property bool $active
 * @method static Builder active()
 */
trait HasActive
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * @param $value
     * @return bool
     */
    public function getActiveAttribute($value)
    {
        return boolval($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function setActiveAttribute($value)
    {
        return $this->attributes['active'] = boolval($value);
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->getAttribute('active');
    }
}