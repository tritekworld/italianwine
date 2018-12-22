<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_search_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_search_theme_setup' );
	function jardiwinery_sc_search_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_search_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('jardiwinery_sc_search')) {	
	function jardiwinery_sc_search($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'jardiwinery'),
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
		if (empty($ajax)) $ajax = jardiwinery_get_theme_option('use_ajax_search');
		if (empty($style)) $style = jardiwinery_param_is_on(jardiwinery_get_theme_option('fullscreen_search')) ? 'fullscreen' : 'regular';
		if ($style == 'fullscreen') {
			$ajax = "no";
			$state = "closed";
		}
		// Load core messages
		jardiwinery_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (jardiwinery_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'jardiwinery') : esc_attr__('Start search', 'jardiwinery')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (jardiwinery_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !jardiwinery_param_is_off($scheme) && !jardiwinery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_search', 'jardiwinery_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_search_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_search_reg_shortcodes');
	function jardiwinery_sc_search_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'jardiwinery'),
			"desc" => wp_kses_data( __("Show search form", 'jardiwinery') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select style to display search field", 'jardiwinery') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'jardiwinery'),
						"rounded" => esc_html__('Rounded', 'jardiwinery')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select search field initial state", 'jardiwinery') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'jardiwinery'),
						"opened" => esc_html__('Opened', 'jardiwinery'),
						"closed" => esc_html__('Closed', 'jardiwinery')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'jardiwinery') ),
					"value" => esc_html__("Search &hellip;", 'jardiwinery'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'jardiwinery'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'jardiwinery') ),
					"value" => "yes",
					"options" => jardiwinery_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
if ( !function_exists( 'jardiwinery_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_search_reg_shortcodes_vc');
	function jardiwinery_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert search form", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'jardiwinery'),
					"description" => wp_kses_data( __("Select style to display search field", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'jardiwinery') => "regular",
						esc_html__('Flat', 'jardiwinery') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'jardiwinery'),
					"description" => wp_kses_data( __("Select search field initial state", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'jardiwinery')  => "fixed",
						esc_html__('Opened', 'jardiwinery') => "opened",
						esc_html__('Closed', 'jardiwinery') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'jardiwinery'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'jardiwinery'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'jardiwinery') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>