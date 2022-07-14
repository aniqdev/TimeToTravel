@extends('layouts.main')

@section('page-title', 'Автор')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
	<li class="breadcrumb-item"><a href="/">Home</a></li>
	<li class="breadcrumb-item active">Автор</li>
</ol>
@endsection

@section('content')
<style>
.socials i{
    font-size: 40px;
    margin-top: 7px;
    color: #17a2b8;
    width: 40px;
    text-align: center;
}
</style>

<h2>{{ $user->name }}</h2>

<div class="row">
	<div class="col-md-6 col-lg-5 col-xl-4">
		<div class="img-thumbnail">
			<img class="d-block mx-auto" src="{{ $user->avatar }}" alt="" style="max-width:100%">
		</div>
	</div>
	<div class="col-md-6 col-lg-7 col-xl-8">
		<div class="table-responsive">
			<table class="table">
				<tbody>
					<tr>
						<th style="width:50%">Имя:</th>
						<td>{{ $user->name }} {{ $user->surname }}</td>
					</tr>
					<tr>
						<th>О себе</th>
						<td>{{ $user->description }}</td>
					</tr>
					<tr>
						<td>
							<div class="form-group socials">
								<label for="email" class="form-label">Социальные сети</label>
								<div class="input-group" title="whatsapp">
									<div class="pull-left">
										<i class="fab fa-whatsapp-square"></i>
									</div>
									<span class=" mt-2 ml-1" disabled type="text" name="phone">{{ $user->socials->phone }}</span>
								</div>
								<div class="input-group" title="instagram">
									<div class="pull-left">
										<i class="fab fa-instagram-square"></i>
									</div>
									<span class=" mt-2 ml-1" disabled type="text" name="phone">{{ $user->socials->instagram }}</span>
								</div>
								<div class="input-group" title="twitter">
									<div class="pull-left">
										<i class="fab fa-twitter-square"></i>
									</div>
									<span class=" mt-2 ml-1" disabled type="text" name="phone">{{ $user->socials->twitter }}</span>
								</div>
								<div class="input-group" title="facebook-messenger">
									<div class="pull-left">
										<i class="fab fa-facebook-messenger"></i>
									</div>
									<span class=" mt-2 ml-1" disabled type="text" name="phone">{{ $user->socials->facebook }}</span>
								</div>
							</div>
						</td>
						<td>
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection