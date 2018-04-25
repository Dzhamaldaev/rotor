<?php

namespace App\Controllers\Forum;

use App\Controllers\BaseController;
use App\Models\Post;
use App\Models\Topic;

class NewController extends BaseController
{
    /**
     * Вывод тем
     */
    public function themes()
    {
        $total = Topic::query()->count();

        if (! $total) {
            abort('default', 'Созданных тем еще нет!');
        }

        if ($total > 500) {
            $total = 500;
        }

        $page = paginate(setting('forumtem'), $total);

        $topics = Topic::query()
            ->orderBy('updated_at', 'desc')
            ->limit($page->limit)
            ->offset($page->offset)
            ->with('forum', 'user', 'lastPost.user')
            ->get();

        return view('forums/new_themes', compact('topics', 'page'));
    }

    /**
     * Вывод сообшений
     */
    public function posts()
    {
        $total = Post::query()->count();

        if (! $total) {
            abort('default', 'Созданных сообщений еще нет!');
        }

        if ($total > 500) {
            $total = 500;
        }

        $page = paginate(setting('forumpost'), $total);

        $posts = Post::query()
            ->orderBy('created_at', 'desc')
            ->limit($page->limit)
            ->offset($page->offset)
            ->with('topic', 'user')
            ->get();

        return view('forums/new_posts', compact('posts', 'page'));
    }
}
