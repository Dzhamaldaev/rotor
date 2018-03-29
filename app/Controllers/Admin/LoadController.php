<?php

namespace App\Controllers\Admin;

use App\Classes\Request;
use App\Classes\Validator;
use App\Models\Load;
use App\Models\User;

class LoadController extends AdminController
{
    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct();

        if (! isAdmin(User::ADMIN)) {
            abort(403, 'Доступ запрещен!');
        }
    }

    /**
     * Главная страница
     */
    public function index()
    {
        $categories = Load::query()
            ->where('parent_id', 0)
            ->with('children', 'new', 'children.new')
            ->orderBy('sort')
            ->get();

        return view('admin/load/index', compact('categories'));
    }

    /**
     * Создание раздела
     */
    public function create()
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, 'Доступ запрещен!');
        }

        $token = check(Request::input('token'));
        $name  = check(Request::input('name'));

        $validator = new Validator();
        $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!')
            ->length($name, 5, 50, ['title' => 'Слишком длинное или короткое название раздела!']);

        if ($validator->isValid()) {

            $max = Load::query()->max('sort') + 1;

            $load = Load::query()->create([
                'name' => $name,
                'sort' => $max,
            ]);

            setFlash('success', 'Новый раздел успешно создан!');
            redirect('/admin/load/edit/' . $load->id);
        } else {
            setInput(Request::all());
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/load');
    }

    /**
     * Редактирование раздела
     */
    public function edit($id)
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, 'Доступ запрещен!');
        }

        $load = Load::query()->with('children')->find($id);

        if (! $load) {
            abort(404, 'Данного раздела не существует!');
        }

        $loads = Load::query()
            ->where('parent_id', 0)
            ->orderBy('sort')
            ->get();

        if (Request::isMethod('post')) {
            $token  = check(Request::input('token'));
            $parent = int(Request::input('parent'));
            $name   = check(Request::input('name'));
            $sort   = check(Request::input('sort'));
            $closed = empty(Request::input('closed')) ? 0 : 1;

            $validator = new Validator();
            $validator->equal($token, $_SESSION['token'], 'Неверный идентификатор сессии, повторите действие!')
                ->length($name, 5, 50, ['title' => 'Слишком длинное или короткое название раздела!'])
                ->notEqual($parent, $load->id, ['parent' => 'Недопустимый выбор родительского раздела!']);

            if (! empty($parent) && $load->children->isNotEmpty()) {
                $validator->addError(['parent' => 'Текущий раздел имеет подразделы!']);
            }

            if ($validator->isValid()) {

                $load->update([
                    'parent_id' => $parent,
                    'name'      => $name,
                    'sort'      => $sort,
                    'closed'    => $closed,
                ]);

                setFlash('success', 'Раздел успешно отредактирован!');
                redirect('/admin/load');
            } else {
                setInput(Request::all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/load/edit', compact('loads', 'load'));
    }
}