function jardiwinery_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof JARDIWINERY_STORAGE['googlemap_init_obj'] == 'undefined') jardiwinery_googlemap_init_styles();
	JARDIWINERY_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		JARDIWINERY_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: JARDIWINERY_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		jardiwinery_googlemap_create(id);

	} catch (e) {
		
		dcl(JARDIWINERY_STORAGE['strings']['googlemap_not_avail']);

	};
}

function jardiwinery_googlemap_create(id) {
	"use strict";

	// Create map
	JARDIWINERY_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(JARDIWINERY_STORAGE['googlemap_init_obj'][id].dom, JARDIWINERY_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers)
		JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	jardiwinery_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].map)
			JARDIWINERY_STORAGE['googlemap_init_obj'][id].map.setCenter(JARDIWINERY_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function jardiwinery_googlemap_add_markers(id) {
	"use strict";
	for (var i in JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (JARDIWINERY_STORAGE['googlemap_init_obj'].geocoder == '') JARDIWINERY_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			JARDIWINERY_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			JARDIWINERY_STORAGE['googlemap_init_obj'].geocoder.geocode({address: JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = JARDIWINERY_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					JARDIWINERY_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						jardiwinery_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(JARDIWINERY_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: JARDIWINERY_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].title;
			JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				JARDIWINERY_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				JARDIWINERY_STORAGE['googlemap_init_obj'][id].map.setCenter(JARDIWINERY_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								JARDIWINERY_STORAGE['googlemap_init_obj'][id].map,
								JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			JARDIWINERY_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function jardiwinery_googlemap_refresh() {
	"use strict";
	for (id in JARDIWINERY_STORAGE['googlemap_init_obj']) {
		jardiwinery_googlemap_create(id);
	}
}

function jardiwinery_googlemap_init_styles() {
	// Init Google map
	JARDIWINERY_STORAGE['googlemap_init_obj'] = {};
	JARDIWINERY_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.jardiwinery_theme_googlemap_styles!==undefined)
		JARDIWINERY_STORAGE['googlemap_styles'] = jardiwinery_theme_googlemap_styles(JARDIWINERY_STORAGE['googlemap_styles']);
}