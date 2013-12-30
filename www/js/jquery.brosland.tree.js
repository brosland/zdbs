$(document).ready(function() {
	function closeFolder(e) {
		e.removeClass('ui-icon-folder-open').addClass('ui-icon-folder-collapsed');
		e.closest('li').children('ul').slideUp(200);
	}
	
	function openFolder(e) {
		e.removeClass('ui-icon-folder-collapsed').addClass('ui-icon-folder-open');
		e.closest('li').children('ul').slideDown(200);
	}
	
	$('a[name="open-all"]').click(function() {
		$('.tree ul .ui-icon-folder-collapsed').each(function() {
			openFolder($(this));
		});
		return false;
	})
	
	$('a[name="close-all"]').click(function(){
		$('.tree ul .ui-icon-folder-open').each(function() {
			closeFolder($(this));
		});
		return false;
	})
	
	$('.tree ul .ui-icon-folder-open, .tree ul .ui-icon-folder-collapsed').click(function() {
		$(this).hasClass('ui-icon-folder-open') ? closeFolder($(this)) : openFolder($(this));
		return false;
	});
});