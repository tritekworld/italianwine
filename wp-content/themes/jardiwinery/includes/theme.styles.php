<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('jardiwinery_action_theme_styles_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_action_theme_styles_theme_setup', 1 );
	function jardiwinery_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('jardiwinery_filter_used_fonts',			'jardiwinery_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('jardiwinery_filter_list_fonts',			'jardiwinery_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('jardiwinery_action_add_styles',			'jardiwinery_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('jardiwinery_filter_add_styles_inline',		'jardiwinery_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('jardiwinery_action_add_scripts',			'jardiwinery_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_action('jardiwinery_action_add_scripts_inline',	'jardiwinery_action_theme_styles_add_scripts_inline');

		// Add theme less files into list for compilation
		add_filter('jardiwinery_filter_compile_less',			'jardiwinery_filter_theme_styles_compile_less');


		/* Color schemes
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		// Next settings are deprecated
		//bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		//bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Additional accented colors (if need)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		text_link		- links
		text_hover		- hover links
		
		// Inverse blocks
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Input colors - form fields
		input_text		- inactive text
		input_hover		- focused text
		input_bd_color	- inactive border
		input_bd_hover	- focused borde
		input_bg_color	- inactive background
		input_bg_hover	- focused background
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		// Next settings are deprecated
		//alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		jardiwinery_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'jardiwinery'),
			
			// Whole block border and background
			'bd_color'				=> '#e4e7e8',
			'bg_color'				=> '#f4f4f4',       //
			
			// Headers, text and links colors
			'text'					=> '#7d7f81',       //
			'text_light'			=> '#acb4b6',
			'text_dark'				=> '#2c3136',       //
			'text_link'				=> '#b2936d',
			'text_hover'			=> '#b2936d',       //

			// Inverse colors
			'inverse_text'			=> '#ffffff',       //
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8f9090',       //
			'input_light'			=> '#cbcccd',       //
			'input_dark'			=> '#232a34',       //
			'input_bd_color'		=> '#e0e0e1',       //
			'input_bd_hover'		=> '#eaeaea',       //
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#9c9ea0',       //
			'alter_light'			=> '#5e6062',       //
			'alter_dark'			=> '#171a1c',       //
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#565a5e',       //
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);


        // Add color schemes
        jardiwinery_add_color_scheme('green', array(

                'title'					=> esc_html__('Green', 'jardiwinery'),

                // Whole block border and background
                'bd_color'				=> '#e4e7e8',
                'bg_color'				=> '#f4f4f4',       //

                // Headers, text and links colors
                'text'					=> '#7d7f81',       //
                'text_light'			=> '#acb4b6',
                'text_dark'				=> '#2c3136',       //
                'text_link'				=> '#809901',
                'text_hover'			=> '#809901',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#ffffff',
                'inverse_dark'			=> '#ffffff',
                'inverse_link'			=> '#ffffff',
                'inverse_hover'			=> '#ffffff',

                // Input fields
                'input_text'			=> '#8f9090',       //
                'input_light'			=> '#cbcccd',       //
                'input_dark'			=> '#232a34',       //
                'input_bd_color'		=> '#e0e0e1',       //
                'input_bd_hover'		=> '#eaeaea',       //
                'input_bg_color'		=> '#f7f7f7',
                'input_bg_hover'		=> '#f0f0f0',

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#9c9ea0',       //
                'alter_light'			=> '#5e6062',       //
                'alter_dark'			=> '#171a1c',       //
                'alter_link'			=> '#20c7ca',
                'alter_hover'			=> '#565a5e',       //
                'alter_bd_color'		=> '#dddddd',
                'alter_bd_hover'		=> '#bbbbbb',
                'alter_bg_color'		=> '#f7f7f7',
                'alter_bg_hover'		=> '#f0f0f0',
            )
        );


        // Add color schemes
        jardiwinery_add_color_scheme('red', array(

                'title'					=> esc_html__('Red', 'jardiwinery'),

                // Whole block border and background
                'bd_color'				=> '#e4e7e8',
                'bg_color'				=> '#f4f4f4',       //

                // Headers, text and links colors
                'text'					=> '#7d7f81',       //
                'text_light'			=> '#acb4b6',
                'text_dark'				=> '#2c3136',       //
                'text_link'				=> '#d34b4f',
                'text_hover'			=> '#d34b4f',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#ffffff',
                'inverse_dark'			=> '#ffffff',
                'inverse_link'			=> '#ffffff',
                'inverse_hover'			=> '#ffffff',

                // Input fields
                'input_text'			=> '#8f9090',       //
                'input_light'			=> '#cbcccd',       //
                'input_dark'			=> '#232a34',       //
                'input_bd_color'		=> '#e0e0e1',       //
                'input_bd_hover'		=> '#eaeaea',       //
                'input_bg_color'		=> '#f7f7f7',
                'input_bg_hover'		=> '#f0f0f0',

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#9c9ea0',       //
                'alter_light'			=> '#5e6062',       //
                'alter_dark'			=> '#171a1c',       //
                'alter_link'			=> '#20c7ca',
                'alter_hover'			=> '#565a5e',       //
                'alter_bd_color'		=> '#dddddd',
                'alter_bd_hover'		=> '#bbbbbb',
                'alter_bg_color'		=> '#f7f7f7',
                'alter_bg_hover'		=> '#f0f0f0',
            )
        );

        // Add color schemes
        jardiwinery_add_color_scheme('yellow', array(

                'title'					=> esc_html__('Yellow', 'jardiwinery'),

                // Whole block border and background
                'bd_color'				=> '#e4e7e8',
                'bg_color'				=> '#f4f4f4',       //

                // Headers, text and links colors
                'text'					=> '#7d7f81',       //
                'text_light'			=> '#acb4b6',
                'text_dark'				=> '#2c3136',       //
                'text_link'				=> '#e2b823',
                'text_hover'			=> '#e2b823',       //

                // Inverse colors
                'inverse_text'			=> '#ffffff',       //
                'inverse_light'			=> '#ffffff',
                'inverse_dark'			=> '#ffffff',
                'inverse_link'			=> '#ffffff',
                'inverse_hover'			=> '#ffffff',

                // Input fields
                'input_text'			=> '#8f9090',       //
                'input_light'			=> '#cbcccd',       //
                'input_dark'			=> '#232a34',       //
                'input_bd_color'		=> '#e0e0e1',       //
                'input_bd_hover'		=> '#eaeaea',       //
                'input_bg_color'		=> '#f7f7f7',
                'input_bg_hover'		=> '#f0f0f0',

                // Alternative blocks (submenu items, etc.)
                'alter_text'			=> '#9c9ea0',       //
                'alter_light'			=> '#5e6062',       //
                'alter_dark'			=> '#171a1c',       //
                'alter_link'			=> '#20c7ca',
                'alter_hover'			=> '#565a5e',       //
                'alter_bd_color'		=> '#dddddd',
                'alter_bd_hover'		=> '#bbbbbb',
                'alter_bg_color'		=> '#f7f7f7',
                'alter_bg_hover'		=> '#f0f0f0',
            )
        );

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		jardiwinery_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '5em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.32em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '1em'
			)
		);
		jardiwinery_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '4.2856em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.32em',
			'margin-top'	=> '1.36em',
			'margin-bottom'	=> '1em'
			)
		);
		jardiwinery_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '3.5711em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.33em',
			'margin-top'	=> '1.65em',
			'margin-bottom'	=> '1.3em'
			)
		);
		jardiwinery_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '2.5em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '1.15em'
			)
		);
		jardiwinery_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '1.7857em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '3.5em',
			'margin-bottom'	=> '1.8em'
			)
		);
		jardiwinery_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Vidaloka',
			'font-size' 	=> '1.42857em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '4em',
			'margin-bottom'	=> '2em'
			)
		);
		jardiwinery_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Lato',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.572em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		jardiwinery_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		jardiwinery_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Montserrat',
			'font-size' 	=> '12',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '2.5em'
			)
		);
		jardiwinery_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Montserrat',
			'font-size' 	=> '0.85711em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '1.3em',
			'margin-bottom'	=> '1.1em'
			)
		);
		jardiwinery_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Montserrat',
			'font-size' 	=> '12px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		jardiwinery_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '1.9em',
			'margin-bottom'	=> '1.8em'
			)
		);
		jardiwinery_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Montserrat',
			'font-size' 	=> '10px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		jardiwinery_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'jardiwinery'),
			'description'	=> '',
			'font-family'	=> 'Montserrat',
			'font-size' 	=> '12px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('jardiwinery_filter_theme_styles_used_fonts')) {
	function jardiwinery_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Lato'] = 1;
        $theme_fonts['Montserrat'] = 1;
        $theme_fonts['Vidaloka'] = 1;
		return $theme_fonts;
	}
}

// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('jardiwinery_filter_theme_styles_list_fonts')) {
	function jardiwinery_filter_theme_styles_list_fonts($list) {
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
        if (!isset($list['Montserrat']))	$list['Montserrat'] = array('family'=>'sans-serif');
        if (!isset($list['Vidaloka']))	$list['Vidaloka'] = array('family'=>'serif', 'link'=>'Vidaloka');
		return $list;


	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('jardiwinery_filter_theme_styles_compile_less')) {
	function jardiwinery_filter_theme_styles_compile_less($files) {
		if (file_exists(jardiwinery_get_file_dir('css/theme.less'))) {
		 	$files[] = jardiwinery_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('jardiwinery_action_theme_styles_add_styles')) {
	function jardiwinery_action_theme_styles_add_styles() {
		if ( jardiwinery_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'jardiwinery-theme-style', jardiwinery_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'jardiwinery-theme-style', jardiwinery_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('jardiwinery_filter_theme_styles_add_styles_inline')) {
	function jardiwinery_filter_theme_styles_add_styles_inline($custom_style) {
		// Submenu width
		$menu_width = jardiwinery_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = jardiwinery_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = jardiwinery_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = jardiwinery_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= jardiwinery_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('jardiwinery_action_theme_styles_add_scripts')) {
	function jardiwinery_action_theme_styles_add_scripts() {
		if (jardiwinery_get_theme_option('show_theme_customizer') == 'yes' && file_exists(jardiwinery_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'jardiwinery-theme_styles-customizer-script', jardiwinery_get_file_url('js/theme.customizer.js'), array(), null, true );
	}
}

// Add theme scripts inline
if (!function_exists('jardiwinery_action_theme_styles_add_scripts_inline')) {
	function jardiwinery_action_theme_styles_add_scripts_inline() {
		echo '<'.'script type="text/javascript"'.'>'
			. "if (typeof JARDIWINERY_STORAGE == 'undefined') var JARDIWINERY_STORAGE = {};"
			. "if (JARDIWINERY_STORAGE['theme_font']=='') JARDIWINERY_STORAGE['theme_font'] = '" . jardiwinery_get_custom_font_settings('p', 'font-family') . "';"
			. "JARDIWINERY_STORAGE['theme_color'] = '" . jardiwinery_get_scheme_color('text_dark') . "';"
			. "JARDIWINERY_STORAGE['theme_bg_color'] = '" . jardiwinery_get_scheme_color('bg_color') . "';"
			. '<'.'/script'.'>';
	}
}
?>