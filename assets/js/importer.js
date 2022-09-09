(function ($) {

    $(document).on('click', '#btnExportCsv', function (e) {
		$('#loadingLogs').show();
		let params = {
			action: ajaxobj.action_runAvatar,
			nonce: ajaxobj.eucap_nonce,
		}
		$.get(ajaxobj.ajax_url, params, function(res){
			const div = $('#logFileContent');
			div.html(res.message[1]);
			$('#loadingLogs').hide();
		}, 'json');
	});

    $(document).on('click', '#btnImportCsv', function (e) {
        const loading = $('#loadingImport')
		loading.show();
		let params = {
			action: ajaxobj.action_import,
			nonce: ajaxobj.g28b2bking_nonce,
		}
		$.get(ajaxobj.ajax_url, params, function(res){
            loading.hide()
			console.log(res.message)
		}, 'json');
	});


}(jQuery));