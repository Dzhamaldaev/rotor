@extends('layout')

@section('title')
    Список новых сообщений - @parent
@stop

@section('content')
    <h1>Список новых сообщений</h1>

    <a href="/forum">Форум</a>

    @foreach ($posts as $data)
        <div class="b">
            <i class="fa fa-file-text-o"></i> <b><a href="/topic/{{ $data['topic_id'] }}/{{ $data['id'] }}">{{ $data->getTopic()->title }}</a></b>
            ({{ $data->getTopic()->posts }})
        </div>
        <div>
            {!! bbCode($data['text']) !!}<br>

            Написал: {{ $data->getUser()->login }} {!! user_online($data->user) !!} <small>({{ dateFixed($data['created_at']) }})</small><br>

            @if (is_admin())
                <span class="data">({{ $data['brow'] }}, {{ $data['ip'] }})</span>
            @endif

        </div>
    @endforeach

    {{ pagination($page) }}
@stop
