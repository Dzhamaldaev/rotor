<?php

namespace App\Models;

class Post extends BaseModel
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

    /**
     * Возвращает топик
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id')->withDefault();
    }

    /**
     * Возвращает загруженные файлы
     */
    public function files()
    {
        return $this->morphMany(File::class, 'relate');
    }

    /**
     * Удаление поста и загруженных файлов
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        if ($this->files->isNotEmpty()) {
            foreach ($this->files as $file) {
                deleteImage('uploads/forum/', $this->topic_id . '/' . $file->hash);
                $file->delete();
            }
        }

        return parent::delete();
    }
}
