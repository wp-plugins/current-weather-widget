/**
 * Current weather widget 0.0.1
 */
/*jslint white: true */
/*global window, sessionStorage, jQuery, currentWeatherWidgetData */

(function ($) {
	'use strict';

	var d,	// "Global" alias for global currentWeatherWidgetData

		apiUrl = 'http://api.openweathermap.org/data/2.1/find/name',

		cacheKey = 'currentWeatherWidgetData',

		cacheData,

		// Class prefix
		p = 'current-weather-widget',

		// Can we use sessionStorage and JSON parsing?
		canCache = (function () {
			try {
				return 'sessionStorage' in window &&
					window.localStorage !== null &&
					window.hasOwnProperty('JSON') &&
					JSON.hasOwnProperty('stringify') &&
					JSON.hasOwnProperty('parse');
			} catch (e) {
				return false;
			}
		}()),

		renderTemp = function (classPart, data) {
			var unit;
			if ( classPart === 'temp' ) {
				unit = ' &deg;' + ( ( d.units === 'imperial' ) ? 'F' : 'C' );
			} else {
				data = Math.round(data);
				unit = '&deg;';
			}
			$('.' + p + '-' + classPart).html(data + unit);
		},

		showWeather = function (data) {
			renderTemp('temp', data.temp);
			renderTemp('high', data.high);
			renderTemp('low', data.low);
		},

		// Cache settings and values.
		setCache = function (data) {
			sessionStorage.setItem(cacheKey, JSON.stringify({
				"cached": new Date().getTime(),
				"city": d.city,
				"country": d.country,
				"units": d.units,
				"lang": d.lang,
				"temp": data.temp,
				"high": data.high,
				"low": data.low
			}));
		},

		// Returns cached data if cached settings match widget settings.
		checkCache = function () {
			var data = JSON.parse(sessionStorage.getItem(cacheKey));
			if ( data ) {
				if ( d.city === data.city &&
						d.country === data.country &&
						d.units === data.units &&
						d.lang === data.lang ) {
					return data;
				}
			}
			return false;
		},

		useCache = function (data) {
			// Have cached data, but how old is it?
			if ( 10 > ( new Date().getTime() - data.cached ) /
					( 1000 * 60 ) ) {
				// Less than 10 minutes old; use cached data.
				showWeather(data);
				// Used cache
				return true;
			}
			// Didn't use cache.
			return false;
		},

		// Logic depends on object returned from service.
		parseResponse = function (data) {
			var w,				// Alias
				img = '',		// HTML img tag string
				desc,			// Capitalize first letter of description
				r = {			// Return data object for rendering/caching
					temp: '',	// HTML string
					high: '',	// HTML string
					low: ''		// HTML string
				};
			// Check Open Weater Map data for proper structure
			if ( data && data.list && data.list.length ) {
				w = data.list[0];
				if ( w.weather && w.weather.length && w.weather[0].icon ) {
					desc = w.weather[0].description[0].toUpperCase() +
						w.weather[0].description.substring(1);
					img = '<img src="http://openweathermap.org/img/w/' +
						w.weather[0].icon + '.png" alt="' + desc +
						'" title="' + desc + '" /> ';
				}
				if ( w.main ) {
					r.temp = img + ' ' + data.list[0].main.temp;
					r.high = data.list[0].main.temp_max;
					r.low = data.list[0].main.temp_min;
					return r;
				}
			}
			return false;
		},

		// On the off-chance that some piece of the 
		// widget is rendering, let's kill it.
		currentWeatherWidgetFail = function () {
			$('.' + p).css('display', 'none');
		},

		// Display results of successful API call.
		currentWeatherSucess = function (data) {
			// Parse the data we're given...
			data = parseResponse(data);
			// into the data we want.
			if (data) {
				showWeather(data);
				if ( canCache ) {
					setCache(data);
				}
			}
		};

	if ( 'currentWeatherWidgetData' in window ) {
		d = currentWeatherWidgetData;
		// Check the cache
		if ( canCache && ( cacheData = checkCache() ) ) {
			if ( useCache(cacheData) ) {
				// Don't make the API call.
				return;
			}
		}
		// Make call to service
		$.ajax({
			url: apiUrl,
			type: 'GET',
			data: {
				q: d.city + ', ' + d.country,
				units: d.units,
				lang: d.lang
			},
			dataType: 'jsonp',
			success: currentWeatherSucess,
			error: function(e) {
				// Something haz gone vrry wrong. Call the fail function.
				currentWeatherWidgetFail();
			}
		});
	} else {
		// Missing our data object. Call the fail function.
		currentWeatherWidgetFail();
	}

}(jQuery));