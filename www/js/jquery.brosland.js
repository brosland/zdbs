$(function() {
	function init() {
		// confirm dialog
		$('a[data-confirm]').click(function(e) {
			if (!confirm($(this).data('confirm'))) {
				e.stopImmediatePropagation();
				return false;
			}
			else {
				var href = $(this).attr('href');
				$(this).attr('href',  href + (href.indexOf('?') != -1 ? '&' : '?') + 'confirmed=1');
			}
		});
		
		// jQueryUI - buttons
		$('a.ui-state-default:has(span.ui-icon)').each(function() {
			$(this).attr('title', $(this).text());
		});
	}
	
	init();
	
	$(document).ajaxStop(function() {
		init();
	});
	
	
	$('.ui-state-default').live({
		mouseenter: function() {
				$(this).addClass('ui-state-hover');
			},
		mouseleave: function() {
				$(this).removeClass('ui-state-hover');
			}
		}
    );
	
	// nette extension
	$.nette.ext('fancybox-window', {
		init: function () {
			this.open($('body')); // otevře fancybox při načtení stránky
		},
		success: function (payload) {
			var snippets;
			if (!payload.snippets || !(snippets = this.ext('snippets'))) return;

			for (var id in payload.snippets) {
				this.open(snippets.getElement(id));
			}
		}
	}, {
		open: function (el) {
			el.find('.fancybox-window').each(function (i, el) {
				var $el = $(el);
				var options = {
					type: 'inline',
					wrapCSS: 'fancybox-window-wrap'
				};
				options = $.extend(options, $el.data('fancybox-opts') || {});
				$.fancybox.open($el, options);
			});
		}
	});
}(jQuery));