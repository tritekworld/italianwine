<?php
/**
 * JardiWinery Framework: Theme specific actions
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_core_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_core_theme_setup', 11 );
	function jardiwinery_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(jardiwinery_get_file_url('css/editor-style.css'));	
		
		// Make theme available for translation
		// Translations can be filed in the /languages directory
		load_theme_textdomain( 'jardiwinery', jardiwinery_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_action('wp_head',						'jardiwinery_wp_title_show');
				add_filter('wp_title',						'jardiwinery_wp_title_modify', 10, 2);
			}

			// Prepare logo text
			add_filter('jardiwinery_filter_prepare_logo_text',	'jardiwinery_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'jardiwinery_add_widget_number', 10, 1);
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'jardiwinery_core_frontend_scripts');
			add_action('wp_footer',		 					'jardiwinery_core_frontend_scripts_inline');
			add_action('jardiwinery_action_add_scripts_inline','jardiwinery_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('jardiwinery_action_prepare_globals',	'jardiwinery_core_prepare_globals');
		}

		// Frontend editor: Save post data
		add_action('wp_ajax_frontend_editor_save',		'jardiwinery_callback_frontend_editor_save');
		//add_action('wp_ajax_nopriv_frontend_editor_save', 'jardiwinery_callback_frontend_editor_save');

		// Frontend editor: Delete post
		add_action('wp_ajax_frontend_editor_delete', 	'jardiwinery_callback_frontend_editor_delete');
		//add_action('wp_ajax_nopriv_frontend_editor_delete', 'jardiwinery_callback_frontend_editor_delete');

		// Register theme specific nav menus
		jardiwinery_register_theme_menus();

		// Register theme specific sidebars
		jardiwinery_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function jardiwinery_core_init_theme() {
	if (jardiwinery_storage_get('theme_inited')===true) return;
	jardiwinery_storage_set('theme_inited', true);

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (jardiwinery_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	jardiwinery_load_custom_options();

	// Fire init theme actions (after custom options are loaded)
	do_action('jardiwinery_action_init_theme');

	// Prepare theme core global variables
	do_action('jardiwinery_action_prepare_globals');

	// Fire after init theme actions
	do_action('jardiwinery_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'jardiwinery_core_prepare_globals' ) ) {
	function jardiwinery_core_prepare_globals() {
		if (!is_admin()) {
			// Logo text and slogan
			jardiwinery_storage_set('logo_text', apply_filters('jardiwinery_filter_prepare_logo_text', jardiwinery_get_custom_option('logo_text')));
			jardiwinery_storage_set('logo_slogan', get_bloginfo('description'));
			
			// Logo image and icons
			$logo        = jardiwinery_get_logo_icon('logo');
			$logo_side   = jardiwinery_get_logo_icon('logo_side');
			$logo_fixed  = jardiwinery_get_logo_icon('logo_fixed');
			$logo_footer = jardiwinery_get_logo_icon('logo_footer');
			jardiwinery_storage_set('logo', $logo);
			jardiwinery_storage_set('logo_icon',   jardiwinery_get_logo_icon('logo_icon'));
			jardiwinery_storage_set('logo_side',   $logo_side   ? $logo_side   : $logo);
			jardiwinery_storage_set('logo_fixed',  $logo_fixed  ? $logo_fixed  : $logo);
			jardiwinery_storage_set('logo_footer', $logo_footer ? $logo_footer : $logo);
	
			$shop_mode = '';
			if (jardiwinery_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = jardiwinery_get_value_gpc('jardiwinery_shop_mode');
			if (empty($shop_mode))
				$shop_mode = jardiwinery_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			jardiwinery_storage_set('shop_mode', $shop_mode);
		}
	}
}


// Return url for the uploaded logo image
if ( !function_exists( 'jardiwinery_get_logo_icon' ) ) {
	function jardiwinery_get_logo_icon($slug) {
		$mult = jardiwinery_get_retina_multiplier();
		$logo_icon = '';
		if ($mult > 1) 			$logo_icon = jardiwinery_get_custom_option($slug.'_retina');
		if (empty($logo_icon))	$logo_icon = jardiwinery_get_custom_option($slug);
		return $logo_icon;
	}
}


// Display logo image with text and slogan (if specified)
if ( !function_exists( 'jardiwinery_show_logo' ) ) {
	function jardiwinery_show_logo($logo_main=true, $logo_fixed=false, $logo_footer=false, $logo_side=false, $logo_text=true, $logo_slogan=true) {
		if ($logo_main===true) 		$logo_main   = jardiwinery_storage_get('logo');
		if ($logo_fixed===true)		$logo_fixed  = jardiwinery_storage_get('logo_fixed');
		if ($logo_footer===true)	$logo_footer = jardiwinery_storage_get('logo_footer');
		if ($logo_side===true)		$logo_side   = jardiwinery_storage_get('logo_side');
		if ($logo_text===true)		$logo_text   = jardiwinery_storage_get('logo_text');
		if ($logo_slogan===true)	$logo_slogan = jardiwinery_storage_get('logo_slogan');
		if (empty($logo_main) && empty($logo_fixed) && empty($logo_footer) && empty($logo_side) && empty($logo_text))
			 $logo_text = get_bloginfo('name');
		if ($logo_main || $logo_fixed || $logo_footer || $logo_side || $logo_text) {
		?>
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>"><?php
				if (!empty($logo_main)) {
					$attr = jardiwinery_getimagesize($logo_main);
                    $alt = basename($logo_main);
                    $alt = substr($alt,0,strlen($alt) - 4);
					echo '<img src="'.esc_url($logo_main).'" class="logo_main" alt="'.esc_html($alt).'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_fixed)) {
					$attr = jardiwinery_getimagesize($logo_fixed);
                    $alt = basename($logo_fixed);
                    $alt = substr($alt,0,strlen($alt) - 4);
					echo '<img src="'.esc_url($logo_fixed).'" class="logo_fixed" alt="'.esc_html($alt).'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_footer)) {
					$attr = jardiwinery_getimagesize($logo_footer);
                    $alt = basename($logo_footer);
                    $alt = substr($alt,0,strlen($alt) - 4);
					echo '<img src="'.esc_url($logo_footer).'" class="logo_footer" alt="'.esc_html($alt).'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_side)) {
					$attr = jardiwinery_getimagesize($logo_side);
                    $alt = basename($logo_side);
                    $alt = substr($alt,0,strlen($alt) - 4);
					echo '<img src="'.esc_url($logo_side).'" class="logo_side" alt="'.esc_html($alt).'"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				echo !empty($logo_text) ? '<div class="logo_text">'.trim($logo_text).'</div>' : '';
				echo !empty($logo_slogan) ? '<br><div class="logo_slogan">' . esc_html($logo_slogan) . '</div>' : '';
			?></a>
		</div>
		<?php 
		}
	} 
}


// Add menu locations
if ( !function_exists( 'jardiwinery_register_theme_menus' ) ) {
	function jardiwinery_register_theme_menus() {
		register_nav_menus(apply_filters('jardiwinery_filter_add_theme_menus', array(
			'menu_main'		=> esc_html__('Main Menu', 'jardiwinery'),
			'menu_user'		=> esc_html__('User Menu', 'jardiwinery'),
			'menu_footer'	=> esc_html__('Footer Menu', 'jardiwinery'),
			'menu_side'		=> esc_html__('Side Menu', 'jardiwinery')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'jardiwinery_register_theme_sidebars' ) ) {
    add_action('widgets_init', 'jardiwinery_register_theme_sidebars');
	function jardiwinery_register_theme_sidebars($sidebars=array()) {
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = jardiwinery_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'jardiwinery_filter_add_theme_sidebars', $sidebars );
		jardiwinery_storage_set('registered_sidebars', $sidebars);
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array_merge( array(
													'name'          => $name,
													'id'            => $id
												),
												jardiwinery_storage_get('widgets_args')
									)
				);
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'jardiwinery_core_frontend_scripts' ) ) {
	function jardiwinery_core_frontend_scripts() {
		
		// Modernizr will load in head before other scripts and styles
		// Use older version (from photostack)
		wp_enqueue_script( 'jardiwinery-core-modernizr-script', jardiwinery_get_file_url('js/photostack/modernizr.min.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = jardiwinery_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = jardiwinery_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !jardiwinery_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current theme fonts
		$theme_fonts = apply_filters('jardiwinery_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
			$google_fonts = '';
			foreach ($theme_fonts as $font=>$v) {
				if (isset($fonts[$font])) {
					$font_name = ($pos=jardiwinery_strpos($font,' ('))!==false ? jardiwinery_substr($font, 0, $pos) : $font;
					if (!empty($fonts[$font]['css'])) {
						$css = $fonts[$font]['css'];
						wp_enqueue_style( 'jardiwinery-font-'.str_replace(' ', '-', $font_name).'-style', $css, array(), null );
					} else {
						$google_fonts .= ($google_fonts ? '%7C' : '') // %7C = |
							. (!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':300,300italic,400,400italic,700,700italic');
					}
				}
			}
			if ($google_fonts)
				wp_enqueue_style( 'jardiwinery-font-google_fonts-style', jardiwinery_get_protocol() . '://fonts.googleapis.com/css?family=' . $google_fonts . '&subset=' . jardiwinery_get_theme_option('fonts_subset'), array(), null );
		}
		
		// Fontello styles must be loaded before main stylesheet
		wp_enqueue_style( 'jardiwinery-fontello-style',  jardiwinery_get_file_url('css/fontello/css/fontello.css'),  array(), null);

		// Main stylesheet
		wp_enqueue_style( 'jardiwinery-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (jardiwinery_get_theme_option('css_animation')=='yes' && (jardiwinery_get_theme_option('animation_on_mobile')=='yes' || !wp_is_mobile()) && !jardiwinery_vc_is_frontend())
			wp_enqueue_style( 'jardiwinery-animation-style',	jardiwinery_get_file_url('css/core.animation.css'), array(), null );

		// Theme stylesheets
		do_action('jardiwinery_action_add_styles');

		// Responsive
		if (jardiwinery_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = jardiwinery_param_is_off(jardiwinery_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			wp_enqueue_style( 'jardiwinery-responsive-style', jardiwinery_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('jardiwinery_action_add_responsive');
			$css = apply_filters('jardiwinery_filter_add_responsive_inline', '');
			if (!empty($css)) wp_add_inline_style( 'jardiwinery-responsive-style', $css );
		}

		// Disable loading JQuery UI CSS
		wp_deregister_style('jquery_ui');
		wp_deregister_style('date-picker-css');


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		wp_enqueue_script( 'superfish', jardiwinery_get_file_url('js/superfish.js'), array('jquery'), null, true );
		if (jardiwinery_get_theme_option('menu_slider')=='yes') {
			wp_enqueue_script( 'jardiwinery-slidemenu-script', jardiwinery_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
		}

		if ( is_single() && jardiwinery_get_custom_option('show_reviews')=='yes' ) {
			wp_enqueue_script( 'jardiwinery-core-reviews-script', jardiwinery_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
		}

		wp_enqueue_script( 'jardiwinery-core-utils-script',	jardiwinery_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		wp_enqueue_script( 'jardiwinery-core-init-script',	jardiwinery_get_file_url('js/core.init.js'), array('jquery'), null, true );
		wp_enqueue_script( 'jardiwinery-theme-init-script',	jardiwinery_get_file_url('js/theme.init.js'), array('jquery'), null, true );

		// Media elements library	
		if (jardiwinery_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
			wp_deregister_style('mediaelement');
			wp_deregister_style('wp-mediaelement');
		}
		
		// Video background
		if (jardiwinery_get_custom_option('show_video_bg') == 'yes' && jardiwinery_get_custom_option('video_bg_youtube_code') != '') {
			wp_enqueue_script( 'jardiwinery-video-bg-script', jardiwinery_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

        // Google map
        if ( jardiwinery_get_custom_option('show_googlemap')=='yes' ) {
            $api_key = jardiwinery_get_theme_option('api_google');
            wp_enqueue_script( 'googlemap', jardiwinery_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
            wp_enqueue_script( 'jardiwinery-googlemap-script', jardiwinery_get_file_url('js/core.googlemap.js'), array(), null, true );
        }


		// Social share buttons
		if (is_singular() && !jardiwinery_storage_get('blog_streampage') && jardiwinery_get_custom_option('show_share')!='hide') {
			wp_enqueue_script( 'jardiwinery-social-share-script', jardiwinery_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !jardiwinery_storage_get('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (jardiwinery_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(jardiwinery_get_file_dir('core/core.customizer/front.customizer.css')))
				wp_enqueue_style(  'jardiwinery-customizer-style',  jardiwinery_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(jardiwinery_get_file_dir('core/core.customizer/front.customizer.js')))
				wp_enqueue_script( 'jardiwinery-customizer-script', jardiwinery_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );
		}
		
		//Debug utils
		if (jardiwinery_get_theme_option('debug_mode')=='yes') {
			wp_enqueue_script( 'jardiwinery-core-debug-script', jardiwinery_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme scripts
		do_action('jardiwinery_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'jardiwinery_enqueue_slider' ) ) {
	function jardiwinery_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			wp_enqueue_style(  'jardiwinery-swiperslider-style', 			jardiwinery_get_file_url('js/swiper/swiper.css'), array(), null );
			// jQuery version of Swiper conflict with Revolution Slider!!! Use DOM version
			wp_enqueue_script( 'jardiwinery-swiperslider-script', 			jardiwinery_get_file_url('js/swiper/swiper.js'), array(), null, true );
		}
	}
}

//  Enqueue Photostack gallery
if ( !function_exists( 'jardiwinery_enqueue_polaroid' ) ) {
	function jardiwinery_enqueue_polaroid() {
		wp_enqueue_style(  'jardiwinery-polaroid-style', 	jardiwinery_get_file_url('js/photostack/component.css'), array(), null );
		wp_enqueue_script( 'jardiwinery-classie-script',		jardiwinery_get_file_url('js/photostack/classie.js'), array(), null, true );
		wp_enqueue_script( 'jardiwinery-polaroid-script',	jardiwinery_get_file_url('js/photostack/photostack.js'), array(), null, true );
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'jardiwinery_enqueue_messages' ) ) {
	function jardiwinery_enqueue_messages() {
		wp_enqueue_style(  'jardiwinery-messages-style',		jardiwinery_get_file_url('js/core.messages/core.messages.css'), array(), null );
		wp_enqueue_script( 'jardiwinery-messages-script',	jardiwinery_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'jardiwinery_enqueue_portfolio' ) ) {
	function jardiwinery_enqueue_portfolio($hover='') {
		wp_enqueue_style( 'jardiwinery-portfolio-style',  jardiwinery_get_file_url('css/core.portfolio.css'), array(), null );
		if (jardiwinery_strpos($hover, 'effect_dir')!==false)
			wp_enqueue_script( 'hoverdir', jardiwinery_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'jardiwinery_enqueue_diagram' ) ) {
	function jardiwinery_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') wp_enqueue_script( 'jardiwinery-diagram-chart-script',	jardiwinery_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') wp_enqueue_script( 'jardiwinery-diagram-raphael-script',	jardiwinery_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'jardiwinery_enqueue_popup' ) ) {
	function jardiwinery_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && jardiwinery_get_theme_option('popup_engine')=='pretty')) {
			wp_enqueue_style(  'jardiwinery-prettyphoto-style',	jardiwinery_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			wp_enqueue_script( 'jardiwinery-prettyphoto-script',	jardiwinery_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && jardiwinery_get_theme_option('popup_engine')=='magnific')) {
			wp_enqueue_style(  'jardiwinery-magnific-style',	jardiwinery_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			wp_enqueue_script( 'jardiwinery-magnific-script',jardiwinery_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && jardiwinery_get_theme_option('popup_engine')=='internal')) {
			jardiwinery_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook


if ( !function_exists( 'jardiwinery_core_frontend_scripts_inline' ) ) {
    function jardiwinery_core_frontend_scripts_inline() {
        do_action('jardiwinery_action_add_scripts_inline');
    }
}

//  Add property="stylesheet" into all tags <link> in the footer
if (!function_exists('jardiwinery_core_add_property_to_link')) {
	//add_filter('style_loader_tag', 'jardiwinery_core_add_property_to_link', 10, 3);
	function jardiwinery_core_add_property_to_link($link, $handle='', $href='') {
		return str_replace('<link ', '<link property="stylesheet" ', $link);
	}
}

//  Add inline scripts in the footer
if (!function_exists('jardiwinery_core_add_scripts_inline')) {
	function jardiwinery_core_add_scripts_inline() {

		$msg = jardiwinery_get_system_message(true); 
		if (!empty($msg['message'])) jardiwinery_enqueue_messages();

        echo '<'.'script'.'>'
			
			. "if (typeof JARDIWINERY_STORAGE == 'undefined') var JARDIWINERY_STORAGE = {};"
			
			// AJAX parameters
			. "JARDIWINERY_STORAGE['ajax_url']			 = '" . esc_url(admin_url('admin-ajax.php')) . "';"
			. "JARDIWINERY_STORAGE['ajax_nonce']		 = '" . esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))) . "';"
			
			// Site base url
			. "JARDIWINERY_STORAGE['site_url']			= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "JARDIWINERY_STORAGE['vc_edit_mode']		= " . (function_exists('jardiwinery_vc_is_frontend') && jardiwinery_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "JARDIWINERY_STORAGE['theme_font']		= '" . jardiwinery_get_custom_font_settings('p', 'font-family') . "';"
			
			// Theme colors
			. "JARDIWINERY_STORAGE['theme_color']		= '" . jardiwinery_get_scheme_color('text_dark') . "';"
			. "JARDIWINERY_STORAGE['theme_bg_color']	= '" . jardiwinery_get_scheme_color('bg_color') . "';"
			
			// Slider height
			. "JARDIWINERY_STORAGE['slider_height']	= " . max(100, jardiwinery_get_custom_option('slider_height')) . ";"
			
			// System message
			. "JARDIWINERY_STORAGE['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "JARDIWINERY_STORAGE['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "JARDIWINERY_STORAGE['toc_menu']		= '" . esc_attr(jardiwinery_get_custom_option('menu_toc')) . "';"
			. "JARDIWINERY_STORAGE['toc_menu_home']	= " . (jardiwinery_get_custom_option('menu_toc')!='hide' && jardiwinery_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "JARDIWINERY_STORAGE['toc_menu_top']	= " . (jardiwinery_get_custom_option('menu_toc')!='hide' && jardiwinery_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "JARDIWINERY_STORAGE['menu_fixed']		= " . (jardiwinery_get_theme_option('menu_attachment')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "JARDIWINERY_STORAGE['menu_mobile']	= " . (jardiwinery_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) jardiwinery_get_theme_option('menu_mobile')) : 0) . ";"
			. "JARDIWINERY_STORAGE['menu_slider']     = " . (jardiwinery_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"
			
			// Right panel demo timer
			. "JARDIWINERY_STORAGE['demo_time']		= " . (jardiwinery_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) jardiwinery_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "JARDIWINERY_STORAGE['media_elements_enabled'] = " . (jardiwinery_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "JARDIWINERY_STORAGE['ajax_search_enabled'] 	= " . (jardiwinery_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "JARDIWINERY_STORAGE['ajax_search_min_length']	= " . min(3, jardiwinery_get_theme_option('ajax_search_min_length')) . ";"
			. "JARDIWINERY_STORAGE['ajax_search_delay']		= " . min(200, max(1000, jardiwinery_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "JARDIWINERY_STORAGE['css_animation']      = " . (jardiwinery_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "JARDIWINERY_STORAGE['menu_animation_in']  = '" . esc_attr(jardiwinery_get_theme_option('menu_animation_in')) . "';"
			. "JARDIWINERY_STORAGE['menu_animation_out'] = '" . esc_attr(jardiwinery_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "JARDIWINERY_STORAGE['popup_engine']	= '" . esc_attr(jardiwinery_get_theme_option('popup_engine')) . "';"

			// E-mail mask
			. "JARDIWINERY_STORAGE['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "JARDIWINERY_STORAGE['contacts_maxlength']	= " . intval(jardiwinery_get_theme_option('message_maxlength_contacts')) . ";"
			. "JARDIWINERY_STORAGE['comments_maxlength']	= " . intval(jardiwinery_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "JARDIWINERY_STORAGE['remember_visitors_settings']	= " . (jardiwinery_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "JARDIWINERY_STORAGE['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "JARDIWINERY_STORAGE['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "JARDIWINERY_STORAGE['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "JARDIWINERY_STORAGE['viewmore_busy']		= false;"
			. "JARDIWINERY_STORAGE['video_resize_inited']	= false;"
			. "JARDIWINERY_STORAGE['top_panel_height']		= 0;"

            . '<'.'/script'.'>';
	}
}

// Show content with the html layout (if not empty)
if ( !function_exists('jardiwinery_show_layout') ) {
    function jardiwinery_show_layout($str, $before='', $after='') {
        if ($str != '') {
            printf("%s%s%s", $before, $str, $after);
        }
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'jardiwinery_add_widget_number' ) ) {
	//add_filter('dynamic_sidebar_params', 'jardiwinery_add_widget_number', 10, 1);
	function jardiwinery_add_widget_number($prm) {
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = jardiwinery_storage_get('current_sidebar');
		if (empty($cur_sidebar)) $cur_sidebar = 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) jardiwinery_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Show <title> tag under old WP (version < 4.1)
if ( !function_exists( 'jardiwinery_wp_title_show' ) ) {
	// add_action('wp_head', 'jardiwinery_wp_title_show');
	function jardiwinery_wp_title_show() {
		?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}
}

// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'jardiwinery_wp_title_modify' ) ) {
	// add_filter( 'wp_title', 'jardiwinery_wp_title_modify', 10, 2 );
	function jardiwinery_wp_title_modify( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'jardiwinery' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'jardiwinery_add_mainmenu_classes' ) ) {
	// add_filter('wp_nav_menu_objects', 'jardiwinery_add_mainmenu_classes', 10, 2);
	function jardiwinery_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && jardiwinery_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = jardiwinery_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !jardiwinery_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'jardiwinery_callback_frontend_editor_save' ) ) {
	function jardiwinery_callback_frontend_editor_save() {

		if ( !wp_verify_nonce( jardiwinery_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( jardiwinery_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'jardiwinery');
			} else {
				$response['error'] = esc_html__('Post update error!', 'jardiwinery');
			}
		} else
			$response['error'] = esc_html__('Post update denied!', 'jardiwinery');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'jardiwinery_callback_frontend_editor_delete' ) ) {
	function jardiwinery_callback_frontend_editor_delete() {

		if ( !wp_verify_nonce( jardiwinery_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( jardiwinery_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'jardiwinery');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'jardiwinery');
			}
		} else
			$response['error'] = esc_html__('Post delete denied!', 'jardiwinery');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'jardiwinery_prepare_logo_text' ) ) {
	function jardiwinery_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>