<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_image_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_image_theme_setup' );
	function jardiwinery_sc_image_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_image_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('jardiwinery_sc_image')) {	
	function jardiwinery_sc_image($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = jardiwinery_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) jardiwinery_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_image', 'jardiwinery_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_image_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_image_reg_shortcodes');
	function jardiwinery_sc_image_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_image", array(
			"title" => esc_html__("Image", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert image into your post (page)", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", 'jardiwinery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Image title (if need)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'jardiwinery'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'jardiwinery') ),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", 'jardiwinery'),
					"desc" => wp_kses_data( __("Float image to left or right side", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('float')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", 'jardiwinery'),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", 'jardiwinery') ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'jardiwinery'),
						"round" => esc_html__('Round', 'jardiwinery')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", 'jardiwinery'),
					"desc" => wp_kses_data( __("The link URL from the image", 'jardiwinery') ),
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
if ( !function_exists( 'jardiwinery_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_image_reg_shortcodes_vc');
	function jardiwinery_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert image", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", 'jardiwinery'),
					"description" => wp_kses_data( __("Select image from library", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Align image to left or right side", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", 'jardiwinery'),
					"description" => wp_kses_data( __("Shape of the image: square or round", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'jardiwinery') => 'square',
						esc_html__('Round', 'jardiwinery') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Image's title", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", 'jardiwinery') ),
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", 'jardiwinery'),
					"description" => wp_kses_data( __("The link URL from the image", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Image extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>