<?php

namespace App\Models;

use App\Traits\Parametrized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Page
 * @package App\Models
 * 
 * @property int $id
 * @property string $title
 * @property string $path
 * @property string $page_text
 * @property string $lang
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $url
 */
class Page extends Model
{
    use SoftDeletes;
    use Parametrized;

    protected $appends = ['url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_role');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('title');
    }

    public function getUrlAttribute()
    {
        return url($this->path);
    }
}
