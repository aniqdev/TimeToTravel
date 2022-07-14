<?php

$item_num = $loop->iteration ?? '{item_num}';

?>
<li class="ui-state-default-dd sight-list-item {{ $path_dot_class ?? ($sight->name === '€€' ? 'path-dot' : '') }}" 
		onmouseenter="sightListItemMouseenter(this)"
		onmouseleave="sightListItemMouseleave(this)"
		data-sightid="{{ $sight->id ?? '0' }}">{{-- Item {{ $item_num }} --}}

	<input type="hidden" name="sights[{{ $item_num }}][id]" value="{{ $sight->id ?? '{sight_id}' }}" class="sight-id">
  	<input type="hidden" name="sights[{{ $item_num }}][priority]" value="{{ $item_num }}" class="sight-priority">
  	<input type="hidden" name="sights[{{ $item_num }}][latitude]" value="{{ $sight->latitude ?? '{latitude}' }}" class="sight-latitude">
  	<input type="hidden" name="sights[{{ $item_num }}][longitude]" value="{{ $sight->longitude ?? '{longitude}' }}" class="sight-longitude">
	<div class="card card-info card-outline">
		<a class="d-block w-100 collapsed" data-toggle="collapse" href="#sight_collaps_{{ $item_num }}" aria-expanded="true">
			<div class="card-header">
				<h4 class="card-title w-100 d-flex justify-content-between">
					<span class="sight-title eclips" title="{{ $sight->name }}">{{ $sight->name }}</span>
					<i class="fas fa-compress-arrows-alt ml-auto mr-3" onclick="toCenter(event, { lng:{{ $sight->longitude ?? '{longitude}' }}, lat:{{ $sight->latitude ?? '{latitude}' }} })"></i>
					<span class="badge badge-info right js-item-num-badge">{{ $item_num }}</span>
				</h4>
			</div>
		</a>
		<div id="sight_collaps_{{ $item_num }}" class="collapse" data-parent="#main_route_form" style="">
			<div class="card-body">
				<div class="form-group">
					<label>Название достопримечательности</label>
					<input name="sights[{{ $item_num }}][name]" value="{{ $sight->name }}" type="text" class="form-control sight-title-input" oninput="sightTitleOninput(this)" placeholder="Название">
				</div>
				<div class="form-group">
					<label>Описание достопримечательности</label>
					<textarea name="sights[{{ $item_num }}][description]" class="form-control" rows="3" placeholder="Описание">{{ isset($sight->description) ? $sight->description : 'Описание' }}</textarea>
				</div>

					<div class="js-object-files-wrapper">
						<div class="sight-buttons d-flex justify-content-between">
							<button class="btn btn-info btn-xs" type="button" onclick="editSightFilesModal(this)">Загрузить файлы</button>
							<button class="btn btn-danger btn-xs" type="button" onclick="removeSight(this)">Удалить достопримечательность</button>
						</div>
						<div class="js-object-files">
							@include('routes.blocks.show-object-files', ['object' => ($sight ?? json_decode('{id:0,images:[],audio:[],video:[]}'))])
						</div>
					</div>

			</div>
		</div>
	</div>
</li>