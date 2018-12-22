<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_section_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_section_theme_setup' );
	function jardiwinery_sc_section_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_section_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

jardiwinery_storage_set('sc_section_dedicated', '');

if (!function_exists('jardiwinery_sc_section')) {	
	function jardiwinery_sc_section($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
            "section_style" => "",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'jardiwinery'),
			"link" => '',
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
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = jardiwinery_get_scheme_color('bg');
			$rgb = jardiwinery_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(jardiwinery_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!jardiwinery_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(jardiwinery_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !jardiwinery_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = jardiwinery_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && jardiwinery_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = jardiwinery_prepare_css_value($width);
		$height = jardiwinery_prepare_css_value($height);
	
		if ((!jardiwinery_param_is_off($scroll) || !jardiwinery_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!jardiwinery_param_is_off($scroll)) jardiwinery_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '')
                    . ($section_style ? ' ' . esc_attr($section_style) : '')
					. ($scheme && !jardiwinery_param_is_off($scheme) && !jardiwinery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (jardiwinery_param_is_on($scroll) && !jardiwinery_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || jardiwinery_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (jardiwinery_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (jardiwinery_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (jardiwinery_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (jardiwinery_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(jardiwinery_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h3 class="sc_section_title sc_item_title">' . trim(jardiwinery_strmacros($title)) . '</h3>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim($description) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.jardiwinery_do_shortcode('[trx_button link="'.esc_url($link).'"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (jardiwinery_param_is_on($pan) ? '</div>' : '')
					. (jardiwinery_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!jardiwinery_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || jardiwinery_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (jardiwinery_param_is_on($dedicated)) {
			if (jardiwinery_storage_get('sc_section_dedicated')=='') {
				jardiwinery_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_section', 'jardiwinery_sc_section');
}

if (!function_exists('jardiwinery_sc_block')) {	
	function jardiwinery_sc_block($atts, $content=null) {
		if (empty($atts)) $atts = array();
		$atts['class'] = (!empty($atts['class']) ? ' ' : '') . 'sc_section_block';
		return apply_filters('jardiwinery_shortcode_output', jardiwinery_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_block', 'jardiwinery_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_section_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_section_reg_shortcodes');
	function jardiwinery_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'jardiwinery'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'jardiwinery') ),
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
				"link" => array(
					"title" => esc_html__("Button URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'jardiwinery'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'jardiwinery'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'jardiwinery') ),
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select block alignment", 'jardiwinery') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'jardiwinery') ),
					"value" => "none",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'jardiwinery'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'jardiwinery'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'jardiwinery'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'jardiwinery') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => jardiwinery_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'jardiwinery'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'jardiwinery') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any background color for this section", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'jardiwinery') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'jardiwinery'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'jardiwinery') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'jardiwinery'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'jardiwinery') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'jardiwinery'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'jardiwinery') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'jardiwinery') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'jardiwinery'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'jardiwinery'),
					"desc" => wp_kses_data( __("Font weight of the text", 'jardiwinery') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'jardiwinery'),
						'300' => esc_html__('Light (300)', 'jardiwinery'),
						'400' => esc_html__('Normal (400)', 'jardiwinery'),
						'700' => esc_html__('Bold (700)', 'jardiwinery')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Content for section container", 'jardiwinery') ),
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
		);
		jardiwinery_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'jardiwinery');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'jardiwinery');
		jardiwinery_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_section_reg_shortcodes_vc');
	function jardiwinery_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'jardiwinery'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'jardiwinery'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'jardiwinery') => 'yes'),
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
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'jardiwinery'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('columns')),
					"type" => "dropdown"
				),
                array(
                    "param_name" => "section_style",
                    "heading" => esc_html__("Section style", 'jardiwinery'),
                    "description" => wp_kses_data( __("Select section style", 'jardiwinery') ),
                    "admin_label" => true,
                    "class" => "",
                    "value" => array(
                        esc_html__('Original', 'jardiwinery') => 'section_style_original',
                        esc_html__('Contacts block', 'jardiwinery') => 'section_style_contact',
                        esc_html__('Title with underline', 'jardiwinery') => 'section_style_underline',
                        esc_html__('Title align to left', 'jardiwinery') => 'section_style_left',
                        esc_html__('Section in inverse color', 'jardiwinery') => 'section_style_inverse'
                    ),
                    "type" => "dropdown"
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
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'jardiwinery'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'jardiwinery') ),
					"group" => esc_html__('Scroll', 'jardiwinery'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'jardiwinery'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'jardiwinery') ),
					"group" => esc_html__('Scroll', 'jardiwinery'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'jardiwinery'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'jardiwinery'),
					"value" => array_flip(jardiwinery_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'jardiwinery'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'jardiwinery'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(jardiwinery_get_sc_param('controls')),
					"type" => "dropdown"
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
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'jardiwinery'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'jardiwinery'),
					"description" => wp_kses_data( __("Any background color for this section", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'jardiwinery'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'jardiwinery'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'jardiwinery'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'jardiwinery'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'jardiwinery'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'jardiwinery') ),
					"group" => esc_html__('Colors and Images', 'jardiwinery'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'jardiwinery') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jardiwinery'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'jardiwinery'),
					"description" => wp_kses_data( __("Font weight of the text", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'jardiwinery') => 'inherit',
						esc_html__('Thin (100)', 'jardiwinery') => '100',
						esc_html__('Light (300)', 'jardiwinery') => '300',
						esc_html__('Normal (400)', 'jardiwinery') => '400',
						esc_html__('Bold (700)', 'jardiwinery') => '700'
					),
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'jardiwinery');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'jardiwinery') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends JARDIWINERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends JARDIWINERY_VC_ShortCodeCollection {}
	}
}
?>