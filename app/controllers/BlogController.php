<?php

class BlogController extends BaseController
{
    /**
     * Главная страница
     */
    public function index()
    {
        $blogs = CatsBlog::orderBy('sort')
            ->with('new')
            ->get()
            ->all();

        if (!$blogs) {
            App::abort('default', 'Разделы блогов еще не созданы!');
        }

        App::view('blog/index', compact('blogs'));
    }

    /**
     * Список блогов
     */
    public function blog($cid)
    {
        $category = CatsBlog::find($cid);

        if (! $category) {
            App::abort('default', 'Данного раздела не существует!');
        }

        $total = Blog::where('category_id', $cid)->count();

        $page = App::paginate(Setting::get('blogpost'), $total);

        $blogs = Blog::where('category_id', $cid)
            ->orderBy('created_at', 'desc')
            ->offset($page['offset'])
            ->limit(Setting::get('blogpost'))
            ->with('user')
            ->get();

        App::view('blog/blog', compact('blogs', 'category', 'page'));
    }

    /**
     * Просмотр статьи
     */
    public function view($id)
    {

        $blog = Blog::select('blogs.*', 'catsblog.name', 'pollings.vote')
            ->where('blogs.id', $id)
            ->leftJoin('catsblog', function ($join) {
                $join->on('blogs.category_id', '=', 'catsblog.id');
            })
            ->leftJoin('pollings', function ($join) {
                $join->on('blogs.id', '=', 'pollings.relate_id')
                    ->where('pollings.relate_type', Blog::class)
                    ->where('pollings.user_id', App::getUserId());
            })
            ->first();

        if (! $blog) {
            App::abort(404, 'Данной статьи не существует!');
        }

        $text = preg_split('|\[nextpage\](<br * /?>)*|', $blog['text'], -1, PREG_SPLIT_NO_EMPTY);

        $total = count($text);
        $page = App::paginate(1, $total);

        if ($page['current'] == 1) {
            $reads = Read::where('relate_type', Blog::class)
                ->where('relate_id', $id)
                ->where('ip', App::getClientIp())
                ->first();

            if (! $reads) {
                $expiresRead = SITETIME + 3600 * Setting::get('blogexpread');

                Read::where('relate_type', Blog::class)
                    ->where('created_at', '<', SITETIME)
                    ->delete();

                Read::create([
                    'relate_type' => Blog::class,
                    'relate_id'   => $id,
                    'ip'          => App::getClientIp(),
                    'created_at'  => $expiresRead,
                ]);

                $blog->increment('visits');
            }
        }

        $end = ($total < $page['offset'] + 1) ? $total : $page['offset'] + 1;

        for ($i = $page['offset']; $i < $end; $i++) {
            $blog['text'] = App::bbCode($text[$i]) . '<br>';
        }

        $tagsList = preg_split('/[\s]*[,][\s]*/', $blog['tags']);

        $tags = '';
        foreach ($tagsList as $key => $value) {
            $comma = (empty($key)) ? '' : ', ';
            $tags .= $comma . '<a href="/blog/tags/' . urlencode($value) . '">' . $value . '</a>';
        }

        App::view('blog/view', compact('blog', 'tags', 'page'));
    }

