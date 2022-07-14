@extends('main')

@section('link')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="{{ asset('js/map.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <h1 class="text-center"> {{ __('messages.route_creation') }} </h1>
        <h4 class="sub-header text-center"> {{ __('messages.overview_info') }}  </h4>
        <div class="content">
            <form method="post" enctype="multipart/form-data"
                action="{{ action('App\Http\Controllers\RoutesController@create') }}" accept-charset="UTF-8">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />

                <label for="name" class="form-label">{{ __('messages.route_name') }}</label>
                <div class="input-group {{ $errors->first('name') ? '' : 'mb-3' }}">
                    <input type="text" class="form-control {{ $errors->first('name') ? 'form-error' : '' }}" name="name" value="{{$name}}">
                </div>
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}

        <label for="transport">{{ __('messages.transport_type') }}</label>
        <select class="form-control" name="transport">
        <option value=0 @if ($option == 0) selected="selected" @endif>{{ __('messages.default') }}</option>
        <option value=1 @if ($option == 1) selected="selected" @endif>{{ __('messages.walking') }}</option>
        <option value=2 @if ($option == 2) selected="selected" @endif>{{ __('messages.car') }}</option>
        <option value=3 @if ($option == 3) selected="selected" @endif>{{ __('messages.public_transport') }}</option>
        </select>

        <label for="description" class="form-label">{{ __('messages.description') }}</label>
        <div class="input-group">
        <textarea class="form-control" name="description">{{ $description }}</textarea>
        </div>

                <label for="mainImage" class="form-label file-input">{{ __('messages.photo') }}</label>
                <div class="input-group">
                    <input type="file" class="form-control" name="mainImage" accept="image/*">
                    {{-- <button class="btn btn-primary" type="button">{{ __('messages.add_more') }}</button> --}}
                </div>
                {!! $errors->first('mainImage', '<p class="help-block">:message</p>') !!}

                <div class="text-center add-route justify-content-center">
                    <button class="btn btn-primary main-btn" type="submit">{{ __('messages.add_sights') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
