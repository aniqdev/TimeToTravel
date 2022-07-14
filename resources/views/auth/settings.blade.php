@extends('layouts.main')

@section('content')
<style>
.socials i{
    font-size: 40px;
    margin-bottom: 7px;
    color: #17a2b8;
    width: 40px;
    text-align: center;
}
</style>
	<div class="row">
		<div class="col mb-4 mt-4">
			<article class="card-body reg-card">
				<h4 class="text-center mb-4 mt-1">{{ __('messages.settings') }}</h4>
				<hr>
				<form method="post" action="{{ action('App\Http\Controllers\AuthController@updateProfile') }}"
				enctype="multipart/form-data" accept-charset="UTF-8">
					@csrf
					<div class="row">
						<div class="col-sm-4">
							
							@if ($user->avatar)
								<label class="d-block" for="avatar_input">
									<img class="img-thumbnail d-block mx-auto" src="{{ $user->avatar }}" alt="">
								</label>
							@endif

							{{-- <div class="input-group">
								<input type="file" class="form-control" name="avatar">
							</div> --}}
							<div class="custom-file mt-2">
								<input name="avatar" type="file" class="custom-file-input" id="avatar_input">
								<label class="custom-file-label" for="avatar_input">jpg, png</label>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-info card-btn mt-5"> {{ __('messages.submit_btn') }}
								</button>
							</div>

						</div>
						<div class="col-sm-8">

							<div class="row">
								<div class="col-sm-6">

									<div class="form-group">
										<label for="name" class="form-label">{{ __('messages.name') }}:</label>
										<div class="input-group">
											<input class="form-control" type="text" name="name" value='{{ $user->name }}'>
										</div>
										@if ($errors->has('name'))
											<span class="text-danger">{{ $errors->first('name') }}</span>
										@endif
									</div>

								</div>
								<div class="col-sm-6">

									<div class="form-group">
										<label for="" class="form-label">{{ __('messages.surname') }}:</label>
										<div class="input-group">
											<input class="form-control" type="text" name="surname" value='{{ $user->surname }}'>
										</div>
										@if ($errors->has('surname'))
											<span class="text-danger">{{ $errors->first('surname') }}</span>
										@endif
									</div>

								</div>
							</div>

							<div class="form-group">
								<label for="email" class="form-label">{{ __('messages.email') }}:</label>
								<div class="input-group">
									<input class="form-control" type="email" name="email" value="{{ $user->email }}">
								</div>
								@if ($errors->has('email'))
									<span class="text-danger">{{ $errors->first('email') }}</span>
								@endif
							</div>

							<div class="row">
								<div class="col-sm-6">

									<div class="form-group">
										<label for="name" class="form-label">Страна:</label>
										<div class="input-group">
											<select class="form-control" type="text" name="country">
												<option value="ru">Россия</option>
											</select>
										</div>
									</div>

								</div>
								<div class="col-sm-6">

									<div class="form-group">
										<label for="name" class="form-label">Язык:</label>
										<div class="input-group">
											<select class="form-control" type="text" name="lang">
												<option value="ru">Русский</option>
											</select>
										</div>
									</div>

								</div>
							</div>

							<div class="row">
								<div class="col-sm-6">

									<div class="form-group">
										<label for="description" class="form-label">{{ __('messages.user_description') }}</label>
										<div class="input-group">
											<textarea class="form-control" type="text"
												name="description">{{ $user->description }}</textarea>
										</div>
									</div>

								</div>
								<div class="col-sm-6">
									<div class="form-group socials">
										<label for="email" class="form-label">Социальные сети</label>
										<div class="input-group" title="whatsapp">
											<div class="pull-left">
												<i class="fab fa-whatsapp-square"></i>
											</div>
											<input class="form-control mb-2 ml-1" type="text" name="socials[phone]" value="{{ $user->socials->phone }}">
										</div>
										<div class="input-group" title="instagram">
											<div class="pull-left">
												<i class="fab fa-instagram-square"></i>
											</div>
											<input class="form-control mb-2 ml-1" type="text" name="socials[instagram]" value="{{ $user->socials->instagram }}">
										</div>
										<div class="input-group" title="twitter">
											<div class="pull-left">
												<i class="fab fa-twitter-square"></i>
											</div>
											<input class="form-control mb-2 ml-1" type="text" name="socials[twitter]" value="{{ $user->socials->twitter }}">
										</div>
										<div class="input-group" title="facebook-messenger">
											<div class="pull-left">
												<i class="fab fa-facebook-messenger"></i>
											</div>
											<input class="form-control mb-2 ml-1" type="text" name="socials[facebook]" value="{{ $user->socials->facebook }}">
										</div>
									</div>
								</div>
							</div>

							<hr>
							<div class="row">
								<div class="col-sm-6">

									<div class="form-group">
										<label for="password" class="form-label">{{ __('messages.new_password') }}:</label>
										<div class="input-group">
											<input class="form-control" type="password" name="password">
										</div>
										@if ($errors->has('password'))
										<span class="text-danger">{{ $errors->first('password') }}</span>
										@endif
									</div>

								</div>
								<div class="col-sm-6">

									<div class="form-group">
										<label for="password" class="form-label">{{ __('messages.repeat_password') }}</label>
										<div class="input-group">
											<input class="form-control" type="password" name="password_confirmation">

										</div>
									</div>
								</div>
							</div>

						</div> {{-- right side (col-sm-8) --}}
					</div> {{-- row --}}

				</form>
			</article>
		</div>
	</div>
@endsection
