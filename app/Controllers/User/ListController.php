<?php

namespace App\Controllers\User;

use App\Classes\Request;
use App\Controllers\BaseController;
use App\Models\User;

class ListController extends BaseController
{
    /**
     * Список пользователей
     */
    public function userlist()
    {
        $total = User::query()->count();
        $page = paginate(setting('userlist'), $total);

        $users = User::query()
            ->orderBy('point', 'desc')
            ->orderBy('login')
            ->offset($page->offset)
            ->limit($page->limit)
            ->get();

        $user = check(Request::input('user', getUser('login')));

        if (Request::isMethod('post')) {

            $position = User::query()
                ->orderBy('point', 'desc')
                ->orderBy('login')
                ->get()
                ->where('login', $user)
                ->keys()
                ->first();

            if (isset($position)) {
                $position += 1;
                $end = ceil($position / $page->limit);

                setFlash('success', 'Позиция в рейтинге: '.$position);
                redirect('/users?page='.$end.'&user='.$user);
            } else {
                setFlash('danger', 'Пользователь не найден!');
            }
        }

        return view('users/users', compact('users', 'page', 'user'));
    }

    /**
     * Список админов
     */
    public function adminlist()
    {
        $users = User::query()
            ->whereIn('level', User::ADMIN_GROUPS)
            ->orderByRaw("field(level, '".implode(',', User::ADMIN_GROUPS)."')")
            ->get();

        return view('users/administrators', compact('users'));
    }

    /**
     * Рейтинг репутации
     */
    public function authoritylist()
    {
        $total = User::query()->count();
        $page = paginate(setting('avtorlist'), $total);

        $users = User::query()
            ->orderBy('rating', 'desc')
            ->orderBy('login')
            ->offset($page->offset)
            ->limit($page->limit)
            ->get();

        $user = check(Request::input('user', getUser('login')));

        if (Request::isMethod('post')) {

            $position = User::query()
                ->orderBy('rating', 'desc')
                ->orderBy('login')
                ->get()
                ->where('login', $user)
                ->keys()
                ->first();

            if (isset($position)) {
                $position += 1;
                $end = ceil($position / $page->limit);

                setFlash('success', 'Позиция в рейтинге: '.$position);
                redirect('/authoritylists?page='.$end.'&user='.$user);
            } else {
                setFlash('danger', 'Пользователь не найден!');
            }
        }

        return view('users/authoritylists', compact('users', 'page', 'user'));
    }

    /**
     * Рейтинг толстосумов
     */
    public function ratinglist()
    {
        $total = User::query()->count();
        $page = paginate(setting('userlist'), $total);

        $users = User::query()
            ->orderBy('money', 'desc')
            ->orderBy('login')
            ->offset($page->offset)
            ->limit($page->limit)
            ->get();

        $user = check(Request::input('user', getUser('login')));

        if (Request::isMethod('post')) {

            $position = User::query()
                ->orderBy('money', 'desc')
                ->orderBy('login')
                ->get()
                ->where('login', $user)
                ->keys()
                ->first();

            if (isset($position)) {
                $position += 1;
                $end = ceil($position / $page->limit);

                setFlash('success', 'Позиция в рейтинге: '.$position);
                redirect('/ratinglists?page='.$end.'&user='.$user);
            } else {
                setFlash('danger', 'Пользователь не найден!');
            }
        }

        return view('users/ratinglists', compact('users', 'page', 'user'));
    }
}
