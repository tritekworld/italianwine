<?php
/**
 * JardiWinery Framework: Team support
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Theme init
if (!function_exists('jardiwinery_team_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_team_theme_setup', 1 );
	function jardiwinery_team_theme_setup() {

		// Add item in the admin menu
		add_action('trx_utils_filter_override_options',						'jardiwinery_team_add_override_options');

		// Save data from override options
		add_action('save_post',								'jardiwinery_team_save_data');
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('jardiwinery_filter_get_blog_type',			'jardiwinery_team_get_blog_type', 9, 2);
		add_filter('jardiwinery_filter_get_blog_title',		'jardiwinery_team_get_blog_title', 9, 2);
		add_filter('jardiwinery_filter_get_current_taxonomy',	'jardiwinery_team_get_current_taxonomy', 9, 2);
		add_filter('jardiwinery_filter_is_taxonomy',			'jardiwinery_team_is_taxonomy', 9, 2);
		add_filter('jardiwinery_filter_get_stream_page_title',	'jardiwinery_team_get_stream_page_title', 9, 2);
		add_filter('jardiwinery_filter_get_stream_page_link',	'jardiwinery_team_get_stream_page_link', 9, 2);
		add_filter('jardiwinery_filter_get_stream_page_id',	'jardiwinery_team_get_stream_page_id', 9, 2);
		add_filter('jardiwinery_filter_query_add_filters',		'jardiwinery_team_query_add_filters', 9, 2);
		add_filter('jardiwinery_filter_detect_inheritance_key','jardiwinery_team_detect_inheritance_key', 9, 1);

		// Extra column for team members lists
		if (jardiwinery_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-team_columns',			'jardiwinery_post_add_options_column', 9);
			add_filter('manage_team_posts_custom_column',	'jardiwinery_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_team] and [trx_team_item]
		add_action('jardiwinery_action_shortcodes_list',		'jardiwinery_team_reg_shortcodes');
		if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
			add_action('jardiwinery_action_shortcodes_list_vc','jardiwinery_team_reg_shortcodes_vc');

		// Meta box fields
		jardiwinery_storage_set('team_override_options', array(
			'id' => 'team-override-options',
			'title' => esc_html__('Team Member Details', 'jardiwinery'),
			'page' => 'team',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"team_member_position" => array(
					"title" => esc_html__('Position',  'jardiwinery'),
					"desc" => wp_kses_data( __("Position of the team member", 'jardiwinery') ),
					"class" => "team_member_position",
					"std" => "",
					"type" => "text"),
				"team_member_email" => array(
					"title" => esc_html__("E-mail",  'jardiwinery'),
					"desc" => wp_kses_data( __("E-mail of the team member - need to take Gravatar (if registered)", 'jardiwinery') ),
					"class" => "team_member_email",
					"std" => "",
					"type" => "text"),
				"team_member_link" => array(
					"title" => esc_html__('Link to profile',  'jardiwinery'),
					"desc" => wp_kses_data( __("URL of the team member profile page (if not this page)", 'jardiwinery') ),
					"class" => "team_member_link",
					"std" => "",
					"type" => "text"),
				"team_member_socials" => array(
					"title" => esc_html__("Social links",  'jardiwinery'),
					"desc" => wp_kses_data( __("Links to the social profiles of the team member", 'jardiwinery') ),
					"class" => "team_member_email",
					"std" => "",
					"type" => "social")
				)
			)
		);
		
		// Add supported data types
		jardiwinery_theme_support_pt('team');
		jardiwinery_theme_support_tx('team_group');
	}
}

if ( !function_exists( 'jardiwinery_team_settings_theme_setup2' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_team_settings_theme_setup2', 3 );
	function jardiwinery_team_settings_theme_setup2() {
		// Add post type 'team' and taxonomy 'team_group' into theme inheritance list
		jardiwinery_add_theme_inheritance( array('team' => array(
			'stream_template' => 'blog-team',
			'single_template' => 'single-team',
			'taxonomy' => array('team_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('team'),
			'override' => 'custom'
			) )
		);
	}
}


// Add override options
if (!function_exists('jardiwinery_team_add_override_options')) {
    //add_action('trx_utils_filter_override_options', 'jardiwinery_team_add_override_options');
    function jardiwinery_team_add_override_options($boxes = array()) {
        $boxes[] = array_merge(jardiwinery_storage_get('team_override_options'), array('callback' => 'jardiwinery_team_show_override_options'));
        return $boxes;
    }
}


// Callback function to show fields in override options
if (!function_exists('jardiwinery_team_show_override_options')) {
	function jardiwinery_team_show_override_options() {
		global $post;

		$data = get_post_meta($post->ID, jardiwinery_storage_get('options_prefix').'_team_data', true);
		$fields = jardiwinery_storage_get_array('team_override_options', 'fields');
		?>
		<input type="hidden" name="override_options_team_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
		<table class="team_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="team_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'team_member_socials') {
							$socials_type = jardiwinery_get_theme_setting('socials_type');
							$social_list = jardiwinery_get_theme_option('social_icons');
							if (is_array($social_list) && count($social_list) > 0) {
								foreach ($social_list as $soc) {
									if ($socials_type == 'icons') {
										$parts = explode('-', $soc['icon'], 2);
										$sn = isset($parts[1]) ? $parts[1] : $soc['icon'];
									} else {
										$sn = basename($soc['icon']);
										$sn = jardiwinery_substr($sn, 0, jardiwinery_strrpos($sn, '.'));
										if (($pos=jardiwinery_strrpos($sn, '_'))!==false)
											$sn = jardiwinery_substr($sn, 0, $pos);
									}   
									$link = isset($meta[$sn]) ? $meta[$sn] : '';
									?>
									<label for="<?php echo esc_attr(($id).'_'.($sn)); ?>"><?php echo esc_attr(jardiwinery_strtoproper($sn)); ?></label><br>
									<input type="text" name="<?php echo esc_attr($id); ?>[<?php echo esc_attr($sn); ?>]" id="<?php echo esc_attr(($id).'_'.($sn)); ?>" value="<?php echo esc_attr($link); ?>" size="30" /><br>
									<?php
								}
							}
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
							<?php
						}
						?>
						<br><small><?php echo esc_attr($field['desc']); ?></small>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from override options
if (!function_exists('jardiwinery_team_save_data')) {
	//add_action('save_post', 'jardiwinery_team_save_data');
	function jardiwinery_team_save_data($post_id) {
		// verify nonce
		if ( !wp_verify_nonce( jardiwinery_get_value_gp('override_options_team_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='team' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = jardiwinery_storage_get_array('team_override_options', 'fields');

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				if (isset($_POST[$id])) {
					if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
						foreach ($_POST[$id] as $sn=>$link) {
							$_POST[$id][$sn] = stripslashes($link);
						}
						$data[$id] = $_POST[$id];
					} else {
						$data[$id] = stripslashes($_POST[$id]);
					}
				}
			}
		}

		update_post_meta($post_id, jardiwinery_storage_get('options_prefix').'_team_data', $data);
	}
}



// Return true, if current page is team member page
if ( !function_exists( 'jardiwinery_is_team_page' ) ) {
	function jardiwinery_is_team_page() {
		$is = in_array(jardiwinery_storage_get('page_template'), array('blog-team', 'single-team'));
		if (!$is) {
			if (!jardiwinery_storage_empty('pre_query'))
				$is = jardiwinery_storage_call_obj_method('pre_query', 'get', 'post_type')=='team' 
						|| jardiwinery_storage_call_obj_method('pre_query', 'is_tax', 'team_group') 
						|| (jardiwinery_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=jardiwinery_get_template_page_id('blog-team')) > 0 
								&& $id==jardiwinery_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='team' || is_tax('team_group') || (is_page() && ($id=jardiwinery_get_template_page_id('blog-team')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'jardiwinery_team_detect_inheritance_key' ) ) {
	//add_filter('jardiwinery_filter_detect_inheritance_key',	'jardiwinery_team_detect_inheritance_key', 9, 1);
	function jardiwinery_team_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return jardiwinery_is_team_page() ? 'team' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'jardiwinery_team_get_blog_type' ) ) {
	//add_filter('jardiwinery_filter_get_blog_type',	'jardiwinery_team_get_blog_type', 9, 2);
	function jardiwinery_team_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('team_group') || is_tax('team_group'))
			$page = 'team_category';
		else if ($query && $query->get('post_type')=='team' || get_query_var('post_type')=='team')
			$page = $query && $query->is_single() || is_single() ? 'team_item' : 'team';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'jardiwinery_team_get_blog_title' ) ) {
	//add_filter('jardiwinery_filter_get_blog_title',	'jardiwinery_team_get_blog_title', 9, 2);
	function jardiwinery_team_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( jardiwinery_strpos($page, 'team')!==false ) {
			if ( $page == 'team_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'team_group' ), 'team_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'team_item' ) {
				$title = jardiwinery_get_post_title();
			} else {
				$title = esc_html__('All team', 'jardiwinery');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'jardiwinery_team_get_stream_page_title' ) ) {
	//add_filter('jardiwinery_filter_get_stream_page_title',	'jardiwinery_team_get_stream_page_title', 9, 2);
	function jardiwinery_team_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (jardiwinery_strpos($page, 'team')!==false) {
			if (($page_id = jardiwinery_team_get_stream_page_id(0, $page=='team' ? 'blog-team' : $page)) > 0)
				$title = jardiwinery_get_post_title($page_id);
			else
				$title = esc_html__('All team', 'jardiwinery');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'jardiwinery_team_get_stream_page_id' ) ) {
	//add_filter('jardiwinery_filter_get_stream_page_id',	'jardiwinery_team_get_stream_page_id', 9, 2);
	function jardiwinery_team_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (jardiwinery_strpos($page, 'team')!==false) $id = jardiwinery_get_template_page_id('blog-team');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'jardiwinery_team_get_stream_page_link' ) ) {
	//add_filter('jardiwinery_filter_get_stream_page_link',	'jardiwinery_team_get_stream_page_link', 9, 2);
	function jardiwinery_team_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (jardiwinery_strpos($page, 'team')!==false) {
			$id = jardiwinery_get_template_page_id('blog-team');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'jardiwinery_team_get_current_taxonomy' ) ) {
	//add_filter('jardiwinery_filter_get_current_taxonomy',	'jardiwinery_team_get_current_taxonomy', 9, 2);
	function jardiwinery_team_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( jardiwinery_strpos($page, 'team')!==false ) {
			$tax = 'team_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'jardiwinery_team_is_taxonomy' ) ) {
	//add_filter('jardiwinery_filter_is_taxonomy',	'jardiwinery_team_is_taxonomy', 9, 2);
	function jardiwinery_team_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('team_group')!='' || is_tax('team_group') ? 'team_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'jardiwinery_team_query_add_filters' ) ) {
	//add_filter('jardiwinery_filter_query_add_filters',	'jardiwinery_team_query_add_filters', 9, 2);
	function jardiwinery_team_query_add_filters($args, $filter) {
		if ($filter == 'team') {
			$args['post_type'] = 'team';
		}
		return $args;
	}
}





// ---------------------------------- [trx_team] ---------------------------------------

/*
[trx_team id="unique_id" columns="3" style="team-1|team-2|..."]
	[trx_team_item user="user_login"]
	[trx_team_item member="member_id"]
	[trx_team_item name="team member name" photo="url" email="address" position="director"]
[/trx_team]
*/
if ( !function_exists( 'jardiwinery_sc_team' ) ) {
	function jardiwinery_sc_team($atts, $content=null){	
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "team-3",
			"slider" => "no",
			"controls" => "no",
			"slides_space" => 0,
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 3,
			"columns" => 3,
			"offset" => "",
			"orderby" => "title",
			"order" => "asc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'jardiwinery'),
			"link" => '',
			"scheme" => '',
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

		if (empty($id)) $id = "sc_team_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && jardiwinery_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$class .= ($class ? ' ' : '') . jardiwinery_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = jardiwinery_get_css_dimensions_from_values($width);
		$hs = jardiwinery_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (jardiwinery_param_is_off($custom) && $count < $columns) $columns = $count;

		jardiwinery_storage_set('sc_team_data', array(
			'id' => $id,
            'style' => $style,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs
            )
        );

		if (jardiwinery_param_is_on($slider)) jardiwinery_enqueue_slider('swiper');
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_team_wrap'
						. ($scheme && !jardiwinery_param_is_off($scheme) && !jardiwinery_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_team sc_team_style_'.esc_attr($style)
							. ' ' . esc_attr(jardiwinery_get_template_property($style, 'container_classes'))
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
						.'"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!jardiwinery_param_is_off($animation) ? ' data-animation="'.esc_attr(jardiwinery_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_team_subtitle sc_item_subtitle">' . trim(jardiwinery_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_team_title sc_item_title">' . trim(jardiwinery_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_team_descr sc_item_descr">' . trim(jardiwinery_strmacros($description)) . '</div>' : '')
					. (jardiwinery_param_is_on($slider) 
						? ('<div class="sc_slider_swiper swiper-slider-container'
										. ' ' . esc_attr(jardiwinery_get_slider_controls_classes($controls))
										. (jardiwinery_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && jardiwinery_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && jardiwinery_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
									. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
									. ' data-slides-min-width="250"'
								. '>'
							. '<div class="slides swiper-wrapper">')
						: ($columns > 1 // && jardiwinery_get_template_property($style, 'need_columns')
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
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
				'post_type' => 'team',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = jardiwinery_query_add_sort_order($args, $orderby, $order);
			$args = jardiwinery_query_add_posts_and_cats($args, $ids, 'team', $cat, 'team_group');
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => jardiwinery_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					"columns_count" => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = jardiwinery_get_post_data($args);
				$post_meta = get_post_meta($post_data['post_id'], jardiwinery_storage_get('options_prefix').'_team_data', true);
				$thumb_sizes = jardiwinery_get_thumb_sizes(array('layout' => $style));
				$args['position'] = $post_meta['team_member_position'];
				$args['link'] = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : $post_data['post_link'];
				$args['email'] = $post_meta['team_member_email'];
				$args['photo'] = $post_data['post_thumb'];
				$mult = jardiwinery_get_retina_multiplier();
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*$mult);
				$args['socials'] = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$args['socials'] = jardiwinery_do_shortcode('[trx_socials size="tiny" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
	
				$output .= jardiwinery_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}

		if (jardiwinery_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {// && jardiwinery_get_template_property($style, 'need_columns')) {
			$output .= '</div>';
		}

		$output .= (!empty($link) ? '<div class="sc_team_button sc_item_button">'.jardiwinery_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_team -->'
				. '</div><!-- /.sc_team_wrap -->';
	
		// Add template specific scripts and styles
		do_action('jardiwinery_action_blog_scripts', $style);
	
		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_team', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_team', 'jardiwinery_sc_team');
}


if ( !function_exists( 'jardiwinery_sc_team_item' ) ) {
	function jardiwinery_sc_team_item($atts, $content=null) {
		if (jardiwinery_in_shortcode_blogger()) return '';
		extract(jardiwinery_html_decode(shortcode_atts( array(
			// Individual params
			"user" => "",
			"member" => "",
			"name" => "",
			"position" => "",
			"photo" => "",
			"email" => "",
			"link" => "",
			"socials" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		jardiwinery_storage_inc_array('sc_team_data', 'counter');
	
		$id = $id ? $id : (jardiwinery_storage_get_array('sc_team_data', 'id') ? jardiwinery_storage_get_array('sc_team_data', 'id') . '_' . jardiwinery_storage_get_array('sc_team_data', 'counter') : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = jardiwinery_get_thumb_sizes(array('layout' => jardiwinery_storage_get_array('sc_team_data', 'style')));
	
		if (!empty($socials)) $socials = jardiwinery_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($socials).'"][/trx_socials]');
	
		if (!empty($user) && $user!='none' && ($user_obj = get_user_by('login', $user)) != false) {
			$meta = get_user_meta($user_obj->ID);
			if (empty($email))		$email = $user_obj->data->user_email;
			if (empty($name))		$name = $user_obj->data->display_name;
			if (empty($position))	$position = isset($meta['user_position'][0]) ? $meta['user_position'][0] : '';
			if (empty($descr))		$descr = isset($meta['description'][0]) ? $meta['description'][0] : '';
			if (empty($socials))	$socials = jardiwinery_show_user_socials(array('author_id'=>$user_obj->ID, 'echo'=>false));
		}
	
		if (!empty($member) && $member!='none' && ($member_obj = (intval($member) > 0 ? get_post($member, OBJECT) : get_page_by_title($member, OBJECT, 'team'))) != null) {
			if (empty($name))		$name = $member_obj->post_title;
			if (empty($descr))		$descr = $member_obj->post_excerpt;
			$post_meta = get_post_meta($member_obj->ID, jardiwinery_storage_get('options_prefix').'_team_data', true);
			if (empty($position))	$position = $post_meta['team_member_position'];
			if (empty($link))		$link = !empty($post_meta['team_member_link']) ? $post_meta['team_member_link'] : get_permalink($member_obj->ID);
			if (empty($email))		$email = $post_meta['team_member_email'];
			if (empty($photo)) 		$photo = wp_get_attachment_url(get_post_thumbnail_id($member_obj->ID));
			if (empty($socials)) {
				$socials = '';
				$soc_list = $post_meta['team_member_socials'];
				if (is_array($soc_list) && count($soc_list)>0) {
					$soc_str = '';
					foreach ($soc_list as $sn=>$sl) {
						if (!empty($sl))
							$soc_str .= (!empty($soc_str) ? '|' : '') . ($sn) . '=' . ($sl);
					}
					if (!empty($soc_str))
						$socials = jardiwinery_do_shortcode('[trx_socials size="tiny" shape="round" socials="'.esc_attr($soc_str).'"][/trx_socials]');
				}
			}
		}
		if (empty($photo)) {
			$mult = jardiwinery_get_retina_multiplier();
			if (!empty($email)) $photo = get_avatar($email, $thumb_sizes['w']*$mult);
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = jardiwinery_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}
		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => jardiwinery_storage_get_array('sc_team_data', 'style'),
			'number' => jardiwinery_storage_get_array('sc_team_data', 'counter'),
			'columns_count' => jardiwinery_storage_get_array('sc_team_data', 'columns'),
			'slider' => jardiwinery_storage_get_array('sc_team_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => jardiwinery_storage_get_array('sc_team_data', 'css_wh'),
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo,
			'socials' => $socials
		);
		$output = jardiwinery_show_post_layout($args, $post_data);

		return apply_filters('jardiwinery_shortcode_output', $output, 'trx_team_item', $atts, $content);
	}
	jardiwinery_require_shortcode('trx_team_item', 'jardiwinery_sc_team_item');
}
// ---------------------------------- [/trx_team] ---------------------------------------



// Add [trx_team] and [trx_team_item] in the shortcodes list
if (!function_exists('jardiwinery_team_reg_shortcodes')) {
	//add_filter('jardiwinery_action_shortcodes_list',	'jardiwinery_team_reg_shortcodes');
	function jardiwinery_team_reg_shortcodes() {
		if (jardiwinery_storage_isset('shortcodes')) {

			$users = jardiwinery_get_list_users();
			$members = jardiwinery_get_list_posts(false, array(
				'post_type'=>'team',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$team_groups = jardiwinery_get_list_terms(false, 'team_group');
			$team_styles = jardiwinery_get_list_templates('team');
			$controls	 = jardiwinery_get_list_slider_controls();

			jardiwinery_sc_map_after('trx_tabs', array(

				// Team
				"trx_team" => array(
					"title" => esc_html__("Team", 'jardiwinery'),
					"desc" => wp_kses_data( __("Insert team in your page (post)", 'jardiwinery') ),
					"decorate" => true,
					"container" => false,
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
							"title" => esc_html__("Team style", 'jardiwinery'),
							"desc" => wp_kses_data( __("Select style to display team members", 'jardiwinery') ),
							"value" => "1",
							"type" => "select",
							"options" => $team_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'jardiwinery'),
							"desc" => wp_kses_data( __("How many columns use to show team members", 'jardiwinery') ),
							"value" => 3,
							"min" => 2,
							"max" => 5,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'jardiwinery'),
							"desc" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
							"value" => "",
							"type" => "checklist",
							"options" => jardiwinery_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'jardiwinery'),
							"desc" => wp_kses_data( __("Use slider to show team members", 'jardiwinery') ),
							"value" => "no",
							"type" => "switch",
							"options" => jardiwinery_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'jardiwinery'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'jardiwinery') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'jardiwinery'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'jardiwinery') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'jardiwinery'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'jardiwinery') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'jardiwinery'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'jardiwinery') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => jardiwinery_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'jardiwinery'),
							"desc" => wp_kses_data( __("Alignment of the team block", 'jardiwinery') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => jardiwinery_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'jardiwinery'),
							"desc" => wp_kses_data( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'jardiwinery') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => jardiwinery_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'jardiwinery'),
							"desc" => wp_kses_data( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'jardiwinery') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => jardiwinery_array_merge(array(0 => esc_html__('- Select category -', 'jardiwinery')), $team_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'jardiwinery'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jardiwinery') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'jardiwinery'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'jardiwinery') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'jardiwinery'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'jardiwinery') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => jardiwinery_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'jardiwinery'),
							"desc" => wp_kses_data( __("Select desired posts order", 'jardiwinery') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => jardiwinery_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'jardiwinery'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'jardiwinery') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
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
						"name" => "trx_team_item",
						"title" => esc_html__("Member", 'jardiwinery'),
						"desc" => wp_kses_data( __("Team member", 'jardiwinery') ),
						"container" => true,
						"params" => array(
							"user" => array(
								"title" => esc_html__("Registerd user", 'jardiwinery'),
								"desc" => wp_kses_data( __("Select one of registered users (if present) or put name, position, etc. in fields below", 'jardiwinery') ),
								"value" => "",
								"type" => "select",
								"options" => $users
							),
							"member" => array(
								"title" => esc_html__("Team member", 'jardiwinery'),
								"desc" => wp_kses_data( __("Select one of team members (if present) or put name, position, etc. in fields below", 'jardiwinery') ),
								"value" => "",
								"type" => "select",
								"options" => $members
							),
							"link" => array(
								"title" => esc_html__("Link", 'jardiwinery'),
								"desc" => wp_kses_data( __("Link on team member's personal page", 'jardiwinery') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"name" => array(
								"title" => esc_html__("Name", 'jardiwinery'),
								"desc" => wp_kses_data( __("Team member's name", 'jardiwinery') ),
								"divider" => true,
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'jardiwinery'),
								"desc" => wp_kses_data( __("Team member's position", 'jardiwinery') ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'jardiwinery'),
								"desc" => wp_kses_data( __("Team member's e-mail", 'jardiwinery') ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'jardiwinery'),
								"desc" => wp_kses_data( __("Team member's photo (avatar)", 'jardiwinery') ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"socials" => array(
								"title" => esc_html__("Socials", 'jardiwinery'),
								"desc" => wp_kses_data( __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", 'jardiwinery') ),
								"dependency" => array(
									'user' => array('is_empty', 'none'),
									'member' => array('is_empty', 'none')
								),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'jardiwinery'),
								"desc" => wp_kses_data( __("Team member's short description", 'jardiwinery') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => jardiwinery_get_sc_param('id'),
							"class" => jardiwinery_get_sc_param('class'),
							"animation" => jardiwinery_get_sc_param('animation'),
							"css" => jardiwinery_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_team] and [trx_team_item] in the VC shortcodes list
if (!function_exists('jardiwinery_team_reg_shortcodes_vc')) {
	//add_filter('jardiwinery_action_shortcodes_list_vc',	'jardiwinery_team_reg_shortcodes_vc');
	function jardiwinery_team_reg_shortcodes_vc() {

		$users = jardiwinery_get_list_users();
		$members = jardiwinery_get_list_posts(false, array(
			'post_type'=>'team',
			'orderby'=>'title',
			'order'=>'asc',
			'return'=>'title'
			)
		);
		$team_groups = jardiwinery_get_list_terms(false, 'team_group');
		$team_styles = jardiwinery_get_list_templates('team');
		$controls	 = jardiwinery_get_list_slider_controls();

		// Team
		vc_map( array(
				"base" => "trx_team",
				"name" => esc_html__("Team", 'jardiwinery'),
				"description" => wp_kses_data( __("Insert team members", 'jardiwinery') ),
				"category" => esc_html__('Content', 'jardiwinery'),
				'icon' => 'icon_trx_team',
				"class" => "trx_sc_columns trx_sc_team",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_team_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Team style", 'jardiwinery'),
						"description" => wp_kses_data( __("Select style to display team members", 'jardiwinery') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($team_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'jardiwinery'),
						"description" => wp_kses_data( __("Select color scheme for this block", 'jardiwinery') ),
						"class" => "",
						"value" => array_flip(jardiwinery_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'jardiwinery'),
						"description" => wp_kses_data( __("Use slider to show team members", 'jardiwinery') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'jardiwinery'),
						"class" => "",
						"std" => "no",
						"value" => array_flip(jardiwinery_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'jardiwinery'),
						"description" => wp_kses_data( __("Slider controls style and position", 'jardiwinery') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'jardiwinery'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'jardiwinery'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'jardiwinery') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'jardiwinery'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'jardiwinery'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'jardiwinery') ),
						"group" => esc_html__('Slider', 'jardiwinery'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'jardiwinery'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'jardiwinery') ),
						"group" => esc_html__('Slider', 'jardiwinery'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'jardiwinery'),
						"description" => wp_kses_data( __("Alignment of the team block", 'jardiwinery') ),
						"class" => "",
						"value" => array_flip(jardiwinery_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'jardiwinery'),
						"description" => wp_kses_data( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'jardiwinery') ),
						"class" => "",
						"value" => array("Custom members" => "yes" ),
						"type" => "checkbox"
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
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'jardiwinery'),
						"description" => wp_kses_data( __("Select category to show team members. If empty - select team members from any category (group) or from IDs list", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(jardiwinery_array_merge(array(0 => esc_html__('- Select category -', 'jardiwinery')), $team_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'jardiwinery'),
						"description" => wp_kses_data( __("How many columns use to show team members", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'jardiwinery'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'jardiwinery'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'jardiwinery'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "title",
						"class" => "",
						"value" => array_flip(jardiwinery_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'jardiwinery'),
						"description" => wp_kses_data( __("Select desired posts order", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "asc",
						"class" => "",
						"value" => array_flip(jardiwinery_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Team member's IDs list", 'jardiwinery'),
						"description" => wp_kses_data( __("Comma separated list of team members's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'jardiwinery') ),
						"group" => esc_html__('Query', 'jardiwinery'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
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
					jardiwinery_vc_width(),
					jardiwinery_vc_height(),
					jardiwinery_get_vc_param('margin_top'),
					jardiwinery_get_vc_param('margin_bottom'),
					jardiwinery_get_vc_param('margin_left'),
					jardiwinery_get_vc_param('margin_right'),
					jardiwinery_get_vc_param('id'),
					jardiwinery_get_vc_param('class'),
					jardiwinery_get_vc_param('animation'),
					jardiwinery_get_vc_param('css')
				),
				'default_content' => '
					[trx_team_item user="' . esc_html__( 'Member 1', 'jardiwinery' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 2', 'jardiwinery' ) . '"][/trx_team_item]
					[trx_team_item user="' . esc_html__( 'Member 4', 'jardiwinery' ) . '"][/trx_team_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_team_item",
				"name" => esc_html__("Team member", 'jardiwinery'),
				"description" => wp_kses_data( __("Team member - all data pull out from it account on your site", 'jardiwinery') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_team_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_team_item',
				"as_child" => array('only' => 'trx_team'),
				"as_parent" => array('except' => 'trx_team'),
				"params" => array(
					array(
						"param_name" => "user",
						"heading" => esc_html__("Registered user", 'jardiwinery'),
						"description" => wp_kses_data( __("Select one of registered users (if present) or put name, position, etc. in fields below", 'jardiwinery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($users),
						"type" => "dropdown"
					),
					array(
						"param_name" => "member",
						"heading" => esc_html__("Team member", 'jardiwinery'),
						"description" => wp_kses_data( __("Select one of team members (if present) or put name, position, etc. in fields below", 'jardiwinery') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($members),
						"type" => "dropdown"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'jardiwinery'),
						"description" => wp_kses_data( __("Link on team member's personal page", 'jardiwinery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'jardiwinery'),
						"description" => wp_kses_data( __("Team member's name", 'jardiwinery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", 'jardiwinery'),
						"description" => wp_kses_data( __("Team member's position", 'jardiwinery') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'jardiwinery'),
						"description" => wp_kses_data( __("Team member's e-mail", 'jardiwinery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Member's Photo", 'jardiwinery'),
						"description" => wp_kses_data( __("Team member's photo (avatar)", 'jardiwinery') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "socials",
						"heading" => esc_html__("Socials", 'jardiwinery'),
						"description" => wp_kses_data( __("Team member's socials icons: name=url|name=url... For example: facebook=http://facebook.com/myaccount|twitter=http://twitter.com/myaccount", 'jardiwinery') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					jardiwinery_get_vc_param('id'),
					jardiwinery_get_vc_param('class'),
					jardiwinery_get_vc_param('animation'),
					jardiwinery_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Team extends JARDIWINERY_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Team_Item extends JARDIWINERY_VC_ShortCodeCollection {}

	}
}
?>