"use strict"

var { log } = console

// create route modal
// create route page
add_action('submit:newRoute', function(opts) { // opts:{event, form}
	opts.event.preventDefault()
	$.post(opts.form.action, $(opts.form).serialize(), function(data) {
		if (data.status && data.status === 'ok') {
			location.href = data.location
		}else if(data.errors && data.errors.length){
			data.errors.forEach(err => alert('danger', err))
		}else{
			alert('danger', data.message || 'Error!')
		}
	})
})


;(function() {

	var results_count = 0
	$('#route_search').on('input', function(e) {
	  if (this.value.length > 0) {
		$.get('/routes/searchAjax?query=' + this.value, function(data) {
			if (data.status && data.status === 'ok') {
				results_count = data.results_count
				$('#search_autocomplete').html(data.search_list_html)
			}
		}, 'json')
	  }else{
	  	$('#search_autocomplete').html('')
	  }
	})

	var activeIndex = -1
	$('#route_search').on('keydown', function(e){
		if(e.which === 13 && $('#search_autocomplete li.active').length){
			e.stopPropagation()
			$('#search_autocomplete li.active a')[0].click()
			return false
		}
		$('#search_autocomplete li.active').removeClass('active')
		if (e.which === 38 || e.which === 40) {
			if (e.which === 38) { // arrow up
				activeIndex = in_range(activeIndex - 1, 0, results_count - 1)
			}
			if (e.which === 40) { // arrow down
				activeIndex = in_range(activeIndex + 1, 0, results_count - 1)
			}
			$('#search_autocomplete li.result-item').eq(activeIndex).addClass('active')
			// return false
		}else{
			activeIndex = -1
		}
		log(activeIndex)
	})
	function in_range(num, min, max) {
	  if(num < min) return max
	  if(num > max) return min
	  return num
	}

	$('#route_search').focus(function(){
		document.body.classList.remove('ajax-loader')
		$('#search_autocomplete').css('display', 'block')
	})

	$('body').on('click', function(e) {
	  if (!$(e.target).closest('#main_search_form').length && !$('#route_search:focus').length) {
	  	document.body.classList.add('ajax-loader')
		$('#search_autocomplete').css('display', 'none')
	  }
	})
}())