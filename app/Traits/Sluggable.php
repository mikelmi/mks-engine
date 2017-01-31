<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;

trait Sluggable
{

    protected static function bootSluggable()
    {
        static::saving(function (Model $model) {
            $model->addSlug();
        });
    }

    protected function addSlug()
    {
        $sources = $this->getSlugSource();

        if (!$sources) {
            throw new \InvalidArgumentException('Model ' . get_called_class() . ' does not provide getSlugSource()');
        }

        $values = array_only($this->getAttributes(), (array)$sources);

        $this->{$this->getSlugField()} = implode('-', array_map('str_slug', $values));
    }

    protected function getSlugSource()
    {
        return ['title'];
    }

    protected function getSlugField()
    {
        return 'slug';
    }
}