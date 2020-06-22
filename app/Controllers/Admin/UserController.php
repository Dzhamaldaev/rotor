<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Classes\Validator;
use App\Models\Banhist;
use App\Models\BlackList;
use App\Models\Comment;
use App\Models\File;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends AdminController
{
    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();

        if (! isAdmin(User::BOSS)) {
            abort(403, __('errors.forbidden'));
        }
    }

    /**
     * Главная страница
     *
     * @return string
     */
    public function index(): string
    {
        $users = User::query()
            ->orderByDesc('created_at')
            ->paginate(setting('userlist'));

        return view('admin/users/index', compact('users'));
    }

    /**
     * Поиск пользователей
     *
     * @param Request $request
     * @return string
     */
    public function search(Request $request): string
    {
        $q = check($request->input('q'));

        $search = $q === '1' ? "RLIKE '^[-0-9]'" : "LIKE '$q%'";

        $users = User::query()
            ->whereRaw('login ' . $search)
            ->orderByDesc('point')
            ->paginate(setting('usersearch'))
            ->appends(['q' => $q]);

        return view('admin/users/search', compact('users'));
    }

    /**
     * Редактирование пользователя
     *
     * @param Request   $request
     * @param Validator $validator
     * @return string
     */
    public function edit(Request $request, Validator $validator): string
    {
        $login = check($request->input('user'));
        $user  = getUserByLogin($login);

        if (! $user) {
            abort(404, __('validator.user'));
        }

        $allThemes   = array_map('basename', glob(HOME . '/themes/*', GLOB_ONLYDIR));
        $adminGroups = User::ADMIN_GROUPS;

        $allGroups   = [];
        foreach (User::ALL_GROUPS as $level) {
            $allGroups[$level] = User::getLevelByKey($level);
        }

        if ($request->isMethod('post')) {
            $token     = check($request->input('token'));
            $level     = check($request->input('level'));
            $password  = check($request->input('password'));
            $email     = check($request->input('email'));
            $name      = check($request->input('name'));
            $country   = check($request->input('country'));
            $city      = check($request->input('city'));
            $site      = check($request->input('site'));
            $birthday  = check($request->input('birthday'));
            $point     = int($request->input('point'));
            $money     = int($request->input('money'));
            $status    = check($request->input('status'));
            $posrating = int($request->input('posrating'));
            $negrating = int($request->input('negrating'));
            $themes    = check($request->input('themes'));
            $gender    = $request->input('gender') === 'male' ? 'male' : 'female';
            $info      = check($request->input('info'));
            $created   = check($request->input('created'));

            $validator->equal($token, $_SESSION['token'], __('validator.token'))
                ->in($level, User::ALL_GROUPS, ['level' => __('users.user_level_invalid')])
                ->length($password, 6, 20, __('users.password_length_requirements'), false)
                ->email($email, ['email' => __('validator.email')])
                ->regex($site, '#^https?://([а-яa-z0-9_\-\.])+(\.([а-яa-z0-9\/])+)+$#u', ['site' => __('validator.url')], false)
                ->regex($birthday, '#^[0-9]{2}+\.[0-9]{2}+\.[0-9]{4}$#', ['birthday' => __('validator.date')], false)
                ->regex($created, '#^[0-9]{2}+\.[0-9]{2}+\.[0-9]{4}$#', ['created' => __('validator.date')], false)
                ->length($status, 3, 20, ['status' => __('users.status_short_or_long')], false)
                ->true(in_array($themes, $allThemes, true) || empty($themes), ['themes' => __('users.theme_not_installed')])
                ->length($info, 0, 1000, ['info' => __('users.info_yourself_long')]);

            if ($validator->isValid()) {
                if ($password) {
                    $text     = '<br>' . __('users.user_new_password', ['password' => $password]);
                    $password = password_hash($password, PASSWORD_BCRYPT);
                } else {
                    $text     = null;
                    $password = $user->password;
                }

                $name    = utfSubstr($name, 0, 20);
                $country = utfSubstr($country, 0, 30);
                $city    = utfSubstr($city, 0, 50);
                $rating  = $posrating - $negrating;

                $user->update([
                    'password'   => $password,
                    'level'      => $level,
                    'email'      => $email,
                    'name'       => $name,
                    'country'    => $country,
                    'city'       => $city,
                    'site'       => $site,
                    'birthday'   => $birthday,
                    'point'      => $point,
                    'money'      => $money,
                    'status'     => $status,
                    'rating'     => $rating,
                    'posrating'  => $posrating,
                    'negrating'  => $negrating,
                    'themes'     => $themes,
                    'gender'     => $gender,
                    'info'       => $info,
                    'created_at' => strtotime($created),
                ]);

                clearCache('statuses');
                setFlash('success', __('users.user_success_changed') . $text);
                redirect('/admin/users/edit?user=' . $user->login);

            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $banhist = Banhist::query()
            ->where('user_id', $user->id)
            ->whereIn('type', ['ban', 'change'])
            ->orderByDesc('created_at')
            ->first();

        return view('admin/users/edit', compact('user', 'banhist', 'allThemes', 'allGroups', 'adminGroups'));
    }

    /**
     * Удаление пользователя
     *
     * @param Request   $request
     * @param Validator $validator
     * @return string
     * @throws Exception
     */
    public function delete(Request $request, Validator $validator): string
    {
        $login = check($request->input('user'));
        $user  = getUserByLogin($login);

        if (! $user) {
            abort(404, __('validator.user'));
        }

        if ($request->isMethod('post')) {
            $token       = check($request->input('token'));
            $loginblack  = empty($request->input('loginblack')) ? 0 : 1;
            $mailblack   = empty($request->input('mailblack')) ? 0 : 1;
            $deltopics   = empty($request->input('deltopics')) ? 0 : 1;
            $delposts    = empty($request->input('delposts')) ? 0 : 1;
            $delcomments = empty($request->input('delcomments')) ? 0 : 1;
            $delimages   = empty($request->input('delimages')) ? 0 : 1;

            $validator->equal($token, $_SESSION['token'], __('validator.token'))
                ->notIn($user->level, User::ADMIN_GROUPS, __('users.admins_remove_forbidden'));

            if ($validator->isValid()) {
                if ($loginblack) {
                    $duplicate = BlackList::query()->where('type', 'login')->where('value', $user->login)->first();
                    if (! $duplicate) {
                        BlackList::query()->create([
                            'type'       => 'login',
                            'value'      => $user->login,
                            'user_id'    => getUser('id'),
                            'created_at' => SITETIME,
                        ]);
                    }
                }

                if ($mailblack) {
                    $duplicate = BlackList::query()->where('type', 'email')->where('value', $user->email)->first();
                    if (! $duplicate) {
                        BlackList::query()->create([
                            'type'       => 'email',
                            'value'      => $user->email,
                            'user_id'    => getUser('id'),
                            'created_at' => SITETIME,
                        ]);
                    }
                }

                // Удаление тем форума
                if ($deltopics) {
                    $topics = Topic::query()->where('user_id', $user->id)->pluck('id')->all();
                    $posts  = Post::query()->whereIn('topic_id', $topics)->pluck('id')->all();

                    // Удаление загруженных файлов
                    if ($posts) {
                        $files = File::query()
                            ->where('relate_type', Post::$morphName)
                            ->whereIn('relate_id', $posts)
                            ->get();

                        if ($files->isNotEmpty()) {
                            foreach ($files as $file) {
                                $file->delete();
                            }
                        }
                    }

                    Post::query()->whereIn('topic_id', $topics)->delete();
                    Topic::query()->where('user_id', $user->id)->delete();
                    restatement('forums');
                }

                // Удаление постов форума
                if ($delposts) {
                    $posts  = Post::query()->where('user_id', $user->id)->pluck('id')->all();

                    // Удаление загруженных файлов
                    if ($posts) {
                        $files = File::query()
                            ->where('relate_type', Post::$morphName)
                            ->whereIn('relate_id', $posts)
                            ->get();

                        if ($files->isNotEmpty()) {
                            foreach ($files as $file) {
                                $file->delete();
                            }
                        }
                    }

                    Post::query()->where('user_id', $user->id)->delete();
                    restatement('forums');
                }

                // Удаление комментариев
                if ($delcomments) {
                    $deletes = Comment::query()
                        ->where('user_id', $user->id)
                        ->delete();

                    if ($deletes) {
                        restatement('blogs');
                        restatement('loads');
                        restatement('news');
                        restatement('photos');
                        restatement('offers');
                    }
                }

                // Удаление фотографий в галерее
                if ($delimages) {
                    $user->deleteAlbum();
                }

                $user->delete();

                setFlash('success', __('users.user_success_deleted'));
                redirect('/admin/users');
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/users/delete', compact('user'));
    }
}
