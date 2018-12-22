<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_instagram_widget_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_instagram_widget_theme_setup', 1 );
	function jardiwinery_instagram_widget_theme_setup() {
		if (jardiwinery_exists_instagram_widget()) {
			add_action( 'jardiwinery_action_add_styles', 						'jardiwinery_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',		'jardiwinery_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'jardiwinery_filter_required_plugins',					'jardiwinery_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jardiwinery_exists_instagram_widget' ) ) {
	function jardiwinery_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_instagram_widget_required_plugins' ) ) {
	//add_filter('jardiwinery_filter_required_plugins',	'jardiwinery_instagram_widget_required_plugins');
	function jardiwinery_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', jardiwinery_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'jardiwinery'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jardiwinery_instagram_widget_frontend_scripts' ) ) {
	//add_action( 'jardiwinery_action_add_styles', 'jardiwinery_instagram_widget_frontend_scripts' );
	function jardiwinery_instagram_widget_frontend_scripts() {
		if (file_exists(jardiwinery_get_file_dir('css/plugin.instagram-widget.css')))
			wp_enqueue_style( 'jardiwinery-plugin.instagram-widget-style',  jardiwinery_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'jardiwinery_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_instagram_widget_importer_required_plugins', 10, 2 );
	function jardiwinery_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		if (jardiwinery_strpos($list, 'instagram_widget')!==false && !jardiwinery_exists_instagram_widget() )
			$not_installed .= '<br>' . esc_html__('WP Instagram Widget', 'jardiwinery');
		return $not_installed;
	}
}
?>