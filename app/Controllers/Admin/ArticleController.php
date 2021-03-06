<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Classes\Validator;
use App\Models\Article;
use App\Models\Blog;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ArticleController extends AdminController
{
    /**
     * Главная страница
     *
     * @return string
     */
    public function index(): string
    {
        $categories = Blog::query()
            ->where('parent_id', 0)
            ->orderBy('sort')
            ->with('children', 'new', 'children.new')
            ->get();

        return view('admin/blogs/index', compact('categories'));
    }

    /**
     * Создание раздела
     *
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     */
    public function create(Request $request, Validator $validator): void
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, __('errors.forbidden'));
        }

        $name = $request->input('name');

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
            ->length($name, 3, 50, ['name' => __('validator.text')]);

        if ($validator->isValid()) {
            $max = Blog::query()->max('sort') + 1;

            /** @var Blog $category */
            $category = Blog::query()->create([
                'name'  => $name,
                'sort'  => $max,
            ]);

            setFlash('success', __('blogs.category_success_created'));
            redirect('/admin/blogs/edit/' . $category->id);
        } else {
            setInput($request->all());
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/blogs');
    }

    /**
     * Редактирование раздела
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function edit(int $id, Request $request, Validator $validator): string
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, __('errors.forbidden'));
        }

        /** @var Blog $category */
        $category = Blog::query()->with('children')->find($id);

        if (! $category) {
            abort(404, __('blogs.category_not_exist'));
        }

        $categories = Blog::query()
            ->where('parent_id', 0)
            ->orderBy('sort')
            ->get();

        if ($request->isMethod('post')) {
            $parent = int($request->input('parent'));
            $name   = $request->input('name');
            $sort   = int($request->input('sort'));
            $closed = empty($request->input('closed')) ? 0 : 1;

            $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($name, 3, 50, ['title' => __('validator.text')])
                ->notEqual($parent, $category->id, ['parent' => __('blogs.category_not_exist')]);

            if (! empty($parent) && $category->children->isNotEmpty()) {
                $validator->addError(['parent' => __('blogs.category_has_subcategories')]);
            }

            if ($validator->isValid()) {
                $category->update([
                    'parent_id' => $parent,
                    'name'      => $name,
                    'sort'      => $sort,
                    'closed'    => $closed,
                ]);

                setFlash('success', __('blogs.category_success_edited'));
                redirect('/admin/blogs');
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/blogs/edit', compact('categories', 'category'));
    }

    /**
     * Удаление раздела
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     * @throws Exception
     */
    public function delete(int $id, Request $request, Validator $validator): void
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, __('errors.forbidden'));
        }

        /** @var Blog $category */
        $category = Blog::query()->with('children')->find($id);

        if (! $category) {
            abort(404, __('blogs.category_not_exist'));
        }

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
            ->true($category->children->isEmpty(), __('blogs.category_has_subcategories'));

        $article = Article::query()->where('category_id', $category->id)->first();
        if ($article) {
            $validator->addError(__('blogs.articles_in_category'));
        }

        if ($validator->isValid()) {
            $category->delete();

            setFlash('success', __('blogs.category_success_deleted'));
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/blogs');
    }

    /**
     * Пересчет данных
     *
     * @param Request $request
     *
     * @return void
     */
    public function restatement(Request $request): void
    {
        if (! isAdmin(User::BOSS)) {
            abort(403, __('errors.forbidden'));
        }

        if ($request->input('token') === $_SESSION['token']) {
            restatement('blogs');

            setFlash('success', __('main.success_recounted'));
        } else {
            setFlash('danger', __('validator.token'));
        }

        redirect('/admin/blogs');
    }

    /**
     * Список блогов
     *
     * @param int $id
     *
     * @return string
     */
    public function blog(int $id): string
    {
        $category = Blog::query()->with('parent')->find($id);

        if (! $category) {
            abort(404, __('blogs.category_not_exist'));
        }

        $articles = Article::query()
            ->where('category_id', $id)
            ->orderByDesc('created_at')
            ->with('user')
            ->paginate(setting('blogpost'));

        return view('admin/blogs/blog', compact('articles', 'category'));
    }

    /**
     * Редактирование статьи
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function editArticle(int $id, Request $request, Validator $validator): string
    {
        /** @var Article $article */
        $article = Article::query()->find($id);

        if (! $article) {
            abort(404, __('blogs.article_not_exist'));
        }

        if ($request->isMethod('post')) {
            $title = $request->input('title');
            $text  = $request->input('text');
            $tags  = $request->input('tags');

            $validator
                ->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->length($title, 5, 50, ['title' => __('validator.text')])
                ->length($text, 100, setting('maxblogpost'), ['text' => __('validator.text')])
                ->length($tags, 2, 50, ['tags' => __('blogs.article_error_tags')]);

            if ($validator->isValid()) {
                $article->update([
                    'title' => $title,
                    'text'  => $text,
                    'tags'  => $tags,
                ]);

                clearCache(['statArticles', 'recentArticles']);
                setFlash('success', __('blogs.article_success_edited'));
                redirect('/articles/' . $article->id);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        return view('admin/blogs/edit_blog', compact('article'));
    }

    /**
     * Перенос статьи
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return string
     */
    public function moveArticle(int $id, Request $request, Validator $validator): string
    {
        /** @var Article $article */
        $article = Article::query()->find($id);

        if (! $article) {
            abort(404, __('blogs.article_not_exist'));
        }

        if ($request->isMethod('post')) {
            $cid = int($request->input('cid'));

            /** @var Blog $category */
            $category = Blog::query()->find($cid);

            $validator
                ->equal($request->input('token'), $_SESSION['token'], __('validator.token'))
                ->notEmpty($category, ['cid' => __('blogs.category_not_exist')]);

            if ($category) {
                $validator->empty($category->closed, ['cid' => __('blogs.category_closed')]);
                $validator->notEqual($article->category_id, $category->id, ['cid' => __('blogs.article_error_moving')]);
            }

            if ($validator->isValid()) {
                // Обновление счетчиков
                $category->increment('count_articles');
                Blog::query()->where('id', $article->category_id)->decrement('count_articles');

                $article->update([
                    'category_id' => $category->id,
                ]);

                setFlash('success', __('blogs.article_success_moved'));
                redirect('/articles/' . $article->id);
            } else {
                setInput($request->all());
                setFlash('danger', $validator->getErrors());
            }
        }

        $categories = Blog::query()
            ->where('parent_id', 0)
            ->with('children')
            ->orderBy('sort')
            ->get();

        return view('admin/blogs/move_blog', compact('article', 'categories'));
    }

    /**
     * Удаление статьи
     *
     * @param int       $id
     * @param Request   $request
     * @param Validator $validator
     *
     * @return void
     * @throws Exception
     */
    public function deleteArticle(int $id, Request $request, Validator $validator): void
    {
        $page = int($request->input('page', 1));

        /** @var Article $article */
        $article = Article::query()->find($id);

        if (! $article) {
            abort(404, __('blogs.article_not_exist'));
        }

        $validator->equal($request->input('token'), $_SESSION['token'], __('validator.token'));

        if ($validator->isValid()) {
            $article->comments()->delete();
            $article->delete();

            $article->category->decrement('count_articles');

            clearCache(['statArticles', 'recentArticles']);
            setFlash('success', __('blogs.article_success_deleted'));
        } else {
            setFlash('danger', $validator->getErrors());
        }

        redirect('/admin/blogs/' . $article->category_id . '?page=' . $page);
    }
}
