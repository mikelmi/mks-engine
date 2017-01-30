<?php

namespace App\Services;

use Cviebrock\EloquentTaggable\Services\TagService as BaseTagService;
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
}