/* global jardiwinery_color_schemes, jardiwinery_dependencies, Color */

/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {
	"use strict";

	var cssTemplate = {},
		updateCSS = true;

	if (!jardiwinery_customizer_vars['need_refresh']) {
		for (var i in jardiwinery_color_schemes) {
			cssTemplate[i] = wp.template( 'jardiwinery-color-scheme-'+i );
		}
		cssTemplate['fonts'] = wp.template( 'jardiwinery-fonts' );
	}
	
	// Set initial state of controls
	api.bind('ready', function() {
	
		// Add 'Refresh' button
		if (jardiwinery_customizer_vars['need_refresh']) {
			jQuery('#customize-header-actions .spinner').after('<input type="button" class="button customize-action-refresh" value="'+jardiwinery_customizer_vars['msg_refresh']+'">');
			jQuery('#customize-header-actions .customize-action-refresh').on('click', function(e) {
				api.previewer.send( 'refresh-preview' );
				setTimeout(function() { api.previewer.refresh(); }, 500);
			});
		}
		
		// Check dependencies
		jQuery('#customize-theme-controls .control-section').each(function () {
			jardiwinery_customizer_wp_check_dependencies(jQuery(this));
		});

		// Update colors
		jardiwinery_customizer_wp_change_color_scheme(api('color_scheme')());
	});
	
	// On change any control - check for dependencies
	api.bind('change', function(obj) {
		if (obj.id == 'scheme_storage') return;
		jardiwinery_customizer_wp_check_dependencies(jQuery('#customize-theme-controls #customize-control-'+obj.id).parents('.control-section'));
		jardiwinery_customizer_wp_refresh_preview(obj);
	});

	// Check for dependencies
	function jardiwinery_customizer_wp_check_dependencies(cont) {
		"use strict";
	
		cont.find('.customize-control').each(function() {
			"use strict";
			var id = jQuery(this).attr('id');
			if (id == undefined) return;
			id = id.replace('customize-control-', '');
			var depend = false;
			for (var fld in jardiwinery_dependencies) {
				if (fld == id) {
					depend = jardiwinery_dependencies[id];
					break;
				}
			}
			if (depend) {
				var dep_cnt = 0, dep_all = 0;
				var dep_cmp = typeof depend.compare != 'undefined' ? depend.compare.toLowerCase() : 'and';
				var dep_strict = typeof depend.strict != 'undefined';
				var fld=null, val='';
				for (var i in depend) {
					if (i == 'compare' || i == 'strict') continue;
					dep_all++;
					fld = cont.find('[data-customize-setting-link="'+i+'"]');
					if (fld.length > 0) {
						val = fld.attr('type')=='checkbox' || fld.attr('type')=='radio' 
									? (fld.parents('.customize-control').find('[data-customize-setting-link]:checked').length > 0
										? fld.parents('.customize-control').find('[data-customize-setting-link]:checked').val()
										: 0
										)
									: fld.val();
						if (val===undefined) val = '';
						for (var j in depend[i]) {
							if ( 
								   (depend[i][j]=='not_empty' && val!='') 										// Main field value is not empty - show current field
								|| (depend[i][j]=='is_empty' && val=='')										// Main field value is empty - show current field
								|| (val!=='' && (!isNaN(depend[i][j]) 											// Main field value equal to specified value - show current field
													? val==depend[i][j]
													: (dep_strict 
															? val==depend[i][j]
															: val.indexOf(depend[i][j])==0
														)
												)
									)
								|| (val!='' && depend[i][j].charAt(0)=='^' && val.indexOf(depend[i][j].substr(1))==-1)	// Main field value not equal to specified value - show current field
							) {
								dep_cnt++;
								break;
							}
						}
					} else
						dep_all--;
					if (dep_cnt > 0 && dep_cmp == 'or')
						break;
				}
				if ((dep_cnt > 0 && dep_cmp == 'or') || (dep_cnt == dep_all && dep_cmp == 'and')) {
					jQuery(this).show().removeClass('jardiwinery_options_no_use');
				} else {
					jQuery(this).hide().addClass('jardiwinery_options_no_use');
				}
			}
		});
	}

	// Refresh preview area on change any control
	function jardiwinery_customizer_wp_refresh_preview(obj) {
		"use strict";
		if (obj.transport!='postMessage') return;
		var id = obj.id, val = obj();
		var processed = false;
		// Update the CSS whenever a color setting is changed.
		if (id == 'color_scheme') {
			jardiwinery_customizer_wp_change_color_scheme(val);
		} else if (updateCSS) {
			var simple = api('color_settings')()=='simple';
			for (var opt in jardiwinery_color_schemes['original']) {
				if (opt == id) {
					updateCSS = false;
					// Store new value in the color table
					jardiwinery_customizer_wp_update_color_scheme(opt, val);
					// Duplicate colors if simple
					if (simple) {
						if (id == 'text_link') {
							api('alter_link').set( val );
							api.control( 'alter_link' ).container.find( '.color-picker-hex' )
								.data( 'data-default-color', val )
								.wpColorPicker( 'defaultColor', val );
							jardiwinery_customizer_wp_update_color_scheme('alter_link', val);
						} else if (id == 'text_hover') {
							api('alter_hover').set( val );
							api.control( 'alter_hover' ).container.find( '.color-picker-hex' )
								.data( 'data-default-color', val )
								.wpColorPicker( 'defaultColor', val );
							jardiwinery_customizer_wp_update_color_scheme('alter_hover', val);
						}
					}
					updateCSS = true;
					processed = true;
					break;
				}
			}
			if (!processed) {
				for (var tag in jardiwinery_fonts) {
					for (var prop in jardiwinery_fonts[tag]) {
						if (prop=='title' || prop=='description') continue;
						if (tag+'-'+prop == id) {
							processed = true;
							// Store new value in the fonts table
							jardiwinery_customizer_wp_update_fonts(tag, prop, val);
							break;
						}
					}
					if (processed) break;
				}
			}
			// Refresh CSS
			if (processed) jardiwinery_customizer_wp_update_css();
		}
		// Send message to previewer
		if (!processed) {
			api.previewer.send( 'refresh-other-controls', {id: id, value: val} );
		}
	}
	

	// Store new value in the color table
	function jardiwinery_customizer_wp_update_color_scheme(opt, value) {
		"use strict";
		jardiwinery_color_schemes[api('color_scheme')()][opt] = value;
		api('scheme_storage').set(jardiwinery_serialize(jardiwinery_color_schemes))
	}
	

	// Change color scheme - update colors and generate css
	function jardiwinery_customizer_wp_change_color_scheme(value) {
		"use strict";
		updateCSS = false;
		for (var opt in jardiwinery_color_schemes[value]) {
			if (api(opt) == undefined) continue;
			api( opt ).set( jardiwinery_color_schemes[value][opt] );
			api.control( opt ).container.find( '.color-picker-hex' )
				.data( 'data-default-color', jardiwinery_color_schemes[value][opt] )
				.wpColorPicker( 'defaultColor', jardiwinery_color_schemes[value][opt] );
		}
		updateCSS = true;
		jardiwinery_customizer_wp_update_css();
	}

	// Store new value in the fonts table
	function jardiwinery_customizer_wp_update_fonts(tag, prop, value) {
		"use strict";
		jardiwinery_fonts[tag][prop] = value;
	}
	
	// Generate the CSS for the current Color Scheme and send it to the preview window
	function jardiwinery_customizer_wp_update_css() {
		"use strict";

		if (!updateCSS || jardiwinery_customizer_vars['need_refresh']) return;

		var css = '';
		
		// Add color styles
		for (var scheme in jardiwinery_color_schemes) {
			
			var colors = [];
			
			// Copy all colors!
			for (var i in jardiwinery_color_schemes[scheme]) {
				if (i=='title') continue;
				colors[i] = jardiwinery_color_schemes[scheme][i];
			}
			
			// Make theme specific colors and tints
			if (window.jardiwinery_customizer_wp_add_theme_colors) colors = jardiwinery_customizer_wp_add_theme_colors(colors);

			// Make styles and add into css
			css += cssTemplate[scheme]( colors );
		}

		// Add fonts styles
		var fonts = [], font = [];
		for (var tag in jardiwinery_fonts) {
			font = jardiwinery_fonts[tag];
			fonts[tag+'_font-family'] = font['font-family'] && font['font-family']!='inherit'
												? 'font-family:"' + font['font-family'] + '";'
												: '';
			fonts[tag+'_font-size'] = font['font-size'] && font['font-size']!='inherit'
												? 'font-size:' + jardiwinery_prepare_css_value(font['font-size']) + ';'
												: '';
			fonts[tag+'_line-height'] = font['line-height'] && font['line-height']!='inherit'
												? 'line-height:' + jardiwinery_prepare_css_value(font['line-height']) + ';'
												: '';
			fonts[tag+'_line-height_value'] = font['line-height']	// && font['line-height']!='inherit'
												? jardiwinery_prepare_css_value(font['line-height'])
												: 'inherit';
			fonts[tag+'_font-weight'] = font['font-weight'] && font['font-weight']!='inherit'
												? 'font-weight:' + font['font-weight'] + ';'
												: '';
			fonts[tag+'_font-style'] = font['font-style'] && font['font-style']!='inherit' && font['font-style'].indexOf('i')>=0
												? "font-style:italic;"
												: '';
			fonts[tag+'_text-decoration'] = font['font-style'] && font['font-style']!='inherit' && font['font-style'].indexOf('u')>=0
												? "text-decoration:underline;"
												: '';
			fonts[tag+'_margin-top'] = font['margin-top'] && font['margin-top']!='inherit'
												? "margin-top:" + jardiwinery_prepare_css_value(font['margin-top']) + ";"
												: '';
			fonts[tag+'_margin-top_value'] = font['margin-top']	// && font['margin-top']!='inherit'
												? jardiwinery_prepare_css_value(font['margin-top'])
												: 'inherit';
			fonts[tag+'_margin-bottom'] = font['margin-bottom'] && font['margin-bottom']!='inherit'
												? "margin-bottom:" + jardiwinery_prepare_css_value(font['margin-bottom']) + ";"
												: '';
			fonts[tag+'_margin-bottom_value'] = font['margin-bottom']	// && font['margin-bottom']!='inherit'
												? jardiwinery_prepare_css_value(font['margin-bottom'])
												: 'inherit';
		}
			
		// Make theme specific colors and tints
		if (window.jardiwinery_customizer_wp_add_theme_fonts) fonts = jardiwinery_customizer_wp_add_theme_fonts(fonts);

		// Make styles and add into css
		css += cssTemplate['fonts']( fonts );

		api.previewer.send( 'refresh-customizer-css', css );
	}
	
	// Add ed to css value
	function jardiwinery_prepare_css_value(val) {
		"use strict";
		if (val != '' && val != 'inherit') {
			var ed = val.substr(-1);
			if ('0'<=ed && ed<='9') val += 'px';
		}
		return val;
	}

} )( wp.customize );
