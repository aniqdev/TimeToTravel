@if(count($route->markers)) <h3>Достопримечательности</h3> @endif
<div id="route_sights_{{ $route->id }}">
	@foreach($route->markers as $sight)
		<div class="card card-info card-outline">
			<a class="d-block w-100 collapsed" data-toggle="collapse" href="#route_sight_{{ $sight->id }}" aria-expanded="true"> {{-- collapsed --}}
				<div class="card-header">
					<h5 class="card-title w-100 d-flex justify-content-between sight-title">[{{ $loop->iteration }}] {{ $sight->name }}</h5>
				</div>
			</a>
			<div id="route_sight_{{ $sight->id }}" class="collapse" data-parent="#route_sights_{{ $route->id }}" style=""> {{-- show --}}
				<div class="card-body">
					<p>{{ $sight->description }}</p>
					<div class="my-2">
						@foreach($sight->images as $image)
							<img class="img-thumbnail" src="{{ $image->url }}" style="max-width: 150px;">
						@endforeach
					</div>
					<div class="my-2">
						@foreach($sight->audio as $audio)
							<audio controls src="{{ $audio->url }}">
					            Your browser does not support the
					            <code>audio</code> element.
						    </audio>
						@endforeach
					</div>
					<div class="row my-2">
						@if(!env('HIDE_VIDEO'))
							@foreach($sight->video as $video)
								<div class="col-sm-3">
									<div class="">
										<iframe style="max-width:100%" src="<?= preg_replace('/watch\?v=/', 'embed/', $video->url) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
									</div>
								</div>
							@endforeach
						@else
							<h4><s>Отображение видео отключено</s></h4>
						@endif
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>