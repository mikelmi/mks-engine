<?php

namespace App\Models;

use App\Traits\HasRoles;
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
    use SoftDeletes,
        Parametrized,
        HasRoles;

    protected $appends = ['url'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('title');
    }

    public function getUrlAttribute()
    {
        return url($this->path);
    }
}
