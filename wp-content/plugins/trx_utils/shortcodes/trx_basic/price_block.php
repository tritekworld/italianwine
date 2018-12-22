<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_price_block_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_price_block_theme_setup' );
	function jardiwinery_sc_price_block_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_price_block_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('jardiwinery_sc_price_block')) {	
	function jardiwinery_sc_price_block($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
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
		$output = '';
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode(jardiwinery_sc_clear_around($content));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !jardiwinery_param_is_off($scheme) && !jardiwinery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title"><span>'.($title).'</span></div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_price_block', 'jardiwinery_sc_price_block');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_price_block_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_price_block_reg_shortcodes');
	function jardiwinery_sc_price_block_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_price_block", array(
			"title" => esc_html__("Price block", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert price block with title, price and description", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select style for this price block", 'jardiwinery') ),
					"value" => 1,
					"options" => jardiwinery_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'jardiwinery'),
					"desc" => wp_kses_data( __("Block title", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", 'jardiwinery'),
					"desc" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'jardiwinery'),
					"desc" => wp_kses_data( __('Select icon from Fontello icons set (placed before/instead price)',  'jardiwinery') ),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"money" => array(
					"title" => esc_html__("Money", 'jardiwinery'),
					"desc" => wp_kses_data( __("Money value (dot or comma separated)", 'jardiwinery') ),
					"divider" => true,
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
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('schemes')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Align price to left or right side", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('float')
				), 
				"_content_" => array(
					"title" => esc_html__("Description", 'jardiwinery'),
					"desc" => wp_kses_data( __("Description for this price block", 'jardiwinery') ),
					"divider" => true,
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
if ( !function_exists( 'jardiwinery_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_price_block_reg_shortcodes_vc');
	function jardiwinery_sc_price_block_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert price block with title, price and description", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select style of this price block", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(jardiwinery_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'jardiwinery'),
					"description" => wp_kses_data( __("Block title", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'jardiwinery'),
					"description" => wp_kses_data( __("URL for link from button (at bottom of the block)", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", 'jardiwinery'),
					"description" => wp_kses_data( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon from Fontello icons set (placed before/instead price)", 'jardiwinery') ),
					"class" => "",
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", 'jardiwinery'),
					"description" => wp_kses_data( __("Money value (dot or comma separated)", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", 'jardiwinery'),
					"description" => wp_kses_data( __("Currency character", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'jardiwinery'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", 'jardiwinery'),
					"description" => wp_kses_data( __("Period text (if need). For example: monthly, daily, etc.", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'jardiwinery'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('schemes')),
					"type" => "dropdown"
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
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", 'jardiwinery'),
					"description" => wp_kses_data( __("Description for this price block", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>