<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_gap_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_gap_theme_setup' );
	function jardiwinery_sc_gap_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_gap_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('jardiwinery_sc_gap')) {	
	function jardiwinery_sc_gap($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		$output = jardiwinery_gap_start() . do_shortcode($content) . jardiwinery_gap_end();
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_gap", "jardiwinery_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_gap_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_gap_reg_shortcodes');
	function jardiwinery_sc_gap_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'jardiwinery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Gap inner content", 'jardiwinery') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_gap_reg_shortcodes_vc');
	function jardiwinery_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", 'jardiwinery') ),
			"category" => esc_html__('Structure', 'jardiwinery'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends JARDIWINERY_VC_ShortCodeCollection {}
	}
}
?>