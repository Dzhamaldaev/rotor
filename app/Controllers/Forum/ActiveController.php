<?php

declare(strict_types=1);

namespace App\Controllers\Forum;

use App\Classes\Validator;
use App\Controllers\BaseController;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;

class ActiveController extends BaseController
{
    public $user;

    /**
     * Конструктор
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        $login      = check($request->input('user', getUser('login')));
        $this->user = getUserByLogin($login);

        if (! $this->user) {
            abort(404, __('validator.user'));
        }
    }

    /**
     * Вывод тем
     *
     * @return string
     */
    public function topics(): string
    {
        $user  = $this->user;
        $total = Topic::query()->where('user_id', $user->id)->count();

        if (! $total) {
            abort('default', __('forums.topics_not_created'));
        }

        $page = paginate(setting('forumtem'), $total);

        $topics = Topic::query()
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit($page->limit)
            ->offset($page->offset)
            ->with('forum', 'user', 'lastPost.user')
            ->get();

        return view('forums/active_topics', compact('topics', 'user', 'page'));
    }

    /**
     * Вывод сообшений
     *
     * @return string
     */
    public function posts(): string
    {
        $user  = $this->user;
        $total = Post::query()->where('user_id', $user->id)->count();

        if (! $total) {
            abort('default', __('forums.posts_not_created'));
        }

        $page = paginate(setting('forumpost'), $total);

        $posts = Post::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($page->limit)
            ->offset($page->offset)
            ->with('topic', 'user')
            ->get();

        return view('forums/active_posts', compact('posts', 'user', 'page'));
    }

    /**
     * Удаление сообщений
     *
     * @param Request   $request
     * @param Validator $validator
     * @return string
     * @throws \Exception
     */
    public function delete(Request $request, Validator $validator): string
    {
        if (! $request->ajax()) {
            redirect('/');
        }

        if (! isAdmin()) {
            abort(403, __('forums.posts_deleted_moderators'));
        }

        $token = check($request->input('token'));
        $tid   = int($request->input('tid'));

        $validator->equal($token, $_SESSION['token'], __('validator.token'));

        $post = Post::query()
            ->where('id', $tid)
            ->with('topic.forum')
            ->first();

        $validator->true($post, __('forums.post_not_exist'));

        if ($validator->isValid()) {
            $post->delete();
            $post->topic->decrement('count_posts');
            $post->topic->forum->decrement('count_posts');

            return json_encode(['status' => 'success']);
        }

        return json_encode(['status' => 'error', 'message' => current($validator->getErrors())]);
    }
}

