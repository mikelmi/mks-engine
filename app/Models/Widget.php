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

    public function getClassTitleAttribute()
    {
        return app(WidgetManager::class)->title($this->class, 'Opack');
    }
}
