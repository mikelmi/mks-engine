<?php

namespace App\Models;

use App\Services\WidgetManager;
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
 * @property integer $ordering
 * @property bool $status
 */
class Widget extends Model
{
    use Parametrized;

    protected $appends = ['class_title'];

    protected $casts = [
        'status' => 'boolean'
    ];

    private $html_attr;

    public function getClassTitleAttribute()
    {
        return app(WidgetManager::class)->title($this->class, 'Opack');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function routes()
    {
        return $this->hasMany(WidgetRoutes::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_role');
    }

    /**
     * @return array
     */
    public function getHtmlAttributes()
    {
        if (!isset($this->html_attr)) {
            $this->html_attr = [];

            if ($class = $this->param('css_class')) {
                $this->html_attr['class'] = $class;
            }

            if ($id = $this->param('html_id')) {
                $this->html_attr['id'] = $id;
            }
        }

        return $this->html_attr;
    }
}
