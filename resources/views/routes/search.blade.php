@extends('layouts.main')

@section('page-title', 'Поиск' )

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
	<li class="breadcrumb-item"><a href="/">Home</a></li>
	<li class="breadcrumb-item active">Поиск</li>
</ol>
@endsection

@section('content')
<style>
.search-item-card{
	color: #555;
}
.search-item-card .description{
	margin-top: 0;
    margin-bottom: 1rem;
    height: 75px;
    overflow: hidden;
}
.search-item-card .widget-user-username,
.search-item-card .widget-user-desc{
	text-shadow: 0 0 5px #000;
}
</style>
<h5 class="mb-3">
	<form action="{{ route('routes.search') }}" class="d-inline-block mr-5">
		<div class="input-group">
			<input type="text" name="query" value="{{ request('query') }}" placeholder="Type Message ..." class="form-control" autofocus>
			<span class="input-group-append">
				<button type="submit" class="btn btn-outline-info"><i class="fas fa-search"></i></button>
			</span>
		</div>
	</form>
	{{ $message }}
</h5>
<div class="row">
	@foreach($routes as $route)
		<div class="col-md-4">
			<a href="{{ route('routes.edit', $route) }}" class="card card-widget widget-user shadow-lg search-item-card">
				<div class="widget-user-header text-white" style="background: url('{{ $route->preview_url }}') center center;">
					<h3 class="widget-user-username text-right">{{ $route->author->name }}</h3>
					<h5 class="widget-user-desc text-right">{{ $route->author->surname }}</h5>
				</div>
				<div class="widget-user-image" title="{{ $route->author->name }}">
					<img class="img-circle" src="{{ $route->author->avatar }}" alt="User Avatar">
				</div>
				<div class="card-footer">
					<h3 class="title eclips">{{ $route->name }}</h3>
					<p class="description">{{ $route->description }}</p>
				</div>
			</a>
		</div>
	@endforeach
</div>
@endsection