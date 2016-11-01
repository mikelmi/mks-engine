<?php

namespace App\Models;


use App\Contracts\NestedMenuInterface;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Category
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property Section $section
 * @method Category ofSection($section)
 * @property Category[] $children
 * @property int $depth
 * @property string $slug
 */
class Category extends Model implements NestedMenuInterface
{
    use NodeTrait;
    use Sluggable;

    public $timestamps = false;

    protected $table = 'categories';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    protected function getScopeAttributes()
    {
        return [ 'section_id' ];
    }

    public function scopeOfSection(Builder $query, $section)
    {
        $sectionModel = $section instanceof Section ? $section : Section::find($section);

        return call_user_func([$sectionModel->type, 'scoped'], ['section_id' => $sectionModel->id]);
    }

    public static function getTree($section, $root = null)
    {
        return self::ofSection($section)->defaultOrder()->withDepth()->get()->toTree($root);
    }

    public static function getFlatTree($section, $root = null)
    {
        return self::ofSection($section)->defaultOrder()->withDepth()->get()->toFlatTree($root);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children->count() > 0;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return null;
    }

    /**
     * @return array|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}