<!-- Sight Files Modal -->
<div class="modal fade" id="edit_files_modal" tabindex="-1" aria-labelledby="edit_files_modal_label" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" action="{{ route('routes.store') }}" onsubmit="editRouteFiles(event, this )" enctype="multipart/form-data" method="POST" id="edit_files_form">
			@csrf
			<input type="hidden" name="sight_id" value="" id="object_id_input">
			<div class="modal-header">
				<h5 class="modal-title" id="edit_files_modal_label">Добавить файлы</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label>Фото</label>
					<div class="custom-file">
						<input name="images[]" type="file" class="custom-file-input" id="images_file_input" multiple>
						<label class="custom-file-label" for="images_file_input">Фото</label>
					</div>
				</div>

				<div class="form-group">
					<label>Аудио</label>
					<div class="custom-file">
						<input name="audio[]" type="file" class="custom-file-input" id="audio_file_input" multiple>
						<label class="custom-file-label" for="audio_file_input">Аудио</label>
					</div>
				</div>

				<div class="form-group video-input-wrapper ">
					<label>
						<span>Видео</span>
						<a class="btn btn-default btn-xs ml-1" onclick="addVideoInputClick(this)">
							<i class="fas fa-plus" role="button"></i> Add
						</a>
					</label>
				</div>

			</div>
			<div class="modal-footer text left">
				<button type="submit" class="btn btn-info">Сохранить</button>
			</div>
		</form>
	</div>
</div>
<script>
var object_files_element
function editFilesModal(button, object_type, object_id) { // 'sight_id', 10
	$('#edit_files_form').trigger('reset')
	$('input#object_id_input').attr('name', object_type).val(object_id)
	$('#edit_files_modal').modal('show')
	object_files_element = $(button).closest('.js-object-files-wrapper').find('.js-object-files')
}
function editSightFilesModal(button) { // 'sight_id', 10
	var object_id = button.closest('.sight-list-item').querySelector('.sight-id').value
	$('#edit_files_form').trigger('reset')
	$('input#object_id_input').attr('name', 'sight_id').val(object_id)
	$('#edit_files_modal').modal('show')
	object_files_element = $(button).closest('.js-object-files-wrapper').find('.js-object-files')
}
function editRouteFiles(event, form) { // opts: {event, form}
	event.preventDefault()
	var formData = new FormData(form)

	fetch('/routes/updateRouteFiles', {
	    method: 'POST',
	    headers: { Accept: 'application/json' },
	    body: formData
	}).then(response => response.json())
	  .then(data => {
	  	if (data.status && data.status === 'ok' && data.view) {
	  		object_files_element.html(data.view)
	  		$('#edit_files_form').trigger('reset')
	  		$('#edit_files_modal').modal('hide')
	  		do_action('setMarkerImages')
	  		updateSortImagesEvents()
	  	}else{
	  		alert('danger', data.message || 'Error!')
	  	}
	  })
	  .catch(error => console.log(error));
}
</script>