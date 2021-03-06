<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Classes\Validator;
use App\Models\User;
use Illuminate\Http\Request;

class ReglistController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (! isAdmin(User::MODER)) {
            abort(403, __('errors.forbidden'));
        }
    }

    /**
     * Главная страница
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function index(Request $request, Validator $validator): string
    {
        if ($request->isMethod('post')) {
            $page   = int($request->input('page', 1));
            $choice = intar($request->input('choice'));
            $action = $request->input('action');

            $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->notEmpty($choice, __('admin.reglists.users_not_selected'))
                ->in($action, ['yes', 'no'], ['action' => __('main.action_not_selected')]);

            if ($validator->isValid()) {
                if ($action === 'yes') {
                    User::query()
                        ->whereIn('id', $choice)
                        ->update([
                            'level' => User::USER
                        ]);

                    setFlash('success', __('admin.reglists.users_success_approved'));
                } else {
                    $users = User::query()
                        ->whereIn('id', $choice)
                        ->get();

                    foreach ($users as $user) {
                        $user->delete();
                    }

                    setFlash('success', __('admin.reglists.users_success_deleted'));
                }

                redirect('/admin/reglists?page=' . $page);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $users = User::query()
            ->where('level', User::PENDED)
            ->orderByDesc('created_at')
            ->paginate(setting('reglist'));

        return view('admin/reglists/index', compact('users'));
    }
}
