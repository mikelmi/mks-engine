<?php

namespace App\Services;

use Cviebrock\EloquentTaggable\Services\TagService as BaseTagService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentTaggable\Models\Tag;

class TagService extends BaseTagService
{
    public function getAllTags($class)
    {
        if ($class instanceof Model) {
            $class = get_class($class);
        }

        $sql = 'SELECT DISTINCT t.*' .
            ' FROM taggable_taggables tt LEFT JOIN taggable_tags t ON tt.tag_id=t.tag_id' .
            ' WHERE tt.taggable_type = ?';

        return Tag::fromQuery($sql, [$class]);
    }

    /**
     * @return Collection
     */
    public function allWithCounts()
    {
        $sql = 'SELECT t.tag_id as id, tt.tag_id as tid, t.name, t.normalized, COUNT(tt.tag_id) as count
                  FROM taggable_taggables tt LEFT JOIN taggable_tags t ON tt.tag_id=t.tag_id
                  GROUP BY t.tag_id, tt.tag_id';

        return Tag::fromQuery($sql);
    }

    /**
     * @param array|int $id
     * @return int
     */
    public function deleteById($id)
    {
        \DB::beginTransaction();

        $result = \DB::table('taggable_taggables')
            ->whereIn('tag_id', (array)$id)
            ->delete();

        $tags = Tag::find((array) $id);

        if ($tags->count()) {
            $result = Tag::destroy($id);
        }

        \DB::commit();

        return $result;
    }
}