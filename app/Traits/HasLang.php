<?php
/**
 * User: mike
 * Date: 30.11.17
 * Time: 2:38
 */

namespace App\Traits;
use Illuminate\Database\Query\Builder;

/**
 * Trait HasLang
 * @package App\Traits
 *
 * @property string $lang
 */
trait HasLang
{
    /**
     * @param Builder $query
     * @param string $lang
     * @return mixed
     */
    public function scopeByLang($query, $lang = null)
    {
        if ($lang) {
            return $query->where(function ($query) use ($lang) {
                $query->where('lang', $lang)
                      ->orWhereNull('lang')
                      ->orWhere('lang', '');
            });
        }
        return $query;
    }
}