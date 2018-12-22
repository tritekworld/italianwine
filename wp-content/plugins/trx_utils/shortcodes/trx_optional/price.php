<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_price_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_price_theme_setup' );
	function jardiwinery_sc_price_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_price_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_price_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_price id="unique_id" currency="$" money="29.99" period="monthly"]
*/

if (!function_exists('jardiwinery_sc_price')) {	
	function jardiwinery_sc_price($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$output = '';
		if (!empty($money)) {
			$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
			$m = explode('.', str_replace(',', '.', $money));
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. '>'
                . (!empty($period) ? '<span class="sc_price_period">'.($period).'</span>' : (!empty($m[1]) ? '<span class="sc_price_period_empty"></span>' : ''))
				. '<span class="sc_price_currency">'.($currency).'</span>'
				. '<span class="sc_price_money">'.($m[0]).'</span>'
				. (!empty($m[1]) ? '<span class="sc_price_info">' : '')
				. (!empty($m[1]) ? '<span class="sc_price_penny">'.($m[1]).'</span>' : '')

				. (!empty($m[1]) ? '</span>' : '')
				. '</div>';
		}
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_price', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_price', 'jardiwinery_sc_price');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_price_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_price_reg_shortcodes');
	function jardiwinery_sc_price_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_price", array(
			"title" => esc_html__("Price", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert price with decoration", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"money" => array(
					"title" => esc_html__("Money", 'jardiwinery'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", 'jardiwinery'),
					"desc" => wp_kses_data( __("Currency character", 'jardiwinery') ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", 'jardiwinery'),
					"desc" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('float')
				), 
				"top" => jardiwinery_get_sc_param('top'),
				"bottom" => jardiwinery_get_sc_param('bottom'),
				"left" => jardiwinery_get_sc_param('left'),
				"right" => jardiwinery_get_sc_param('right'),
				"id" => jardiwinery_get_sc_param('id'),
				"class" => jardiwinery_get_sc_param('class'),
				"css" => jardiwinery_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_price_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_price_reg_shortcodes_vc');
	function jardiwinery_sc_price_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price",
			"name" => esc_html__("Price", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert price with decoration", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_price',
			"class" => "trx_sc_single trx_sc_price",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'jardiwinery'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'jardiwinery'),
					"description" => wp_kses_data( __("Currency character", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'jardiwinery'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Align price to left or right side", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('float')),
					"type" => "dropdown"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Price extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>