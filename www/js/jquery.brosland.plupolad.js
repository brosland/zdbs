jQuery.fn.extend({
	uploader: function(options) {
		var container = $(this)
			.append($(document.createElement('h3')).text('Nahrávanie súborov'))
			.append(createTable())
			.append(createButtons());
		var uploader = new plupload.Uploader({
			runtimes: 'html5,silverlight,browserplus,flash',
			browse_button: 'pick' + options.token,
			container: container.attr('id'),
			max_file_size: options.max_file_size || 0,
			chunk_size: '2mb',
			url: options.url,
			flash_swf_url: options.basePath + '/plugins/plupload/runtimes/plupload.flash.swf',
			silverlight_xap_url: options.basePath + '/plugins/plupload/runtimes/plupload.silverlight.xap',
			filters: options.filters
		});
		
		uploader.init();
		container.data('uploader', uploader);

		uploader.bind('FilesAdded', function(up, files) {
			$.each(files, function(i, file) {
				if(file.status == plupload.QUEUED) {
					addRow(file);
				}
			});

			$.get(options.pingUrl, function(payload) {
				$.nette.success(payload);
				up.refresh(); // Reposition Flash/Silverlight
				up.start();
			});
		});

		uploader.bind('FilesRemoved', function(up, files) {
			$.each(files, function(i, file) {
				removeRow(file);
			});

			up.refresh(); // Reposition Flash/Silverlight
		});

		uploader.bind('BeforeUpload', function(up, file) {
			$('#' + file.id).addClass('uploading');
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + ' td.state').text(file.percent + '%');
		});

		uploader.bind('FileUploaded', function(up, file, response) {
			try {
				var payload = $.parseJSON(response.response);

				if(payload.error) {
					$('#' + file.id).removeClass().addClass('upload-error');
					$('#' + file.id + ' td.name').append('<br>' + payload.message);
					return;
				}
			} catch (e) {
				// not json
			}

			$.get(options.pingUrl, function(payload) {
				$.nette.success(payload);
			});

			$('#' + file.id).removeClass().addClass('uploaded');
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + ' td.state').text(file.percent + '%');
		});

		uploader.bind('Error', function(up, err) {
			var message = 'Chyba ' + err.code + ': ' + err.message;

			if(err.code == plupload.HTTP_ERROR || err.code == plupload.FAILED) {
				$('#' + err.file.id).removeClass().addClass('upload-error');
					$('#' + err.file.id + ' td.name').append('<br>Prenos súboru zlyhal.');
					return;
			} else if(err.code == plupload.FILE_EXTENSION_ERROR) {
				message = 'Súbor "' + err.file.name + '" má nepodporovaný formát(.' + /[^.]+$/.exec(err.file.name) + ').';
			} else if(err.code == plupload.FILE_SIZE_ERROR) {
				message = 'Veľkosť súboru prekračuje maximálny limit(' + Math.ceil(up.settings.max_file_size/ 1024/ 1024) + ' mb).';
			}

			alert(message);
			up.refresh(); // Reposition Flash/Silverlight
		});
		
		function createTable() {
			var tr = $(document.createElement('tr'))
				.append($(document.createElement('th')).text('Ikona'))
				.append($(document.createElement('th')).text('Názov súboru'))
				.append($(document.createElement('th')).addClass('column-right').text('Veľkosť'))
				.append($(document.createElement('th')).addClass('column-right').text('Odoslané'))
				.append($(document.createElement('th')).addClass('column-right'));
			
			return $(document.createElement('div')).addClass('table').css('padding-bottom', '10px')
				.append($(document.createElement('table')).attr('id', 'filelist')
					.append($(document.createElement('thead')).append(tr))
					.append($(document.createElement('tbody'))));
		}
		
		function createButtons() {
			return $(document.createElement('a')).attr('id', 'pick' + options.token).attr('href', '#').addClass('button ui-state-default').text('Vybrať súbor');
		}
		
		function addRow(file) {
			var icon = $(document.createElement('span')).text(/[^.]+$/.exec(file.name));
			var cancel = $(document.createElement('a')).addClass('ui-state-default cancel').attr('href', '#')
				.append($(document.createElement('span')).addClass('ui-icon ui-icon-close'))
				.click(function() {
					uploader.removeFile(file);
					return false;
				});
			
			container.find('.table tbody').append($(document.createElement('tr')).attr('id', file.id)
				.append($(document.createElement('td')).append(icon))
				.append($(document.createElement('td')).addClass('name').text(file.name))
				.append($(document.createElement('td')).addClass('column-right').text(Math.ceil(file.size/ 1024) + ' kb'))
				.append($(document.createElement('td')).addClass('state column-right').text('0%'))
				.append($(document.createElement('td')).addClass('column-cancel').append(cancel)));
		}
		
		function removeRow(file) {
			$('#' + file.id).slideUp(function() {
				$(this).remove();
			});
		}
	}
});