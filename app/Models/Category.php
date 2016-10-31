<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    public $timestamps = false;

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
        $sectionId = $section instanceof Section ? $section->id : $section;

        return self::scoped(['section_id' => $sectionId]);
    }

    public static function getTree($section, $root = null)
    {
        return self::ofSection($section)->defaultOrder()->withDepth()->get()->toTree($root);
    }
}