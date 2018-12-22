<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_title_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_title_theme_setup' );
	function jardiwinery_sc_title_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_title_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('jardiwinery_sc_title')) {	
	function jardiwinery_sc_title($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
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
		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= jardiwinery_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !jardiwinery_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !jardiwinery_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(jardiwinery_strpos($image, 'http:')!==false ? $image : jardiwinery_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !jardiwinery_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_title', 'jardiwinery_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_title_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_title_reg_shortcodes');
	function jardiwinery_sc_title_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'jardiwinery'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'jardiwinery') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title content", 'jardiwinery') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title type (header level)", 'jardiwinery') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'jardiwinery'),
						'2' => esc_html__('Header 2', 'jardiwinery'),
						'3' => esc_html__('Header 3', 'jardiwinery'),
						'4' => esc_html__('Header 4', 'jardiwinery'),
						'5' => esc_html__('Header 5', 'jardiwinery'),
						'6' => esc_html__('Header 6', 'jardiwinery'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title style", 'jardiwinery') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'jardiwinery'),
						'underline' => esc_html__('Underline', 'jardiwinery'),
						'divider' => esc_html__('Divider', 'jardiwinery'),
						'iconed' => esc_html__('With icon (image)', 'jardiwinery')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'jardiwinery'),
					"desc" => wp_kses_data( __("Title text alignment", 'jardiwinery') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'jardiwinery'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'jardiwinery'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'jardiwinery') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'jardiwinery'),
						'100' => esc_html__('Thin (100)', 'jardiwinery'),
						'300' => esc_html__('Light (300)', 'jardiwinery'),
						'400' => esc_html__('Normal (400)', 'jardiwinery'),
						'600' => esc_html__('Semibold (600)', 'jardiwinery'),
						'700' => esc_html__('Bold (700)', 'jardiwinery'),
						'900' => esc_html__('Black (900)', 'jardiwinery')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select color for the title", 'jardiwinery') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'jardiwinery'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'jardiwinery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => jardiwinery_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'jardiwinery'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'jardiwinery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => jardiwinery_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'jardiwinery'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'jardiwinery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'jardiwinery'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'jardiwinery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'jardiwinery'),
						'medium' => esc_html__('Medium', 'jardiwinery'),
						'large' => esc_html__('Large', 'jardiwinery')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'jardiwinery'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'jardiwinery') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'jardiwinery'),
						'left' => esc_html__('Left', 'jardiwinery')
					)
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
if ( !function_exists( 'jardiwinery_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_title_reg_shortcodes_vc');
	function jardiwinery_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'jardiwinery'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'jardiwinery'),
					"description" => wp_kses_data( __("Title content", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'jardiwinery'),
					"description" => wp_kses_data( __("Title type (header level)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'jardiwinery') => '1',
						esc_html__('Header 2', 'jardiwinery') => '2',
						esc_html__('Header 3', 'jardiwinery') => '3',
						esc_html__('Header 4', 'jardiwinery') => '4',
						esc_html__('Header 5', 'jardiwinery') => '5',
						esc_html__('Header 6', 'jardiwinery') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'jardiwinery'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'jardiwinery') => 'regular',
						esc_html__('Underline', 'jardiwinery') => 'underline',
						esc_html__('Divider', 'jardiwinery') => 'divider',
						esc_html__('With icon (image)', 'jardiwinery') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'jardiwinery'),
					"description" => wp_kses_data( __("Title text alignment", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'jardiwinery'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'jardiwinery'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'jardiwinery') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'jardiwinery') => 'inherit',
						esc_html__('Thin (100)', 'jardiwinery') => '100',
						esc_html__('Light (300)', 'jardiwinery') => '300',
						esc_html__('Normal (400)', 'jardiwinery') => '400',
						esc_html__('Semibold (600)', 'jardiwinery') => '600',
						esc_html__('Bold (700)', 'jardiwinery') => '700',
						esc_html__('Black (900)', 'jardiwinery') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'jardiwinery'),
					"description" => wp_kses_data( __("Select color for the title", 'jardiwinery') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'jardiwinery'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => jardiwinery_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'jardiwinery'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'jardiwinery') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'jardiwinery'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => jardiwinery_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'jardiwinery') ),
					"group" => esc_html__('Icon &amp; Image', 'jardiwinery'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'jardiwinery'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'jardiwinery') ),
					"group" => esc_html__('Icon &amp; Image', 'jardiwinery'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'jardiwinery') => 'small',
						esc_html__('Medium', 'jardiwinery') => 'medium',
						esc_html__('Large', 'jardiwinery') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'jardiwinery'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'jardiwinery') ),
					"group" => esc_html__('Icon &amp; Image', 'jardiwinery'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'jardiwinery') => 'top',
						esc_html__('Left', 'jardiwinery') => 'left'
					),
					"type" => "dropdown"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('animation'),
				jardiwinery_get_vc_param('css'),
				jardiwinery_get_vc_param('margin_top'),
				jardiwinery_get_vc_param('margin_bottom'),
				jardiwinery_get_vc_param('margin_left'),
				jardiwinery_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>