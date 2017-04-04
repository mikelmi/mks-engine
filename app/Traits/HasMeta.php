<?php
/**
 * Author: mike
 * Date: 04.04.17
 * Time: 13:29
 */

namespace App\Traits;


use App\Models\MetaTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class HasMeta
 * @package App\Traits
 *
 * @property MetaTag|null $metaTag
 */
trait HasMeta
{
    /**
     * @var MetaTag
     */
    protected $metaTag;

    /**
     * @var array
     */
    protected $metaData;

    protected static function bootHasMeta()
    {
        static::deleting(function (Model $model) {
            $model->metaTag()->delete();
        });

        static::saved(function(Model $model) {
            $meta = $model->getMeta();

            if ($meta instanceof MetaTag) {
                if ($meta->isEmpty()) {
                    $model->metaTag()->delete();
                } else {
                    $model->metaTag()->save($meta);
                }
            }
        });
    }

    /**
     * @return MorphOne
     */
    public function metaTag()
    {
        return $this->morphOne(MetaTag::class, 'model');
    }

    /**
     * @param array $data
     */
    public function setMeta(array $data)
    {
        $this->getMetaOrCreate()->fill($data);
    }

    /**
     * @return MetaTag|null
     */
    public function getMeta()
    {
        if (!$this->metaTag) {
            $this->metaTag = $this->getAttribute('metaTag');
        }

        return $this->metaTag;
    }

    /**
     * @return MetaTag
     */
    public function getMetaOrCreate(): MetaTag
    {
        return $this->getMeta() ?: ($this->metaTag = new MetaTag());
    }

    /**
     * @param $value
     */
    public function setMetaAttribute($value)
    {
        $this->setMeta($value);
    }

    /**
     * @return MetaTag|null
     */
    public function getMetaAttribute()
    {
        return $this->getMeta();
    }

    /**
     * @return bool
     */
    public function hasMeta(): bool
    {
        return !empty($this->getMeta());
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return MorphOne|mixed|null
     */
    public function meta(string $key = null, $default = null)
    {
        if (!$key) {
            return $this->metaTag();
        }

        return $this->getMetaData($key, $default);
    }

    /**
     * @return array
     */
    public function getMetaArray(): array
    {
        return $this->getMetaOrCreate()->toArray();
    }

    /**
     * @param string|null $key
     * @param string|null $default
     * @return array|string|null
     */
    public function getMetaData(string $key = null, string $default = null)
    {
        if (!isset($this->metaData)) {
            $this->metaData = [];

            if (method_exists($this, 'getDefaultMeta')) {
                $this->metaData = $this->getDefaultMeta();
            }

            if ($meta = $this->getMeta()) {
                $this->metaData = $meta->values($this->metaData);
            }
        }

        if ($key) {
            return array_get($this->metaData, $key, $default);
        }

        return $this->metaData;
    }
}