<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 16:44
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field;
use Mikelmi\MksAdmin\Form\FieldInterface;

class CategorySelect extends Field
{
    protected $class = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var int|string|null
     */
    protected $section;

    /**
     * @var int|string|null
     */
    protected $category;

    /**
     * @var string
     */
    protected $categoryType = '';

    /**
     * @var string
     */
    protected $sectionName = 'section';

    /**
     * @var string
     */
    protected $categoryName = 'category';

    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label ?: __('general.Category'));
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return CategorySelect
     */
    public function setUrl(string $url): CategorySelect
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryType(): string
    {
        return $this->categoryType;
    }

    /**
     * @param string $categoryType
     * @return CategorySelect
     */
    public function setCategoryType(string $categoryType): CategorySelect
    {
        $this->categoryType = $categoryType;
        return $this;
    }

    /**
     * @return int|null|string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param int|null|string $section
     * @return CategorySelect
     */
    public function setSection($section)
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return int|null|string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int|null|string $category
     * @return CategorySelect
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getSectionName(): string
    {
        return $this->sectionName;
    }

    /**
     * @param string $sectionName
     * @return CategorySelect
     */
    public function setSectionName(string $sectionName): CategorySelect
    {
        $this->sectionName = $sectionName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @param string $categoryName
     * @return CategorySelect
     */
    public function setCategoryName(string $categoryName): CategorySelect
    {
        $this->categoryName = $categoryName;
        return $this;
    }

    protected function getDefaultAttributes(): array
    {
        $result = parent::getDefaultAttributes();

        unset($result['name']);

        $result['data-url'] = route('admin::category.select', ['type' => $this->getCategoryType()]);
        $result['data-section-id'] = $this->getSection();
        $result['data-category-id'] = $this->getCategory();
        $result['section-empty'] = __('general.Section');
        $result['category-empty'] = __('general.Category');
        $result['section-field'] = $this->getSectionName();
        $result['category-field'] = $this->getCategoryName();

        return $result;
    }

    public function setValue($value): FieldInterface
    {
        parent::setValue($value);

        if (is_array($value)) {
            if ($value) {
                $this->setSection($value[0]);
            }
            if (count($value) > 1) {
                $this->setCategory($value[1]);
            }
        } elseif (is_string($value) || is_int($value)) {
            $this->setCategory($value);
        }

        return $this;
    }

    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        return '<mks-category-select ' . html_attr($attr) . '></mks-category-select>';
    }
}