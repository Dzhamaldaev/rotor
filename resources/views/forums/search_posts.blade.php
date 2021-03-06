@extends('layout')

@section('title', __('main.search_request') . ' ' . $find)

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/forums">{{ __('index.forums') }}</a></li>
            <li class="breadcrumb-item"><a href="/forums/search">{{ __('main.search') }}</a></li>
            <li class="breadcrumb-item active">{{ __('main.search_request') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <p>{{ __('forums.found_posts') }}: {{ $posts->total() }}</p>

    @foreach ($posts as $post)
        <div class="section mb-3 shadow">
            <i class="fa fa-file-alt"></i> <b><a href="/topics/{{ $post->topic_id }}/{{ $post->id }}">{{ $post->topic->title }}</a></b>

            <div class="section-message">
                {!! bbCode($post->text) !!}<br>
                {{ __('forums.forum') }}: <a href="/topics/{{ $post->topic->forum->id }}">{{ $post->topic->forum->title }}</a><br>
                {{ __('main.posted') }}: {!! $post->user->getProfile() !!} <small>({{ dateFixed($post->created_at) }})</small><br>
            </div>
        </div>
    @endforeach

    {{ $posts->links() }}
@stop