    /**
     * Редактированиe статьи
     */
    public function edit($id)
    {
        if (! is_user()) {
            App::abort(403, 'Для редактирования статьи необходимо авторизоваться');
        }

        $blog = Blog::find($id);

        if (! $blog) {
            App::abort(404, 'Данной статьи не существует!');
        }

        if ($blog->user_id != App::getUserId()) {
            App::abort('default', 'Изменение невозможно, вы не автор данной статьи!');
        }

        if (Request::isMethod('post')) {

            $token = check(Request::input('token'));
            $cid   = abs(intval(Request::input('cid')));
            $title = check(Request::input('title'));
            $text  = check(Request::input('text'));
            $tags  = check(Request::input('tags'));

            $category = CatsBlog::find($cid);

            $validation = new Validation();
            $validation
                ->addRule('equal', [$token, $_SESSION['token']], 'Неверный идентификатор сессии, повторите действие!')
                ->addRule('string', $title, ['title' => 'Слишком длинный или короткий заголовок!'], true, 5, 50)
                ->addRule('string', $text, ['text' => 'Слишком длинный или короткий текст статьи!'], true, 100, Setting::get('maxblogpost'))
                ->addRule('string', $tags, ['tags' => 'Слишком длинные или короткие метки статьи!'], true, 2, 50)
                ->addRule('bool', Flood::isFlood(), ['text' => 'Антифлуд! Разрешается добавлять статьи раз в ' . Flood::getPeriod() . ' секунд!'])
                ->addRule('not_empty', $category, ['cid' => 'Раздела для статьи не существует!']);

            if ($validation->run()) {

                // Обновление счетчиков
                if ($blog->category_id != $category->id) {
                    $category->increment('count');
                    CatsBlog::where('id', $blog->category_id)->decrement('count');
                }

                $blog->update([
                    'category_id' => $category->id,
                    'title'       => $title,
                    'text'        => $text,
                    'tags'        => $tags,
                ]);

                App::setFlash('success', 'Статья успешно отредактирована!');
                App::redirect('/article/'.$blog->id);
            } else {
                App::setInput(Request::all());
                App::setFlash('danger', $validation->getErrors());
            }
        }

        $cats = CatsBlog::select('id', 'name')
            ->pluck('name', 'id')
            ->all();

        App::view('blog/edit', compact('blog', 'cats'));
    }

    /**
     * Просмотр по категориям
     */
    public function blogs()
    {
        $total = Blog::distinct('user_id')
            ->join('users', 'blogs.user_id', '=', 'users.id')
            ->count('user_id');

        $page = App::paginate(Setting::get('bloggroup'), $total);

        $blogs = Blog::select('user_id', 'login')
            ->selectRaw('count(*) as cnt, sum(comments) as comments')
            ->join('users', 'blogs.user_id', '=', 'users.id')
            ->offset($page['offset'])
            ->limit($page['limit'])
            ->groupBy('user_id')
            ->orderBy('cnt', 'desc')
            ->get();

        App::view('blog/user_blogs', compact('blogs', 'page'));
    }

    /**
     * Создание статьи
     */
    public function create()
    {
        $cid = abs(intval(Request::input('cid')));

        if (! is_user()) {
            App::abort(403, 'Для публикации новой статьи необходимо авторизоваться');
        }

        $cats = CatsBlog::select('id', 'name')
            ->pluck('name', 'id')
            ->all();

        if (! $cats) {
            App::abort('default', 'Разделы блогов еще не созданы!');
        }

        if (Request::isMethod('post')) {

            $token = check(Request::input('token'));
            $title = check(Request::input('title'));
            $text  = check(Request::input('text'));
            $tags  = check(Request::input('tags'));

            $category = CatsBlog::find($cid);

            $validation = new Validation();
            $validation
                ->addRule('equal', [$token, $_SESSION['token']], 'Неверный идентификатор сессии, повторите действие!')
                ->addRule('string', $title, ['title' => 'Слишком длинный или короткий заголовок!'], true, 5, 50)
                ->addRule('string', $text, ['text' => 'Слишком длинный или короткий текст статьи!'], true, 100, Setting::get('maxblogpost'))
                ->addRule('string', $tags, ['tags' => 'Слишком длинные или короткие метки статьи!'], true, 2, 50)
                ->addRule('bool', Flood::isFlood(), ['text' => 'Антифлуд! Разрешается добавлять статьи раз в ' . Flood::getPeriod() . ' секунд!'])
                ->addRule('not_empty', $category, ['cid' => 'Раздела для новой статьи не существует!']);

            if ($validation->run()) {

                $text = antimat($text);

                $article = Blog::create([
                    'category_id' => $cid,
                    'user_id'     => App::getUserId(),
                    'title'       => $title,
                    'text'        => $text,
                    'tags'        => $tags,
                    'created_at'  => SITETIME,
                ]);

                $category->increment('count');

                $user = User::where('id', App::getUserId());
                $user->update([
                    'point' => Capsule::raw('point + 5'),
                    'money' => Capsule::raw('money + 100'),
                ]);

                App::setFlash('success', 'Статья успешно опубликована!');
                App::redirect('/article/'.$article->id);
            } else {
                App::setInput(Request::all());
                App::setFlash('danger', $validation->getErrors());
            }
        }

        App::view('blog/create', ['cats' => $cats, 'cid' => $cid]);
    }

