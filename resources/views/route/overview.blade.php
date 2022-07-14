@extends('main')

@section('link')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="{{ asset('js/map.js') }}"></script>
@endsection

@section('content')
    <div class="container text-center">
        <h1> {{ __('messages.overview_route') }} </h1>
        <h4 class="sub-header"> {{ __('messages.overview_info') }} </h4>
        <div class="content">

            <div class="card card-body text-center">
                <label for="name" class="route-label">{{ __('messages.route_name') }}</label>
                <div class="mb-3">
                    <p>{{ $name }}</p>
                </div>

                <label for="transport" class="route-label">{{ __('messages.transport_type') }}</label>
                <div class="mb-3">
                    @switch ($option)
                        @case(0)
                            <p>{{ __('messages.default') }}</p>
                        @break
                        @case(1)
                            <p>{{ __('messages.walking') }}</p>
                        @break
                        @case(2)
                            <p>{{ __('messages.car') }}</p>
                        @break
                        @case(3)
                            <p>{{ __('messages.public_transport') }}</p>
                        @break
                    @endswitch
                </div>

                <label for="description" class="route-label">{{ __('messages.description') }}</label>
                <div class="mb-3">
                    <p>{{ $description }}</p>
                </div>

                <label for="length" class="route-label">{{ __('messages.sights_amount') }}</label>
                <div>
                    <p>{{ $length }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('main') }}" class="btn btn-primary back-btn" role="button">{{ __('messages.back_to_main') }}</a>
    </div>
@endsection
