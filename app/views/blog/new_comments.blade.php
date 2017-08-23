@extends('layout')

@section('title')
    Блоги - Новые комментарии (Стр. {{ $page['current'] }}) - @parent
@stop

@section('content')

    <h1>Новые комментарии</h1>

    @if ($comments->isNotEmpty())
        @foreach ($comments as $data)
            <div class="b">
                <i class="fa fa-comment"></i> <b><a href="/article/{{ $data['relate_id'] }}/comments">{{ $data['title'] }}</a></b> ({{ $data['comments'] }})
            </div>

            <div>
                {!! App::bbCode($data['text']) !!}<br>
                Написал: {!! profile($data['user']) !!} <small>({{ date_fixed($data['time']) }})</small><br>

                @if (is_admin())
                    <span class="data">({{ $data['brow'] }}, {{ $data['ip'] }})</span>
                @endif
            </div>
        @endforeach

        {{ App::pagination($page) }}
    @else
        {{ show_error('Комментарии не найдены!') }}
    @endif
@stop