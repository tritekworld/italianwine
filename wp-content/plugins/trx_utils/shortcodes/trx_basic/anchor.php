<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_anchor_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_anchor_theme_setup' );
	function jardiwinery_sc_anchor_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_anchor_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('jardiwinery_sc_anchor')) {	
	function jardiwinery_sc_anchor($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(jardiwinery_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (jardiwinery_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_anchor", "jardiwinery_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_anchor_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_anchor_reg_shortcodes');
	function jardiwinery_sc_anchor_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'jardiwinery'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'jardiwinery') ),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'jardiwinery'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'jardiwinery'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'jardiwinery') ),
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"id" => jardiwinery_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_anchor_reg_shortcodes_vc');
	function jardiwinery_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'jardiwinery') ),
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'jardiwinery'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'jardiwinery'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'jardiwinery'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'jardiwinery'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'jardiwinery') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				jardiwinery_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>