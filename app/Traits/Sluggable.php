<?php

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;

trait Sluggable
{

    protected static function bootSluggable()
    {
        static::creating(function (Model $model) {
            $model->addSlug();
        });

        static::updating(function (Model $model) {
            $model->addSlug();
        });
    }

    protected function addSlug()
    {
        $sources = $this->getSlugSource();

        if (!$sources) {
            throw new \InvalidArgumentException('Model ' . get_called_class() . ' does not provide getSlugSource()');
        }

        $this->{$this->getSlugField()} = implode('-', array_map('str_slug', $this->getAttributes((array)$sources)));
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