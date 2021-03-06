@extends('layout')

@section('title', $article->title . ' - ' . __('main.comments'))

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/blogs">{{ __('index.blogs') }}</a></li>

            @if ($article->category->parent->id)
                <li class="breadcrumb-item"><a href="/blogs/{{ $article->category->parent->id }}">{{ $article->category->parent->name }}</a></li>
            @endif

            <li class="breadcrumb-item"><a href="/blogs/{{ $article->category->id }}">{{ $article->category->name }}</a></li>
            <li class="breadcrumb-item"><a href="/articles/{{ $article->id }}">{{ $article->title }}</a></li>
            <li class="breadcrumb-item active">{{ __('main.comments') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <a href="/articles/rss/{{ $article->id }}">{{ __('main.rss') }}</a><hr>

    @if ($comments->isNotEmpty())
        @foreach ($comments as $comment)
            <div class="post" id="comment_{{ $comment->id }}">
                <div class="b">
                    <div class="img">
                        {!! $comment->user->getAvatar() !!}
                        {!! $comment->user->getOnline() !!}
                    </div>

                    @if (getUser())
                        <div class="float-right">
                            @if (getUser('id') !== $comment->user_id)
                                <a href="#" onclick="return postReply(this)" title="{{ __('main.reply') }}"><i class="fa fa-reply text-muted"></i></a>

                                <a href="#" onclick="return postQuote(this)" title="{{ __('main.quote') }}"><i class="fa fa-quote-right text-muted"></i></a>

                                <a href="#" onclick="return sendComplaint(this)" data-type="{{ $comment->relate->getMorphClass() }}" data-id="{{ $comment->id }}" data-token="{{ $_SESSION['token'] }}" data-page="{{ $comments->currentPage() }}" rel="nofollow" title="{{ __('main.complain') }}"><i class="fa fa-bell text-muted"></i></a>
                            @endif

                            @if ($comment->created_at + 600 > SITETIME && getUser('id') === $comment->user->id)
                                <a href="/articles/edit/{{ $article->id }}/{{ $comment->id }}?page={{ $comments->currentPage() }}" data-toggle="tooltip" title="{{ __('main.edit') }}"><i class="fa fa-pencil-alt text-muted"></i></a>
                            @endif

                            @if (isAdmin())
                                <a href="#" onclick="return deleteComment(this)" data-rid="{{ $comment->relate_id }}" data-id="{{ $comment->id }}" data-type="{{ $comment->relate->getMorphClass() }}" data-token="{{ $_SESSION['token'] }}" data-toggle="tooltip" title="{{ __('main.delete') }}"><i class="fa fa-times text-muted"></i></a>
                            @endif
                        </div>
                    @endif

                    <b>{!! $comment->user->getProfile() !!}</b> <small>({{ dateFixed($comment->created_at) }})</small><br>
                    {!! $comment->user->getStatus() !!}
                </div>
                <div class="section-message">
                    {!! bbCode($comment->text) !!}<br>
                </div>

                @if (isAdmin())
                    <span class="data">({{ $comment->brow }}, {{ $comment->ip }})</span>
                @endif
            </div>
        @endforeach
    @else
        {!! showError(__('main.empty_comments')) !!}
    @endif

    {{ $comments->links() }}

    @if (getUser())
        <div class="section-form p-2 shadow">
            <form action="/articles/comments/{{ $article->id }}" method="post">
                @csrf
                <div class="form-group{{ hasError('msg') }}">
                    <label for="msg">{{ __('main.message') }}:</label>
                    <textarea class="form-control markItUp" maxlength="{{ setting('comment_length') }}" id="msg" rows="5" name="msg" required>{{ getInput('msg') }}</textarea>
                    <div class="invalid-feedback">{{ textError('msg') }}</div>
                    <span class="js-textarea-counter"></span>
                </div>

                <button class="btn btn-success">{{ __('main.write') }}</button>
            </form>
        </div>

        <a href="/rules">{{ __('main.rules') }}</a> /
        <a href="/stickers">{{ __('main.stickers') }}</a> /
        <a href="/tags">{{ __('main.tags') }}</a><br><br>

    @else
        {!! showError(__('main.not_authorized')) !!}
    @endif
@stop
