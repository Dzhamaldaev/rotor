@extends('layout')

@section('title', __('ratings.votes_gave' . ' ' . $user->getName()))

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/users/{{ $user->login }}">{{ $user->login }}</a></li>

            @if (getUser() && getUser('id') !== $user->id)
                <li class="breadcrumb-item"><a href="/users/{{ $user->login }}/rating">{{ __('index.reputation_edit') }}</a></li>
            @endif

            <li class="breadcrumb-item active">{{ __('ratings.votes_gave') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <a href="/ratings/{{ $user->login }}/received" class="badge badge-light">{{ __('ratings.votes_received') }}</a>
    <a href="/ratings/{{ $user->login }}/gave" class="badge badge-success">{{ __('ratings.votes_gave') }}</a>
    <hr>

    @if ($ratings->isNotEmpty())
        @foreach ($ratings as $data)
            <div class="b">
                @if ($data->vote === '-')
                    <i class="fa fa-thumbs-down text-danger"></i>
                @else
                    <i class="fa fa-thumbs-up text-success"></i>
                @endif

                <b>{!! $data->recipient->getProfile() !!}</b> ({{ dateFixed($data->created_at) }})
            </div>
            <div>
                {{ __('main.comment') }}:
                {!! bbCode($data->text) !!}
            </div>
        @endforeach
    @else
        {!! showError(__('ratings.empty_ratings')) !!}
    @endif

    {{ $ratings->links() }}
@stop
