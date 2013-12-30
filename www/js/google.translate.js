function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'sk',
		includedLanguages: 'en,de,fr,ru',
		layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT,
		autoDisplay: false,
		multilanguagePage: true
	}, 'google_translate_element');
	
	$(document).ready(function() {
		$('#change-lang').click(function() {
			var jObj = $('.goog-te-combo');
			var db = jObj.get(0);
			jObj.val('en');
			fireEvent(db, 'change');
			return false;
		});
		
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
	});
}