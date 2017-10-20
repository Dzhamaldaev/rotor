@extends('layout_rss')

@section('title')
    RSS новостей
@stop

@section('content')

    @foreach ($newses as $news):
        <?php $news->text = bbCode($news->text); ?>
        <?php $news->text = str_replace(['/uploads/smiles', '[cut]'], [siteUrl().'/uploads/smiles', ''], $news->text); ?>

        <item>
            <title>{{ $news->title }}</title>
            <link>{{  siteUrl() }}/news/{{ $news->id }}</link>
            <description>{{ $news->text }}</description>
            <author>{{ $news->user->login }}</author>
            <pubDate>{{ date("r", $news->created_at) }}</pubDate>
            <category>Новости</category>
            <guid>{{ siteUrl() }}/news/{{ $news->id }}</guid>
        </item>
    @endforeach
@stop
