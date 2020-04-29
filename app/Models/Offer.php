<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Offer
 *
 * @property int id
 * @property string type
 * @property string title
 * @property string text
 * @property int user_id
 * @property int rating
 * @property int created_at
 * @property string status
 * @property int count_comments
 * @property int closed
 * @property string reply
 * @property int reply_user_id
 * @property int updated_at
 */
class Offer extends BaseModel
{
    public const DONE    = 'done';
    public const WAIT    = 'wait';
    public const CANCEL  = 'cancel';
    public const PROCESS = 'process';

    /**
     * Статусы
     */
    public const STATUSES = [
        self::DONE,
        self::WAIT,
        self::CANCEL,
        self::PROCESS,
    ];

    public const OFFER = 'offer';
    public const ISSUE = 'issue';

    /**
     * Типы
     */
    public const TYPES = [
        self::OFFER,
        self::ISSUE,
    ];

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
     * Morph name
     *
     * @var string
     */
    public static $morphName = 'offers';

    /**
     * Возвращает связь с голосованием
     *
     * @return morphOne
     */
    public function polling(): morphOne
    {
        return $this->morphOne(Polling::class, 'relate')->where('user_id', getUser('id'));
    }

    /**
     * Возвращает связь пользователей
     *
     * @return BelongsTo
     */
    public function replyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reply_user_id')->withDefault();
    }

    /**
     * Возвращает последнии комментарии
     *
     * @param int $limit
     * @return HasMany
     */
    public function lastComments($limit = 15): HasMany
    {
        return $this->hasMany(Comment::class, 'relate_id')
            ->where('relate_type', self::class)
            ->orderBy('created_at')
            ->with('user')
            ->limit($limit);
    }

    /**
     * Возвращает статус записи
     *
     * @return string
     */
    public function getStatus(): string
    {
        switch ($this->status) {
            case 'process':
                $status = '<span class="font-weight-bold text-primary"><i class="fa fa-spinner"></i> ' . __('offers.process') . '</span>';
                break;
            case 'done':
                $status = '<span class="font-weight-bold text-success"><i class="fa fa-check-circle"></i> ' . __('offers.done') . '</span>';
                break;
            case 'cancel':
                $status = '<span class="font-weight-bold text-danger"><i class="fa fa-times-circle"></i> ' . __('offers.cancel') . '</span>';
                break;
            default:
                $status = '<span class="font-weight-bold text-warning"><i class="fa fa-question-circle"></i> ' . __('offers.wait') . '</span>';
        }

        return $status;
    }

}
