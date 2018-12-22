<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_line_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_line_theme_setup' );
	function jardiwinery_sc_line_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_line_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_line_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_line id="unique_id" style="none|solid|dashed|dotted|double|groove|ridge|inset|outset" top="margin_in_pixels" bottom="margin_in_pixels" width="width_in_pixels_or_percent" height="line_thickness_in_pixels" color="line_color's_name_or_#rrggbb"]
*/

if (!function_exists('jardiwinery_sc_line')) {	
	function jardiwinery_sc_line($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"color" => "",
			"title" => "",
			"position" => "",
			"image" => "",
			"repeat" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if (empty($style)) $style = 'solid';
		if (empty($position)) $position = 'center center';
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$block_height = '';
		if ($style=='image' && !empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$attr = jardiwinery_getimagesize($image);
			if (is_array($attr) && $attr[1] > 0)
				$block_height = $attr[1];
		} else if (!empty($title) && empty($height) && !in_array($position, array('left center', 'center center', 'right center'))) {
			$block_height = '1.5em';
		}
		$border_pos = in_array($position, array('left top', 'center top', 'right top')) ? 'bottom' : 'top';

		$css .= jardiwinery_get_css_dimensions_from_values($width, $block_height)
			. ($style=='image' && !empty($image)
				? ( 'background-image: url(' . esc_url($image) . ');'
					. (jardiwinery_param_is_on($repeat) ? 'background-repeat: repeat-x;' : '')
					)
				: ( ($height !='' ? 'border-'.esc_attr($border_pos).'-width:' . esc_attr(jardiwinery_prepare_css_value($height)) . ';' : '')
					. ($style != '' ? 'border-'.esc_attr($border_pos).'-style:' . esc_attr($style) . ';' : '')
					. ($color != '' ? 'border-'.esc_attr($border_pos).'-color:' . esc_attr($color) . ';' : '')
					)
				);
		$output = '<div' . ($id ? ' id="'.esc_attr($id) . '"' : '') 
				. ' class="sc_line sc_line_position_'.esc_attr(str_replace(' ', '_', $position)) . ' sc_line_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. (!empty($title) ? '<span class="sc_line_title">' . trim($title) . '</span>' : '')
				. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_line', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_line', 'jardiwinery_sc_line');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_line_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_line_reg_shortcodes');
	function jardiwinery_sc_line_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_line", array(
			"title" => esc_html__("Line", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert Line into your post (page)", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Line style", 'jardiwinery') ),
					"value" => "solid",
					"dir" => "horizontal",
					"options" => jardiwinery_get_list_line_styles(),
					"type" => "checklist"
				),
				"image" => array(
					"title" => esc_html__("Image as separator", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site to use it as separator", 'jardiwinery') ),
					"readonly" => false,
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "",
					"type" => "media"
				),
				"repeat" => array(
					"title" => esc_html__("Repeat image", 'jardiwinery'),
					"desc" => wp_kses_data( __("To repeat an image or to show single picture", 'jardiwinery') ),
					"dependency" => array(
						'style' => array('image')
					),
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"color" => array(
					"title" => esc_html__("Color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Line color", 'jardiwinery') ),
					"dependency" => array(
						'style' => array('solid', 'dashed', 'dotted', 'double')
					),
					"value" => "",
					"type" => "color"
				),
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title that is going to be placed in the center of the line (if not empty)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"position" => array(
					"title" => esc_html__("Title position", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title position", 'jardiwinery') ),
					"dependency" => array(
						'title' => array('not_empty')
					),
					"value" => "center center",
					"options" => jardiwinery_get_list_bg_image_positions(),
					"type" => "select"
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
if ( !function_exists( 'jardiwinery_sc_line_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_line_reg_shortcodes_vc');
	function jardiwinery_sc_line_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_line",
			"name" => esc_html__("Line", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert line (delimiter)", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			"class" => "trx_sc_single trx_sc_line",
			'icon' => 'icon_trx_line',
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jardiwinery'),
					"description" => wp_kses_data( __("Line style", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"std" => "solid",
					"value" => array_flip(jardiwinery_get_list_line_styles()),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image as separator", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site to use it as separator", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "repeat",
					"heading" => esc_html__("Repeat image", 'jardiwinery'),
					"description" => wp_kses_data( __("To repeat an image or to show single picture", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('image')
					),
					"class" => "",
					"value" => array("Repeat image" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Line color", 'jardiwinery'),
					"description" => wp_kses_data( __("Line color", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'style',
						'value' => array('solid','dotted','dashed','double')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title that is going to be placed in the center of the line (if not empty)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Title position", 'jardiwinery'),
					"description" => wp_kses_data( __("Title position", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"std" => "center center",
					"value" => array_flip(jardiwinery_get_list_bg_image_positions()),
					"type" => "dropdown"
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Line extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>