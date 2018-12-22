<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('jardiwinery_sc_slider_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_slider_theme_setup' );
	function jardiwinery_sc_slider_theme_setup() {
		add_action('jardiwinery_action_shortcodes_list', 		'jardiwinery_sc_slider_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_sc_slider_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('jardiwinery_sc_slider')) {	
	function jardiwinery_sc_slider($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "no",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => jardiwinery_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
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

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && jardiwinery_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		jardiwinery_storage_set('sc_slider_data', array(
			'engine' => $engine,
            'width'  => jardiwinery_prepare_css_value($width),
            'height' => jardiwinery_prepare_css_value($height),
            'links'  => jardiwinery_param_is_on($links),
            'bg_image' => jardiwinery_get_theme_setting('slides_type')=='bg',
            'crop_image' => $crop
            )
        );
	
		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
		
		$class2 = jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = jardiwinery_get_css_dimensions_from_values($width);
		$hs = jardiwinery_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
		
		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
		
		$output = (in_array($pagination, array('full', 'over')) 
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class2 ? ' '.esc_attr($class2) : '')
							. '"'
						. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
						. ($hs ? ' style="'.esc_attr($hs).'"' : '') 
						.'>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (jardiwinery_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (jardiwinery_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (jardiwinery_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. (jardiwinery_storage_get_array('sc_slider_data', 'bg_image') ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) 
							? ($class2 ? ' '.esc_attr($class2) : '') . ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							: '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per_view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && jardiwinery_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && jardiwinery_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';
	
		jardiwinery_enqueue_slider($engine);
	
		if ($engine=='revo') {
			if (!empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {
			
			$caption = '';
	
			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && jardiwinery_storage_get_array('sc_slider_data', 'bg_image') ? ' style="'.esc_attr($hs).'"' : '')
				.'>';
	
			$content = do_shortcode($content);
			
			if (jardiwinery_param_is_on($custom) && $content) {
				$output .= $content;
			} else {
				global $post;
		
				if (!empty($ids)) {
					$posts = explode(',', $ids);
					$count = count($posts);
				}
			
				$args = array(
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);
		
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
		
				$args = jardiwinery_query_add_sort_order($args, $orderby, $order);
				$args = jardiwinery_query_add_filters($args, 'thumbs');
				$args = jardiwinery_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
				$query = new WP_Query( $args );
	
				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating

				$post_rating = jardiwinery_storage_get('options_prefix').'_reviews_avg'.(jardiwinery_get_theme_option('reviews_first')=='author' ? '' : '2');
				
				while ( $query->have_posts() ) { 
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (jardiwinery_param_is_on($crop)) {
						$post_attachment = jardiwinery_storage_get_array('sc_slider_data', 'bg_image')
							? jardiwinery_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: jardiwinery_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!jardiwinery_storage_get_array('sc_slider_data', 'bg_image')) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';
	
					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(jardiwinery_get_file_dir('templates/_parts/widgets-posts.php'))) {
							jardiwinery_template_set_args('widgets-posts', array(
								'post_number' => $post_number,
								'post_rating' => $post_rating,
								'show_date' => $show_date,
								'show_image' => $show_image,
								'show_author' => $show_author,
								'show_links' => $show_links,
								'show_counters' => $show_counters
							));
							get_template_part(jardiwinery_get_file_slug('templates/_parts/widgets-posts.php'));
							$output .= jardiwinery_storage_get('widgets_posts_output');
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div' 
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. (jardiwinery_storage_get_array('sc_slider_data', 'bg_image') ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>' 
						. (jardiwinery_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!jardiwinery_storage_get_array('sc_slider_data', 'bg_image') ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!jardiwinery_param_is_off($titles)) {
						$post_hover_bg  = jardiwinery_get_scheme_color('text_link');
						$post_bg = '';
						if ($post_hover_bg!='' && !jardiwinery_is_inherit_option($post_hover_bg)) {
							$rgb = jardiwinery_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = jardiwinery_get_post_excerpt();
						if (jardiwinery_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = jardiwinery_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = jardiwinery_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(jardiwinery_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (jardiwinery_get_custom_option('show_reviews')=='yes' && jardiwinery_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = jardiwinery_reviews_marks_to_display(get_post_meta($post_id, jardiwinery_storage_get('options_prefix').'_reviews_avg'.((jardiwinery_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (jardiwinery_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(jardiwinery_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (jardiwinery_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (jardiwinery_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(jardiwinery_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (jardiwinery_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}
	
			$output .= '</div>';
			if ($engine=='swiper') {
				if (jardiwinery_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (jardiwinery_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}
		
		} else
			$output = '';
		
		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}
	
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_slider', 'jardiwinery_sc_slider');
}


if (!function_exists('jardiwinery_sc_slider_item')) {	
	function jardiwinery_sc_slider_item($atts, $content=null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}

		$engine = jardiwinery_storage_get_array('sc_slider_data', 'engine');
		$crop_image = jardiwinery_storage_get_array('sc_slider_data', 'crop_image');
		$bg_image = jardiwinery_storage_get_array('sc_slider_data', 'bg_image');
		$links = jardiwinery_storage_get_array('sc_slider_data', 'links');
		$width = jardiwinery_storage_get_array('sc_slider_data', 'width');
		$height = jardiwinery_storage_get_array('sc_slider_data', 'height');
	
		if ($src && jardiwinery_param_is_on($crop_image)) {
			$src = jardiwinery_storage_get_array('sc_slider_data', 'bg_image')
				? jardiwinery_get_resized_image_url($src, !empty($width) && jardiwinery_strpos($width, '%')===false ? $width : null, !empty($height) && jardiwinery_strpos($height, '%')===false ? $height : null)
				: jardiwinery_get_resized_image_tag($src, !empty($width) && jardiwinery_strpos($width, '%')===false ? $width : null, !empty($height) && jardiwinery_strpos($height, '%')===false ? $height : null);
		} else if ($src && !$bg_image) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}
	
		$css .= ($bg_image ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($width)  ? 'width:'  . esc_attr($width)  . ';' : '')
				. (!empty($height) ? 'height:' . esc_attr($height) . ';' : '');
	
		$content = do_shortcode($content);
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($engine).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>' 
				. ($src && jardiwinery_param_is_on($links) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$bg_image ? $src : $content)
				. ($src && jardiwinery_param_is_on($links) ? '</a>' : '')
			. '</div>';
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_slider_item', 'jardiwinery_sc_slider_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'jardiwinery_sc_slider_reg_shortcodes' ) ) {
	//add_action('jardiwinery_action_shortcodes_list', 'jardiwinery_sc_slider_reg_shortcodes');
	function jardiwinery_sc_slider_reg_shortcodes() {
	
		jardiwinery_sc_map("trx_slider", array(
			"title" => esc_html__("Slider", 'jardiwinery'),
			"desc" => wp_kses_data( __("Insert slider into your post (page)", 'jardiwinery') ),
			"decorate" => true,
			"container" => false,
			"params" => array_merge(array(
				"engine" => array(
					"title" => esc_html__("Slider engine", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'jardiwinery') ),
					"value" => "swiper",
					"type" => "checklist",
					"options" => jardiwinery_get_sc_param('sliders')
				),
				"align" => array(
					"title" => esc_html__("Float slider", 'jardiwinery'),
					"desc" => wp_kses_data( __("Float slider to left or right side", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => jardiwinery_get_sc_param('float')
				),
				"custom" => array(
					"title" => esc_html__("Custom slides", 'jardiwinery'),
					"desc" => wp_kses_data( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'jardiwinery') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				)
				),
				function_exists('jardiwinery_exists_revslider') && jardiwinery_exists_revslider() ? array(
				"alias" => array(
					"title" => esc_html__("Revolution slider alias", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select Revolution slider to display", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('revo')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"options" => jardiwinery_get_sc_param('revo_sliders')
				)) : array(), array(
				"cat" => array(
					"title" => esc_html__("Swiper: Category list", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select category to show post's images. If empty - select posts from any category or from IDs list", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => jardiwinery_array_merge(array(0 => esc_html__('- Select category -', 'jardiwinery')), jardiwinery_get_sc_param('categories'))
				),
				"count" => array(
					"title" => esc_html__("Swiper: Number of posts", 'jardiwinery'),
					"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Swiper: Offset before select posts", 'jardiwinery'),
					"desc" => wp_kses_data( __("Skip posts before select next part.", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Swiper: Post order by", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select desired posts sorting method", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "date",
					"type" => "select",
					"options" => jardiwinery_get_sc_param('sorting')
				),
				"order" => array(
					"title" => esc_html__("Swiper: Post order", 'jardiwinery'),
					"desc" => wp_kses_data( __("Select desired posts order", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => jardiwinery_get_sc_param('ordering')
				),
				"ids" => array(
					"title" => esc_html__("Swiper: Post IDs list", 'jardiwinery'),
					"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Swiper: Show slider controls", 'jardiwinery'),
					"desc" => wp_kses_data( __("Show arrows inside slider", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"pagination" => array(
					"title" => esc_html__("Swiper: Show slider pagination", 'jardiwinery'),
					"desc" => wp_kses_data( __("Show bullets for switch slides", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						'no'   => esc_html__('None', 'jardiwinery'),
						'yes'  => esc_html__('Dots', 'jardiwinery'), 
						'full' => esc_html__('Side Titles', 'jardiwinery'),
						'over' => esc_html__('Over Titles', 'jardiwinery')
					)
				),
				"titles" => array(
					"title" => esc_html__("Swiper: Show titles section", 'jardiwinery'),
					"desc" => wp_kses_data( __("Show section with post's title and short post's description", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						"no"    => esc_html__('Not show', 'jardiwinery'),
						"slide" => esc_html__('Show/Hide info', 'jardiwinery'),
						"fixed" => esc_html__('Fixed info', 'jardiwinery')
					)
				),
				"descriptions" => array(
					"title" => esc_html__("Swiper: Post descriptions", 'jardiwinery'),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"desc" => wp_kses_data( __("Show post's excerpt max length (characters)", 'jardiwinery') ),
					"value" => 0,
					"min" => 0,
					"max" => 1000,
					"step" => 10,
					"type" => "spinner"
				),
				"links" => array(
					"title" => esc_html__("Swiper: Post's title as link", 'jardiwinery'),
					"desc" => wp_kses_data( __("Make links from post's titles", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"crop" => array(
					"title" => esc_html__("Swiper: Crop images", 'jardiwinery'),
					"desc" => wp_kses_data( __("Crop images in each slide or live it unchanged", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"autoheight" => array(
					"title" => esc_html__("Swiper: Autoheight", 'jardiwinery'),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => jardiwinery_get_sc_param('yes_no')
				),
				"slides_per_view" => array(
					"title" => esc_html__("Swiper: Slides per view", 'jardiwinery'),
					"desc" => wp_kses_data( __("Slides per view showed in this slider", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 1,
					"min" => 1,
					"max" => 6,
					"step" => 1,
					"type" => "spinner"
				),
				"slides_space" => array(
					"title" => esc_html__("Swiper: Space between slides", 'jardiwinery'),
					"desc" => wp_kses_data( __("Size of space (in px) between slides", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"step" => 10,
					"type" => "spinner"
				),
				"interval" => array(
					"title" => esc_html__("Swiper: Slides change interval", 'jardiwinery'),
					"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'jardiwinery') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 5000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
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
			)),
			"children" => array(
				"name" => "trx_slider_item",
				"title" => esc_html__("Slide", 'jardiwinery'),
				"desc" => wp_kses_data( __("Slider item", 'jardiwinery') ),
				"container" => false,
				"params" => array(
					"src" => array(
						"title" => esc_html__("URL (source) for image file", 'jardiwinery'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current slide", 'jardiwinery') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
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
if ( !function_exists( 'jardiwinery_sc_slider_reg_shortcodes_vc' ) ) {
	//add_action('jardiwinery_action_shortcodes_list_vc', 'jardiwinery_sc_slider_reg_shortcodes_vc');
	function jardiwinery_sc_slider_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_slider",
			"name" => esc_html__("Slider", 'jardiwinery'),
			"description" => wp_kses_data( __("Insert slider", 'jardiwinery') ),
			"category" => esc_html__('Content', 'jardiwinery'),
			'icon' => 'icon_trx_slider',
			"class" => "trx_sc_collection trx_sc_slider",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_slider_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "engine",
					"heading" => esc_html__("Engine", 'jardiwinery'),
					"description" => wp_kses_data( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('sliders')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Float slider", 'jardiwinery'),
					"description" => wp_kses_data( __("Float slider to left or right side", 'jardiwinery') ),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom slides", 'jardiwinery'),
					"description" => wp_kses_data( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'jardiwinery') ),
					"class" => "",
					"value" => array(esc_html__('Custom slides', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				)
				),
				function_exists('jardiwinery_exists_revslider') && jardiwinery_exists_revslider() ? array(
				array(
					"param_name" => "alias",
					"heading" => esc_html__("Revolution slider alias", 'jardiwinery'),
					"description" => wp_kses_data( __("Select Revolution slider to display", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'engine',
						'value' => array('revo')
					),
					"value" => array_flip(jardiwinery_array_merge(array('none' => esc_html__('- Select slider -', 'jardiwinery')), jardiwinery_get_sc_param('revo_sliders'))),
					"type" => "dropdown"
				)) : array(), array(
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", 'jardiwinery'),
					"description" => wp_kses_data( __("Select category. If empty - show posts from any category or from IDs list", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(jardiwinery_array_merge(array(0 => esc_html__('- Select category -', 'jardiwinery')), jardiwinery_get_sc_param('categories'))),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Swiper: Number of posts", 'jardiwinery'),
					"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Swiper: Offset before select posts", 'jardiwinery'),
					"description" => wp_kses_data( __("Skip posts before select next part.", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Swiper: Post sorting", 'jardiwinery'),
					"description" => wp_kses_data( __("Select desired posts sorting method", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('sorting')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Swiper: Post order", 'jardiwinery'),
					"description" => wp_kses_data( __("Select desired posts order", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(jardiwinery_get_sc_param('ordering')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Swiper: Post IDs list", 'jardiwinery'),
					"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'jardiwinery') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Swiper: Show slider controls", 'jardiwinery'),
					"description" => wp_kses_data( __("Show arrows inside slider", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Show controls', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pagination",
					"heading" => esc_html__("Swiper: Show slider pagination", 'jardiwinery'),
					"description" => wp_kses_data( __("Show bullets or titles to switch slides", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"std" => "no",
					"value" => array(
							esc_html__('None', 'jardiwinery') => 'no',
							esc_html__('Dots', 'jardiwinery') => 'yes', 
							esc_html__('Side Titles', 'jardiwinery') => 'full',
							esc_html__('Over Titles', 'jardiwinery') => 'over'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "titles",
					"heading" => esc_html__("Swiper: Show titles section", 'jardiwinery'),
					"description" => wp_kses_data( __("Show section with post's title and short post's description", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(
							esc_html__('Not show', 'jardiwinery') => "no",
							esc_html__('Show/Hide info', 'jardiwinery') => "slide",
							esc_html__('Fixed info', 'jardiwinery') => "fixed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "descriptions",
					"heading" => esc_html__("Swiper: Post descriptions", 'jardiwinery'),
					"description" => wp_kses_data( __("Show post's excerpt max length (characters)", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Swiper: Post's title as link", 'jardiwinery'),
					"description" => wp_kses_data( __("Make links from post's titles", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Titles as a links', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "crop",
					"heading" => esc_html__("Swiper: Crop images", 'jardiwinery'),
					"description" => wp_kses_data( __("Crop images in each slide or live it unchanged", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Crop images', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Swiper: Autoheight", 'jardiwinery'),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Autoheight', 'jardiwinery') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "slides_per_view",
					"heading" => esc_html__("Swiper: Slides per view", 'jardiwinery'),
					"description" => wp_kses_data( __("Slides per view showed in this slider", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Swiper: Space between slides", 'jardiwinery'),
					"description" => wp_kses_data( __("Size of space (in px) between slides", 'jardiwinery') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Swiper: Slides change interval", 'jardiwinery'),
					"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'jardiwinery') ),
					"group" => esc_html__('Details', 'jardiwinery'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "5000",
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_slider_item",
			"name" => esc_html__("Slide", 'jardiwinery'),
			"description" => wp_kses_data( __("Slider item - single slide", 'jardiwinery') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_slider_item',
			"class" => "trx_sc_single trx_sc_slider_item",
			"as_child" => array('only' => 'trx_slider'),
			"as_parent" => array('except' => 'trx_slider'),
			"params" => array(
				array(
					"param_name" => "src",
					"heading" => esc_html__("URL (source) for image file", 'jardiwinery'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current slide", 'jardiwinery') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				jardiwinery_get_vc_param('id'),
				jardiwinery_get_vc_param('class'),
				jardiwinery_get_vc_param('css')
			)
		) );
		
		class WPBakeryShortCode_Trx_Slider extends JARDIWINERY_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Slider_Item extends JARDIWINERY_VC_ShortCodeSingle {}
	}
}
?>