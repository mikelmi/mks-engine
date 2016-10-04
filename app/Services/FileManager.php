<?php

namespace App\Services;


use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\UnreadableFileException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class FileManager
{
    /**
     * @var FilesystemAdapter
     */
    private $fs;

    /**
     * @var string
     */
    private $thumbnailRoute;

    /**
     * FileManager constructor.
     * @param FilesystemAdapter $filesystem
     * @param null $thumbnailRoute
     */
    public function __construct(FilesystemAdapter $filesystem, $thumbnailRoute = null)
    {
        $this->fs = $filesystem;
        $this->thumbnailRoute = $thumbnailRoute;
    }

    /**
     * @param null|string $path
     * @return array
     * @throws FileNotFoundException
     */
    public function getList($path = null)
    {
        if ($path && $path != '/' && !$this->fs->exists($path)) {
            throw new FileNotFoundException($path);
        }

        $data = [];

        $image_extensions = ['jpeg', 'jpg', 'png', 'gif', 'svg'];

        $finder = (new Finder())
            ->in($this->getPath($path))
            ->depth('== 0')
            ->ignoreDotFiles(false)
            ->notName('.gitignore')
            ->notName('.gitkeep')
            ->sortByName()
            ->sortByType();

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $date = new \DateTime('@' . $file->getMTime());
            $thumbnail = null;
            $isDir = $file->isDir();

            if ($this->thumbnailRoute && !$isDir && in_array(strtolower($file->getExtension()), $image_extensions)) {
                $relativePath = $this->getRelativePath($file);
                $thumbnail = route($this->thumbnailRoute, $this->getRelativeUrl($relativePath));
            }

            $data[] = [
                'name' => utf8_encode($file->getBasename()),
                'rights' => $this->parsePerms($file->getPerms()),
                'size' => $file->getSize(),
                'date' => $date->format('Y-m-d H:i:s'),
                'type' => $isDir ? 'dir' : 'file',
                'thumbnail' => $thumbnail
            ];
        }

        return $data;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isDir($path)
    {
        return is_dir($this->getPath($path));
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isFile($path)
    {
        return is_file($this->getPath($path));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function makeDirectory($name)
    {
        return $this->fs->makeDirectory($name);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function exists($path)
    {
        return $this->fs->exists($path);
    }

    /**
     * @param string|array $path
     * @return bool
     */
    public function remove($path)
    {
        $items = (array) $path;

        $result = 0;

        foreach ($items as $item) {
            if ($this->isDir($item)) {
                if ($this->fs->deleteDirectory($item)) {
                    $result++;
                }
                continue;
            }

            if ($this->fs->delete($item)) {
                $result++;
            }
        }

        return $result === count($items);
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     */
    public function rename($from, $to)
    {
        return $this->fs->getDriver()->rename($from, $to);
    }

    /**
     * @param string $path
     * @param string $newPath
     * @return bool
     */
    public function move($path, $newPath)
    {
        $to = $newPath;
        
        if ($this->isDir($newPath)) {
            $to .= DIRECTORY_SEPARATOR . basename($path);
        }
        
        return $this->rename($path, $to);
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $singleFilename
     * @return bool
     */
    public function copy($from, $to, $singleFilename = null)
    {
        $toPath = $to;

        if ($this->isDir($to)) {
            $toPath .= DIRECTORY_SEPARATOR . basename($from);
        }

        return $this->fs->copy($from, $toPath);
    }

    /**
     * @param string|array $path
     * @param int $permissions
     * @param bool $recursive
     * @return bool
     */
    public function chmod($path, $permissions, $recursive = false)
    {
        $items = array_map([$this, 'getPath'], (array)$path);

        $result = 0;
        
        foreach ($items as $item) {
            if (!$this->exists($item)) {
                continue;
            }

            if (is_dir($item) && $recursive === true) {
                $finder = new Finder();
                $finder->in($item);

                foreach($finder as $sub) {
                    if (chmod($sub, $permissions)) {
                        $result++;
                    }
                }
            }

            if (chmod($item, $permissions)) {
                $result++;
            }
        }
        
        return $result === count($items);
    }

    /**
     * @param array $items
     * @param string|null $destination
     * @return bool|string
     */
    public function compress(array $items, $destination = null)
    {
        $files = array_map([$this, 'getPath'], $items);

        $zip = new \ZipArchive();

        if (!$destination) {
            $archive = tempnam(sys_get_temp_dir(), 'filemanager');
        } else {
            $archive = $this->getPath($destination);
        }

        if ($zip->open($archive, \ZipArchive::CREATE) === true) {
            foreach ($files as $item) {
                if (is_dir($item)) {
                    $zip->addEmptyDir($this->getRelativePath($item));

                    $finder = new Finder();

                    foreach ($finder->in($item) as $file) {
                        if ($file->isDir()) {
                            $zip->addEmptyDir($this->getRelativePath($file->getRealPath()));

                            continue;
                        }

                        $zip->addFile($file->getRealPath(), $this->getRelativePath($file->getRealPath()));
                    }

                    continue;
                }

                $zip->addFile($item, $this->getRelativePath($item));
            }

            if ($zip->close()) {
                return $destination ? true : $archive;
            }
        }

        return false;
    }

    /**
     * @param string $item
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($item)
    {
        return $this->fs->get($item);
    }

    /**
     * @param string $path
     * @return SplFileInfo
     * @throws FileNotFoundException
     */
    public function getFile($path)
    {
        if (!$this->exists($path))
        {
            throw new FileNotFoundException($path);
        }

        return new SplFileInfo($this->getPath($path), dirname($path), $path);
    }

    /**
     * @param $item
     * @param $destination
     * @return bool
     * @throws FileNotFoundException
     * @throws UnreadableFileException
     */
    public function extract($item, $destination)
    {
        if (!$this->exists($item)) {
            throw new FileNotFoundException($item);
        }

        $path = $this->getPath($item);

        $zip = new \ZipArchive();
        if ($zip->open($path) === false) {
            throw new UnreadableFileException($item);
        }

        $this->makeDirectory($destination);

        $zip->extractTo($this->getPath($destination));

        return $zip->close();
    }

    /**
     * @param UploadedFile $file
     * @param $destination
     * @param null $name
     * @return false|string
     */
    public function upload(UploadedFile $file, $destination, $name = null)
    {
        return $this->fs->putFileAs($destination, $file, $name ?: $file->getFilename());
    }

    /**
     * @param string $item
     * @param string $content
     * @return bool
     */
    public function put($item, $content)
    {
        return $this->fs->put($item, $content);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getPath($path = '')
    {
        return $this->adapter()->applyPathPrefix($path);
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->getPath();
    }

    /**
     * @return AbstractAdapter
     */
    private function adapter()
    {
        return $this->fs->getDriver()->getAdapter();
    }

    /**
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        return $this->fs->url($path);
    }

    /**
     * @param $path
     * @return string
     */
    public function getRelativeUrl($path)
    {
        return $this->adapter()->getRelativeUrl($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getRelativePath($path)
    {
        return ltrim($this->adapter()->removePathPrefix($path), '/');
    }

    /**
     * Get readable file persmissions
     *
     * @param $perms
     * @return string
     */
    private function parsePerms($perms)
    {
        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x') :
            (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x') :
            (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x') :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    /**
     * @return \League\Flysystem\FilesystemInterface
     */
    public function getFilesystem()
    {
        return $this->fs->getDriver();
    }
}