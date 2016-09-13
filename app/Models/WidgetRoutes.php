<?php

namespace App\Models;

use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 * Class WidgetRoutes
 * @package App\Models
 * 
 * @property int $id
 * @property string $route
 * @property Collection $params
 */
class WidgetRoutes extends Model
{
    use Parametrized;

    public $timestamps = false;

    protected $fillable = ['route', 'params'];

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
