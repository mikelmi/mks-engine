<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string $slug
 */
class Section extends Model
{
    use Sluggable;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }
}