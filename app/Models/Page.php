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
 */
class Page extends Model
{
    use SoftDeletes;
    use Parametrized;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_role');
    }
}
