<div class="view js-file-object-data"
	data-view="routes.blocks.show-object-files"
	data-object="{{ isset($sight) ? 'sight' : 'route' }}"
	data-objectid="{{ $object->id ?? 0 }}"
>
	<div class="form-group">
		<label>Изображения</label>
		<div class="my-2 js-sortable-images">
			@foreach($object->images as $image)
				<div class="object-image position-relative js-file-object img-thumbnail" 
					 data-imageid="{{ $image->id }}"
					 data-imageurl="{{ $image->url }}"
					 data-imagepreview="{{ $image->preview }}"
					 title="{{ $image->title }}"
					 order="{{ $image->order }}">
					
					<a href="{{ $image->url }}" class="d-block img-thumbnai" data-lightbox="image-{{ $object->id ?? 0 }}">
						<img class="" src="{{ $image->preview }}" style="max-width: 100%;">
					</a>
					<div class="object-title elipsis">{{ $image->title }}</div>
					<div class="controls d-flex justify-content-around img-thumbnai mt-1">
						<div class="edit cursor-pointer" onclick="editImageTitleModal(this)">
							<i class="fas fa-pencil-alt"></i>
						</div>
						<button type="button" class="remove-btn"
						onclick="removeObjectFileClick(this, '{{ isset($sight) ? 'SightImage' : 'RouteImage' }}', {{ $image->id }})">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				</div>
			@endforeach
		</div>
	</div>

	<div class="form-group">
		<label>Аудио</label>
		<div class="my-2">
			@foreach($object->audio as $audio)
				<div class="object-audio position-relative js-file-object img-thumbnail mb-1" style="width: 100%;"
						data-audioid="{{ $audio->id }}"
						data-audiourl="{{ $audio->url }}"
						title="{{ $audio->title }}">
					<div class="audio-title elipsis">{{ $audio->title }}</div>
					<button type="button" class="position-absolute remove-btn" 
						onclick="removeObjectFileClick(this, '{{ isset($sight) ? 'SightAudio' : 'RouteAudio' }}', {{ $audio->id }})"><i class="fas fa-trash-alt"></i></button>
					<button type="button" class="position-absolute remove-btn" 
						style="transform: translateX(-30px); background-color: #17a2b8c7;" 
						onclick="editAudioTitleModal(this, '{{ isset($sight) ? 'sight' : 'route' }}', {{ $audio->id }})"><i class="fas fa-pencil-alt"></i></button>
					<audio controls src="{{ $audio->url }}" style="width: 100%;">
						Your browser does not support the
						<code>audio</code> element.
					</audio>
				</div>
			@endforeach
		</div>
	</div>

	@if(!env('HIDE_VIDEO') || 1)
	<div class="form-group">
		<label>Видео</label>
		<div class="row my-2">
			@foreach($object->video as $video)
				<div class="col-sm-6 js-file-object">
					<button type="button" class="position-absolute remove-btn"
						onclick="removeObjectFileClick(this, '{{ isset($sight) ? 'SightVideo' : 'RouteVideo' }}', {{ $video->id }})">&times;</button>
					<div class="">
						<iframe style="max-width:100%" src="<?= preg_replace('/watch\?v=/', 'embed/', $video->url) ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	@else
	<h4><s>Отображение видео отключено</s></h4>
	@endif
</div>