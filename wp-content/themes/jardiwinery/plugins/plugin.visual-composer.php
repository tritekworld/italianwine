<?php
/* WPBakery PageBuilder support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_vc_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_vc_theme_setup', 1 );
	function jardiwinery_vc_theme_setup() {
		if (jardiwinery_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'jardiwinery_filter_importer_options',				'jardiwinery_vc_importer_set_options' );
			}
			add_action('jardiwinery_action_add_styles',		 				'jardiwinery_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',		'jardiwinery_vc_importer_required_plugins', 10, 2 );
			add_filter( 'jardiwinery_filter_required_plugins',					'jardiwinery_vc_required_plugins' );
		}
	}
}

// Check if WPBakery PageBuilder installed and activated
if ( !function_exists( 'jardiwinery_exists_visual_composer' ) ) {
	function jardiwinery_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery PageBuilder in frontend editor mode
if ( !function_exists( 'jardiwinery_vc_is_frontend' ) ) {
	function jardiwinery_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_vc_required_plugins' ) ) {
	//add_filter('jardiwinery_filter_required_plugins',	'jardiwinery_vc_required_plugins');
	function jardiwinery_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', jardiwinery_storage_get('required_plugins'))) {
			$path = jardiwinery_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'WPBakery PageBuilder',
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'jardiwinery_vc_frontend_scripts' ) ) {
	//add_action( 'jardiwinery_action_add_styles', 'jardiwinery_vc_frontend_scripts' );
	function jardiwinery_vc_frontend_scripts() {
		if (file_exists(jardiwinery_get_file_dir('css/plugin.visual-composer.css')))
			wp_enqueue_style( 'jardiwinery-plugin.visual-composer-style',  jardiwinery_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'jardiwinery_vc_importer_required_plugins' ) ) {
	//add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_vc_importer_required_plugins', 10, 2 );
	function jardiwinery_vc_importer_required_plugins($not_installed='', $list='') {
		if (!jardiwinery_exists_visual_composer() )		// && jardiwinery_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>WPBakery PageBuilder';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jardiwinery_vc_importer_set_options' ) ) {
	//add_filter( 'jardiwinery_filter_importer_options',	'jardiwinery_vc_importer_set_options' );
	function jardiwinery_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', jardiwinery_storage_get('required_plugins')) && jardiwinery_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>