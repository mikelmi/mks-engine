<?php

namespace App\Traits;

use Cviebrock\EloquentTaggable\Services\TagService;
use Illuminate\Support\Collection;

trait Taggable
{
    use \Cviebrock\EloquentTaggable\Taggable;

    public function syncTags($tags)
    {
        if (!$tags) {
            return $this->detag();
        }

        /** @var Collection $exists */
        $exists = $this->tags->pluck('name','normalized');
        $existsAll = $exists->keys()->merge($exists->values())->all();

        if (!$exists) {
            return $this->tag($tags);
        }

        $tags = app(TagService::class)->buildTagArray($tags);

        foreach ($tags as $tagName) {
            if (!in_array($tagName, $existsAll)) {
                $this->addOneTag($tagName);
            }
        }

        foreach ($exists as $normalized => $name) {
            if (!in_array($normalized, $tags) && !in_array($name, $tags)) {
                $this->removeOneTag($name);
            }
        }

        return $this->load('tags');
    }
}