<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_hide_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_hide_theme_setup' );
	function jardiwinery_sc_hide_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('jardiwinery_sc_hide')) {	
	function jardiwinery_sc_hide($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<'.'script type="text/javascript"'.'>
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</'.'script'.'>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_hide', 'jardiwinery_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_hide_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_hide_reg_shortcodes');
	function jardiwinery_sc_hide_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'jardiwinery'),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any block's CSS-selector", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'jardiwinery'),
					"desc" => wp_kses_data( __("New state for the block: hide or show", 'jardiwinery') ),
					"value" => "yes",
					"size" => "small",
					"options" => jardiwinery_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>