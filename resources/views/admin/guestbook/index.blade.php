@extends('layout')

@section('title')
    {{ __('index.guestbook') }}
@stop

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/admin">{{ __('index.panel') }}</a></li>
            <li class="breadcrumb-item active">{{ __('index.guestbook') }}</li>
        </ol>
    </nav>
@stop

@section('header')
    @if (getUser() || setting('bookadds'))
        <div class="float-right">
            <a class="btn btn-light" href="/guestbook?page={{ $posts->currentPage() }}"><i class="fas fa-wrench"></i></a>
        </div>
    @endif


    <h1>{{ __('index.guestbook') }}</h1>
@stop

@section('content')
    @if ($posts->isNotEmpty())
        <form action="/admin/guestbook/delete?page={{ $posts->currentPage() }}" method="post">
            @csrf
            @foreach ($posts as $post)
                <div class="section mb-3 shadow">
                    <div class="user-avatar">
                        @if ($post->user_id)
                            {!! $post->user->getAvatar() !!}
                            {!! $post->user->getOnline() !!}
                        @else
                            <img class="img-fluid rounded-circle avatar-default" src="/assets/img/images/avatar_guest.png" alt="">
                        @endif
                    </div>

                    <div class="section-user d-flex align-items-center">
                        <div class="flex-grow-1">
                            @if ($post->user_id)
                                {!! $post->user->getProfile() !!}
                                <small class="section-date text-muted font-italic">{{ dateFixed($post->created_at) }}</small><br>
                                <small class="font-italic">{!! $post->user->getStatus() !!}</small>
                            @else
                                @if ($post->guest_name)
                                    <span class="section-author font-weight-bold" data-login="{{ $post->guest_name }}">{{ $post->guest_name }}</span>
                                @else
                                    <span class="section-author font-weight-bold" data-login="{{ setting('guestsuser') }}">{{ setting('guestsuser') }}</span>
                                @endif
                                <small class="section-date text-muted font-italic">{{ dateFixed($post->created_at) }}</small>
                            @endif
                        </div>

                        <div class="text-right">
                            <a href="/admin/guestbook/reply/{{ $post->id }}?page={{ $posts->currentPage() }}"><i class="fa fa-reply text-muted"></i></a>
                            <a href="/admin/guestbook/edit/{{ $post->id }}?page={{ $posts->currentPage() }}"><i class="fas fa-pencil-alt text-muted"></i></a>
                            <input type="checkbox" name="del[]" value="{{ $post->id }}">
                        </div>
                    </div>

                    <div class="section-body border-top my-1 py-1">
                        <div class="section-message">
                            {!! bbCode($post->text) !!}
                        </div>

                        @if ($post->edit_user_id)
                                <div class="small"><i class="fa fa-exclamation-circle text-danger"></i> {{ __('main.changed') }}: {{ $post->editUser->getName() }} ({{ dateFixed($post->updated_at) }})</div>
                        @endif

                        @if ($post->reply)
                            <div class="text-danger">{{ __('guestbook.answer') }}: {!! bbCode($post->reply) !!}</div>
                        @endif

                        <div class="small text-muted font-italic mt-2">
                            {{ $post->brow }}, {{ $post->ip }}
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="float-right">
                <button class="btn btn-sm btn-danger">{{ __('main.delete_selected') }}</button>
            </div>
        </form>

        {{ $posts->links() }}

        {{ __('guestbook.total_messages') }}: <b>{{ $posts->total() }}</b><br><br>

        @if (isAdmin('boss'))
            <i class="fa fa-times"></i> <a href="/admin/guestbook/clear?token={{ $_SESSION['token'] }}" onclick="return confirm('{{ __('guestbook.confirm_delete') }}')">{{ __('main.clear') }}</a><br>
        @endif
    @else
        {!! showError(__('main.empty_messages')) !!}
    @endif
@stop