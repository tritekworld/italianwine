<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_button_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_button_theme_setup' );
	function jardiwinery_sc_button_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_button_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('jardiwinery_sc_button')) {	
	function jardiwinery_sc_button($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
            "style_color" => "",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
        if ($style == 'icon' && empty($icon)) $icon = 'icon-arrow';
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (jardiwinery_param_is_on($popup)) jardiwinery_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
                    . (!empty($style_color) ? ' '.esc_attr($style_color) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (jardiwinery_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. ($style == 'icon' ? '' : do_shortcode($content))
			. '</a>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_button', 'jardiwinery_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_button_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_button_reg_shortcodes');
	function jardiwinery_sc_button_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'jardiwinery'),
			"desc" => wp_kses_data( __("Button with link", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'jardiwinery'),
					"desc" => wp_kses_data( __("Button caption", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select button's shape", 'jardiwinery') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'jardiwinery'),
						'round' => esc_html__('Round', 'jardiwinery')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select button's style", 'jardiwinery') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'jardiwinery'),
						'border' => esc_html__('Border', 'jardiwinery')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select button's size", 'jardiwinery') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'jardiwinery'),
						'medium' => esc_html__('Medium', 'jardiwinery'),
						'large' => esc_html__('Large', 'jardiwinery')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'jardiwinery'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set (not for small filled or bordered button)',  'jardiwinery') ),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any color for button's caption", 'jardiwinery') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any color for button's background", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Align button to left, center or right", 'jardiwinery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("URL for link on button click", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'jardiwinery'),
					"desc" => wp_kses_data( __("Target for link on button click", 'jardiwinery') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'jardiwinery'),
					"desc" => wp_kses_data( __("Open link target in popup window", 'jardiwinery') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'jardiwinery'),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", 'jardiwinery') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => jardiwinery_shortcodes_width(),
				"height" => jardiwinery_shortcodes_height(),
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
if ( !function_exists( 'jardiwinery_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_button_reg_shortcodes_vc');
	function jardiwinery_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'jardiwinery'),
			"description" => wp_kses_data( __("Button with link", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'jardiwinery'),
					"description" => wp_kses_data( __("Button caption", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'jardiwinery'),
					"description" => wp_kses_data( __("Select button's shape", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'jardiwinery') => 'square',
						esc_html__('Round', 'jardiwinery') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'jardiwinery'),
					"description" => wp_kses_data( __("Select button's style", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'jardiwinery') => 'filled',
						esc_html__('Border', 'jardiwinery') => 'border',
                        esc_html__('Only icon', 'jardiwinery') => 'icon'
					),
					"type" => "dropdown"
				),
                array(
                    "param_name" => "style_color",
                    "heading" => esc_html__("Button's style color", 'jardiwinery'),
                    "description" => wp_kses_data( __("Select button's style color", 'jardiwinery') ),
                    "class" => "",
                    'dependency' => array(
                        'element' => 'style',
                        'value' => 'border'
                    ),
                    "value" => array(
                        esc_html__('Original', 'jardiwinery') => 'original',
                        esc_html__('Light', 'jardiwinery') => 'light'
                    ),
                    "type" => "dropdown"
                ),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'jardiwinery'),
					"description" => wp_kses_data( __("Select button's size", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'jardiwinery') => 'small',
						esc_html__('Medium (use only for button with icon)', 'jardiwinery') => 'medium',
						esc_html__('Large', 'jardiwinery') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'jardiwinery') ),
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'jardiwinery'),
					"description" => wp_kses_data( __("Any color for button's caption", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'jardiwinery'),
					"description" => wp_kses_data( __("Any color for button's background", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Align button to left, center or right", 'jardiwinery') ),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jardiwinery'),
					"description" => wp_kses_data( __("URL for the link on button click", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Link', 'jardiwinery'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'jardiwinery'),
					"description" => wp_kses_data( __("Target for the link on button click", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Link', 'jardiwinery'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'jardiwinery'),
					"description" => wp_kses_data( __("Open link target in popup window", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Link', 'jardiwinery'),
					"value" => array(esc_html__('Open in popup', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'jardiwinery'),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Link', 'jardiwinery'),
					"value" => "",
					"type" => "textfield"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>