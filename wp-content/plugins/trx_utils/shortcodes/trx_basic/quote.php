<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_quote_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_quote_theme_setup' );
	function jardiwinery_sc_quote_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_quote_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_quote_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_quote id="unique_id" cite="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/quote]
*/

if (!function_exists('jardiwinery_sc_quote')) {	
	function jardiwinery_sc_quote($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
            "sub_title" => "",
			"cite" => "",
            "style" => "style_quote_image",
            "bg_image" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

        if ($bg_image > 0) {
            $attach = wp_get_attachment_image_src( $bg_image, 'full' );
            if (isset($attach[0]) && $attach[0]!='')
                $bg_image = $attach[0];
        }

		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width);
        $css .= ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '');
		$cite_param = $cite != '' ? ' cite="'.esc_attr($cite).'"' : '';
		$title = $title=='' ? $cite : $title;
		$content = do_shortcode($content);
		if (jardiwinery_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<blockquote' 
			. ($id ? ' id="'.esc_attr($id).'"' : '') . ($cite_param) 
			. ' class="sc_quote'
            . (!empty($class) ? ' '.esc_attr($class) : '')
            . (!empty($style) ? ' '.esc_attr($style) : '')
            .'"'
			. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
				. ($content)
				. ($title == '' ? '' : ('<p class="sc_quote_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($title) . ($cite!='' ? '</a>' : '') . '</p>'))
                . ($sub_title == '' ? '' : ('<p class="sc_quote_sub_title">' . ($cite!='' ? '<a href="'.esc_url($cite).'">' : '') . ($sub_title) . ($cite!='' ? '</a>' : '') . '</p>'))
			.'</blockquote>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_quote', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_quote', 'jardiwinery_sc_quote');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_quote_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_quote_reg_shortcodes');
	function jardiwinery_sc_quote_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_quote", array(
			"title" => esc_html__("Quote", 'jardiwinery'),
			"desc" => wp_kses_data( __("Quote text", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"cite" => array(
					"title" => esc_html__("Quote cite", 'jardiwinery'),
					"desc" => wp_kses_data( __("URL for quote cite", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"title" => array(
					"title" => esc_html__("Title (author)", 'jardiwinery'),
					"desc" => wp_kses_data( __("Quote title (author name)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
                "sub_title" => array(
                    "title" => esc_html__("Subtitle (author)", 'jardiwinery'),
                    "desc" => wp_kses_data( __("Quote title (author name)", 'jardiwinery') ),
                    "value" => "",
                    "type" => "text"
                ),
				"_content_" => array(
					"title" => esc_html__("Quote content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Quote content", 'jardiwinery') ),
					"rows" => 4,
					"value" => "",
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
if ( !function_exists( 'jardiwinery_sc_quote_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_quote_reg_shortcodes_vc');
	function jardiwinery_sc_quote_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_quote",
			"name" => esc_html__("Quote", 'jardiwinery'),
			"description" => wp_kses_data( __("Quote text", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_quote',
			"class" => "trx_sc_single trx_sc_quote",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "cite",
					"heading" => esc_html__("Quote cite", 'jardiwinery'),
					"description" => wp_kses_data( __("URL for the quote cite link", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title (author)", 'jardiwinery'),
					"description" => wp_kses_data( __("Quote title (author name)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
                array(
                    "param_name" => "sub_title",
                    "heading" => esc_html__("Subtitle (author)", 'jardiwinery'),
                    "description" => wp_kses_data( __("Quote title (author name)", 'jardiwinery') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => "",
                    "type" => "textfield"
                ),
                array(
                    "param_name" => "style",
                    "heading" => esc_html__("Quote's style", 'jardiwinery'),
                    "description" => wp_kses_data( __("Select quote's style", 'jardiwinery') ),
                    "class" => "",
                    "value" => array(
                        esc_html__('Image', 'jardiwinery') => 'style_quote_image',
                        esc_html__('Light', 'jardiwinery') => 'style_quote_light'

                    ),
                    "type" => "dropdown"
                ),
                array(
                    "param_name" => "bg_image",
                    "heading" => esc_html__("Background image URL", 'jardiwinery'),
                    "description" => wp_kses_data( __("Select background image from library for this section", 'jardiwinery') ),
                    'dependency' => array(
                        'element' => 'style',
                        'value' => 'style_quote_image'
                    ),
                    "class" => "",
                    "value" => "",
                    "type" => "attach_image"
                ),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Quote content", 'jardiwinery'),
					"description" => wp_kses_data( __("Quote content", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_vc_width(),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Quote extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>