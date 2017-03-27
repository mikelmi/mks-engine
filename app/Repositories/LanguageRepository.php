<?php

namespace App\Repositories;


use App\Models\Language;
use Illuminate\Support\Collection;
use App\Services\Settings;
use App\Services\Json;

class LanguageRepository
{

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @var string
     */
    private $source;

    /**
     * @var Collection
     */
    private $data;

    /**
     * LanguageRepository constructor.
     * @param Settings $settings
     * @param string $source
     */
    public function __construct(Settings $settings, $source)
    {
        $this->settings = $settings;
        $this->source = $source;
    }

    /**
     * @return Collection
     */
    public function all()
    {
        if (!isset($this->data)) {
            $this->data = collect(Json::make($this->source)->all());
        }

        return $this->data;
    }

    /**
     * @return Collection
     */
    public function available()
    {
        return collect($this->settings->get('languages', []))->map(function($item) {
            return new Language($item);
        });
    }

    /**
     * @return Collection
     */
    public function enabled()
    {
        return $this->available()->filter(function(Language $lang) {
            return $lang->isEnabled();
        });
    }

    /**
     * @param $key
     * @return Language
     */
    public function get($key)
    {
        return $this->available()->get($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return $this->available()->has($key);
    }

    /**
     * @param $code
     * @return int
     */
    public function add($code)
    {
        if (!$this->all()->has($code)) {
            throw new \InvalidArgumentException('Invalid language code: ' . $code);
        }

        $available = $this->available();

        if ($available->has($code)) {
            throw new \InvalidArgumentException('Language "' . $code . '" already exists');
        }

        $lang = $this->all()->get($code);
        $lang['enabled'] = true;
        $available->put($code, $lang);

        $this->settings->set('languages', $available->toArray());

        return $this->settings->save();
    }

    /**
     * @param array|string $keys
     * @return int
     */
    public function delete($keys)
    {
        $languages = $this->available()->except($keys);
        $this->settings->set('languages', $languages->toArray());

        return $this->settings->save();
    }

    public function enable($key)
    {
        return $this->setStatus($key, true);
    }

    public function disable($key)
    {
        return $this->setStatus($key, false);
    }

    /**
     * @param string $key
     * @param bool $status
     * @return int
     */
    public function setStatus($key, $status)
    {
        $languages = $this->available();
        $language = $languages->get($key);

        if (!$language) {
            throw new \InvalidArgumentException('Language "' . $key . '" not found');
        }

        $language->setEnabled((bool) $status);
        $languages->put($key, $language);

        $this->settings->set('languages', $languages->toArray());

        return $this->settings->save();
    }

    /**
     * @param array $keys
     * @param bool $status
     * @return int
     */
    public function setStatuses(array $keys, $status)
    {
        $languages = $this->available()->map(function(Language $item, $key) use ($keys, $status) {
            if (in_array($key, $keys)) {
                $item->setEnabled($status);
            }

            return $item;
        });

        $this->settings->set('languages', $languages->toArray());

        return $this->settings->save();
    }

    public function save(Language $language)
    {
        $languages = $this->available();
        $languages->put($language->getIso(), $language);

        $this->settings->set('languages', $languages->toArray());

        return $this->settings->save();
    }

    /**
     * @return array
     */
    public function locales()
    {
        return $this->enabled()->keys()->toArray();
    }
}