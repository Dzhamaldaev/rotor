@extends('layout')

@section('title', __('index.votes'))

@section('header')
    @if (getUser())
        <div class="float-right">
            <a class="btn btn-success" href="/votes/create">{{ __('main.create') }}</a>
        </div><br>
    @endif

    <h1>{{ __('index.votes') }}</h1>
@stop

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/admin">{{ __('index.panel') }}</a></li>
            <li class="breadcrumb-item active">{{ __('index.votes') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    @if ($votes->isNotEmpty())
        @foreach ($votes as $vote)
            <div class="b">
                <i class="fa fa-chart-bar"></i>
                <b><a href="/votes/{{ $vote['id'] }}">{{ $vote->title }}</a></b>

                <div class="float-right">
                    <a href="/admin/votes/edit/{{ $vote->id }}" data-toggle="tooltip" title="{{ __('main.edit') }}"><i class="fa fa-pencil-alt text-muted"></i></a>
                    <a href="/admin/votes/close/{{ $vote->id }}?token={{ $_SESSION['token'] }}" onclick="return confirm('{{ __('votes.confirm_close') }}')" data-toggle="tooltip" title="{{ __('main.close') }}"><i class="fa fa-lock text-muted"></i></a>

                    @if (isAdmin('boss'))
                        <a href="/admin/votes/delete/{{ $vote->id }}?token={{ $_SESSION['token'] }}" onclick="return confirm('{{ __('votes.confirm_delete') }}')" data-toggle="tooltip" title="{{ __('main.delete') }}"><i class="fa fa-times text-muted"></i></a>
                    @endif
                </div>

            </div>
            <div>
                @if ($vote->topic->id)
                    {{ __('forums.topic') }}: <a href="/topics/{{ $vote->topic->id }}">{{ $vote->topic->title }}</a><br>
                @endif

                {{ __('main.created') }}: {{ dateFixed($vote->created_at) }}<br>
                {{ __('main.votes') }}: {{ $vote->count }}<br>
            </div>
        @endforeach
    @else
        {!! showError(__('votes.empty_votes')) !!}
    @endif

    {{ $votes->links() }}

    @if (isAdmin('boss'))
        <i class="fa fa-sync"></i> <a href="/admin/votes/restatement?token={{ $_SESSION['token'] }}">{{ __('main.recount') }}</a><br>
    @endif

    <i class="fa fa-briefcase"></i> <a href="/admin/votes/history">{{ __('votes.archive_votes') }}</a><br>
@stop
