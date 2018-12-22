<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_countdown_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_countdown_theme_setup' );
	function jardiwinery_sc_countdown_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_countdown_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_countdown_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_countdown date="" time=""]

if (!function_exists('jardiwinery_sc_countdown')) {	
	function jardiwinery_sc_countdown($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "1",
			"align" => "center",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height);
		if (empty($interval)) $interval = 1;
		wp_enqueue_script( 'jardiwinery-jquery-plugin-script', jardiwinery_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'jardiwinery-countdown-script', jardiwinery_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
			. '>'
				. ($align=='center' ? '<div class="sc_countdown_inner">' : '')
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Days', 'jardiwinery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator"></div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Hours', 'jardiwinery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator"></div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Minutes', 'jardiwinery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator"></div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Seconds', 'jardiwinery').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
				. ($align=='center' ? '</div>' : '')
			. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_countdown", "jardiwinery_sc_countdown");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_countdown_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_countdown_reg_shortcodes');
	function jardiwinery_sc_countdown_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_countdown", array(
			"title" => esc_html__("Countdown", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert countdown object", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"date" => array(
					"title" => esc_html__("Date", 'jardiwinery'),
					"desc" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'jardiwinery') ),
					"value" => "",
					"format" => "yy-mm-dd",
					"type" => "date"
				),
				"time" => array(
					"title" => esc_html__("Time", 'jardiwinery'),
					"desc" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Countdown style", 'jardiwinery') ),
					"value" => "1",
					"type" => "checklist",
					"options" => jardiwinery_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Align counter to left, center or right", 'jardiwinery') ),
					"divider" => true,
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
if ( !function_exists( 'jardiwinery_sc_countdown_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_countdown_reg_shortcodes_vc');
	function jardiwinery_sc_countdown_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_countdown",
			"name" => esc_html__("Countdown", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert countdown object", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_countdown',
			"class" => "trx_sc_single trx_sc_countdown",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "date",
					"heading" => esc_html__("Date", 'jardiwinery'),
					"description" => wp_kses_data( __("Upcoming date (format: yyyy-mm-dd)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "time",
					"heading" => esc_html__("Time", 'jardiwinery'),
					"description" => wp_kses_data( __("Upcoming time (format: HH:mm:ss)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jardiwinery'),
					"description" => wp_kses_data( __("Countdown style", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Align counter to left, center or right", 'jardiwinery') ),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Countdown extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>