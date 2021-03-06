@extends('layout')

@section('title', __('index.blacklist'))

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/admin">{{ __('index.panel') }}</a></li>
            <li class="breadcrumb-item active">{{ __('index.blacklist') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <?php $active = ($type === 'email') ? 'success' : 'light'; ?>
    <a href="/admin/blacklists?type=email" class="badge badge-{{ $active }}">{{ __('admin.blacklists.email') }}</a>
    <?php $active = ($type === 'login') ? 'success' : 'light'; ?>
    <a href="/admin/blacklists?type=login" class="badge badge-{{ $active }}">{{ __('admin.blacklists.logins') }}</a>
    <?php $active = ($type === 'domain') ? 'success' : 'light'; ?>
    <a href="/admin/blacklists?type=domain" class="badge badge-{{ $active }}">{{ __('admin.blacklists.domains') }}</a>
    <br><br>

    @if ($lists->isNotEmpty())
        <form action="/admin/blacklists/delete?type={{ $type }}&amp;page={{ $lists->currentPage() }}" method="post">
            @csrf
            @foreach ($lists as $list)
                <div class="b">
                    <input type="checkbox" name="del[]" value="{{ $list->id }}">

                    <i class="fa fa-pencil-alt"></i> <b>{{ $list->value }}</b>
                </div>
                <div>
                    {{ __('main.added') }}: {!! $list->user->getProfile() !!} ({{ dateFixed($list->created_at) }})
                </div>
            @endforeach

            <button class="btn btn-sm btn-danger">{{ __('main.delete_selected') }}</button>
        </form>
    @else
        {!! showError( __('admin.blacklists.empty_list')) !!}
    @endif

    {{ $lists->links() }}

    <div class="section-form p-2 shadow">
        <form action="/admin/blacklists?type={{ $type }}" method="post">
            @csrf
            <div class="form-inline">
                <div class="form-group{{ hasError('value') }}">
                    <input type="text" class="form-control" id="value" name="value" maxlength="100" value="{{ getInput('value') }}" placeholder="{{ __('main.record') }}" required>
                </div>

                <button class="btn btn-primary">{{ __('main.add') }}</button>
            </div>
            <div class="invalid-feedback">{{ textError('value') }}</div>
        </form>
    </div>

    {{ __('main.total') }}: <b>{{ $lists->total() }}</b><br>
@stop
