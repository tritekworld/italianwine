<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_tabs_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_tabs_theme_setup' );
	function jardiwinery_sc_tabs_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_tabs_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_tabs_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_tabs id="unique_id" tab_names="Planning|Development|Support" style="1|2" initial="1 - num_tabs"]
	[trx_tab]Randomised words which don't look even slightly believable. If you are going to use a passage. You need to be sure there isn't anything embarrassing hidden in the middle of text established fact that a reader will be istracted by the readable content of a page when looking at its layout.[/trx_tab]
	[trx_tab]Fact reader will be distracted by the <a href="#" class="main_link">readable content</a> of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have evolved over. There are many variations of passages of Lorem Ipsum available, but the majority.[/trx_tab]
	[trx_tab]Distracted by the  readable content  of a page when. Looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using content here, content here, making it look like readable English will uncover many web sites still in their infancy. Various versions have  evolved over.  There are many variations of passages of Lorem Ipsum available.[/trx_tab]
[/trx_tabs]
*/

if (!function_exists('jardiwinery_sc_tabs')) {	
	function jardiwinery_sc_tabs($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"scroll" => "no",
			"style" => "1",
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
	
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width);
	
		if (!jardiwinery_param_is_off($scroll)) jardiwinery_enqueue_slider();
		if (empty($id)) $id = 'sc_tabs_'.str_replace('.', '', mt_rand());
	
		jardiwinery_storage_set('sc_tab_data', array(
			'counter'=> 0,
            'scroll' => $scroll,
            'height' => jardiwinery_prepare_css_value($height),
            'id'     => $id,
            'titles' => array()
            )
        );
	
		$content = do_shortcode($content);
	
		$sc_tab_titles = jardiwinery_storage_get_array('sc_tab_data', 'titles');
	
		$initial = max(1, min(count($sc_tab_titles), (int) $initial));
	
		$tabs_output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
							. ' class="sc_tabs sc_tabs_style_'.esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
							. ' data-active="' . ($initial-1) . '"'
							. '>'
						.'<ul class="sc_tabs_titles">';
		$titles_output = '';
		for ($i = 0; $i < count($sc_tab_titles); $i++) {
			$classes = array('sc_tabs_title');
			if ($i == 0) $classes[] = 'first';
			else if ($i == count($sc_tab_titles) - 1) $classes[] = 'last';
			$titles_output .= '<li class="'.join(' ', $classes).'">'
								. '<a href="#'.esc_attr($sc_tab_titles[$i]['id']).'" class="theme_button" id="'.esc_attr($sc_tab_titles[$i]['id']).'_tab">' . ($sc_tab_titles[$i]['title']) . '</a>'
								. '</li>';
		}
	
		wp_enqueue_script('jquery-ui-tabs', false, array('jquery','jquery-ui-core'), null, true);
		wp_enqueue_script('jquery-effects-fade', false, array('jquery','jquery-effects-core'), null, true);
	
		$tabs_output .= $titles_output
			. '</ul>' 
			. ($content)
			.'</div>';
		return apply_filters('jardiwinery_shortcode_output', $tabs_output, 'trx_tabs', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_tabs", "jardiwinery_sc_tabs");
}


if (!function_exists('jardiwinery_sc_tab')) {	
	function jardiwinery_sc_tab($atts, $content = null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"tab_id" => "",		// get it from VC
			"title" => "",		// get it from VC
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		jardiwinery_storage_inc_array('sc_tab_data', 'counter');
		$counter = jardiwinery_storage_get_array('sc_tab_data', 'counter');
		if (empty($id))
			$id = !empty($tab_id) ? $tab_id : jardiwinery_storage_get_array('sc_tab_data', 'id').'_'.intval($counter);
		$sc_tab_titles = jardiwinery_storage_get_array('sc_tab_data', 'titles');
		if (isset($sc_tab_titles[$counter-1])) {
			$sc_tab_titles[$counter-1]['id'] = $id;
			if (!empty($title))
				$sc_tab_titles[$counter-1]['title'] = $title;
		} else {
			$sc_tab_titles[] = array(
				'id' => $id,
				'title' => $title
			);
		}
		jardiwinery_storage_set_array('sc_tab_data', 'titles', $sc_tab_titles);
		$output = '<div id="'.esc_attr($id).'"'
					.' class="sc_tabs_content' 
						. ($counter % 2 == 1 ? ' odd' : ' even') 
						. ($counter == 1 ? ' first' : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. '>' 
				. (jardiwinery_param_is_on(jardiwinery_storage_get_array('sc_tab_data', 'scroll')) 
					? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical" style="height:'.(jardiwinery_storage_get_array('sc_tab_data', 'height') != '' ? jardiwinery_storage_get_array('sc_tab_data', 'height') : '200px').';"><div class="sc_scroll_wrapper swiper-wrapper"><div class="sc_scroll_slide swiper-slide">' 
					: '')
				. do_shortcode($content) 
				. (jardiwinery_param_is_on(jardiwinery_storage_get_array('sc_tab_data', 'scroll')) 
					? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical '.esc_attr($id).'_scroll_bar"></div></div>' 
					: '')
			. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_tab', $atts, $content);
	}
	jardiwinery_require_shortcode("trx_tab", "jardiwinery_sc_tab");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_tabs_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_tabs_reg_shortcodes');
	function jardiwinery_sc_tabs_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_tabs", array(
			"title" => esc_html__("Tabs", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert tabs in your page (post)", 'jardiwinery') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Tabs style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select style for tabs items", 'jardiwinery') ),
					"value" => 1,
					"options" => jardiwinery_get_list_styles(1, 2),
					"type" => "radio"
				),
				"initial" => array(
					"title" => esc_html__("Initially opened tab", 'jardiwinery'),
					"desc" => wp_kses_data( __("Number of initially opened tab", 'jardiwinery') ),
					"divider" => true,
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'jardiwinery'),
					"desc" => wp_kses_data( __("Use scroller to show tab content (height parameter required)", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
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
			),
			"children" => array(
				"name" => "trx_tab",
				"title" => esc_html__("Tab", 'jardiwinery'),
				"desc" => wp_kses_data( __("Tab item", 'jardiwinery') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Tab title", 'jardiwinery'),
						"desc" => wp_kses_data( __("Current tab title", 'jardiwinery') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Tab content", 'jardiwinery'),
						"desc" => wp_kses_data( __("Current tab content", 'jardiwinery') ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => jardiwinery_get_sc_param('id'),
					"class" => jardiwinery_get_sc_param('class'),
					"css" => jardiwinery_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_tabs_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_tabs_reg_shortcodes_vc');
	function jardiwinery_sc_tabs_reg_shortcodes_vc() {
	
		$tab_id_1 = 'sc_tab_'.time() . '_1_' . rand( 0, 100 );
		$tab_id_2 = 'sc_tab_'.time() . '_2_' . rand( 0, 100 );
		vc_map( array(
			"base" => "trx_tabs",
			"name" => esc_html__("Tabs", 'jardiwinery'),
			"desc" => wp_kses_data( __("Tabs", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_tabs',
			"class" => "trx_sc_collection trx_sc_tabs",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_tab'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Tabs style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select style of tabs items", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened tab", 'jardiwinery'),
					"desc" => wp_kses_data( __("Number of initially opened tab", 'jardiwinery') ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Scroller", 'jardiwinery'),
					"desc" => wp_kses_data( __("Use scroller to show tab content (height parameter required)", 'jardiwinery') ),
					"class" => "",
					"value" => array("Use scroller" => "yes" ),
					"type" => "checkbox"
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
			'default_content' => '
				[trx_tab title="' . esc_html__( 'Tab 1', 'jardiwinery' ) . '" tab_id="'.esc_attr($tab_id_1).'"][/trx_tab]
				[trx_tab title="' . esc_html__( 'Tab 2', 'jardiwinery' ) . '" tab_id="'.esc_attr($tab_id_2).'"][/trx_tab]
			',
			"custom_markup" => '
				<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
					<ul class="tabs_controls">
					</ul>
					%content%
				</div>
			',
			'js_view' => 'VcTrxTabsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_tab",
			"name" => esc_html__("Tab item", 'jardiwinery'),
			"desc" => wp_kses_data( __("Single tab item", 'jardiwinery') ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_tab",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_tab',
			"as_child" => array('only' => 'trx_tabs'),
			"as_parent" => array('except' => 'trx_tabs'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Tab title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title for current tab", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "tab_id",
					"heading" => esc_html__("Tab ID", 'jardiwinery'),
					"desc" => wp_kses_data( __("ID for current tab (required). Please, start it from letter.", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css')
			),
		  'js_view' => 'VcTrxTabView'
		) );
		class WPBakeryShortCode_Trx_Tabs extends JARDIWINERY_VC_ShortCodeTabs {}
		class WPBakeryShortCode_Trx_Tab extends JARDIWINERY_VC_ShortCodeTab {}
	}
}
?>