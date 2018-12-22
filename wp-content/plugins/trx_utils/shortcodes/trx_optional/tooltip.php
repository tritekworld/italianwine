<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_tooltip_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_tooltip_theme_setup' );
	function jardiwinery_sc_tooltip_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tooltip id="unique_id" title="Tooltip text here"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/tooltip]
*/

if (!function_exists('jardiwinery_sc_tooltip')) {	
	function jardiwinery_sc_tooltip($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_tooltip', 'jardiwinery_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_tooltip_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_tooltip_reg_shortcodes');
	function jardiwinery_sc_tooltip_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", 'jardiwinery'),
			"desc" => wp_kses_data( __("Create tooltip for selected text", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Tooltip title (required)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", 'jardiwinery') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => jardiwinery_get_sc_param('id'),
				"class" => jardiwinery_get_sc_param('class'),
				"css" => jardiwinery_get_sc_param('css')
			)
		));
	}
}
?>