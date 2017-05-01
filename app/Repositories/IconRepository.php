<?php
/**
 * Author: mike
 * Date: 05.04.17
 * Time: 15:00
 */

namespace App\Repositories;


use Goutte\Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class IconRepository
{
    /**
     * @var FilesystemAdapter
     */
    private $storage;

    /**
     * @var string
     */
    private $remoteUrl;

    /**
     * @var string
     */
    private $namePrefix = 'fa-';

    /**
     * @var Collection
     */
    private $icons;

    /**
     * IconRepository constructor.
     * @param FilesystemAdapter $storage
     * @param string $remoteUrl
     */
    public function __construct(FilesystemAdapter $storage, string $remoteUrl)
    {
        $this->storage = $storage;
        $this->remoteUrl = $remoteUrl;
    }

    /**
     * @return array
     */
    protected function parse(): array
    {
        $content = Cache::remember('icons-crawler', 60, function() {
            $client = new Client();
            $crawler = $client->request('GET', $this->remoteUrl);
            return $crawler->html();
        });

        $crawler = new Crawler($content);

        $prefix = preg_quote($this->namePrefix);

        $icons = $crawler->filter('#wrap > .container > .row i.fa')->each(function($node) use ($prefix) {
            $name = trim($node->getNode(0)->nextSibling->textContent);

            return [
                'name' => preg_replace('/^'.$prefix.'/', '', $name),
                'unicode' => $node->text(),
            ];
        });

        return $icons;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        if (!isset($this->icons)) {
            $icons = null;
            $filename = 'icons.json';

            if ($this->storage->exists($filename)) {
                $icons = json_decode($this->storage->get($filename),true);
            }

            if (!$icons) {
                $icons = $this->parse();
                $this->storage->put($filename, json_encode($icons));
            }

            $this->icons = collect($icons);
        }

        return $this->icons;
    }
}