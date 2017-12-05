<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 07.02.17
 * Time: 20:20
 */

namespace App\Traits;


use App\Models\Category;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasSection
 * @package App\Traits
 *
 * @property int $section_id
 * @property Section $section
 */
trait HasSection
{
    protected static function bootHasSection()
    {
        static::saving(function (Model $model) {
            /** @var Category $category */
            if ($category = $model->category) {
                $model->section()->associate($category->section);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}