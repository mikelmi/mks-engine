<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MetaTag
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $author
 * @property string $raw
 */
class MetaTag extends Model
{
    public $timestamps = false;

    protected $table = 'meta_tags';

    protected $fillable = ['title', 'description', 'keywords', 'author', 'raw'];

    protected $attributes = [
        'title' => '',
        'description' => '',
        'keywords' => '',
        'author' => '',
        'raw' => '',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $attributes = array_only($this->getAttributes(), $this->getFillable());

        foreach ($attributes as $value) {
            if (!empty($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $default
     * @return array
     */
    public function values(array $default = []): array
    {
        $result = $this->toArray();

        foreach ($default as $key => $value) {
            if (array_key_exists($key, $result) && empty($result[$key])) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
