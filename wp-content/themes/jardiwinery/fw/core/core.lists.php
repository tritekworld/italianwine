<?php
/**
 * JardiWinery Framework: return lists
 *
 * @package jardiwinery
 * @since jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'jardiwinery_get_list_styles' ) ) {
	function jardiwinery_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'jardiwinery'), $i);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'jardiwinery_get_list_margins' ) ) {
	function jardiwinery_get_list_margins($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'jardiwinery'),
				'tiny'		=> esc_html__('Tiny',		'jardiwinery'),
				'small'		=> esc_html__('Small',		'jardiwinery'),
				'medium'	=> esc_html__('Medium',		'jardiwinery'),
				'large'		=> esc_html__('Large',		'jardiwinery'),
				'huge'		=> esc_html__('Huge',		'jardiwinery'),
                'super_huge'		=> esc_html__('Super Huge',		'jardiwinery'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'jardiwinery'),
				'small-'	=> esc_html__('Small (negative)',	'jardiwinery'),
				'medium-'	=> esc_html__('Medium (negative)',	'jardiwinery'),
				'large-'	=> esc_html__('Large (negative)',	'jardiwinery'),
				'huge-'		=> esc_html__('Huge (negative)',	'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_margins', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'jardiwinery_get_list_animations' ) ) {
	function jardiwinery_get_list_animations($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'jardiwinery'),
				'bounced'		=> esc_html__('Bounced',		'jardiwinery'),
				'flash'			=> esc_html__('Flash',		'jardiwinery'),
				'flip'			=> esc_html__('Flip',		'jardiwinery'),
				'pulse'			=> esc_html__('Pulse',		'jardiwinery'),
				'rubberBand'	=> esc_html__('Rubber Band',	'jardiwinery'),
				'shake'			=> esc_html__('Shake',		'jardiwinery'),
				'swing'			=> esc_html__('Swing',		'jardiwinery'),
				'tada'			=> esc_html__('Tada',		'jardiwinery'),
				'wobble'		=> esc_html__('Wobble',		'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_animations', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'jardiwinery_get_list_line_styles' ) ) {
	function jardiwinery_get_list_line_styles($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'jardiwinery'),
				'dashed'=> esc_html__('Dashed', 'jardiwinery'),
				'dotted'=> esc_html__('Dotted', 'jardiwinery'),
				'double'=> esc_html__('Double', 'jardiwinery'),
				'image'	=> esc_html__('Image', 'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_line_styles', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'jardiwinery_get_list_animations_in' ) ) {
	function jardiwinery_get_list_animations_in($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'jardiwinery'),
				'bounceIn'			=> esc_html__('Bounce In',			'jardiwinery'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'jardiwinery'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'jardiwinery'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'jardiwinery'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'jardiwinery'),
				'fadeIn'			=> esc_html__('Fade In',			'jardiwinery'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'jardiwinery'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'jardiwinery'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'jardiwinery'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'jardiwinery'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'jardiwinery'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'jardiwinery'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'jardiwinery'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'jardiwinery'),
				'flipInX'			=> esc_html__('Flip In X',			'jardiwinery'),
				'flipInY'			=> esc_html__('Flip In Y',			'jardiwinery'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'jardiwinery'),
				'rotateIn'			=> esc_html__('Rotate In',			'jardiwinery'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','jardiwinery'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'jardiwinery'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'jardiwinery'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','jardiwinery'),
				'rollIn'			=> esc_html__('Roll In',			'jardiwinery'),
				'slideInUp'			=> esc_html__('Slide In Up',		'jardiwinery'),
				'slideInDown'		=> esc_html__('Slide In Down',		'jardiwinery'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'jardiwinery'),
				'slideInRight'		=> esc_html__('Slide In Right',		'jardiwinery'),
				'zoomIn'			=> esc_html__('Zoom In',			'jardiwinery'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'jardiwinery'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'jardiwinery'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'jardiwinery'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_animations_in', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'jardiwinery_get_list_animations_out' ) ) {
	function jardiwinery_get_list_animations_out($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'jardiwinery'),
				'bounceOut'			=> esc_html__('Bounce Out',			'jardiwinery'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'jardiwinery'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'jardiwinery'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'jardiwinery'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'jardiwinery'),
				'fadeOut'			=> esc_html__('Fade Out',			'jardiwinery'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'jardiwinery'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'jardiwinery'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'jardiwinery'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'jardiwinery'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'jardiwinery'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'jardiwinery'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'jardiwinery'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'jardiwinery'),
				'flipOutX'			=> esc_html__('Flip Out X',			'jardiwinery'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'jardiwinery'),
				'hinge'				=> esc_html__('Hinge Out',			'jardiwinery'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'jardiwinery'),
				'rotateOut'			=> esc_html__('Rotate Out',			'jardiwinery'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'jardiwinery'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'jardiwinery'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'jardiwinery'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'jardiwinery'),
				'rollOut'			=> esc_html__('Roll Out',		'jardiwinery'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'jardiwinery'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'jardiwinery'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'jardiwinery'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'jardiwinery'),
				'zoomOut'			=> esc_html__('Zoom Out',			'jardiwinery'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'jardiwinery'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'jardiwinery'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'jardiwinery'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_animations_out', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('jardiwinery_get_animation_classes')) {
	function jardiwinery_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return jardiwinery_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!jardiwinery_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'jardiwinery_get_list_categories' ) ) {
	function jardiwinery_get_list_categories($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'jardiwinery_get_list_terms' ) ) {
	function jardiwinery_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = jardiwinery_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = jardiwinery_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'jardiwinery_get_list_posts_types' ) ) {
	function jardiwinery_get_list_posts_types($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('jardiwinery_filter_list_post_types', array());
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'jardiwinery_get_list_posts' ) ) {
	function jardiwinery_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = jardiwinery_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'jardiwinery');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set($hash, $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'jardiwinery_get_list_pages' ) ) {
	function jardiwinery_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return jardiwinery_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'jardiwinery_get_list_users' ) ) {
	function jardiwinery_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = jardiwinery_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'jardiwinery');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_users', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'jardiwinery_get_list_sliders' ) ) {
	function jardiwinery_get_list_sliders($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_list_sliders', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'jardiwinery_get_list_slider_controls' ) ) {
	function jardiwinery_get_list_slider_controls($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'jardiwinery'),
				'side'		=> esc_html__('Side', 'jardiwinery'),
				'pagination'=> esc_html__('Pagination', 'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_slider_controls', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'jardiwinery_get_slider_controls_classes' ) ) {
	function jardiwinery_get_slider_controls_classes($controls) {
		if (jardiwinery_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'jardiwinery_get_list_popup_engines' ) ) {
	function jardiwinery_get_list_popup_engines($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'jardiwinery'),
				"magnific"	=> esc_html__("Magnific popup", 'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_popup_engines', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_menus' ) ) {
	function jardiwinery_get_list_menus($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'jardiwinery');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'jardiwinery_get_list_sidebars' ) ) {
	function jardiwinery_get_list_sidebars($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_sidebars'))=='') {
			if (($list = jardiwinery_storage_get('registered_sidebars'))=='') $list = array();
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'jardiwinery_get_list_sidebars_positions' ) ) {
	function jardiwinery_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'jardiwinery'),
				'left'  => esc_html__('Left',  'jardiwinery'),
				'right' => esc_html__('Right', 'jardiwinery')
				);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'jardiwinery_get_sidebar_class' ) ) {
	function jardiwinery_get_sidebar_class() {
		$sb_main = jardiwinery_get_custom_option('show_sidebar_main');
		$sb_outer = jardiwinery_get_custom_option('show_sidebar_outer');
		return (jardiwinery_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (jardiwinery_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_body_styles' ) ) {
	function jardiwinery_get_list_body_styles($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'jardiwinery'),
				'wide'	=> esc_html__('Wide',		'jardiwinery')
				);
			if (jardiwinery_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'jardiwinery');
				$list['fullscreen']	= esc_html__('Fullscreen',	'jardiwinery');
			}
			$list = apply_filters('jardiwinery_filter_list_body_styles', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_templates' ) ) {
	function jardiwinery_get_list_templates($mode='') {
		if (($list = jardiwinery_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = jardiwinery_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: jardiwinery_strtoproper($v['layout'])
										);
				}
			}
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_templates_blog' ) ) {
	function jardiwinery_get_list_templates_blog($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_templates_blog'))=='') {
			$list = jardiwinery_get_list_templates('blog');
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_templates_blogger' ) ) {
	function jardiwinery_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_templates_blogger'))=='') {
			$list = jardiwinery_array_merge(jardiwinery_get_list_templates('blogger'), jardiwinery_get_list_templates('blog'));
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_templates_single' ) ) {
	function jardiwinery_get_list_templates_single($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_templates_single'))=='') {
			$list = jardiwinery_get_list_templates('single');
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_templates_header' ) ) {
	function jardiwinery_get_list_templates_header($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_templates_header'))=='') {
			$list = jardiwinery_get_list_templates('header');
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_templates_forms' ) ) {
	function jardiwinery_get_list_templates_forms($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_templates_forms'))=='') {
			$list = jardiwinery_get_list_templates('forms');
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_article_styles' ) ) {
	function jardiwinery_get_list_article_styles($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'jardiwinery'),
				"stretch" => esc_html__('Stretch', 'jardiwinery')
				);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_post_formats_filters' ) ) {
	function jardiwinery_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'jardiwinery'),
				"thumbs"  => esc_html__('With thumbs', 'jardiwinery'),
				"reviews" => esc_html__('With reviews', 'jardiwinery'),
				"video"   => esc_html__('With videos', 'jardiwinery'),
				"audio"   => esc_html__('With audios', 'jardiwinery'),
				"gallery" => esc_html__('With galleries', 'jardiwinery')
				);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_portfolio_filters' ) ) {
	function jardiwinery_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'jardiwinery'),
				"tags"		=> esc_html__('Tags', 'jardiwinery'),
				"categories"=> esc_html__('Categories', 'jardiwinery')
				);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_hovers' ) ) {
	function jardiwinery_get_list_hovers($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'jardiwinery');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'jardiwinery');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'jardiwinery');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'jardiwinery');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'jardiwinery');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'jardiwinery');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'jardiwinery');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'jardiwinery');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'jardiwinery');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'jardiwinery');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'jardiwinery');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'jardiwinery');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'jardiwinery');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'jardiwinery');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'jardiwinery');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'jardiwinery');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'jardiwinery');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'jardiwinery');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'jardiwinery');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'jardiwinery');
			$list['square effect1']  = esc_html__('Square Effect 1',  'jardiwinery');
			$list['square effect2']  = esc_html__('Square Effect 2',  'jardiwinery');
			$list['square effect3']  = esc_html__('Square Effect 3',  'jardiwinery');
			$list['square effect5']  = esc_html__('Square Effect 5',  'jardiwinery');
			$list['square effect6']  = esc_html__('Square Effect 6',  'jardiwinery');
			$list['square effect7']  = esc_html__('Square Effect 7',  'jardiwinery');
			$list['square effect8']  = esc_html__('Square Effect 8',  'jardiwinery');
			$list['square effect9']  = esc_html__('Square Effect 9',  'jardiwinery');
			$list['square effect10'] = esc_html__('Square Effect 10',  'jardiwinery');
			$list['square effect11'] = esc_html__('Square Effect 11',  'jardiwinery');
			$list['square effect12'] = esc_html__('Square Effect 12',  'jardiwinery');
			$list['square effect13'] = esc_html__('Square Effect 13',  'jardiwinery');
			$list['square effect14'] = esc_html__('Square Effect 14',  'jardiwinery');
			$list['square effect15'] = esc_html__('Square Effect 15',  'jardiwinery');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'jardiwinery');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'jardiwinery');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'jardiwinery');
			$list['square effect_more']  = esc_html__('Square Effect More',  'jardiwinery');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'jardiwinery');
			$list = apply_filters('jardiwinery_filter_portfolio_hovers', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'jardiwinery_get_list_blog_counters' ) ) {
	function jardiwinery_get_list_blog_counters($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'jardiwinery'),
				'likes'		=> esc_html__('Likes', 'jardiwinery'),
				'rating'	=> esc_html__('Rating', 'jardiwinery'),
				'comments'	=> esc_html__('Comments', 'jardiwinery')
				);
			$list = apply_filters('jardiwinery_filter_list_blog_counters', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_alter_sizes' ) ) {
	function jardiwinery_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'jardiwinery'),
					'1_2' => esc_html__('1x2', 'jardiwinery'),
					'2_1' => esc_html__('2x1', 'jardiwinery'),
					'2_2' => esc_html__('2x2', 'jardiwinery'),
					'1_3' => esc_html__('1x3', 'jardiwinery'),
					'2_3' => esc_html__('2x3', 'jardiwinery'),
					'3_1' => esc_html__('3x1', 'jardiwinery'),
					'3_2' => esc_html__('3x2', 'jardiwinery'),
					'3_3' => esc_html__('3x3', 'jardiwinery')
					);
			$list = apply_filters('jardiwinery_filter_portfolio_alter_sizes', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_hovers_directions' ) ) {
	function jardiwinery_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'jardiwinery'),
				'right_to_left' => esc_html__('Right to Left',  'jardiwinery'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'jardiwinery'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'jardiwinery'),
				'scale_up'      => esc_html__('Scale Up',  'jardiwinery'),
				'scale_down'    => esc_html__('Scale Down',  'jardiwinery'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'jardiwinery'),
				'from_left_and_right' => esc_html__('From Left and Right',  'jardiwinery'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_portfolio_hovers_directions', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'jardiwinery_get_list_label_positions' ) ) {
	function jardiwinery_get_list_label_positions($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'jardiwinery'),
				'bottom'	=> esc_html__('Bottom',		'jardiwinery'),
				'left'		=> esc_html__('Left',		'jardiwinery'),
				'over'		=> esc_html__('Over',		'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_label_positions', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'jardiwinery_get_list_bg_image_positions' ) ) {
	function jardiwinery_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'jardiwinery'),
				'center top'   => esc_html__("Center Top", 'jardiwinery'),
				'right top'    => esc_html__("Right Top", 'jardiwinery'),
				'left center'  => esc_html__("Left Center", 'jardiwinery'),
				'center center'=> esc_html__("Center Center", 'jardiwinery'),
				'right center' => esc_html__("Right Center", 'jardiwinery'),
				'left bottom'  => esc_html__("Left Bottom", 'jardiwinery'),
				'center bottom'=> esc_html__("Center Bottom", 'jardiwinery'),
				'right bottom' => esc_html__("Right Bottom", 'jardiwinery')
			);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'jardiwinery_get_list_bg_image_repeats' ) ) {
	function jardiwinery_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'jardiwinery'),
				'repeat-x'	=> esc_html__('Repeat X', 'jardiwinery'),
				'repeat-y'	=> esc_html__('Repeat Y', 'jardiwinery'),
				'no-repeat'	=> esc_html__('No Repeat', 'jardiwinery')
			);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'jardiwinery_get_list_bg_image_attachments' ) ) {
	function jardiwinery_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'jardiwinery'),
				'fixed'		=> esc_html__('Fixed', 'jardiwinery'),
				'local'		=> esc_html__('Local', 'jardiwinery')
			);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'jardiwinery_get_list_bg_tints' ) ) {
	function jardiwinery_get_list_bg_tints($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'jardiwinery'),
				'light'	=> esc_html__('Light', 'jardiwinery'),
				'dark'	=> esc_html__('Dark', 'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_bg_tints', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_field_types' ) ) {
	function jardiwinery_get_list_field_types($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'jardiwinery'),
				'textarea' => esc_html__('Text Area','jardiwinery'),
				'password' => esc_html__('Password',  'jardiwinery'),
				'radio'    => esc_html__('Radio',  'jardiwinery'),
				'checkbox' => esc_html__('Checkbox',  'jardiwinery'),
				'select'   => esc_html__('Select',  'jardiwinery'),
				'date'     => esc_html__('Date','jardiwinery'),
				'time'     => esc_html__('Time','jardiwinery'),
				'button'   => esc_html__('Button','jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_field_types', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'jardiwinery_get_list_googlemap_styles' ) ) {
	function jardiwinery_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_googlemap_styles', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'jardiwinery_get_list_icons' ) ) {
	function jardiwinery_get_list_icons($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_icons'))=='') {
			$list = jardiwinery_parse_icons_classes(jardiwinery_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'jardiwinery_get_list_socials' ) ) {
	function jardiwinery_get_list_socials($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_socials'))=='') {
            $list = jardiwinery_get_list_images("images/socials", "png");
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'jardiwinery_get_list_yesno' ) ) {
	function jardiwinery_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'jardiwinery'),
			'no'  => esc_html__("No", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'jardiwinery_get_list_onoff' ) ) {
	function jardiwinery_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'jardiwinery'),
			"off" => esc_html__("Off", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'jardiwinery_get_list_showhide' ) ) {
	function jardiwinery_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'jardiwinery'),
			"hide" => esc_html__("Hide", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'jardiwinery_get_list_orderings' ) ) {
	function jardiwinery_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'jardiwinery'),
			"desc" => esc_html__("Descending", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'jardiwinery_get_list_directions' ) ) {
	function jardiwinery_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'jardiwinery'),
			"vertical" => esc_html__("Vertical", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'jardiwinery_get_list_shapes' ) ) {
	function jardiwinery_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'jardiwinery'),
			"square" => esc_html__("Square", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'jardiwinery_get_list_sizes' ) ) {
	function jardiwinery_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'jardiwinery'),
			"small"  => esc_html__("Small", 'jardiwinery'),
			"medium" => esc_html__("Medium", 'jardiwinery'),
			"large"  => esc_html__("Large", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'jardiwinery_get_list_controls' ) ) {
	function jardiwinery_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'jardiwinery'),
			"side" => esc_html__("Side", 'jardiwinery'),
			"bottom" => esc_html__("Bottom", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'jardiwinery_get_list_floats' ) ) {
	function jardiwinery_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'jardiwinery'),
			"left" => esc_html__("Float Left", 'jardiwinery'),
			"right" => esc_html__("Float Right", 'jardiwinery')
		);
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'jardiwinery_get_list_alignments' ) ) {
	function jardiwinery_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'jardiwinery'),
			"left" => esc_html__("Left", 'jardiwinery'),
			"center" => esc_html__("Center", 'jardiwinery'),
			"right" => esc_html__("Right", 'jardiwinery')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'jardiwinery');
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'jardiwinery_get_list_hpos' ) ) {
	function jardiwinery_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'jardiwinery');
		if ($center) $list['center'] = esc_html__("Center", 'jardiwinery');
		$list['right'] = esc_html__("Right", 'jardiwinery');
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'jardiwinery_get_list_vpos' ) ) {
	function jardiwinery_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'jardiwinery');
		if ($center) $list['center'] = esc_html__("Center", 'jardiwinery');
		$list['bottom'] = esc_html__("Bottom", 'jardiwinery');
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'jardiwinery_get_list_sortings' ) ) {
	function jardiwinery_get_list_sortings($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'jardiwinery'),
				"title" => esc_html__("Alphabetically", 'jardiwinery'),
				"views" => esc_html__("Popular (views count)", 'jardiwinery'),
				"comments" => esc_html__("Most commented (comments count)", 'jardiwinery'),
				"author_rating" => esc_html__("Author rating", 'jardiwinery'),
				"users_rating" => esc_html__("Visitors (users) rating", 'jardiwinery'),
				"random" => esc_html__("Random", 'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_list_sortings', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'jardiwinery_get_list_columns' ) ) {
	function jardiwinery_get_list_columns($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'jardiwinery'),
				"1_1" => esc_html__("100%", 'jardiwinery'),
				"1_2" => esc_html__("1/2", 'jardiwinery'),
				"1_3" => esc_html__("1/3", 'jardiwinery'),
				"2_3" => esc_html__("2/3", 'jardiwinery'),
				"1_4" => esc_html__("1/4", 'jardiwinery'),
				"3_4" => esc_html__("3/4", 'jardiwinery'),
				"1_5" => esc_html__("1/5", 'jardiwinery'),
				"2_5" => esc_html__("2/5", 'jardiwinery'),
				"3_5" => esc_html__("3/5", 'jardiwinery'),
				"4_5" => esc_html__("4/5", 'jardiwinery'),
				"1_6" => esc_html__("1/6", 'jardiwinery'),
				"5_6" => esc_html__("5/6", 'jardiwinery'),
				"1_7" => esc_html__("1/7", 'jardiwinery'),
				"2_7" => esc_html__("2/7", 'jardiwinery'),
				"3_7" => esc_html__("3/7", 'jardiwinery'),
				"4_7" => esc_html__("4/7", 'jardiwinery'),
				"5_7" => esc_html__("5/7", 'jardiwinery'),
				"6_7" => esc_html__("6/7", 'jardiwinery'),
				"1_8" => esc_html__("1/8", 'jardiwinery'),
				"3_8" => esc_html__("3/8", 'jardiwinery'),
				"5_8" => esc_html__("5/8", 'jardiwinery'),
				"7_8" => esc_html__("7/8", 'jardiwinery'),
				"1_9" => esc_html__("1/9", 'jardiwinery'),
				"2_9" => esc_html__("2/9", 'jardiwinery'),
				"4_9" => esc_html__("4/9", 'jardiwinery'),
				"5_9" => esc_html__("5/9", 'jardiwinery'),
				"7_9" => esc_html__("7/9", 'jardiwinery'),
				"8_9" => esc_html__("8/9", 'jardiwinery'),
				"1_10"=> esc_html__("1/10", 'jardiwinery'),
				"3_10"=> esc_html__("3/10", 'jardiwinery'),
				"7_10"=> esc_html__("7/10", 'jardiwinery'),
				"9_10"=> esc_html__("9/10", 'jardiwinery'),
				"1_11"=> esc_html__("1/11", 'jardiwinery'),
				"2_11"=> esc_html__("2/11", 'jardiwinery'),
				"3_11"=> esc_html__("3/11", 'jardiwinery'),
				"4_11"=> esc_html__("4/11", 'jardiwinery'),
				"5_11"=> esc_html__("5/11", 'jardiwinery'),
				"6_11"=> esc_html__("6/11", 'jardiwinery'),
				"7_11"=> esc_html__("7/11", 'jardiwinery'),
				"8_11"=> esc_html__("8/11", 'jardiwinery'),
				"9_11"=> esc_html__("9/11", 'jardiwinery'),
				"10_11"=> esc_html__("10/11", 'jardiwinery'),
				"1_12"=> esc_html__("1/12", 'jardiwinery'),
				"5_12"=> esc_html__("5/12", 'jardiwinery'),
				"7_12"=> esc_html__("7/12", 'jardiwinery'),
				"10_12"=> esc_html__("10/12", 'jardiwinery'),
				"11_12"=> esc_html__("11/12", 'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_list_columns', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'jardiwinery_get_list_dedicated_locations' ) ) {
	function jardiwinery_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'jardiwinery'),
				"center"  => esc_html__('Above the text of the post', 'jardiwinery'),
				"left"    => esc_html__('To the left the text of the post', 'jardiwinery'),
				"right"   => esc_html__('To the right the text of the post', 'jardiwinery'),
				"alter"   => esc_html__('Alternates for each post', 'jardiwinery')
			);
			$list = apply_filters('jardiwinery_filter_list_dedicated_locations', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'jardiwinery_get_post_format_name' ) ) {
	function jardiwinery_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'jardiwinery') : esc_html__('galleries', 'jardiwinery');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'jardiwinery') : esc_html__('videos', 'jardiwinery');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'jardiwinery') : esc_html__('audios', 'jardiwinery');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'jardiwinery') : esc_html__('images', 'jardiwinery');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'jardiwinery') : esc_html__('quotes', 'jardiwinery');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'jardiwinery') : esc_html__('links', 'jardiwinery');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'jardiwinery') : esc_html__('statuses', 'jardiwinery');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'jardiwinery') : esc_html__('asides', 'jardiwinery');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'jardiwinery') : esc_html__('chats', 'jardiwinery');
		else						$name = $single ? esc_html__('standard', 'jardiwinery') : esc_html__('standards', 'jardiwinery');
		return apply_filters('jardiwinery_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'jardiwinery_get_post_format_icon' ) ) {
	function jardiwinery_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('jardiwinery_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'jardiwinery_get_list_fonts_styles' ) ) {
	function jardiwinery_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','jardiwinery'),
				'u' => esc_html__('U', 'jardiwinery')
			);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'jardiwinery_get_list_fonts' ) ) {
	function jardiwinery_get_list_fonts($prepend_inherit=false) {
		if (($list = jardiwinery_storage_get('list_fonts'))=='') {
			$list = array();
			$list = jardiwinery_array_merge($list, jardiwinery_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>jardiwinery_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = jardiwinery_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('jardiwinery_filter_list_fonts', $list);
			if (jardiwinery_get_theme_setting('use_list_cache')) jardiwinery_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? jardiwinery_array_merge(array('inherit' => esc_html__("Inherit", 'jardiwinery')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'jardiwinery_get_list_font_faces' ) ) {
	function jardiwinery_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
        $fonts = jardiwinery_storage_get('required_custom_fonts');
		$list = array();
        if (is_array($fonts)) {
            foreach ($fonts as $font) {
                if (($url = jardiwinery_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
                    $list[sprintf(esc_html__('%s (uploaded font)', 'jardiwinery'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>