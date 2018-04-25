<?php

namespace App\Models;

class Wall extends BaseModel
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Возвращает связь пользователей
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id')->withDefault();
    }
}
