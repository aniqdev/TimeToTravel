<!-- Edit Image Title Modal -->
<div class="modal fade" id="edit_image_title_modal" tabindex="-1" aria-labelledby="new_route_modal_label" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" action="{{ route('echo') }}" onsubmit="editImageTitleSubmit(this, event)">
			@csrf
			<input type="hidden" name="image_id" class="js-img-id">
			<input type="hidden" name="object_type" class="js-object-type">

			<div class="modal-header">
				<h5 class="modal-title" id="new_route_modal_label">Редактирование</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="img-thumbnail">
					<img class="mx-auto d-block js-img-preview" src="" style="max-width: 100%;">
				</div>
				
				<div class="form-group">
					<label>Введите название</label>
					<input type="text" name="title" value="Название" class="form-control js-img-title-input">
				</div>

			</div>
			<div class="modal-footer text left">
				<button type="submit" class="btn btn-info">Сохранить</button>
			</div>
		</form>
	</div>
</div>