    /**
     * Комментарии
     */
    public function comments($id)
    {
        $blog = Blog::where('id', $id)->first();

        if (!$blog) {
            App::abort('default', 'Данной статьи не существует!');
        }

        if (Request::isMethod('post')) {
            $token = check(Request::input('token'));
            $msg = check(Request::input('msg'));

            $validation = new Validation();
            $validation
                ->addRule('bool', is_user(), 'Чтобы добавить комментарий необходимо авторизоваться')
                ->addRule('equal', [$token, $_SESSION['token']], 'Неверный идентификатор сессии, повторите действие!')
                ->addRule('string', $msg, ['msg' => 'Слишком длинное или короткое название!'], true, 5, 1000)
                ->addRule('bool', Flood::isFlood(), ['msg' => 'Антифлуд! Разрешается отправлять сообщения раз в ' . Flood::getPeriod() . ' секунд!']);

            if ($validation->run()) {
                $msg = antimat($msg);

                Comment::create([
                    'relate_type' => Blog::class,
                    'relate_id'   => $blog->id,
                    'text'        => $msg,
                    'user_id'     => App::getUserId(),
                    'created_at'  => SITETIME,
                    'ip'          => App::getClientIp(),
                    'brow'        => App::getUserAgent(),
                ]);

                $user = User::where('id', App::getUserId());
                $user->update([
                    'allcomments' => Capsule::raw('allcomments + 1'),
                    'point'       => Capsule::raw('point + 1'),
                    'money'       => Capsule::raw('money + 5'),
                ]);

                $blog->update([
                    'comments' => Capsule::raw('comments + 1'),
                ]);

                App::setFlash('success', 'Комментарий успешно добавлен!');
                App::redirect('/article/' . $blog->id . '/end');
            } else {
                App::setInput(Request::all());
                App::setFlash('danger', $validation->getErrors());
            }
        }

        $total = Comment::where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->count();

        $page = App::paginate(Setting::get('blogcomm'), $total);

        $comments = Comment::where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->orderBy('created_at')
            ->offset($page['offset'])
            ->limit(Setting::get('blogcomm'))
            ->get();

        App::view('blog/comments', compact('blog', 'comments', 'page'));
    }


    /**
     * Подготовка к редактированию комментария
     */
    public function editComment($id, $cid)
    {
        $page = abs(intval(Request::input('page', 1)));

        if (!is_user()) {
            App::abort(403, 'Для редактирования комментариев небходимо авторизоваться!');
        }

        $comment = Comment::where('relate_type', Blog::class)
            ->where('id', $cid)
            ->where('user_id', App::getUserId())
            ->first();

        if (!$comment) {
            App::abort('default', 'Комментарий удален или вы не автор этого комментария!');
        }

        if ($comment['created_at'] + 600 < SITETIME) {
            App::abort('default', 'Редактирование невозможно, прошло более 10 минут!');
        }

        App::view('blog/editcomment', compact('comment', 'page'));
    }

