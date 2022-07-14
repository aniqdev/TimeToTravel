@extends('layouts.app')

@section('content')
<div class="register-box">
	<div class="register-logo">
		<a href="../../index2.html"><b>Admin</b>LTE</a>
	</div>
	<div class="card">
		<div class="card-body register-card-body">
			<p class="login-box-msg">Register a new membership</p>
			<form action="{{ action('App\Http\Controllers\AuthController@webRegister') }}" method="post">
				@csrf
				<div class="input-group mb-3">
					<input name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Full name">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-user"></span>
						</div>
					</div>
					@error('name')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="input-group mb-3">
					<input name="surname" value="{{ old('surname') }}" type="text" class="form-control @error('surname') is-invalid @enderror" placeholder="Full name">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-user"></span>
						</div>
					</div>
					@error('surname')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group">
					<label for="description" class="form-label mt-3">{{ __('messages.user_description') }}</label>
					<div class="input-group">
						<textarea class="form-control @error('description') is-invalid @enderror" type="text" name="description" style="border-right: inset;"></textarea>
					</div>
					@error('description')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="input-group mb-3">
					<input name="email" type="email" class="form-control @error('description') is-invalid @enderror" placeholder="Email">
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
					<input name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
					@error('password')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>
				
				<div class="input-group mb-3">
					<input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Retype password">
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
					@error('password_confirmation')
						<span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<label for="avatar" class="form-label file-input">{{ __('messages.upload_avatar') }} </label>
				<div class="custom-file mb-4">
				    <input name="avatar" type="file" class="custom-file-input" id="avatar" accept="image/*">
				    <label class="custom-file-label" for="avatar">Choose file</label>
				</div>

				<div class="row">
					<div class="col-8">
						<div class="icheck-primary">
							<input type="checkbox" id="agreeTerms" name="terms" value="agree">
							<label for="agreeTerms">
							I agree to the <a href="#">terms</a>
							</label>
						</div>
					</div>
					<div class="col-4">
						<button type="submit" class="btn btn-primary btn-block">Register</button>
					</div>
				</div>

			</form>

			{{-- <div class="social-auth-links text-center">
				<p>- OR -</p>
				<a href="#" class="btn btn-block btn-primary">
				<i class="fab fa-facebook mr-2"></i>
				Sign up using Facebook
				</a>
				<a href="#" class="btn btn-block btn-danger">
				<i class="fab fa-google-plus mr-2"></i>
				Sign up using Google+
				</a>
			</div> --}}

			<a href="{{ route('login') }}" class="text-center">I already have a membership</a>
		</div>
	</div>
</div>
@endsection

@section('content_')
	<div class="row">
		<div class="col mb-4 mt-4">
			<article class="card-body reg-card">
				<h4 class="card-title text-center mb-4 mt-1">{{ __('messages.register') }}</h4>
				<hr>
				<form method="post" action="{{ action('App\Http\Controllers\AuthController@webRegister') }}"
				enctype="multipart/form-data" accept-charset="UTF-8">
					@csrf
					<div class="form-group">
						<label for="name" class="form-label">{{ __('messages.name') }}*:</label>
						<div class="input-group">
							<input class="form-control" type="text" name="name" value="{{ old('name') }}">
						</div>
						@if ($errors->has('name'))
							<span class="text-danger">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<div class="form-group">
						<label for="surname" class="form-label">{{ __('messages.surname') }}*:</label>
						<div class="input-group">
							<input class="form-control" type="text" name="surname" value="{{ old('surname') }}">
						</div>
						@if ($errors->has('surname'))
							<span class="text-danger">{{ $errors->first('surname') }}</span>
						@endif
					</div>
					<div class="form-group">
						<label for="email" class="form-label">{{ __('messages.email') }}*:</label>
						<div class="input-group">
							<input class="form-control" type="email" name="email" value="{{ old('email') }}">
						</div>
						@if ($errors->has('email'))
							<span class="text-danger">{{ $errors->first('email') }}</span>
						@endif
						<div class="form-group">
							<label for="description" class="form-label mt-3">{{ __('messages.user_description') }}</label>
							<div class="input-group">
								<textarea class="form-control" type="text" name="description"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="form-label">{{ __('messages.password') }} ({{ __('messages.password_help') }}):</label>
							<div class="input-group">
								<input class="form-control" type="password" name="password">
							</div>
							@if ($errors->has('password'))
								<span class="text-danger">{{ $errors->first('password') }}</span>
							@endif
						</div>
						<div class="form-group">
							<label for="password" class="form-label">{{ __('messages.repeat_password') }}</label>
							<div class="input-group">
								<input class="form-control" type="password" name="password_confirmation">
							</div>
						</div>
						<label for="avatar" class="form-label file-input">{{ __('messages.upload_avatar') }} </label>
						<div class="input-group">
							<input type="file" class="form-control" name="avatar" accept="image/*">
						</div>
						<div class="form-group text-center">
							<button type="submit" class="btn btn-primary card-btn mt-5 text-center">{{ __('messages.register') }}
							</button>
						</div>
				</form>
			</article>
		</div>
	</div>
@endsection
