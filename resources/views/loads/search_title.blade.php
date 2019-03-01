@extends('layout')

@section('title')
    Поиск запроса {{ $find }}
@stop

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/loads">{{ trans('loads.title') }}</a></li>
            <li class="breadcrumb-item"><a href="/loads/search">Поиск</a></li>
            <li class="breadcrumb-item active">Поиск запроса</li>
        </ol>
    </nav>
@stop

@section('content')
    Найдено совпадений в названии: <b>{{ $page->total }}</b><br><br>

    @foreach ($downs as $data)
        <?php $rating = $data->rated ? round($data->rating / $data->rated, 1) : 0; ?>

        <div class="b">
            <i class="fa fa-file"></i>
            <b><a href="/downs/{{ $data->id }}">{{ $data->title }}</a></b> ({{ $data->count_comments }})
        </div>

        <div>
            Категория: <a href="/loads/{{ $data->category->id }}">{{ $data->category->name }}</a><br>
            Рейтинг: {{ $rating }}<br>
            Скачиваний: {{ $data->loads }}<br>
            Добавил: {!! $data->user->getProfile() !!} ({{ dateFixed($data->created_at) }})
        </div>
    @endforeach

    {!! pagination($page) !!}
@stop
