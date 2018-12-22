<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_chat_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_chat_theme_setup' );
	function jardiwinery_sc_chat_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_chat_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_chat_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('jardiwinery_sc_chat')) {	
	function jardiwinery_sc_chat($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
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
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = jardiwinery_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (jardiwinery_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
						. ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
						. ($title == '' ? '' : ('<div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
						. '<div class="sc_chat_content">'.($content).'</div>'
					. '</div>'
				. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_chat', 'jardiwinery_sc_chat');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_chat_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_chat_reg_shortcodes');
	function jardiwinery_sc_chat_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_chat", array(
			"title" => esc_html__("Chat", 'jardiwinery'),
			"desc" => wp_kses_data( __("Chat message", 'jardiwinery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Item title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Chat item title", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"photo" => array(
					"title" => esc_html__("Item photo", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'jardiwinery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"link" => array(
					"title" => esc_html__("Item link", 'jardiwinery'),
					"desc" => wp_kses_data( __("Chat item link", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Chat item content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Current chat item content", 'jardiwinery') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'jardiwinery_sc_chat_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_chat_reg_shortcodes_vc');
	function jardiwinery_sc_chat_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_chat",
			"name" => esc_html__("Chat", 'jardiwinery'),
			"description" => wp_kses_data( __("Chat message", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_chat',
			"class" => "trx_sc_container trx_sc_chat",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Item title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title for current chat item", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Item photo", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jardiwinery'),
					"description" => wp_kses_data( __("URL for the link on chat title click", 'jardiwinery') ),
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
			),
			'js_view' => 'VcTrxTextContainerView'
		
		) );
		
		class WPBakeryShortCode_Trx_Chat extends JARDIWINERY_VC_ShortCodeContainer {}
	}
}
?>