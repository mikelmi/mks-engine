<?php

namespace App\Contracts;


use Illuminate\Support\Collection;

interface NestedMenuInterface
{
    /**
     * @return bool
     */
    public function hasChildren();

    /**
     * @return int
     */
    public function getDepth();

    /**
     * @return bool
     */
    public function isCurrent();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return array|Collection
     */
    public function getChildren();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return array
     */
    public function htmlAttributes(): array;

    /**
     * @return string
     */
    public function getIcon();
}