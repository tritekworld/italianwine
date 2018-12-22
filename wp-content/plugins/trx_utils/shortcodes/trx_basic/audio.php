<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_audio_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_audio_theme_setup' );
	function jardiwinery_sc_audio_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_audio_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_audio_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('jardiwinery_sc_audio')) {	
	function jardiwinery_sc_audio($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (jardiwinery_param_is_on($controls) ? ' controls="controls"' : '')
			. (jardiwinery_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ' width="'.esc_attr($width).'" height="'.esc_attr($height).'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( jardiwinery_get_custom_option('substitute_audio')=='no') {
			if (jardiwinery_param_is_on($frame)) {
				$audio = jardiwinery_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = jardiwinery_substitute_audio($audio, false);
			}
		}
		if (jardiwinery_get_theme_option('use_mediaelement')=='yes')
			wp_enqueue_script('wp-mediaelement');
		return apply_filters('jardiwinery_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_audio", "jardiwinery_sc_audio");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_audio_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_audio_reg_shortcodes');
	function jardiwinery_sc_audio_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_audio", array(
			"title" => esc_html__("Audio", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert audio player", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for audio file", 'jardiwinery'),
					"desc" => wp_kses_data( __("URL for audio file", 'jardiwinery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose audio', 'jardiwinery'),
						'action' => 'media_upload',
						'type' => 'audio',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose audio file', 'jardiwinery'),
							'update' => esc_html__('Select audio file', 'jardiwinery')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'jardiwinery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title of the audio file", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"author" => array(
					"title" => esc_html__("Author", 'jardiwinery'),
					"desc" => wp_kses_data( __("Author of the audio file", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Show controls", 'jardiwinery'),
					"desc" => wp_kses_data( __("Show controls in audio player", 'jardiwinery') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('show_hide')
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay audio", 'jardiwinery'),
					"desc" => wp_kses_data( __("Autoplay audio on page load", 'jardiwinery') ),
					"value" => "off",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select block alignment", 'jardiwinery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
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
if ( !function_exists( 'jardiwinery_sc_audio_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_audio_reg_shortcodes_vc');
	function jardiwinery_sc_audio_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_audio",
			"name" => esc_html__("Audio", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert audio player", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_audio',
			"class" => "trx_sc_single trx_sc_audio",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for audio file", 'jardiwinery'),
					"description" => wp_kses_data( __("Put here URL for audio file", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title of the audio file", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'jardiwinery'),
					"description" => wp_kses_data( __("Author of the audio file", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'jardiwinery'),
					"description" => wp_kses_data( __("Show/hide controls", 'jardiwinery') ),
					"class" => "",
					"value" => array("Hide controls" => "hide" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay", 'jardiwinery'),
					"description" => wp_kses_data( __("Autoplay audio on page load", 'jardiwinery') ),
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Select block alignment", 'jardiwinery') ),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
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
			),
		) );
		
		class WPBakeryShortCode_Trx_Audio extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>