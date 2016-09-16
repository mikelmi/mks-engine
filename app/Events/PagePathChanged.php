<?php
/**
 * Event fired when Page::path changed
 */

namespace App\Events;


class PagePathChanged extends Event
{
    /**
     * path before saving
     *
     * @var string
     */
    private $oldPath;

    /**
     * new path after saving
     *
     * @var string
     */
    private $newPath;

    /**
     * PagePathChanged constructor.
     * @param string $oldPath
     * @param string $newPath
     */
    public function __construct($oldPath, $newPath)
    {
        $this->oldPath = $oldPath;
        $this->newPath = $newPath;
    }

    /**
     * @return string
     */
    public function getOldPath()
    {
        return $this->oldPath;
    }

    /**
     * @return string
     */
    public function getNewPath()
    {
        return $this->newPath;
    }
}