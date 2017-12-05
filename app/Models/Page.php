<?php

namespace App\Models;

use App\Traits\HasMeta;
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
 * @property string $url
 */
class Page extends Model
{
    use SoftDeletes,
        Parametrized,
        HasRoles,
        HasMeta;

    protected $appends = ['url'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('title');
    }

    public function getUrlAttribute()
    {
        $path = $this->path;

        if ($this->lang) {
            $path = $this->lang . '/' . $path;
        }
        return url($path);
    }

    public function getDefaultMeta(): array
    {
        return [
            'title' => $this->attributes['title']
        ];
    }
}
