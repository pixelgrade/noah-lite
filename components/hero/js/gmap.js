/* --- GMAP Init --- */

window.GMap = (function () {

	function init() {
		var $gmap = $('#gmap');

		// Make the Hero map untouchable at first
		// on touch devices. Activate it after a click.
		if ( Modernizr.touch && $gmap.parent().hasClass('hero-container') ) {
			$gmap.parent().addClass('is--untouchable').one('click', function() {
				$(this).removeClass('is--untouchable');
			});
		}

		if ($gmap.length && typeof google !== 'undefined') {
			if (globalDebug) { console.log("GMap Init"); }

			var gmap_link, gmap_variables, gmap_zoom, gmap_style;
			gmap_link = $gmap.data('url');
			gmap_style = typeof $gmap.data('customstyle') !== "undefined" ? "style1" : google.maps.MapTypeId.ROADMAP;
			var gmap_markercontent = $gmap.data('markercontent');

			// Overwrite Math.log to accept a second optional parameter as base for logarhitm
			Math.log = (function () {
				var log = Math.log;
				return function (n, base) {
					return log(n) / (base ? log(base) : 1);
				};
			})();

			var get_url_parameter = function (needed_param, gmap_url) {
				var sURLVariables = (gmap_url.split('?'))[1];
				if (typeof sURLVariables === "undefined") {
					return sURLVariables;
				}
				sURLVariables = sURLVariables.split('&');
				for (var i = 0; i < sURLVariables.length; i++) {
					var sParameterName = sURLVariables[i].split('=');
					if (sParameterName[0] == needed_param) {
						return sParameterName[1];
					}
				}
			};

			var gmap_coordinates = [],
				gmap_zoom;

			if (gmap_link) {
				//Parse the URL and load variables (ll = latitude/longitude; z = zoom)
				var gmap_variables = get_url_parameter('ll', gmap_link);
				if (typeof gmap_variables === "undefined") {
					gmap_variables = get_url_parameter('sll', gmap_link);
				}
				// if gmap_variables is still undefined that means the url was pasted from the new version of google maps
				if (typeof gmap_variables === "undefined") {

					if (gmap_link.split('!3d') != gmap_link) {
						//new google maps old link type

						var split, lt, ln, dist, z;
						split = gmap_link.split('!3d');
						lt = split[1];
						split = split[0].split('!2d');
						ln = split[1];
						split = split[0].split('!1d');
						dist = split[1];
						gmap_zoom = 21 - Math.round(Math.log(Math.round(dist / 218), 2));
						gmap_coordinates = [lt, ln];

					} else {
						//new google maps new link type

						var gmap_link_l;

						gmap_link_l = gmap_link.split('@')[1];
						gmap_link_l = gmap_link_l.split('z/')[0];

						gmap_link_l = gmap_link_l.split(',');

						var latitude = gmap_link_l[0];
						var longitude = gmap_link_l[1];
						var zoom = gmap_link_l[2];

						if (zoom.indexOf('z') >= 0)
							zoom = zoom.substring(0, zoom.length - 1);

						gmap_coordinates[0] = latitude;
						gmap_coordinates[1] = longitude;
						gmap_zoom = zoom;
					}


				} else {
					gmap_zoom = get_url_parameter('z', gmap_link);
					if (typeof gmap_zoom === "undefined") {
						gmap_zoom = 10;
					}
					gmap_coordinates = gmap_variables.split(',');
				}
			}

			if ( gmap_markercontent.length ) {
				gmap_markercontent = '<div class="map__marker-wrap">' +
					'<div class="map__marker">' +
						gmap_markercontent +
					'</div>' +
				'</div>';
			} else {
				gmap_markercontent = '<div class="map__marker-wrap is-empty">' +
					'<div class="map__marker"></div>' +
				'</div>';
			}

			$gmap.gmap3({
				map: {
					options: {
						center: new google.maps.LatLng(gmap_coordinates[0], gmap_coordinates[1]),
						zoom: parseInt(gmap_zoom),
						mapTypeId: gmap_style,
						mapTypeControlOptions: {mapTypeIds: []},
						scrollwheel: false
					}
				},
				overlay: {
					latLng: new google.maps.LatLng(gmap_coordinates[0], gmap_coordinates[1]),
					options: {
						content: gmap_markercontent
					}
				},
				styledmaptype: {
					id: "style1",
					options: {
						name: "Style 1"
					},
					styles: [
						{
							"stylers": [
								{"saturation": -100},
								{"gamma": 2.45},
								{"visibility": "simplified"}
							]
						}, {
							"featureType": "road",
							"stylers": [
								{"hue": $("body").data("color") ? $("body").data("color") : "#ffaa00"},
								{"saturation": 48},
								{"gamma": 0.40},
								{"visibility": "on"}
							]
						}, {
							"featureType": "administrative",
							"stylers": [
								{"visibility": "on"}
							]
						}
					]
				}
			});
		}
	}

	return {
		init: init
	}

})();
