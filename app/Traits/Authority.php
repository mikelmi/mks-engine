<?php

namespace App\Traits;


use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class Authority
 * @package App\Traits
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $author
 * @property string $author_name
 */
trait Authority
{
    /**
     * add authors columns to the table
     *
     * @param Blueprint $table
     */
    public static function addColumns(Blueprint $table)
    {
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('updated_by')->nullable();

        $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('SET NULL');
    }

    /**
     * drop authors columns from table
     * 
     * @param Blueprint $table
     */
    public static function dropColumns(Blueprint $table)
    {
        $table->dropColumn(['created_by', 'updated_by']);
    }
    
    protected static function bootAuthority()
    {
        static::creating(function (Model $model) {
            $model->setAuthor('creating');
        });

        static::updating(function (Model $model) {
            $model->setAuthor('updating');
        });
    }

    protected function setAuthor($type)
    {
        if ($userId = \Auth::id()) {

            if ($type == 'creating') {
                $this->created_by = $userId;
            }

            $this->updated_by = $userId;
        }
    }

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return BelongsTo
     */
    public function author()
    {
        return $this->createdBy();
    }

    /**
     * @return null|string
     */
    public function getAuthorName()
    {
        $author = $this->author;

        return $author ? $author->name : null;
    }

    public function getAuthorNameAttribute()
    {
        return $this->getAuthorName();
    }

    public function scopeWithAuthor(Builder $query)
    {
        return $query->with('author');
    }
}