<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_socials_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_socials_theme_setup' );
	function jardiwinery_sc_socials_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_socials_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('jardiwinery_sc_socials')) {	
	function jardiwinery_sc_socials($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => jardiwinery_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		jardiwinery_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? jardiwinery_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) jardiwinery_storage_set_array('sc_social_data', 'icons', $list);
		} else if (jardiwinery_param_is_off($custom))
			$content = do_shortcode($content);
		if (jardiwinery_storage_get_array('sc_social_data', 'icons')===false) jardiwinery_storage_set_array('sc_social_data', 'icons', jardiwinery_get_custom_option('social_icons'));
		$output = jardiwinery_prepare_socials(jardiwinery_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_socials', 'jardiwinery_sc_socials');
}


if (!function_exists('jardiwinery_sc_social_item')) {	
	function jardiwinery_sc_social_item($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = jardiwinery_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(jardiwinery_get_socials_dir($name.'.png')))
					$icon = jardiwinery_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (jardiwinery_storage_get_array('sc_social_data', 'icons')===false) jardiwinery_storage_set_array('sc_social_data', 'icons', array());
			jardiwinery_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	jardiwinery_require_shortcode('trx_social_item', 'jardiwinery_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_socials_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_socials_reg_shortcodes');
	function jardiwinery_sc_socials_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'jardiwinery'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'jardiwinery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'jardiwinery'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'jardiwinery') ),
					"value" => jardiwinery_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'jardiwinery'),
						'images' => esc_html__('Images', 'jardiwinery')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'jardiwinery'),
					"desc" => wp_kses_data( __("Size of the icons", 'jardiwinery') ),
					"value" => "small",
					"options" => jardiwinery_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'jardiwinery'),
					"desc" => wp_kses_data( __("Shape of the icons", 'jardiwinery') ),
					"value" => "square",
					"options" => jardiwinery_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'jardiwinery'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'jardiwinery'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"options" => jardiwinery_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'jardiwinery'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'jardiwinery') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'jardiwinery'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'jardiwinery'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'jardiwinery'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'jardiwinery') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_socials_reg_shortcodes_vc');
	function jardiwinery_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'jardiwinery'),
			"description" => wp_kses_data( __("Custom social icons", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'jardiwinery'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'jardiwinery') ),
					"class" => "",
					"std" => jardiwinery_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'jardiwinery') => 'icons',
						esc_html__('Images', 'jardiwinery') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'jardiwinery'),
					"description" => wp_kses_data( __("Size of the icons", 'jardiwinery') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(jardiwinery_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'jardiwinery'),
					"description" => wp_kses_data( __("Shape of the icons", 'jardiwinery') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(jardiwinery_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'jardiwinery'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'jardiwinery'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'jardiwinery') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'jardiwinery'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'jardiwinery') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'jardiwinery'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'jardiwinery'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends JARDIWINERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>