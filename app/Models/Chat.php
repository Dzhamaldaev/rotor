<?php

namespace App\Models;

class Chat extends BaseModel
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
    public function editUser()
    {
        return $this->belongsTo(User::class, 'edit_user_id')->withDefault();
    }
}
