@section('header')
    <h1>{{ __('settings.adverts') }}</h1>
@stop

<form action="/admin/settings?act=adverts" method="post">
    @csrf
    <div class="form-group{{ hasError('sets[rekusershow]') }}">
        <label for="rekusershow">{{ __('settings.adverts_count_links') }}:</label>
        <input type="number" class="form-control" id="rekusershow" name="sets[rekusershow]" maxlength="2" value="{{ getInput('sets.rekusershow', $settings['rekusershow']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekusershow]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[rekuserprice]') }}">
        <label for="rekuserprice">{{ __('settings.adverts_price') }}:</label>
        <input type="number" class="form-control" id="rekuserprice" name="sets[rekuserprice]" maxlength="8" value="{{ getInput('sets.rekuserprice', $settings['rekuserprice']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekuserprice]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[rekuserpoint]') }}">
        <label for="rekuserpoint">{{ __('settings.adverts_points') }}:</label>
        <input type="number" class="form-control" id="rekuserpoint" name="sets[rekuserpoint]" maxlength="3" value="{{ getInput('sets.rekuserpoint', $settings['rekuserpoint']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekuserpoint]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[rekuseroptprice]') }}">
        <label for="rekuseroptprice">{{ __('settings.adverts_option') }}:</label>
        <input type="number" class="form-control" id="rekuseroptprice" name="sets[rekuseroptprice]" maxlength="8" value="{{ getInput('sets.rekuseroptprice.', $settings['rekuseroptprice']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekuseroptprice]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[rekusertime]') }}">
        <label for="rekusertime">{{ __('settings.adverts_term') }}:</label>
        <input type="number" class="form-control" id="rekusertime" name="sets[rekusertime]" maxlength="3" value="{{ getInput('sets.rekusertime', $settings['rekusertime']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekusertime]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[rekusertotal]') }}">
        <label for="rekusertotal">{{ __('settings.adverts_max_links') }}:</label>
        <input type="number" class="form-control" id="rekusertotal" name="sets[rekusertotal]" maxlength="2" value="{{ getInput('sets.rekusertotal', $settings['rekusertotal']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekusertotal]') }}</div>
    </div>

    <div class="form-group{{ hasError('sets[rekuserpost]') }}">
        <label for="rekuserpost">{{ __('settings.adverts_per_page') }}:</label>
        <input type="number" class="form-control" id="rekuserpost" name="sets[rekuserpost]" maxlength="2" value="{{ getInput('sets.rekuserpost', $settings['rekuserpost']) }}" required>
        <div class="invalid-feedback">{{ textError('sets[rekuserpost]') }}</div>
    </div>

    <button class="btn btn-primary">{{ __('main.save') }}</button>
</form>
