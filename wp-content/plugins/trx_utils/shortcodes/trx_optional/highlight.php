<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_highlight_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_highlight_theme_setup' );
	function jardiwinery_sc_highlight_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_highlight_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('jardiwinery_sc_highlight')) {	
	function jardiwinery_sc_highlight($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(jardiwinery_prepare_css_value($font_size)) . '; line-height: 1.9em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_highlight', 'jardiwinery_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_highlight_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_highlight_reg_shortcodes');
	function jardiwinery_sc_highlight_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'jardiwinery'),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'jardiwinery'),
					"desc" => wp_kses_data( __("Highlight type", 'jardiwinery') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'jardiwinery'),
						1 => esc_html__('Type 1', 'jardiwinery'),
						2 => esc_html__('Type 2', 'jardiwinery'),
						3 => esc_html__('Type 3', 'jardiwinery')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Color for the highlighted text", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Background color for the highlighted text", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'jardiwinery'),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Content for highlight", 'jardiwinery') ),
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


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_highlight_reg_shortcodes_vc');
	function jardiwinery_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'jardiwinery'),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'jardiwinery'),
					"description" => wp_kses_data( __("Highlight type", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'jardiwinery') => 0,
							esc_html__('Type 1', 'jardiwinery') => 1,
							esc_html__('Type 2', 'jardiwinery') => 2,
							esc_html__('Type 3', 'jardiwinery') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'jardiwinery'),
					"description" => wp_kses_data( __("Color for the highlighted text", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jardiwinery'),
					"description" => wp_kses_data( __("Background color for the highlighted text", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jardiwinery'),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'jardiwinery'),
					"description" => wp_kses_data( __("Content for highlight", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>