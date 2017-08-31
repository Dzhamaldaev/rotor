@extends('layout')

@section('title')
    {{ $photo->title }} - @parent
@stop

@section('content')

    <h1>{{ $photo['title'] }}</h1>

    @if ($photo)

    <?php
    $links = [
        ['url' => '/admin/gallery?act=edit&amp;gid='.$photo['id'], 'label' => 'Редактировать', 'show' => is_admin()],
        ['url' => '/admin/gallery?act=del&amp;del='.$photo['id'].'&amp;uid='.$_SESSION['token'], 'label' => 'Удалить', 'params' => ['onclick' => "return confirm('Вы подтверждаете удаление изображения?')"], 'show' => is_admin()],
        ['url' => '/gallery/'.$photo['id'].'/edit', 'label' => 'Редактировать', 'show' => (($photo['user'] == getUsername()) && !is_admin())],
        ['url' => '/gallery/'.$photo['id'].'/delete?token='.$_SESSION['token'], 'label' => 'Удалить', 'params' => ['onclick' => "return confirm('Вы подтверждаете удаление изображения?')"], 'show' => (($photo['user'] == getUsername()) && !is_admin())],
    ];
    ?>

    <ol class="breadcrumb">
        <?php foreach ($links as $link): ?>
            <?php if (isset($link['show']) && $link['show'] == false) continue; ?>
            <li class="breadcrumb-item"><a href="<?= $link['url'] ?>"><?= $link['label'] ?></a></li>
        <?php endforeach; ?>
    </ol>

    <div>
        <a href="/uploads/pictures/{{ $photo['link'] }}" class="gallery"><img  class="img-fluid" src="/uploads/pictures/{{ $photo['link'] }}" alt="image"></a><br>

        @if ($photo['text'])
            {!! bbCode($photo['text']) !!}<br>
        @endif

        <div class="js-rating">Рейтинг:
            @unless (getUserId() == $photo['user_id'])
                <a class="post-rating-down<?= $photo->vote == -1 ? ' active' : '' ?>" href="#" onclick="return changeRating(this);" data-id="{{ $photo['id'] }}" data-type="{{ Photo::class }}" data-vote="-1" data-token="{{ $_SESSION['token'] }}"><i class="fa fa-thumbs-down"></i></a>
            @endunless
            <span>{!! format_num($photo['rating']) !!}</span>
            @unless (getUserId() == $photo['user_id'])
                <a class="post-rating-up<?= $photo->vote == 1 ? ' active' : '' ?>" href="#" onclick="return changeRating(this);" data-id="{{ $photo['id'] }}" data-type="{{ Photo::class }}" data-vote="1" data-token="{{ $_SESSION['token'] }}"><i class="fa fa-thumbs-up"></i></a>
            @endunless
        </div>

        Размер: {{ formatFileSize(HOME.'/uploads/pictures/'.$photo['link']) }}<br>
        Добавлено: {!! profile($photo['user']) !!} ({{ dateFixed($photo['time']) }})<br>
        <a href="/gallery/{{ $photo['id'] }}/comments">Комментарии</a> ({{ $photo['comments'] }})
        <a href="/gallery/{{ $photo['id'] }}/end">&raquo;</a>
    </div>
    <br>

    <?php $nav = photo_navigation($photo['id']); ?>

    @if ($nav['next'] || $nav['prev'])
        <div class="form" style="text-align:center">
            @if ($nav['next'])
                <a href="/gallery/{{ $nav['next'] }}">&laquo; Назад</a> &nbsp;
            @endif

            @if ($nav['prev'])
                &nbsp; <a href="/gallery/{{ $nav['prev'] }}">Вперед &raquo;</a>
            @endif
        </div>
    @endif
    <i class="fa fa-arrow-circle-up"></i> <a href="/gallery/album/{{ $photo->getUser()->login }}">В альбом</a><br>

    @else
        {{ showError('Ошибка! Данного изображения нет в базе') }}
    @endif

    <i class="fa fa-arrow-circle-left"></i> <a href="/gallery">В галерею</a><br>
@stop
