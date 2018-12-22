<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_call_to_action_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_call_to_action_theme_setup' );
	function jardiwinery_sc_call_to_action_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_call_to_action_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_call_to_action_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_call_to_action id="unique_id" style="1|2" align="left|center|right"]
	[inner shortcodes]
[/trx_call_to_action]
*/

if (!function_exists('jardiwinery_sc_call_to_action')) {	
	function jardiwinery_sc_call_to_action($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
            "style_color" => "",
			"align" => "center",
			"custom" => "no",
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'jardiwinery'),
			"link2" => '',
			"link2_caption" => '',
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
	
		if (empty($id)) $id = "sc_call_to_action_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = jardiwinery_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? jardiwinery_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h']) 
				: jardiwinery_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(jardiwinery_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (jardiwinery_get_custom_option('substitute_video')=='no') {
				$video = jardiwinery_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = jardiwinery_substitute_video($video, $width, $height, false);
				}
			}
			if (jardiwinery_get_theme_option('use_mediaelement')=='yes')
				wp_enqueue_script('wp-mediaelement');
		}
		
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_call_to_action_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_call_to_action_buttons sc_item_buttons'.($need_columns && $style==2 ? ' column-1_'.esc_attr($need_columns) : '').'">'
							. (!empty($link) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'"]'.esc_html($link_caption).'[/trx_button]').'</div>'
								: '')
							. (!empty($link2) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'"]'.esc_html($link2_caption).'[/trx_button]').'</div>'
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_call_to_action'
					. (jardiwinery_param_is_on($accent) ? ' sc_call_to_action_accented' : '')
					. ' sc_call_to_action_style_' . esc_attr($style) 
					. ' sc_call_to_action_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
                    . (!empty($style_color) ? ' '.esc_attr($style_color) : '')
					. '"'
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. ($style==2 && $align=='right' ? $buttons : '')
				. '<div class="sc_call_to_action_info'.($need_columns ? ' column-'.esc_attr($need_columns-1).'_'.esc_attr($need_columns) : '').'">'
					. (!empty($subtitle) ? '<h6 class="sc_call_to_action_subtitle sc_item_subtitle">' . trim(jardiwinery_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h3 class="sc_call_to_action_title sc_item_title">' . trim(jardiwinery_strmacros($title)) . '</h3>' : '')
					. (!empty($description) ? '<div class="sc_call_to_action_descr sc_item_descr">' . trim(jardiwinery_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				. ($need_columns ? '</div>' : '')
			. '</div>';
	
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_call_to_action', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_call_to_action', 'jardiwinery_sc_call_to_action');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_call_to_action_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_call_to_action_reg_shortcodes');
	function jardiwinery_sc_call_to_action_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_call_to_action", array(
			"title" => esc_html__("Call to action", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert call to action block in your page (post)", 'jardiwinery') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title for the block", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'jardiwinery'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'jardiwinery'),
					"desc" => wp_kses_data( __("Short description for the block", 'jardiwinery') ),
					"value" => "",
					"type" => "textarea"
				),
				"style" => array(
					"title" => esc_html__("Style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select style to display block", 'jardiwinery') ),
					"value" => "1",
					"type" => "checklist",
					"options" => jardiwinery_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Alignment elements in the block", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
				),
				"accent" => array(
					"title" => esc_html__("Accented", 'jardiwinery'),
					"desc" => wp_kses_data( __("Fill entire block with links color from current color scheme", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"custom" => array(
					"title" => esc_html__("Custom", 'jardiwinery'),
					"desc" => wp_kses_data( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"image" => array(
					"title" => esc_html__("Image", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site to include image into this block", 'jardiwinery') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"video" => array(
					"title" => esc_html__("URL for video file", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select video from media library or paste URL for video file from other site to include video into this block", 'jardiwinery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'jardiwinery'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'jardiwinery'),
							'update' => esc_html__('Select video file', 'jardiwinery')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'jardiwinery'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", 'jardiwinery'),
					"desc" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'jardiwinery') ),
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
if ( !function_exists( 'jardiwinery_sc_call_to_action_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_call_to_action_reg_shortcodes_vc');
	function jardiwinery_sc_call_to_action_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_call_to_action",
			"name" => esc_html__("Call to Action", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert call to action block in your page (post)", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_call_to_action',
			"class" => "trx_sc_collection trx_sc_call_to_action",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block's style", 'jardiwinery'),
					"description" => wp_kses_data( __("Select style to display this block", 'jardiwinery') ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(jardiwinery_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
                array(
                    "param_name" => "style_color",
                    "heading" => esc_html__("Block's style color", 'jardiwinery'),
                    "description" => wp_kses_data( __("Select block's style color", 'jardiwinery') ),
                    "class" => "",
                    "value" => array(
                        esc_html__('Original', 'jardiwinery') => 'style_color_original',
                        esc_html__('Light', 'jardiwinery') => 'style_color_light'
                    ),
                    "type" => "dropdown"
                ),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Select block alignment", 'jardiwinery') ),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "accent",
					"heading" => esc_html__("Accent", 'jardiwinery'),
					"description" => wp_kses_data( __("Fill entire block with links color from current color scheme", 'jardiwinery') ),
					"class" => "",
					"value" => array("Fill with links color" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", 'jardiwinery'),
					"description" => wp_kses_data( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", 'jardiwinery') ),
					"class" => "",
					"value" => array("Custom content" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image", 'jardiwinery'),
					"description" => wp_kses_data( __("Image to display inside block", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "video",
					"heading" => esc_html__("URL for video file", 'jardiwinery'),
					"description" => wp_kses_data( __("Paste URL for video file to display inside block", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title for the block", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'jardiwinery'),
					"description" => wp_kses_data( __("Subtitle for the block", 'jardiwinery') ),
					"group" => esc_html__('Captions', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'jardiwinery'),
					"description" => wp_kses_data( __("Description for the block", 'jardiwinery') ),
					"group" => esc_html__('Captions', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'jardiwinery'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jardiwinery') ),
					"group" => esc_html__('Captions', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'jardiwinery'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jardiwinery') ),
					"group" => esc_html__('Captions', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", 'jardiwinery'),
					"description" => wp_kses_data( __("Link URL for the second button at the bottom of the block", 'jardiwinery') ),
					"group" => esc_html__('Captions', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", 'jardiwinery'),
					"description" => wp_kses_data( __("Caption for the second button at the bottom of the block", 'jardiwinery') ),
					"group" => esc_html__('Captions', 'jardiwinery'),
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
		
		class WPBakeryShortCode_Trx_Call_To_Action extends JARDIWINERY_VC_ShortCodeCollection {}
	}
}
?>