<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_list_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_list_theme_setup' );
	function jardiwinery_sc_list_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_list_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('jardiwinery_sc_list')) {	
	function jardiwinery_sc_list($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
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
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		jardiwinery_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || jardiwinery_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_list', 'jardiwinery_sc_list');
}


if (!function_exists('jardiwinery_sc_list_item')) {	
	function jardiwinery_sc_list_item($atts, $content=null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		jardiwinery_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || jardiwinery_param_is_inherit($icon)) $icon = jardiwinery_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || jardiwinery_param_is_inherit($icon_color)) $icon_color = jardiwinery_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (jardiwinery_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (jardiwinery_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (jardiwinery_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_list_item', 'jardiwinery_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_list_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_list_reg_shortcodes');
	function jardiwinery_sc_list_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_list", array(
			"title" => esc_html__("List", 'jardiwinery'),
			"desc" => wp_kses_data( __("List items with specific bullets", 'jardiwinery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Bullet's style for each list item", 'jardiwinery') ),
					"value" => "ul",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", 'jardiwinery'),
					"desc" => wp_kses_data( __("List items color", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'jardiwinery'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'jardiwinery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", 'jardiwinery'),
					"desc" => wp_kses_data( __("List icons color", 'jardiwinery') ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => jardiwinery_get_sc_param('top'),
				"bottom" => jardiwinery_get_sc_param('bottom'),
				"left" => jardiwinery_get_sc_param('left'),
				"right" => jardiwinery_get_sc_param('right'),
				"id" => jardiwinery_get_sc_param('id'),
				"class" => jardiwinery_get_sc_param('class'),
				"animation" => jardiwinery_get_sc_param('animation'),
				"css" => jardiwinery_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", 'jardiwinery'),
				"desc" => wp_kses_data( __("List item with specific bullet", 'jardiwinery') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", 'jardiwinery'),
						"desc" => wp_kses_data( __("Current list item content", 'jardiwinery') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", 'jardiwinery'),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", 'jardiwinery'),
						"desc" => wp_kses_data( __("Text color for this item", 'jardiwinery') ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'jardiwinery'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'jardiwinery') ),
						"value" => "",
						"type" => "icons",
						"options" => jardiwinery_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", 'jardiwinery'),
						"desc" => wp_kses_data( __("Icon color for this item", 'jardiwinery') ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", 'jardiwinery'),
						"desc" => wp_kses_data( __("Link URL for the current list item", 'jardiwinery') ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", 'jardiwinery'),
						"desc" => wp_kses_data( __("Link target for the current list item", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"id" => jardiwinery_get_sc_param('id'),
					"class" => jardiwinery_get_sc_param('class'),
					"css" => jardiwinery_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_list_reg_shortcodes_vc');
	function jardiwinery_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", 'jardiwinery'),
			"description" => wp_kses_data( __("List items with specific bullets", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", 'jardiwinery'),
					"description" => wp_kses_data( __("Bullet's style for each list item", 'jardiwinery') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(jardiwinery_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'jardiwinery'),
					"description" => wp_kses_data( __("List items color", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'jardiwinery'),
					"description" => wp_kses_data( __("List icons color", 'jardiwinery') ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", 'jardiwinery'),
			"description" => wp_kses_data( __("List item with specific bullet", 'jardiwinery') ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jardiwinery'),
					"description" => wp_kses_data( __("Link URL for the current list item", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'jardiwinery'),
					"description" => wp_kses_data( __("Link target for the current list item", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", 'jardiwinery'),
					"description" => wp_kses_data( __("Text color for this item", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", 'jardiwinery'),
					"description" => wp_kses_data( __("Icon color for this item", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends JARDIWINERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends JARDIWINERY_VC_ShortCodeContainer {}
	}
}
?>