<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_googlemap_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_googlemap_theme_setup' );
	function jardiwinery_sc_googlemap_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_googlemap_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('jardiwinery_sc_googlemap')) {	
	function jardiwinery_sc_googlemap($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = jardiwinery_get_custom_option('googlemap_style');
        $api_key = jardiwinery_get_theme_option('api_google');
        wp_enqueue_script( 'googlemap', jardiwinery_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
        wp_enqueue_script( 'jardiwinery-googlemap-script', jardiwinery_get_file_url('js/core.googlemap.js'), array(), null, true );
		jardiwinery_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = jardiwinery_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => jardiwinery_get_custom_option('googlemap_title'),
				'description' => jardiwinery_strmacros(jardiwinery_get_custom_option('googlemap_description')),
				'latlng' => jardiwinery_get_custom_option('googlemap_latlng'),
				'address' => jardiwinery_get_custom_option('googlemap_address'),
				'point' => jardiwinery_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !jardiwinery_param_is_off($scheme) && !jardiwinery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(jardiwinery_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_googlemap", "jardiwinery_sc_googlemap");
}


if (!function_exists('jardiwinery_sc_googlemap_marker')) {	
	function jardiwinery_sc_googlemap_marker($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		jardiwinery_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : jardiwinery_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	jardiwinery_require_shortcode("trx_googlemap_marker", "jardiwinery_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_googlemap_reg_shortcodes');
	function jardiwinery_sc_googlemap_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'jardiwinery'),
					"desc" => wp_kses_data( __("Map zoom factor", 'jardiwinery') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select map style", 'jardiwinery') ),
					"value" => "default",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('schemes')
				),
				"width" => jardiwinery_shortcodes_width('100%'),
				"height" => jardiwinery_shortcodes_height(240),
				"top" => jardiwinery_get_sc_param('top'),
				"bottom" => jardiwinery_get_sc_param('bottom'),
				"left" => jardiwinery_get_sc_param('left'),
				"right" => jardiwinery_get_sc_param('right'),
				"id" => jardiwinery_get_sc_param('id'),
				"class" => jardiwinery_get_sc_param('class'),
				"animation" => jardiwinery_get_sc_param('animation'),
				"css" => jardiwinery_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'jardiwinery'),
				"desc" => wp_kses_data( __("Google map marker", 'jardiwinery') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'jardiwinery'),
						"desc" => wp_kses_data( __("Address of this marker", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'jardiwinery'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'jardiwinery'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'jardiwinery') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'jardiwinery'),
						"desc" => wp_kses_data( __("Title for this marker", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'jardiwinery'),
						"desc" => wp_kses_data( __("Description for this marker", 'jardiwinery') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => jardiwinery_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_googlemap_reg_shortcodes_vc');
	function jardiwinery_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'jardiwinery'),
					"description" => wp_kses_data( __("Map zoom factor", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jardiwinery'),
					"description" => wp_kses_data( __("Map custom style", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jardiwinery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_vc_width('100%'),
				jardiwinery_vc_height(240),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'jardiwinery') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'jardiwinery'),
					"description" => wp_kses_data( __("Address of this marker", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'jardiwinery'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title for this marker", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				jardiwinery_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends JARDIWINERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends JARDIWINERY_VC_ShortCodeCollection {}
	}
}
?>