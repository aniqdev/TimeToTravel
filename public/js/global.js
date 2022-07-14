var log = console.log

function alert(status, text) {
	if(text === undefined) text = status
	var alert =	`<div class="alert alert-${status} alert-dismissible fade show">
					${text}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>`
	$('#errors_list').prepend(alert)
	$(".alert-success").delay(4000).slideUp(400, function() {
	    $(this).alert('close');
	});
}

$(".alert-success").delay(4000).slideUp(400, function() {
    $(this).alert('close');
});

(function() {
	var actions = []
	window.add_action = function(action, callback) {
		if(!actions[action]) actions[action] = []
		actions[action].push(callback)
	}
	window.do_action = function(action, data = {}) {
		log('action', action)
		if(actions[action]){
			actions[action].forEach(callback => {
				callback.apply({},Array.from(arguments).slice(1))
			})
		}
	}
}())