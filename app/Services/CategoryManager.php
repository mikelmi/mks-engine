<?php

namespace App\Services;


use App\Contracts\CategoryType;
use App\Models\Category;
use App\Models\Section;

class CategoryManager
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * CategoryManager constructor.
     * @param array $types
     */
    public function __construct(array $types)
    {
        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * @param CategoryType $type
     */
    public function addType(CategoryType $type)
    {
        $this->types[$type->type()] = $type->title();
    }

    /**
     * @return CategoryType[]
     */
    public function getTypes()
    {
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
                    'slug' => $item->slug,
                    'selected' => $selected && in_array($item->id, $selected)
                ];
            })->toArray();
        }

        return $sections;
    }

    /**
     * @param null $type
     * @param null $selected
     * @return array
     */
    public function getSelectOptionsFlat($type = null, $selected = null)
    {
        $sections = Section::select(['id', 'title']);

        if ($type) {
            $sections->where('type', $type);
        }

        if ($selected) {
            $selected = (array) $selected;
        }

        $result = [];

        foreach ($sections->get() as $section) {
            $group = $section->title;
            $result[$group] = Category::getFlatTree($section['id'])->map(function($item) use ($selected, $group) {
                return [
                    'id' => $item->id,
                    'text' => str_repeat('-', $item->depth) . $item->title,
                    'slug' => $item->slug,
                    'selected' => $selected && in_array($item->id, $selected),
                    'group' => $group
                ];
            })->toArray();
        }

        return $result;
    }

    /**
     * @param null $type
     * @param null $selected
     * @return array
     */
    public function getSelectSections($type = null, $selected = null)
    {
        $sections = Section::select(['id', 'title', 'slug'])->orderBy('title');

        if ($type) {
            $sections->where('type', $type);
        }

        if ($selected) {
            $selected = (array) $selected;
        }

        return $sections->get()->map(function($item) use ($selected) {
            return [
                'id' => $item->id,
                'text' => $item->title,
                'slug' => $item->slug,
                'selected' => $selected && in_array($item->id, $selected),
            ];
        })->toArray();
    }
}