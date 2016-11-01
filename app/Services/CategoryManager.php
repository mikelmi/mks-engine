<?php

namespace App\Services;


use App\Events\CategoryTypesCollect;
use App\Models\Category;
use App\Models\Section;
use Illuminate\Support\Collection;

class CategoryManager
{
    /**
     * @var Collection|null
     */
    private $types;

    /**
     * @return Collection
     */
    public function getTypes()
    {
        if (!isset($this->types)) {
            $this->types = new Collection();
            event(new CategoryTypesCollect($this->types));
        }

        return $this->types;
    }

    /**
     * @return bool
     */
    public function hasTypes()
    {
        return $this->getTypes()->count() > 0;
    }

    /**
     * @param string|null $type
     * @return array
     */
    public function getSelectOptions($type = null, $selected = null)
    {
        $sections = Section::select(['id', 'title as text']);

        if ($type) {
            $sections->where('type', $type);
        }

        $sections = $sections->get()->toArray();

        if ($selected) {
            $selected = (array) $selected;
        }

        foreach ($sections as &$section) {
            $section['children'] = Category::getFlatTree($section['id'])->map(function($item) use ($selected) {
                return [
                    'id' => $item->id,
                    'text' => str_repeat('-', $item->depth) . $item->title,
                    'selected' => $selected && in_array($item->id, $selected)
                ];
            })->toArray();
        }

        return $sections;
    }
}