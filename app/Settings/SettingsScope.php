<?php

namespace App\Settings;


use Illuminate\Config\Repository;

class SettingsScope
{
    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $title;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * SettingsScope constructor.
     * @param string|null $name
     * @param string|null $title
     * @param string|null $view
     */
    public function __construct($name = null, $title = null, $view = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->view = $view;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string|null $default
     * @return null|string
     */
    public function getView($default = null)
    {
        return $this->view ? $this->view : $default;
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->title = $view;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);

        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    public function afterSave(Repository $old, Repository $new)
    {
        
    }

    public function beforeSave(&$data) {
        
    }

    /**
     * @param Repository $repository
     * @return Repository
     */
    public function getModel(Repository $repository)
    {
        return $repository;
    }
}