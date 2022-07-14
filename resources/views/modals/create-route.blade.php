<!-- New Route Modal -->
<div class="modal fade" id="new_route_modal" tabindex="-1" aria-labelledby="new_route_modal_label" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" action="{{ route('routes.store') }}" onsubmit="do_action('submit:newRoute', { event, form:this })">
			@csrf
			<div class="modal-header">
				<h5 class="modal-title" id="new_route_modal_label">Создать маршрут</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label>Введите название нового маршрута</label>
					<input type="text" name="name" value="Название" class="form-control">
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
</div>
<script>
add_action('modal:newRoute', function() {
	$('#new_route_modal').modal('show')
})
</script>