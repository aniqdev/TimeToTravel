@extends('layouts.main')

@section('page-title')
Список маршрутов
@endsection

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
	<li class="breadcrumb-item"><a href="/">Home</a></li>
	<li class="breadcrumb-item active">Маршруты</li>
</ol>
@endsection

@section('content')
<div class="container-fluid routes-index">
	<div class="page-sets d-flex">

		<form action="{{ route('routes.search') }}" class="d-inline-block mr-5">
			<div class="input-group position-relative">
				<input type="text" name="query" value="" placeholder="Type Message ..." class="form-control" id="route_search">
				<ul class="search-autocomplete" id="search_autocomplete"></ul>
				<span class="input-group-append">
					<button type="submit" class="btn btn-outline-info"><i class="fas fa-search"></i></button>
				</span>
			</div>
		</form>

		{{ $routes->links() }}

		<div class="page-set-sorting ml-auto mb-3">
			<select class="form-control" onchange="location.href = this.value">
				<option {{ session('routesOrder') === 'title_asc' ? 'selected' : '' }} value="?setRoutesOrder=title_asc">названию</option>
				<option {{ session('routesOrder') === 'date_asc' ? 'selected' : '' }} value="?setRoutesOrder=date_asc">дате (от старых)</option>
				<option {{ session('routesOrder') === 'date_desc' ? 'selected' : '' }} value="?setRoutesOrder=date_desc">дате (от новых)</option>
			</select>
		</div>
		<div class="page-set-perpage ml-1 mb-3">
			<select class="form-control" onchange="location.href = this.value">
				<option {{ session('routesPerPage') == '10' ? 'selected' : '' }} value="?setRoutesPerPage=10">10</option>
				<option {{ session('routesPerPage') == '20' ? 'selected' : '' }} value="?setRoutesPerPage=20">20</option>
				<option {{ session('routesPerPage') == '50' ? 'selected' : '' }} value="?setRoutesPerPage=50">50</option>
				<option {{ session('routesPerPage') == '100' ? 'selected' : '' }} value="?setRoutesPerPage=100">100</option>
			</select>
		</div>
	</div>
	@foreach($routes as $route)
	<div class="row route-item position-relative {{ $route->trashed() ? 'trashed' : '' }}" id="route_{{ $route->id }}">
		<div class="restore-link position-absolute">
			Удалено
			<button class="btn btn-default" onclick="restoreRoute({{ $route->id }})">восстановить</button>
		</div>
		<div class="col-sm-3 mb-3">
			<img class="img-thumbnail" src="{{ $route->preview_url }}" alt="">
			<div class="btn-group mt-2 d-flex" role="group" aria-label="Basic example">
			  <button type="button" class="btn btn-secondary" onclick="showRouteMapModal({{ $route->id }})">Показать</button>
			  <a href="{{ route('routes.edit', $route) }}" class="btn btn-info">Редактровать</a>
			  <button type="button" class="btn btn-danger" onclick="deleteRouteClick({{ $route->id }})">Удалить</button>
			</div>
		</div>
		<div class="col-sm-9 mb-3">
			<div class="route-info card px-2">
				<h3 class="d-flex">
					<span>{{ $route->name }}</span>
					<a class="ml-auto" href="{{ route('users.showAutor', $route->author) }}"><small>{{ $route->author->name }} {{ $route->author->surname }}</small></a>
				</h3>
				<p>{{ $route->description }}</p>
				<div class="my-2">
					@foreach($route->images as $image)
						<a href="{{ $image->url }}" data-lightbox="image-{{ $route->id }}">
							<img class="img-thumbnail" src="{{ $image->preview }}" style="max-width: 150px;">
						</a>
					@endforeach
				</div>
				<div class="my-2">
					@foreach($route->audio as $audio)
						<audio controls src="{{ $audio->url }}">
				            Your browser does not support the
				            <code>audio</code> element.
					    </audio>
					@endforeach
				</div>
				@if(!env('HIDE_VIDEO'))
					<div class="row my-2">
						@foreach($route->video as $video)
							<div class="col-sm-3">
								<div class="">
									<iframe style="max-width:100%" src="{{ $video->embed }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
								</div>
							</div>
						@endforeach
					</div>
				@else
					<h4><s>Отображение видео отключено</s></h4>
				@endif
				{{-- sight list was here --}}
			</div>
		</div>
	</div>
	@endforeach
	{{ $routes->links() }}
</div>
<script>
function deleteRouteClick(route_id) {
	if(!confirm('Удалить?')) return false
	$.ajax('/routes/' + route_id, 
		{
			type : 'DELETE',
			data: {
				_token: $('meta[name="csrf-token"]').attr('content')
			},
			success: function(data) {
				if (data && data.status === 'ok'){
					$('#route_'+route_id).addClass('trashed')
				}
			}
		})
}
function restoreRoute(route_id) {
	$.post('/routes/restore', 
		{
			route_id: route_id,
			_token: $('meta[name="csrf-token"]').attr('content')
		},
		function (data) {
			if (data && data.status === 'ok'){
				$('#route_'+route_id).removeClass('trashed')
			}
		})
}
</script>

@include('modals.show-route-map')
@endsection