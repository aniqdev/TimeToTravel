@extends('layouts.main')

@section('page-title', __('messages.route_creation') )

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
	<li class="breadcrumb-item"><a href="/">Home</a></li>
	<li class="breadcrumb-item active">Create route</li>
</ol>
@endsection

@section('content')
<div class="container justify-center" style="max-width: 800px;">
	<form class="modal-content" action="{{ route('routes.store') }}" onsubmit="do_action('submit:newRoute', { event, form:this })">
		@csrf
		<div class="modal-header">
			<h5 class="modal-title">Создать маршрут</h5>
		</div>
		<div class="modal-body">

			<div class="form-group">
				<label>Введите название нового маршрута</label>
				<input type="text" name="name" placeholder="Название" class="form-control">
			</div>

			<div class="form-group">
				<label>Описание маршрута</label>
				<textarea name="description" class="form-control" rows="3" placeholder="Описание">Описание</textarea>
			</div>

			<div class="form-group">
				<label for="">Город</label>
				<select name="city_id" class="form-control">
					@foreach($cities as $city)
						<option value="{{ $city->id }}">{{ $city->title }}</option>
					@endforeach
				</select>
			</div>

		</div>
		<div class="modal-footer text left">
			<button type="submit" class="btn btn-info">Создать</button>
		</div>
	</form>
</div>
@endsection