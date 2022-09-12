(function ($) {

    $(document).on('click', '#btnImportCsv', function (e) {
        const loading = $('#loadingImport');
		const returnBox =  $('#messageImport');
		loading.show();
		returnBox.html("Iniciando importação de produtos...").attr( "style", "display: block !important;" );
		const file = $('#csvFile').prop('files')[0];
		const form_data = new FormData();
		form_data.append('file', file);
		form_data.append('action', ajaxobj.action_import);
		form_data.append('security', ajaxobj.security)
		$.ajax({
			url: ajaxobj.ajax_url,
			type: 'post',
			processData: false,
			contentType: false,
			data: form_data,
			success: function (data, textStatus, jqXHR) {
				console.log(data)
				returnBox.html(data)
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
			},
			complete: function (jqXHR, textStatus) {
				loading.hide();
			}
		});
	});

	function importStatus() {
		let params = {
			action: ajaxobj.action_importStatus,
			nonce: ajaxobj.security,
		}
		$.get(ajaxobj.ajax_url, params, function(res){
			console.log(res.message)
		}, 'json');
	}

}(jQuery));