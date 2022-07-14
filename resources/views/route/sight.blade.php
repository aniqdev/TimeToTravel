@extends('main')

@section('link')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ $key }}&callback=initMap&v=weekly"
        async>
    </script>
    <script src="{{ asset('js/map.js') }}"></script>
    <script src="{{ asset('js/file_input.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid sight-content">
        <div class="row">
            <div class="col" id="map"></div>
            <div class="route-form col">
                <h4 class="header">{{ __('messages.add_sight_instruction') }} </h4>

                <form method="post" action="{{ action('App\Http\Controllers\RoutesController@addSight') }}"
                    enctype="multipart/form-data" accept-charset="UTF-8">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                    <input name="route_id" type="hidden" value="{{ $route_id }}" />
                    <input name="order" type="hidden" value="{{ $order }}" />
                    <input name="length" type="hidden" value="{{ $length }}" />

                    <div class="input-group {{ $errors->first('longitude') ? '' : 'mb-3' }}">
                        <label for="longitude" class="form-label">{{ __('messages.longitude') }}&nbsp&nbsp</label>
                        <input type="text" class="form-control {{ $errors->first('longitude') ? 'form-error' : '' }}"
                            id="longitude" name="longitude" placeholder="-73.985428" value='{{ $longitude }}'>
                    </div>
                    {!! $errors->first('longitude', '<p class="help-block">:message</p>') !!}

                    <div class="input-group {{ $errors->first('latitude') ? '' : 'mb-3' }}">
                        <label for="latitude" class="form-label">{{ __('messages.latitude') }}&nbsp&nbsp</label>
                        <input type="text" class="form-control {{ $errors->first('latitude') ? 'form-error' : '' }}"
                            id="latitude" name="latitude" placeholder="40.748817" value='{{ $latitude }}'>
                    </div>
                    {!! $errors->first('latitude', '<p class="help-block">:message</p>') !!}

                    <div class="input-group mb-3">
                        <label for="order" class="form-label">{{ __('messages.priority') }}&nbsp&nbsp</label>
                        <input type="text" class="form-control" name="order" value="{{ $order }}" readonly>
                    </div>

                    <label for="name" class="form-label {{ $errors->first('name') ? '' : 'mb-3' }}">
                        {{ __('messages.sight_name') }}</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control {{ $errors->first('name') ? 'form-error' : '' }}"
                            name="name" value='{{ $name }}'>
                    </div>
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}

                    <label for="description" class="form-label">{{ __('messages.description') }}</label>
                    <div class="input-group">
                        <textarea class="form-control" name="description">{{ $description }}</textarea>
                    </div>

                    <div>
                        <label class="form-label file-input">{{ __('messages.photo') }}</label>
                        <div class="row">
                            @if (isset($images))
                                @foreach ($images as $image_url)
                                    <div class="col-lg-4 col-md-4 col-xs-4">
                                        <img class="img-fluid  d-block mx-auto" src="{{ $image_url }}" alt="">
                                        <button class="btn btn-danger d-block mx-auto mt-2" type="button"
                                            onclick="return this.parentNode.remove();">{{ __('messages.delete_btn') }}</button>
                                        <input type="hidden" value="{{ $image_url }}"
                                            name="{{ 'uploaded_images[' . $loop->iteration . ']' }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        @if (count($errors->get('images.*')) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->get('images.*') as $image_input_error)
                                        Фото {{ $loop->iteration }}:
                                        @foreach ($image_input_error as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="imageInputBlock"></div>
                        <button class="btn btn-primary d-block my-3" type="button" onclick="AddFileInput()">
                        {{ __('messages.add_more') }}</button>
                    </div>


                    <label for="audio" class="form-label file-input">{{ __('messages.audio') }}</label>
                    @if (isset($audio) && $audio != "")
                    <p class="help-block">{{ __('messages.audio_help') }}</p>
                    @endif
                    <div class="input-group">
                        <input type="file" class="form-control" name="audio" accept="audio/*">
                        {{-- <button class="btn btn-primary" type="button">{{ __('messages.add_more') }}</button> --}}
                    </div>
                    {!! $errors->first('audio', '<p class="help-block">:message</p>') !!}

                    <div class="container-fluid btn-form">


                        <div class="add-route text-center">
                            <button name="action" class="btn btn-success btn-padding btn-group justify-content-center"
                                type="submit" value="save">{{ __('messages.save_btn') }}</button>
                            <button name="action" class="btn btn-danger btn-padding btn-group justify-content-center"
                                type="submit" value="delete" @if ($length < 2) disabled @endif>{{ __('messages.delete_btn') }}</button>
                        </div>

                        <div class="add-route text-center">
                            <button name="action" class="btn btn-primary btn-padding btn-group justify-content-center"
                                type="submit" value="prev" @if ($order < 2) disabled @endif>{{ __('messages.prev_btn') }}</button>
                            <button name="action" class="btn btn-primary btn-padding btn-group justify-content-center"
                                type="submit" value="next" @if ($length <= $order) disabled @endif>{{ __('messages.next_btn') }}</button>
                        </div>

                        <div class="add-route text-center">
                            <button name="action" class="btn btn-primary btn-padding btn-group justify-content-center"
                                type="submit" value="new">{{ __('messages.add_sight_btn') }}</button>
                            <button name="action" class="btn btn-primary btn-padding btn-group justify-content-center"
                                type="submit" value="end">{{ __('messages.finish_btn') }}</button>
                        </div>

                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection
