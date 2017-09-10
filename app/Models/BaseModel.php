<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Возвращает связь пользователей
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Возвращает объект пользователя
     */
    public function getUser()
    {
        return $this->user ?? new User();
    }

    /**
     * Возвращает логин пользователя
     *
     * @param string $value
     * @return string
     */
    public function getLoginAttribute($value)
    {
        return $value ?? setting('guestsuser');
    }
}
