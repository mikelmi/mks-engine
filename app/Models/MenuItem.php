<?php

namespace App\Models;


use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class MenuItem
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $route
 * @property string $url
 * @property string $target
 * @property Menu $menu
 */
class MenuItem extends Model
{
    use NodeTrait, Parametrized;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    protected function getScopeAttributes()
    {
        return [ 'menu_id' ];
    }
}