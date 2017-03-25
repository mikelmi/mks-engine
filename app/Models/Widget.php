<?php

namespace App\Models;

use App\Services\WidgetManager;
use App\Traits\HasActive;
use App\Traits\HasPriority;
use App\Traits\HasRoles;
use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Widget
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $class
 * @property string $title
 * @property string $content
 * @property string $position
 * @property string $lang
 */
class Widget extends Model
{
    use Parametrized,
        HasPriority,
        HasActive,
        HasRoles;

    protected $appends = ['class_title'];

    public function getClassTitleAttribute()
    {
        return app(WidgetManager::class)->title($this->class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routes()
    {
        return $this->hasMany(WidgetRoutes::class);
    }

    /**
     * @return array
     */
    public function getHtmlAttributes(): array
    {
        $result = $this->param('attr');

        return is_array($result) ? $result : [];
    }
}
