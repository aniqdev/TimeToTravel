<div class="form-group">
	<label>Название маршрута</label>
	<input type="text" class="form-control" name="name" value="{{ $route->name }}" oninput="routeNameChange(this)">
</div>

<div class="form-group">
	<label for="description" class="form-label">Описание</label>
	<textarea class="form-control" name="description">{{ $route->description }}</textarea>
</div>

<div class="form-group">
	<label for="">Город</label>
	<select name="city_id" class="form-control" onchange="change_city(this)">
		@foreach($cities as $city)
			<option {{ $city->id === $route->city->id ? 'selected' : '' }} value="{{ $city->id }}" data-center='{"lng": {{ $city->longitude }}, "lat": {{ $city->latitude }} }'>{{ $city->title }}</option>
		@endforeach
	</select>
</div>

<div class="form-group">
	<label class="d-block">Основное изображение</label>
	<label class="d-block cursor-pointer" for="preview_url_input">
		<img class="img-thumbnail d-block m-auto" src="{{ $route->preview_url ?? '/images/preview-1.jpg' }}" style="max-width: 100%;max-height: 150px;" id="edit_route_preview_img">
	</label>
	<div class="custom-file mt-2">
		<input name="preview_url" type="file" class="custom-file-input" id="preview_url_input" onchange="mainImageInputChange(this)">
		<label class="custom-file-label" for="preview_url_input">jpg, png</label>
	</div>
</div>

<script>
function routeNameChange(input) {
	$('.route-name').text(input.value)
}
function mainImageInputChange(image_input) {
	var file = image_input.files[0];
	const fileType = file['type'];
	const validImageTypes = ['image/jpeg', 'image/png'];
	if (!validImageTypes.includes(fileType)) {
			alert('danger', 'Wrong format')
			return false
	}
	if (FileReader) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#edit_route_preview_img').attr('src', reader.result)
		};
		reader.readAsDataURL(file);
	}
}
</script>

<div class="row mt-4">
	<div class="col-6">
		<div class="form-group">
			<label for="">Цена</label>
			<input name="price" value="{{ $route->price }}" type="text" class="form-control">
		</div>
	</div>
	<div class="col-6">
		<div class="form-group">
			<label for="">Старая цена</label>
			<input name="old_price" value="{{ $route->old_price }}" type="text" class="form-control">
		</div>
	</div>
</div>

<div class="row mt-4">
	<div class="col-6">
		<div class="form-group" title="Расстояние маршрута в метрах">
			<label for="route_length">Длина(метров)</label>
			<div class="input-group">
				<input name="length" value="{{ $route->length }}" type="text" class="form-control" id="route_length" {{ isset($route->options->auto_distance) ? 'readonly' : '' }}>
				<span class="input-group-append" title="расчитывать автоматически">
					<label class="btn btn-info">A
						<input name="options[auto_distance]" value="1" type="checkbox" class="" id="auto_distance_chbx" {{ isset($route->options->auto_distance) ? 'checked' : '' }}>
					</label>
				</span>
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="form-group" title="Продолжительность маршрута в минутах">
			<label for="route_duration">Время(минут)</label>
			<input name="duration" value="{{ $route->duration }}" type="text" class="form-control" id="route_duration">
		</div>
	</div>
</div>

<div class="row mt-4">
	<div class="col-6">
		<div class="form-group">
			<label for="">Транспорт</label>
			<select name="transport" class="form-control" id='profile' style='flex-grow: 1; margin-left: 3px'>
				<option value='walking'>Пеший</option>
				{{-- <option value='cycling'>Велосипедный</option>
				<option value='driving'>Автомобильный</option>
				<option value='driving-traffic'>С учётом пробок</option> --}}
			</select>
		</div>
	</div>
	<div class="col-6">
		<div class="form-group">
			<label for="">Язык</label>
			<select name="language" class="form-control">
			  <option value="ru">Русский</option>
			</select>
		</div>
	</div>
</div>

<div class="js-object-files-wrapper">
	<button class="btn btn-info btn-xs" type="button" onclick="editFilesModal(this, 'route_id', {{ $route->id }})">Загрузить файлы</button>
	<div class="js-object-files">
		@include('routes.blocks.show-object-files', ['object' => $route])
	</div>
</div>

