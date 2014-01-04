$(document).ready(function() {
	function initSortButtons() {
		$('.table-sorting input:submit').hide();
		$('.table-sorting input:checkbox').each(function() {
			var checkbox = $(this);
			var classes = ['ui-icon-triangle-1-s', 'ui-icon-triangle-1-n'];
			var icon = $(document.createElement('a'))
				.addClass('ui-state-default')
				.attr('href', '#')
				.attr('title', checkbox.attr('title'))
				.append($(document.createElement('span'))
					.addClass('ui-icon').addClass($(this).is(':checked') ? classes[0] : classes[1]))
				.click(function() {
					if(checkbox.is(':checked')) {
						$(this).find('span').removeClass(classes[0]).addClass(classes[1]);
						checkbox.prop('checked', false);
					} else {
						$(this).find('span').removeClass(classes[1]).addClass(classes[0]);
						checkbox.prop('checked', true);
					}
					
					checkbox.change();
					return false;
				});

			checkbox.closest('label').hide().after(icon);
		});
	}
	
	initSortButtons();
	
	$(document).ajaxStop(function() { 
		initSortButtons();
	});
	
	$(document).on('change', '.table-sorting select, .table-sorting input:checkbox', function() {
		$(this).closest('form').find('input[name="sorting[sort]"]:first').click();
	});
	
	$(document).on('click', '.table input:checkbox[name="checkedAll"]', function() {
		$(this).closest('.table').find('input.row-checkbox').prop('checked', $(this).is(':checked')).trigger('change');
	});
	
	$(document).on('change', '.table input.row-checkbox', function() {
		var tr = $(this).closest('tr');
		$(this).is(':checked') ? tr.addClass('selected') : tr.removeClass('selected');
	});

	$(document).on('change', '.table input.row-checkbox', function() {
		var table = $(this).closest('.table');
		table.find('input:checkbox[name="checkedAll"]').prop('checked', true);
		table.find('input.row-checkbox').each(function() {
			if(!$(this).is(':checked')) {
				$('.table input:checkbox[name="checkedAll"]').prop('checked', false);
				return false;
			}
		});
	});
});