(function($) {
	$.fn.accordion = function(options) {
		var el = $(this);
		var settings = $.extend({}, {langs: ['en', 'de', 'fr', 'ru']}, options);
		var googleSelectLang = $('.goog-te-combo');
		var langMenu = $(document.createElement('div'));
		langMenu.addClass('lang-menu');
		
		
		
		for(var i in settings.langs) {
			var option = $(document.createElement('a'));
			option.attr('href', '#').data('lang', settings.langs[i]).click(function() {
				alert($(this).data('lang'));
			});
			langMenu.append(option);
		}
		
		function onSelectLang(option) {
			var db = googleSelectLang.get(0);
			googleSelectLang.val(option.data('lang'));
			fireEvent(db, 'change');
		}
		
		function hide() {
			$(this).next().slideUp('fast');
		}
		
		function fireEvent(element, event) {
			if(document.createEventObject) {
				// dispatch for IE
				var evt = document.createEventObject();
				return element.fireEvent('on' + event, evt);
			}
			else {
				// dispatch for firefox + others
				var evt = document.createEvent('HTMLEvents');
				evt.initEvent(event, true, true ); // event type,bubbling,cancelable
				return !element.dispatchEvent(evt);
			}
		}
	}
})(jQuery);