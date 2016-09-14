<?php

namespace App\Events;


class PagePathChanged extends Event
{
    private $oldPath;

    private $newPath;

    public function __construct($oldPath, $newPath)
    {
        $this->oldPath = $oldPath;
        $this->newPath = $newPath;
    }

    public function getOldPath()
    {
        return $this->oldPath;
    }

    public function getNewPath()
    {
        return $this->newPath;
    }
}