<?php
/**
 * Author: mike
 * Date: 23.05.17
 * Time: 20:14
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class HasFeatured
 * @package App\Traits
 *
 * @property bool $featured
 * @method static Builder featured()
 * @method static Builder notFeatured()
 */
trait HasFeatured
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotFeatured($query)
    {
        return $query->where('featured', false);
    }

    /**
     * @param $value
     * @return bool
     */
    public function getFeaturedAttribute($value)
    {
        return boolval($value);
    }

    /**
     * @param $value
     * @return bool
     */
    public function setFeaturedAttribute($value)
    {
        return $this->attributes['featured'] = boolval($value);
    }

    /**
     * @return mixed
     */
    public function isFeatured()
    {
        return $this->getAttribute('featured');
    }
}