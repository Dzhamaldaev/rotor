@extends('layout')

@section('title', __('admin.bans.user_ban') . ' ' .$user->getName())

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/admin">{{ __('index.panel') }}</a></li>
            <li class="breadcrumb-item"><a href="/admin/bans">{{ __('index.ban_unban') }}</a></li>
            <li class="breadcrumb-item active">{{ __('admin.bans.user_ban') }} {{ $user->getName() }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <h3>{!! $user->getGender() !!} {!! $user->getProfile() !!}</h3>

    @if ($user->lastBan->id)
        {{ __('users.last_ban') }}: {{ dateFixed($user->lastBan->created_at) }}<br>
        {{ __('users.banned') }}: <b>{!! $user->lastBan->sendUser->getProfile() !!}</b><br>
        {{ __('users.term') }}: {{ formatTime($user->lastBan->term) }}<br>
        {{ __('users.reason_ban') }}: {!! bbCode($user->lastBan->reason) !!}<br>
    @endif

    <i class="fa fa-history"></i> <b><a href="/admin/banhists/view?user={{ $user->login }}">{{ __('index.ban_history') }}</a></b><br><br>

    @if ($user->level === 'banned' && $user->timeban > SITETIME)
        <div class="section-form p-2 shadow">
            <div class="p-1 my-1 bg-danger text-white">{{ __('users.user_banned') }}</div>
            {{ __('users.ending_ban') }}: {{ formatTime($user->timeban - SITETIME) }}<br>
        </div>

        <i class="fa fa-pencil-alt"></i> <a href="/admin/bans/change?user={{ $user->login }}">{{ __('main.change') }}</a><br>
        <i class="fa fa-check-circle"></i> <a href="/admin/bans/unban?user={{ $user->login }}&amp;token={{ $_SESSION['token'] }}" onclick="return confirm('{{ __('admin.bans.confirm_unban') }}')">{{ __('users.unban') }}</a><hr>
    @else
        <div class="section-form p-2 shadow">
            <form method="post" action="/admin/bans/edit?user={{ $user->login }}">
                @csrf
                <div class="form-group{{ hasError('time') }}">
                    <label for="time">{{ __('admin.bans.time_ban') }}:</label>
                    <input class="form-control" name="time" id="time" value="{{ getInput('time') }}" required>
                    <div class="invalid-feedback">{{ textError('time') }}</div>
                </div>

                <?php $inputType = getInput('type'); ?>
                <div class="form-group{{ hasError('type') }}">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="inputTypeMinutes" name="type" value="minutes"{{ $inputType === 'minutes' ? ' checked' : '' }}>
                        <label class="custom-control-label" for="inputTypeMinutes">{{ __('main.minutes') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="inputTypeHours" name="type" value="hours"{{ $inputType === 'hours' ? ' checked' : '' }}>
                        <label class="custom-control-label" for="inputTypeHours">{{ __('main.hours') }}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="inputTypeDays" name="type" value="days"{{ $inputType === 'days' ? ' checked' : '' }}>
                        <label class="custom-control-label" for="inputTypeDays">{{ __('main.days') }}</label>
                    </div>
                    <div class="invalid-feedback">{{ textError('type') }}</div>
                </div>

                <div class="form-group{{ hasError('reason') }}">
                    <label for="reason">{{ __('users.reason_ban') }}:</label>
                    <textarea class="form-control markItUp" id="reason" rows="5" name="reason" required>{{ getInput('reason') }}</textarea>
                    <div class="invalid-feedback">{{ textError('reason') }}</div>
                </div>

                <div class="form-group{{ hasError('note') }}">
                    <label for="notice">{{ __('main.note') }}:</label>
                    <textarea class="form-control markItUp" id="notice" rows="5" name="notice">{{ getInput('notice', $user->note->text) }}</textarea>
                    <div class="invalid-feedback">{{ textError('notice') }}</div>
                </div>

                <button class="btn btn-primary">{{ __('admin.bans.banned') }}</button>
            </form>
        </div>

        <p class="text-muted font-italic">{{ __('admin.bans.ban_hint') }}</p>
    @endif
@stop
