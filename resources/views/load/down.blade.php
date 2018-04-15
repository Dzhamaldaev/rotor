@extends('layout')

@section('title')
    {{ $down->title }}
@stop

@section('description', stripString($down->text))

@section('content')

    <h1>{{ $down->title }}</h1>

    @if (isAdmin('admin'))
        <a href="/admin/down/edit/{{ $down->id }}">Редактировать</a> /
        <a href="/admin/down/move/{{ $down->id }}">Переместить</a>
    @endif

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/load">Загрузки</a></li>

            @if ($down->category->parent->id)
                <li class="breadcrumb-item"><a href="/load/{{ $down->category->parent->id }}">{{ $down->category->parent->name }}</a></li>
            @endif

            <li class="breadcrumb-item"><a href="/load/{{ $down->category_id }}">{{ $down->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $down->title }}</li>
            <li class="breadcrumb-item"><a href="/down/rss/{{ $down->id }}">RSS-лента</a></li>
        </ol>
    </nav>

    @if (! $down->active && $down->user_id == getUser('id'))
        <div class="info">
            <b>Внимание!</b> Данная загрузка ожидает проверки модератором!<br>
            <i class="fa fa-pencil-alt"></i> <a href="/down/edit/{{ $down->id }}">Перейти к редактированию</a>
        </div><br>
    @endif

    <div class="message">
        {!! bbCode($down->text) !!}
    </div><br>

    @if ($down->files->isNotEmpty())

        @if ($files)
            @foreach ($files as $file)
                @if (file_exists(UPLOADS.'/files/'.$file->hash))
                    <div class="mt-3">
                        <i class="fa fa-download"></i> <b><a href="/down/download/{{ $file->id }}">{{ $file->name }}</a></b> ({{ formatSize($file->size) }})

                        @if ($file->extension === 'mp3')
                            <audio preload="none" controls style="max-width:100%;">
                                <source src="/uploads/files/{{ $file->hash }}" type="audio/mp3">
                            </audio>
                        @endif

                        @if ($file->extension === 'mp4')
                            <?php $poster = $images->isNotEmpty() ? '/uploads/screen/' . $images->first()->hash : null; ?>

                           <video width="640" height="360" style="max-width:100%;" poster="{{ $poster }}" preload="none" controls playsinline>
                               <source src="/uploads/files/{{ $file->hash }}" type="video/mp4">
                           </video>
                        @endif

                        @if ($file->extension === 'zip')
                            <a href="/down/zip/{{ $file->id }}">Просмотреть архив</a><br>
                        @endif
                    </div>
                @endif
            @endforeach
        @endif

        @if ($images)
            <div class="mt-3">
                @foreach ($images as $image)
                    <a href="/uploads/screen/{{ $image->hash }}" class="gallery" data-group="{{ $down->id }}">{!! resizeImage('/uploads/screen/' . $image->hash, ['alt' => $down->title]) !!}</a><br>
                @endforeach
            </div>
        @endif
    @else
        {!! showError('Файлы еще не загружены!') !!}
    @endif

    <div class="mt-3">
        <i class="fa fa-comment"></i> <a href="/down/comments/{{ $down->id }}">Комментарии</a> ({{ $down->count_comments }})
        <a href="/down/end/{{ $down->id }}">&raquo;</a><br>

        Рейтинг: {!! ratingVote($rating) !!}<br>
        Всего голосов: <b>{{ $down->rated }}</b><br>
        Всего скачиваний: <b>{{ $down->loads }}</b><br>
        Добавлено: {!! profile($down->user) !!} ({{ dateFixed($down->created_at) }})<br><br>
    </div>

    @if (getUser() && getUser('id') != $down->user_id)

        <label for="score">Проверочный код:</label><br>
        <form class="form-inline" action="/down/vote/{{ $down->id }}" method="post">
            <input type="hidden" name="token" value="{{ $_SESSION['token'] }}">

            <div class="form-group{{ hasError('score') }}">
                <select class="form-control" id="score" name="score">
                    <option value="5" {{ $down->vote == 5 ? ' selected' : '' }}>Отлично</option>
                    <option value="4" {{ $down->vote == 4 ? ' selected' : '' }}>Хорошо</option>
                    <option value="3" {{ $down->vote == 3 ? ' selected' : '' }}>Нормально</option>
                    <option value="2" {{ $down->vote == 2 ? ' selected' : '' }}>Плохо</option>
                    <option value="1" {{ $down->vote == 1 ? ' selected' : '' }}>Отстой</option>
                </select>
                {!! textError('protect') !!}
            </div>
            <button class="btn btn-primary">Оценить</button>
        </form>
    @endif
@stop
