<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_table_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_table_theme_setup' );
	function jardiwinery_sc_table_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_table_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_table_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('jardiwinery_sc_table')) {	
	function jardiwinery_sc_table($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_table', 'jardiwinery_sc_table');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_table_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_table_reg_shortcodes');
	function jardiwinery_sc_table_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_table", array(
			"title" => esc_html__("Table", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert a table into post (page). ", 'jardiwinery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Content alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select alignment for each table cell", 'jardiwinery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
				),
				"_content_" => array(
					"title" => esc_html__("Table content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Content, created with any table-generator", 'jardiwinery') ),
					"divider" => true,
					"rows" => 8,
					"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
					"type" => "textarea"
				),
				"width" => jardiwinery_shortcodes_width(),
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
if ( !function_exists( 'jardiwinery_sc_table_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_table_reg_shortcodes_vc');
	function jardiwinery_sc_table_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_table",
			"name" => esc_html__("Table", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert a table", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_table',
			"class" => "trx_sc_container trx_sc_table",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Cells content alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Select alignment for each table cell", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Table content", 'jardiwinery'),
					"description" => wp_kses_data( __("Content, created with any table-generator", 'jardiwinery') ),
					"class" => "",
					"value" => esc_html__("Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/", 'jardiwinery'),
					"type" => "textarea_html"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_vc_width(),
				jardiwinery_vc_height(),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Table extends JARDIWINERY_VC_ShortCodeContainer {}
	}
}
?>