jQuery(function($) {
	// datepicker
	$.datepicker.regional['sk'] = {
		closeText: 'Zavrieť',
		prevText: '&#x3c;Skôr',
		nextText: 'Neskôr&#x3e;',
		currentText: 'Teraz',
		monthNames: ['január', 'február', 'marec', 'apríl', 'máj', 'jún', 'júl', 'august', 'september', 'október', 'november', 'december'],
		monthNamesShort: ['jan', 'feb', 'mar', 'apr', 'máj', 'jún', 'júl', 'aug', 'sep', 'okt', 'nov', 'dec'],
		dayNames: ['nedeľa', 'pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota'],
		dayNamesShort: ['ne', 'po', 'ut', 'st', 'št', 'pi', 'so'],
		dayNamesMin: ['ne', 'po', 'ut', 'st', 'št', 'pi', 'so'],
		weekHeader: 'Týždeň',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	
	$.datepicker.setDefaults($.datepicker.regional['sk']);
	
	
	// webalize
	var nodiac = {'á': 'a', 'ä': 'a', 'č': 'c', 'ď': 'd', 'é': 'e', 'ě': 'e',
		'í': 'i', 'ľ': 'l', 'ĺ': 'l', 'ň': 'n', 'ó': 'o', 'ô': 'o','ř': 'r',
		'š': 's', 'ť': 't', 'ú': 'u', 'ů': 'u', 'ý': 'y', 'ž': 'z'
	};
	
	$.brosland = {
		webalize: function webalize(s) {
			s = s.toLowerCase();
			var s2 = '';
			for(var i=0; i < s.length; i++) {
				s2 += (typeof nodiac[s.charAt(i)] !== 'undefined' ?
					nodiac[s.charAt(i)] : s.charAt(i));
			}
			return s2.replace(/[^a-z0-9_]+/g, '-').replace(/^-|-$/g, '');
		}
	};
});