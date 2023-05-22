$(document).ready(function() {
	//Load external HTML code

	$('#nav_bar').load('extra_html/nav_bar.html');
	$('#remove_modal_extra_html').load('extra_html/delete_modal.html');
	
	//Main functions that are used a lot

	window.loadData = function(url, target) {
		$(target).load(url);
	};

	window.postData = function(url, formData, url_target, id) {
		$.post(url, formData, function(response) {
		  console.log(response);
		  loadData(url_target, id);
		});
	};

	$('#auto_vietos_div').removeClass('hidden');
		$('#auto_vietos').click(function(event) {
		  $('#spa_vietos_div').addClass('hidden');
		  $('#auto_vietos_div').removeClass('hidden');
		});
		$('#spa_vietos').click(function(event) {
		  $('#auto_vietos_div').addClass('hidden');
		  $('#spa_vietos_div').removeClass('hidden');
	});

	let dataId;
	let dataCategory;
	let dataUrl;
	let dataTable;
			
	$(document).on('click', '#confirm_cancel', function() {
		if (!$('#remove_modal_extra_html').hasClass('hidden')) {
		  $('#remove_modal_extra_html').addClass('hidden');
		}
	});

	$(document).on('click', '#confirm_delete', function() {
		if (!$('#remove_modal_extra_html').hasClass('hidden')) {
		  $('#remove_modal_extra_html').addClass('hidden');
			postData('backend/remove.php', {category: dataCategory, id: dataId}, dataUrl, dataTable);
		}
	});

	$(document).on('click', '#deleteme', function() {
		if ($('#remove_modal_extra_html').hasClass('hidden')) {
		  $('#remove_modal_extra_html').removeClass('hidden');
		  dataId = $(this).closest('#deleteme').data('id');
		  dataCategory = $(this).closest('#deleteme').data('category');
		  dataUrl = $(this).closest('#deleteme').data('url');
		  dataTable = $(this).closest('#deleteme').data('table');
		}
	});
	
	
	
	
});