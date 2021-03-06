@extends('layout')

@section('title', __('main.comments') . ' ' . $user->getName() . ' (' . __('main.page_num', ['page' => $comments->currentPage()]) . ')')

@section('header')
    <h1>{{ __('main.comments') }} {{ $user->getName() }}</h1>
@stop

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/photos">{{ __('index.photos') }}</a></li>
            <li class="breadcrumb-item active">{{ __('main.comments') }} {{ $user->getName() }}</li>
        </ol>
    </nav>
@stop

@section('content')
    @if ($comments->isNotEmpty())
        @foreach ($comments as $data)
            <div class="post">
                <div class="b">
                    <i class="fa fa-comment"></i> <b><a href="/photos/comment/{{ $data->relate_id }}/{{ $data->id }}">{{ $data->title }}</a></b>

                    @if (isAdmin())
                        <a href="#" class="float-right" onclick="return deleteComment(this)" data-rid="{{ $data->relate_id }}" data-id="{{ $data->id }}" data-type="{{ $data->relate->getMorphClass() }}" data-token="{{ $_SESSION['token'] }}" data-toggle="tooltip" title="{{ __('main.delete') }}"><i class="fa fa-times"></i></a>
                    @endif
                </div>

                <div>
                    {!! bbCode($data->text) !!}<br>
                    {{ __('main.posted') }}: <b>{!! $data->user->getProfile() !!}</b> <small>({{ dateFixed($data->created_at) }})</small><br>

                    @if (isAdmin())
                        <span class="data">({{ $data->brow }}, {{ $data->ip }})</span>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        {!! showError(__('main.empty_comments')) !!}
    @endif

    {{ $comments->links() }}
@stop
