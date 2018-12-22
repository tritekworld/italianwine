<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_popup_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_popup_theme_setup' );
	function jardiwinery_sc_popup_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_popup_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_popup_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_popup id="unique_id" class="class_name" style="css_styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_popup]
*/

if (!function_exists('jardiwinery_sc_popup')) {	
	function jardiwinery_sc_popup($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
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
		jardiwinery_enqueue_popup('magnific');
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_popup mfp-with-anim mfp-hide' . ($class ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_popup', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_popup', 'jardiwinery_sc_popup');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_popup_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_popup_reg_shortcodes');
	function jardiwinery_sc_popup_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_popup", array(
			"title" => esc_html__("Popup window", 'jardiwinery'),
			"desc" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'jardiwinery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Container content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Content for section container", 'jardiwinery') ),
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
				"css" => jardiwinery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_popup_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_popup_reg_shortcodes_vc');
	function jardiwinery_sc_popup_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_popup",
			"name" => esc_html__("Popup window", 'jardiwinery'),
			"description" => wp_kses_data( __("Container for any html-block with desired class and style for popup window", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_popup',
			"class" => "trx_sc_collection trx_sc_popup",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Popup extends JARDIWINERY_VC_ShortCodeCollection {}
	}
}
?>