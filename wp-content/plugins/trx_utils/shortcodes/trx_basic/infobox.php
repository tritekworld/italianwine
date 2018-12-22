<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_infobox_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_infobox_theme_setup' );
	function jardiwinery_sc_infobox_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_infobox_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('jardiwinery_sc_infobox')) {	
	function jardiwinery_sc_infobox($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
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
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		} else if ($icon=='none')
			$icon = '';

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (jardiwinery_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !jardiwinery_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_infobox', 'jardiwinery_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_infobox_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_infobox_reg_shortcodes');
	function jardiwinery_sc_infobox_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Infobox style", 'jardiwinery') ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'jardiwinery'),
						'info' => esc_html__('Info', 'jardiwinery'),
						'success' => esc_html__('Success', 'jardiwinery'),
						'error' => esc_html__('Error', 'jardiwinery')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", 'jardiwinery'),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", 'jardiwinery') ),
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'jardiwinery'),
					"desc" => wp_kses_data( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'jardiwinery') ),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Text color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any color for text and headers", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any background color for this infobox", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Content for infobox", 'jardiwinery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => jardiwinery_get_sc_param('top'),
				"bottom" => jardiwinery_get_sc_param('bottom'),
				"left" => jardiwinery_get_sc_param('left'),
				"right" => jardiwinery_get_sc_param('right'),
				"id" => jardiwinery_get_sc_param('id'),
				"class" => jardiwinery_get_sc_param('class'),
				"animation" => jardiwinery_get_sc_param('animation'),
				"css" => jardiwinery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_infobox_reg_shortcodes_vc');
	function jardiwinery_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", 'jardiwinery'),
			"description" => wp_kses_data( __("Box with info or error message", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jardiwinery'),
					"description" => wp_kses_data( __("Infobox style", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'jardiwinery') => 'regular',
							esc_html__('Info', 'jardiwinery') => 'info',
							esc_html__('Success', 'jardiwinery') => 'success',
							esc_html__('Error', 'jardiwinery') => 'error',
							esc_html__('Result', 'jardiwinery') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", 'jardiwinery'),
					"description" => wp_kses_data( __("Create closeable box (with close button)", 'jardiwinery') ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", 'jardiwinery') ),
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'jardiwinery'),
					"description" => wp_kses_data( __("Any color for the text and headers", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jardiwinery'),
					"description" => wp_kses_data( __("Any background color for this infobox", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends JARDIWINERY_VC_ShortCodeContainer {}
	}
}
?>