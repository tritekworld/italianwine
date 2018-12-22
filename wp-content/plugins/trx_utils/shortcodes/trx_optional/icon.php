<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_icon_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_icon_theme_setup' );
	function jardiwinery_sc_icon_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_icon_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('jardiwinery_sc_icon')) {	
	function jardiwinery_sc_icon($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !jardiwinery_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(jardiwinery_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || jardiwinery_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !jardiwinery_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_icon', 'jardiwinery_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_icon_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_icon_reg_shortcodes');
	function jardiwinery_sc_icon_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert icon", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'jardiwinery'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'jardiwinery') ),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Icon's color", 'jardiwinery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'jardiwinery'),
					"desc" => wp_kses_data( __("Shape of the icon background", 'jardiwinery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'jardiwinery'),
						'round' => esc_html__('Round', 'jardiwinery'),
						'square' => esc_html__('Square', 'jardiwinery')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Icon's background color", 'jardiwinery') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'jardiwinery'),
					"desc" => wp_kses_data( __("Icon's font size", 'jardiwinery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'jardiwinery'),
					"desc" => wp_kses_data( __("Icon font weight", 'jardiwinery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'jardiwinery'),
						'300' => esc_html__('Light (300)', 'jardiwinery'),
						'400' => esc_html__('Normal (400)', 'jardiwinery'),
						'700' => esc_html__('Bold (700)', 'jardiwinery')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Icon text alignment", 'jardiwinery') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"top" => jardiwinery_get_sc_param('top'),
				"bottom" => jardiwinery_get_sc_param('bottom'),
				"left" => jardiwinery_get_sc_param('left'),
				"right" => jardiwinery_get_sc_param('right'),
				"id" => jardiwinery_get_sc_param('id'),
				"class" => jardiwinery_get_sc_param('class'),
				"css" => jardiwinery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_icon_reg_shortcodes_vc');
	function jardiwinery_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert the icon", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'jardiwinery'),
					"description" => wp_kses_data( __("Icon's color", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jardiwinery'),
					"description" => wp_kses_data( __("Background color for the icon", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'jardiwinery'),
					"description" => wp_kses_data( __("Shape of the icon background", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'jardiwinery') => 'none',
						esc_html__('Round', 'jardiwinery') => 'round',
						esc_html__('Square', 'jardiwinery') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jardiwinery'),
					"description" => wp_kses_data( __("Icon's font size", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'jardiwinery'),
					"description" => wp_kses_data( __("Icon's font weight", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'jardiwinery') => 'inherit',
						esc_html__('Thin (100)', 'jardiwinery') => '100',
						esc_html__('Light (300)', 'jardiwinery') => '300',
						esc_html__('Normal (400)', 'jardiwinery') => '400',
						esc_html__('Bold (700)', 'jardiwinery') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Align icon to left, center or right", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jardiwinery'),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>