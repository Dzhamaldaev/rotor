<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Classes\Validator;
use App\Models\Status;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class StatusController extends AdminController
{
    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();

        if (! isAdmin(User::ADMIN)) {
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
        $statuses = Status::query()->orderByDesc('topoint')->get();

        return view('admin/status/index', compact('statuses'));
    }

    /**
     * Добавление статуса
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function create(Request $request, Validator $validator): string
    {
        if ($request->isMethod('post')) {
            $topoint = int($request->input('topoint'));
            $point   = int($request->input('point'));
            $name    = $request->input('name');
            $color   = $request->input('color');

            $validator
                ->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($name, 3, 30, ['name' => __('statuses.status_length')])
                ->regex($color, '|^#+[A-f0-9]{6}$|', ['color' => __('validator.color')], false);

            if ($validator->isValid()) {
                Status::query()->create([
                    'topoint' => $topoint,
                    'point'   => $point,
                    'name'    => $name,
                    'color'   => $color,
                ]);

                setFlash('success', __('statuses.status_success_added'));
                redirect('/admin/status');
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/status/create');
    }

    /**
     * Редактирование статуса
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function edit(Request $request, Validator $validator): string
    {
        $id = int($request->input('id'));

        $status = Status::query()->find($id);

        if (! $status) {
            abort(404, __('statuses.status_not_found'));
        }

        if ($request->isMethod('post')) {
            $topoint = int($request->input('topoint'));
            $point   = int($request->input('point'));
            $name    = $request->input('name');
            $color   = $request->input('color');

            $validator
                ->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($name, 3, 30, ['name' => __('statuses.status_length')])
                ->regex($color, '|^#+[A-f0-9]{6}$|', ['color' => __('validator.color')], false);

            if ($validator->isValid()) {
                $status->update([
                    'topoint' => $topoint,
                    'point'   => $point,
                    'name'    => $name,
                    'color'   => $color,
                ]);

                setFlash('success', __('statuses.status_success_edited'));
                redirect('/admin/status');
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/status/edit', compact('status'));
    }

    /**
     * Удаление статуса
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     * @throws Exception
     */
    public function delete(Request $request, Validator $validator): void
    {
        $id = int($request->input('id'));

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'));

        $status = Status::query()->find($id);
        $validator->notEmpty($status, __('statuses.status_not_found'));

        if ($validator->isValid()) {
            $status->delete();

            setFlash('success', __('statuses.status_success_deleted'));
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/status');
    }
}
