<?php

namespace App\Models;


use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $lang
 * @property string $position
 * @property boolean $active
 */
class Menu extends Model
{
    use Parametrized;

    protected $table = 'menu';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function scopeActive($query, $active = true)
    {
        return $query->where('active', $active);
    }

    public function getActiveAttribute($value)
    {
        return boolval($value);
    }
}