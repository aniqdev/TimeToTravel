<div class="wrapper">
	<nav class="main-header navbar navbar-expand navbar-white navbar-light">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
			</li>
			{{-- <li class="nav-item d-none d-sm-inline-block">
				<a href="{{ route('dashboard') }}" class="nav-link">Home</a>
			</li> --}}
			{{-- <li class="nav-item d-none d-sm-inline-block">
				<a href="{{ route('logout') }}" class="nav-link">Logout</a>
			</li> --}}
		</ul>
		{{-- <form action="simple-results.html">
			<div class="input-group">
				<input type="search" class="form-control form-control-sdm" placeholder="Type your keywords here">
				<div class="input-group-append">
					<button type="submit" class="btn btn-smd btn-default">
						<i class="fa fa-search"></i>
					</button>
				</div>
			</div>
		</form> --}}
		<ul class="navbar-nav ml-auto">
			@if(false)
			<li class="nav-item">
				<a class="nav-link" data-widget="navbar-search" href="#" role="button">
				<i class="fas fa-search"></i>
				</a>
				<div class="navbar-search-block" style="display: none;">
					<form class="form-inline" action="{{ route('routes.search') }}">
						<div class="input-group input-group-sm position-relative">
							<input name="query" value="{{ request('query') }}" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="route_search">
							<div class="input-group-append">
								<button class="btn btn-navbar" type="submit">
								<i class="fas fa-search"></i>
								</button>
								<button class="btn btn-navbar" type="button" data-widget="navbar-search">
								<i class="fas fa-times"></i>
								</button>
							</div>
							<ul class="search-autocomplete" id="search_autocomplete"></ul>
						</div>
					</form>
				</div>
			</li>
			@endif
			{{-- <li class="nav-item dropdown">
				<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
				<i class="far fa-comments"></i>
				<span class="badge badge-danger navbar-badge">3</span>
				</a>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
					<a href="#" class="dropdown-item">
						<div class="media">
							<img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
							<div class="media-body">
								<h3 class="dropdown-item-title">
									Brad Diesel
									<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
								</h3>
								<p class="text-sm">Call me whenever you can...</p>
								<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
							</div>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item">
						<div class="media">
							<img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
							<div class="media-body">
								<h3 class="dropdown-item-title">
									John Pierce
									<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
								</h3>
								<p class="text-sm">I got your message bro</p>
								<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
							</div>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item">
						<div class="media">
							<img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
							<div class="media-body">
								<h3 class="dropdown-item-title">
									Nora Silvester
									<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
								</h3>
								<p class="text-sm">The subject goes here</p>
								<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
							</div>
						</div>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
				</div>
			</li> --}}
			{{-- <li class="nav-item dropdown">
				<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
				<i class="far fa-bell"></i>
				<span class="badge badge-warning navbar-badge">15</span>
				</a>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
					<span class="dropdown-item dropdown-header">15 Notifications</span>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item">
					<i class="fas fa-envelope mr-2"></i> 4 new messages
					<span class="float-right text-muted text-sm">3 mins</span>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item">
					<i class="fas fa-users mr-2"></i> 8 friend requests
					<span class="float-right text-muted text-sm">12 hours</span>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item">
					<i class="fas fa-file mr-2"></i> 3 new reports
					<span class="float-right text-muted text-sm">2 days</span>
					</a>
					<div class="dropdown-divider"></div>
					<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
				</div>
			</li> --}}
			{{-- <li class="nav-item">
				<a class="nav-link" data-widget="fullscreen" href="#" role="button">
				<i class="fas fa-expand-arrows-alt"></i>
				</a>
			</li> --}}
			{{-- <li class="nav-item">
				<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
				<i class="fas fa-th-large"></i>
				</a>
			</li> --}}
			{{-- <li class="nav-item">
				<a class="nav-link" href="{{ route('logout') }}" role="button">
				<i class="fas fa-sign-out-alt"></i>
				</a>
			</li> --}}
		</ul>
	</nav>
	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<a href="/" class="brand-link">
		<img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-light">Time<b>To</b>Travel</span>
		</a>
		<div class="sidebar">
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="image">
					<img src="{{ auth()->user()->avatar ? auth()->user()->avatar : 'https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png' }}" class="img-circle elevation-2" alt="User Image">
				</div>
				<div class="info" title="Edit profile">
					<a href="{{ route('settings') }}" class="d-block">{{ auth()->user()->name }}</a>
				</div>
				<a class="ml-auto info" href="{{ route('logout') }}" role="button" title="Logout">
					<i class="fas fa-sign-out-alt"></i>
				</a>
			</div>
			@include('blocks.menu')
		</div>
	</aside>
	<div class="content-wrapper" style="min-height: 1172.56px;">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0">@yield('page-title')</h1>
					</div>
					<div class="col-sm-6">
						@yield('breadcrumbs')
					</div>
				</div>
			</div>
		</div>
		<div class="content">
			<div class="container-fluid">
				@yield('content')
			</div>
		</div>
	</div>
	<aside class="control-sidebar control-sidebar-dark" style="display: none;">
  
	</aside>
	<footer class="main-footer">
		<strong>Copyright © {{ date('Y') }} <a href="#">TimeToTravel</a>.</strong>
		All rights reserved.
		<div class="float-right d-none d-sm-inline-block">
			<b>Version</b> 1.0.0
		</div>
	</footer>
	<div id="sidebar-overlay"></div>
</div>