<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Classes\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class DelUserController extends AdminController
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
     * @param Request $request
     *
     * @return string
     */
    public function index(Request $request): string
    {
        $users  = collect();
        $period = int($request->input('period'));
        $point  = int($request->input('point'));

        if ($request->isMethod('post')) {
            if ($period < 180) {
                abort('default', __('admin.delusers.invalid_period'));
            }

            $users = User::query()
                ->where('updated_at', '<', strtotime('-' . $period . ' days', SITETIME))
                ->where('point', '<=', $point)
                ->get();

            if ($users->isEmpty()) {
                abort('default', __('admin.delusers.users_not_found'));
            }
        }

        $total = User::query()->count();

        return view('admin/delusers/index', compact('users', 'total', 'period', 'point'));
    }

    /**
     * Очистка пользователей
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     */
    public function clear(Request $request, Validator $validator): void
    {
        $period = int($request->input('period'));
        $point  = int($request->input('point'));

        $validator
            ->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
            ->gte($period, 180, __('admin.delusers.invalid_period'));

        $users = User::query()
            ->where('updated_at', '<', strtotime('-' . $period . ' days', SITETIME))
            ->where('point', '<=', $point)
            ->get();

        $validator->true($users->isNotEmpty(), __('admin.delusers.users_not_found'));

        if ($validator->isValid()) {
            foreach ($users as $user) {
                $user->deleteAlbum();
                $user->delete();
            }

            setFlash('success', __('admin.delusers.success_deleted'));
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/delusers');
    }
}
