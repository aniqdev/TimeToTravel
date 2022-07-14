<!-- Edit Audio Title Modal -->
<div class="modal fade" id="edit_audio_title_modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" action="{{ route('echo') }}" onsubmit="editAudioTitleSubmit(this, event)">
			@csrf
			<input type="hidden" name="audio_id" id="modal_audio_id_input">
			<input type="hidden" name="object_type" id="modal_audio_object_input">

			<div class="modal-header">
				<h5 class="modal-title">Редактирование</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<audio controls src="" style="width: 100%;" id="modal_audio_preview_src">
						Your browser does not support the
						<code>audio</code> element.
				</audio>
				
				<div class="form-group">
					<label>Введите название</label>
					<input type="text" name="title" value="Название" class="form-control" id="modal_audio_title_input">
				</div>

			</div>
			<div class="modal-footer text left">
				<button type="submit" class="btn btn-info">Сохранить</button>
			</div>
		</form>
	</div>
</div>