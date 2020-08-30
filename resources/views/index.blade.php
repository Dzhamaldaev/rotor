@extends('layout')

@section('title')
    {{ setting('logos') }}
@stop

@section('header')
    @include('ads/_top')
    <h1>{{ setting('title') }}</h1>
@stop

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="far fa-circle fa-lg text-muted"></i>
                <a href="/news">{{ __('index.news') }}</a> ({{ statsNewsDate() }})
            </h5>
            <div class="card-text">
                {!! lastNews() !!}
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fa fa-comment fa-lg text-muted"></i>
                <a href="/pages/recent">{{ __('index.communication') }}</a>
            </h5>
            <p class="card-text">
                <i class="far fa-circle fa-lg text-muted"></i> <a class="index" href="/guestbook">{{ __('index.guestbook') }}</a> ({{ statsGuestbook() }})<br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/photos">{{ __('index.photos') }}</a> ({{ statsPhotos() }})<br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/votes">{{ __('index.votes') }}</a> ({{ statVotes()}})<br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/offers">{{ __('index.offers') }}</a> ({{ statsOffers() }})<br>
            </p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fab fa-forumbee fa-lg text-muted"></i>
                <a href="/forums">{{ __('index.forums') }}</a> ({{ statsForum() }})
            </h5>
            <div class="card-text">
                {!! recentTopics() !!}
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fa fa-download fa-lg text-muted"></i>
                <a href="/loads">{{ __('index.loads') }}</a> ({{ statsLoad() }})
            </h5>
            <div class="card-text">
                {!! recentDowns() !!}
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fa fa-globe fa-lg text-muted"></i>
                <a href="/blogs">{{ __('index.blogs') }}</a> ({{ statsBlog() }})
            </h5>
            <div class="card-text">
                {!! recentArticles() !!}
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fa fa-list-alt fa-lg text-muted"></i>
                <a href="/boards">{{ __('index.boards') }}</a> ({{ statsBoard() }})
            </h5>
        </div>
        <div class="card-body">
            {!! recentBoards() !!}
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fa fa-cog fa-lg text-muted"></i>
                <a href="/pages">{{ __('index.pages') }}</a>
            </h5>
            <div class="card-text">
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/files/docs">{{ __('index.docs') }}</a><br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/search">{{ __('index.search') }}</a><br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/mails">{{ __('index.mails') }}</a><br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/users">{{ __('index.users') }}</a> ({{  statsUsers() }})<br>
                <i class="far fa-circle fa-lg text-muted"></i> <a href="/administrators">{{ __('index.administrators') }}</a> ({{ statsAdmins() }})
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa fa-chart-line fa-lg text-muted"></i>
                        {{ __('index.courses') }}
                    </h5>
                    <div class="card-text">
                        {!! getCourses() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa fa-calendar-alt fa-lg text-muted"></i>
                        {{ __('index.calendar') }}
                    </h5>
                    <div class="card-text">
                        {!! getCalendar() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('ads/_bottom')
@stop
