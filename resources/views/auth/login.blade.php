@extends('layouts.app')

@section('content')
<div class="login-box">
	<div class="login-logo">
		<a href="#"><b>TimeToTravel</b></a>
	</div>
	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Sign in to start your session</p>
			<form action="{{ action('App\Http\Controllers\AuthController@webLogin') }}" method="post">
				@csrf

				<div class="input-group mb-3">
					<input name="email" value="{{ old('email') }}" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
					@error('email')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="input-group mb-3">
					<input name="password" value="" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
					@error('password')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="row">
					<div class="col-8">
						<div class="icheck-primary">
							<input name="remember" type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
							<label for="remember">Remember Me</label>
						</div>
					</div>
					<div class="col-4">
						<button type="submit" class="btn btn-primary btn-block">Sign In</button>
					</div>
				</div>
			</form>

			{{-- <div class="social-auth-links text-center mb-3">
				<p>- OR -</p>
				<a href="#" class="btn btn-block btn-primary">
				<i class="fab fa-facebook mr-2"></i> Sign in using Facebook
				</a>
				<a href="#" class="btn btn-block btn-danger">
				<i class="fab fa-google-plus mr-2"></i> Sign in using Google+
				</a>
			</div> --}}
			
			{{-- <p class="mb-1">
				<a href="forgot-password.html">I forgot my password</a>
			</p> --}}

			<p class="mb-0">
				<a href="{{ route('register') }}" class="text-center">Register a new membership</a>
			</p>
		</div>
	</div>
</div>
@endsection


@section('content_')
	 <div class="row">
		  <div class="col mb-4 mt-4">
				<article class="card-body login-card">
					 <h4 class="card-title text-center mb-4 mt-1">{{ __('messages.login') }}</h4>
					 <hr>
					 <form method="post" action="{{ action('App\Http\Controllers\AuthController@webLogin') }}"
						  accept-charset="UTF-8">
						  @csrf
						  <div class="form-group">
								<label for="email" class="form-label">{{ __('messages.email') }}:</label>
								<div class="input-group">
									 <input class="form-control" type="email" name="email" value="{{ old('email') }}">
								</div>
								@if ($errors->has('email'))
									 <span class="text-danger">{{ $errors->first('email') }}</span>
								@endif
						  </div>
						  <div class="form-group">
								<label for="password" class="form-label">{{ __('messages.password') }}:</label>
								<div class="input-group">
									 <input class="form-control" type="password" name="password">
								</div>
								@if ($errors->has('password'))
									 <span class="text-danger">{{ $errors->first('password') }}</span>
									 <p class="text-danger">{{ __('messages.password_warning') }}</p>
								@endif
						  </div>
						  <div class="form-group text-center">
								<button type="submit" class="btn btn-primary card-btn"> {{ __('messages.login') }} </button>
						  </div>
					 </form>
				</article>
		  </div>
	 </div>
@endsection
