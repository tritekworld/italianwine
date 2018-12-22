<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_br_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_br_theme_setup' );
	function jardiwinery_sc_br_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('jardiwinery_sc_br')) {	
	function jardiwinery_sc_br($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_br", "jardiwinery_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_br_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_br_reg_shortcodes');
	function jardiwinery_sc_br_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'jardiwinery'),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'jardiwinery'),
					"desc" => wp_kses_data( __("Clear floating (if need)", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'jardiwinery'),
						'left' => esc_html__('Left', 'jardiwinery'),
						'right' => esc_html__('Right', 'jardiwinery'),
						'both' => esc_html__('Both', 'jardiwinery')
					)
				)
			)
		));
	}
}
?>