    /**
     * Редактирование комментария
     */
    public function editpost($id)
    {
        $uid = check(Request::input('uid'));
        $pid = abs(intval(Request::input('pid')));
        $msg = check(Request::input('msg'));
        $page = abs(intval(Request::input('page', 1)));

        if (is_user()) {
            if ($uid == $_SESSION['token']) {
                if (utf_strlen($msg) >= 5 && utf_strlen($msg) < 1000) {
                    $post = DB::run()->queryFetch("SELECT * FROM `comments` WHERE relate_type=? AND `id`=? AND `user`=? LIMIT 1;", ['blog', $pid, App::getUsername()]);

                    if (!empty($post)) {
                        if ($post['time'] + 600 > SITETIME) {
                            $msg = antimat($msg);

                            DB::run()->query("UPDATE `comments` SET `text`=? WHERE relate_type=? AND `id`=?", [$msg, 'blog', $pid]);

                            App::setFlash('success', 'Сообщение успешно отредактировано!');
                            App::redirect("/blog/blog?act=comments&id=$id&page=$page");

                        } else {
                            show_error('Ошибка! Редактирование невозможно, прошло более 10 минут!!');
                        }
                    } else {
                        show_error('Ошибка! Сообщение удалено или вы не автор этого сообщения!');
                    }
                } else {
                    show_error('Ошибка! Слишком длинное или короткое сообщение!');
                }
            } else {
                show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }
        } else {
            show_login('Вы не авторизованы, чтобы редактировать сообщения, необходимо');
        }

        App::view('includes/back', ['link' => '/blog/blog?act=edit&amp;id=' . $id . '&amp;pid=' . $pid . '&amp;page=' . $page, 'title' => 'Вернуться']);
    }

    /**
     * Удаление комментариев
     */
    public function del($id)
    {
        $uid = check(Request::input('uid'));
        $page = abs(intval(Request::input('page', 1)));

        if (isset($_POST['del'])) {
            $del = intar($_POST['del']);
        } else {
            $del = 0;
        }

        if (is_admin()) {
            if ($uid == $_SESSION['token']) {
                if (!empty($del)) {
                    $del = implode(',', $del);

                    $delcomments = DB::run()->exec("DELETE FROM `comments` WHERE relate_type='blog' AND `id` IN (" . $del . ") AND `relate_id`=" . $id . ";");
                    DB::run()->query("UPDATE `blogs` SET `comments`=`comments`-? WHERE `id`=?;", [$delcomments, $id]);

                    App::setFlash('success', 'Выбранные комментарии успешно удалены!');
                    App::redirect("/blog/blog?act=comments&id=$id&page=$page");

                } else {
                    show_error('Ошибка! Отстутствуют выбранные комментарии для удаления!');
                }
            } else {
                show_error('Ошибка! Неверный идентификатор сессии, повторите действие!');
            }
        } else {
            show_error('Ошибка! Удалять комментарии могут только модераторы!');
        }

        App::view('includes/back', ['link' => '/blog/blog?act=comments&amp;id=' . $id . '&amp;page=' . $page, 'title' => 'Вернуться']);
    }

    /**
     * Переадресация на последнюю страницу
     */
    public function end($id)
    {

        $blog = Blog::find($id);

        if (empty($blog)) {
            App::abort(404, 'Выбранная вами статья не существует, возможно она была удалена!');
        }

        $total = Comment::where('relate_type', Blog::class)
            ->where('relate_id', $id)
            ->count();

        $end = ceil($total / Setting::get('blogpost'));
        App::redirect('/article/' . $id . '/comments?page=' . $end);
    }

    /**
     * Печать
     */
    public function print($id)
    {
        $blog = Blog::find($id);

        if (empty($blog)) {
            App::abort('default', 'Данной статьи не существует!');
        }

        $blog['text'] = preg_replace('|\[nextpage\](<br * /?>)*|', '', $blog['text']);

        App::view('blog/print', compact('blog'));
    }

