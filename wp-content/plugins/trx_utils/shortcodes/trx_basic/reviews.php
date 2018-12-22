<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_reviews_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_reviews_theme_setup' );
	function jardiwinery_sc_reviews_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_reviews_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_reviews_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_reviews]
*/

if (!function_exists('jardiwinery_sc_reviews')) {	
	function jardiwinery_sc_reviews($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
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
		$output = jardiwinery_param_is_off(jardiwinery_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(jardiwinery_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_reviews", "jardiwinery_sc_reviews");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_reviews_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_reviews_reg_shortcodes');
	function jardiwinery_sc_reviews_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_reviews", array(
			"title" => esc_html__("Reviews", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert reviews block in the single post", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'jardiwinery') ),
					"divider" => true,
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
if ( !function_exists( 'jardiwinery_sc_reviews_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_reviews_reg_shortcodes_vc');
	function jardiwinery_sc_reviews_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_reviews",
			"name" => esc_html__("Reviews", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert reviews block in the single post", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_reviews',
			"class" => "trx_sc_single trx_sc_reviews",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Align counter to left, center or right", 'jardiwinery') ),
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
		
		class WPBakeryShortCode_Trx_Reviews extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>