<?php

namespace App\Models;


use App\Traits\HasActive;
use App\Traits\OrderByName;
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
 */
class Menu extends Model
{
    use Parametrized,
        HasActive,
        OrderByName;

    protected $table = 'menu';

    public $timestamps = false;

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}