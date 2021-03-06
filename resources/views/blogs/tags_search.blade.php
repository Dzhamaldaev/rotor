@extends('layout')

@section('title', __('blogs.title_tags'))

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/blogs">{{ __('index.blogs') }}</a></li>
            <li class="breadcrumb-item"><a href="/blogs/tags">{{ __('blogs.tag_cloud') }}</a></li>
            <li class="breadcrumb-item active">{{ __('blogs.title_tags') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <p>{{ __('blogs.found_in_tags') }}: {{ $articles->total() }}</p>

    @foreach ($articles as $article)
        <div class="b">
            <i class="fa fa-pencil-alt"></i>
            <b><a href="/articles/{{ $article->id }}">{{ $article->title }}</a></b> ({!! formatNum($article->rating) !!})
        </div>

        <div>
            {{ __('blogs.blog') }}: <a href="/blogs/{{ $article->category_id }}">{{ $article->name }}</a><br>
            {{ __('main.views') }}: {{ $article->visits }}<br>
            {{ __('blogs.tags') }}: {{ $article->tags }}<br>
            {{ __('main.author') }}: {!! $article->user->getProfile() !!}  ({{ dateFixed($article->created_at) }})
        </div>
    @endforeach

    {{ $articles->links() }}
@stop
