@extends('layout')

@section('title')
    Управление форумом
@stop

@section('content')

    <h1>Управление форумом</h1>

    <a href="/forum">Обзор форума</a><hr>

    @if ($forums->isNotEmpty())
        @foreach ($forums as $forum)
            <div class="b">
                <i class="fa fa-file-alt fa-lg text-muted"></i>
                <b><a href="/admin/forum/{{ $forum->id }}">{{ $forum->title }}</a></b>
                ({{ $forum->topics }}/{{ $forum->posts }})

                @if (!empty($forum->description))
                    <p><small>{{ $forum->description }}</small></p>
                @endif

                @if (isAdmin('boss'))
                    <div class="float-right">
                        <a href="/admin/forum/edit/{{ $forum->id }}"><i class="fa fa-pencil-alt"></i></a>
                        <a href="/admin/forum/delete/{{ $forum->id }}?token={{ $_SESSION['token'] }}" onclick="return confirm('Вы уверены что хотите удалить данный раздел?')"><i class="fa fa-times"></i></a>
                    </div>
                @endif
            </div>

            <div>
                @if ($forum->children->isNotEmpty())
                    @foreach ($forum->children as $child)
                        <i class="fa fa-copy text-muted"></i> <b><a href="/admin/forum/{{ $child->id }}">{{ $child->title }}</a></b>
                        ({{ $child->topics }}/{{ $child->posts }})

                        @if (isAdmin('boss'))
                            <a href="/admin/forum/edit/{{ $child->id }}"><i class="fa fa-pencil-alt"></i></a>
                            <a href="/admin/forum/delete/{{ $child->id }}?token={{ $_SESSION['token'] }}" onclick="return confirm('Вы уверены что хотите удалить данный раздел?')"><i class="fa fa-times"></i></a>
                        @endif
                        <br/>
                    @endforeach
                @endif

                @if ($forum->lastTopic->lastPost->id)
                    Тема: <a href="/topic/end/{{ $forum->lastTopic->id }}">{{ $forum->lastTopic->title }}</a>
                    <br/>
                    Сообщение: {{ $forum->lastTopic->lastPost->user->login }} ({{ dateFixed($forum->lastTopic->lastPost->created_at) }})
                @else
                    Темы еще не созданы!
                @endif
            </div>
        @endforeach
    @else
        {!! showError('Разделы форума еще не созданы!') !!}
    @endif

    @if (isAdmin('boss'))
        <div class="form my-3">
            <form action="/admin/forum/create" method="post">
                <input type="hidden" name="token" value="{{ $_SESSION['token'] }}">
                <div class="form-inline">
                    <div class="form-group{{ hasError('title') }}">
                        <input type="text" class="form-control" id="title" name="title" maxlength="50" value="{{ getInput('title') }}" placeholder="Раздел" required>
                    </div>

                    <button class="btn btn-primary">Создать раздел</button>
                </div>
                {!! textError('title') !!}
            </form>
        </div>

        <i class="fa fa-sync"></i> <a href="/admin/forum/restatement?token={{ $_SESSION['token'] }}">Пересчитать</a><br>
    @endif

    <i class="fa fa-wrench"></i> <a href="/admin">В админку</a><br>
@stop