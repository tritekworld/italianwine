<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_number_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_number_theme_setup' );
	function jardiwinery_sc_number_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_number_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_number_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_number id="unique_id" value="400"]
*/

if (!function_exists('jardiwinery_sc_number')) {	
	function jardiwinery_sc_number($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"value" => "",
			"align" => "",
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
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_number' 
					. (!empty($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>';
		for ($i=0; $i < jardiwinery_strlen($value); $i++) {
			$output .= '<span class="sc_number_item">' . trim(jardiwinery_substr($value, $i, 1)) . '</span>';
		}
		$output .= '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_number', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_number', 'jardiwinery_sc_number');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_number_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_number_reg_shortcodes');
	function jardiwinery_sc_number_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_number", array(
			"title" => esc_html__("Number", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert number or any word as set separate characters", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"value" => array(
					"title" => esc_html__("Value", 'jardiwinery'),
					"desc" => wp_kses_data( __("Number or any word", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select block alignment", 'jardiwinery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
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
if ( !function_exists( 'jardiwinery_sc_number_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_number_reg_shortcodes_vc');
	function jardiwinery_sc_number_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_number",
			"name" => esc_html__("Number", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert number or any word as set of separated characters", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			"class" => "trx_sc_single trx_sc_number",
			'icon' => 'icon_trx_number',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", 'jardiwinery'),
					"description" => wp_kses_data( __("Number or any word to separate", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Select block alignment", 'jardiwinery') ),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
					"type" => "dropdown"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Number extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>