    /**
     * RSS всех блогов
     */
    public function rss()
    {
        $blogs = Blog::orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        if ($blogs->isEmpty()) {
            App::abort('default', 'Блоги не найдены!');
        }

        App::view('blog/rss', compact('blogs'));
    }

    /**
     * RSS комментариев к блогу
     */
    public function rssComments($id)
    {
        $blog = Blog::where('id', $id)->with('lastComments')->first();

        if (!$blog) {
            App::abort('default', 'Статья не найдена!');
        }

        App::view('blog/rss_comments', compact('blog'));
    }

    /**
     * Поиск по тегам
     */
    public function tags($tag = null)
    {
        if ($tag) {
            $tag = urldecode($tag);

            if (! is_utf($tag)){
                $tag = win_to_utf($tag);
            }

            if (utf_strlen($tag) < 2) {
                App::setFlash('danger', 'Ошибка! Необходимо не менее 2-х символов в запросе!');
                App::redirect('/blog/tags');
            }

            if (empty($_SESSION['findresult']) || empty($_SESSION['blogfind']) || $tag!=$_SESSION['blogfind']) {

                $result = Blog::select('id')
                    ->where('tags', 'like', '%'.$tag.'%')
                    ->limit(500)
                    ->pluck('id')
                    ->all();

                $_SESSION['blogfind'] = $tag;
                $_SESSION['findresult'] = $result;
            }

            $total = count($_SESSION['findresult']);
            $page = App::paginate(Setting::get('blogpost'), $total);

            $blogs = Blog::select('blogs.*', 'catsblog.name')
                ->whereIn('blogs.id', $_SESSION['findresult'])
                ->join('catsblog', 'blogs.category_id', '=', 'catsblog.id')
                ->orderBy('created_at', 'desc')
                ->offset($page['offset'])
                ->limit(Setting::get('blogpost'))
                ->with('user')
                ->get();

            App::view('blog/tags_search', compact('blogs', 'tag', 'page'));

        } else {
            if (@filemtime(STORAGE."/temp/tagcloud.dat") < time() - 3600) {

                $tags =  Blog::select('tags')
                    ->pluck('tags')
                    ->all();

                $alltag = implode(',', $tags);

                $dumptags = preg_split('/[\s]*[,][\s]*/s', $alltag);
                $tags = array_count_values(array_map('utf_lower', $dumptags));

                arsort($tags);
                array_splice($tags, 100);
                shuffle_assoc($tags);

                file_put_contents(STORAGE."/temp/tagcloud.dat", serialize($tags), LOCK_EX);
            }

            $tags = unserialize(file_get_contents(STORAGE."/temp/tagcloud.dat"));

            $max = max($tags);
            $min = min($tags);

            App::view('blog/tags', compact('tags', 'max', 'min'));
        }
    }

    /**
     * Новые статьи
     */
    public function newArticles()
    {
        $total = Blog::count();

        if ($total > 500) {
            $total = 500;
        }
        $page = App::paginate(Setting::get('blogpost'), $total);

        $blogs = Blog::orderBy('created_at', 'desc')
            ->offset($page['offset'])
            ->limit(Setting::get('blogpost'))
            ->with('user')
            ->get();

        App::view('blog/new_articles', compact('blogs', 'page'));
    }

    /**
     * Новые комментарии
     */
    public function newComments()
    {
        $total = Comment::where('relate_type', Blog::class)->count();

        if ($total > 500) {
            $total = 500;
        }
        $page = App::paginate(Setting::get('blogpost'), $total);

        $comments = Comment::select('comments.*', 'title', 'comments')
            ->where('relate_type', Blog::class)
            ->leftJoin('blogs', 'comments.relate_id', '=', 'blogs.id')
            ->offset($page['offset'])
            ->limit($page['limit'])
            ->orderBy('comments.created_at', 'desc')
            ->with('user')
            ->get();

        App::view('blog/new_comments', compact('comments', 'page'));
    }
}