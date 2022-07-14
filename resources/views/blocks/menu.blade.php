<nav class="mt-2">
	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

		{{-- <li class="nav-item">
			<a href="{{ route('dashboard') }}" class="nav-link ">
				<i class="nav-icon fas fa-tachometer-alt"></i>
				<p>Dashboard</p>
			</a>
		</li> --}}

		{{-- <li class="nav-item">
			<a href="{{ route('settings') }}" class="nav-link">
				<i class="nav-icon fas fa-user"></i>
				<p>О себе</p>
			</a>
		</li> --}}

		<li class="nav-item">{{-- menu-open --}}
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-map"></i>
				<p>
					Маршруты
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview" style="display: none;">
				<li class="nav-item">
					<a href="{{ route('routes.create') }}" class="nav-link cursor-pointer" {{-- onclick="do_action('modal:newRoute')" --}}>
						<i class="far fa-circle nav-icon"></i>
						<p>Добавить маршрут</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('routes.index') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Список маршрутов</p>
					</a>
				</li>
			</ul>
		</li>

		<li class="nav-item">
			<a href="{{ route('routeReviews.index') }}" class="nav-link">{{-- class "actice" for highliting --}}
				<i class="nav-icon fas fa-star"></i>
				<p>Отзывы</p>
			</a>
		</li>

		<li class="nav-item">
			<a href="{{ route('cities.index') }}" class="nav-link">
				<i class="nav-icon fas fa-city"></i>
				<p>Города</p>
			</a>
		</li>

		<li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon fas fa-users"></i>
				<p>
					Пользователи
					<i class="right fas fa-angle-left"></i>
				</p>
			</a>
			<ul class="nav nav-treeview" style="display: none;">
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Создать</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('users.index') }}" class="nav-link">
						<i class="far fa-circle nav-icon"></i>
						<p>Список</p>
					</a>
				</li>
			</ul>
		</li>

		{{-- <li class="nav-item">
			<a href="pages/widgets.html" class="nav-link">
				<i class="nav-icon fas fa-th"></i>
				<p>
					Widgets
					<span class="right badge badge-danger">New</span>
				</p>
			</a>
		</li> --}}
		{{-- <li class="nav-item">
			<a href="#" class="nav-link">
				<i class="fas fa-circle nav-icon"></i>
				<p>Level 1</p>
			</a>
		</li> --}}
		{{-- <li class="nav-header">LABELS</li> --}}
		{{-- <li class="nav-item">
			<a href="#" class="nav-link">
				<i class="nav-icon far fa-circle text-danger"></i>
				<p class="text">Important</p>
			</a>
		</li> --}}
	</ul>
</nav>
<script>
$('.nav-sidebar .nav-link').each(function(index) {
	if (this.href === (location.origin + location.pathname)) {
		$(this).addClass('active')
			.closest('.nav-treeview').css('display', 'block')
			.closest('.nav-item').addClass('menu-open')
	}
})
</script>