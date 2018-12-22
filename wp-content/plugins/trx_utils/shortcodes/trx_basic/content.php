<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_content_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_content_theme_setup' );
	function jardiwinery_sc_content_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_content_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('jardiwinery_sc_content')) {	
	function jardiwinery_sc_content($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !jardiwinery_param_is_off($scheme) && !jardiwinery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_content', 'jardiwinery_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_content_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_content_reg_shortcodes');
	function jardiwinery_sc_content_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_content", array(
			"title" => esc_html__("Content block", 'jardiwinery'),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", 'jardiwinery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('schemes')
				),
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
if ( !function_exists( 'jardiwinery_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_content_reg_shortcodes_vc');
	function jardiwinery_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", 'jardiwinery'),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jardiwinery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends JARDIWINERY_VC_ShortCodeCollection {}
	}
}
?>