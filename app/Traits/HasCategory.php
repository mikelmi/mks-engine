<?php
/**
 * Author: mike
 * Date: 12.05.17
 * Time: 19:15
 */

namespace App\Traits;

use App\Models\Category;

/**
 * Trait HasCategory
 * @package App\Traits
 *
 * @property int $category_id
 * @property Category $category
 */
trait HasCategory
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}