<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_booked_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_booked_theme_setup', 1 );
	function jardiwinery_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (jardiwinery_exists_booked()) {
			add_action('jardiwinery_action_add_styles', 					'jardiwinery_booked_frontend_scripts');
			add_action('jardiwinery_action_shortcodes_list',				'jardiwinery_booked_reg_shortcodes');
			if (function_exists('jardiwinery_exists_visual_composer') && jardiwinery_exists_visual_composer())
				add_action('jardiwinery_action_shortcodes_list_vc',		'jardiwinery_booked_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'jardiwinery_filter_importer_options',			'jardiwinery_booked_importer_set_options' );
				add_filter( 'jardiwinery_filter_importer_import_row',		'jardiwinery_booked_importer_check_row', 9, 4);
			}
		}
		if (is_admin()) {
			add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_booked_importer_required_plugins', 10, 2);
			add_filter( 'jardiwinery_filter_required_plugins',				'jardiwinery_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'jardiwinery_exists_booked' ) ) {
	function jardiwinery_exists_booked() {
		return class_exists('booked_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_booked_required_plugins' ) ) {
	//add_filter('jardiwinery_filter_required_plugins',	'jardiwinery_booked_required_plugins');
	function jardiwinery_booked_required_plugins($list=array()) {
		if (in_array('booked', jardiwinery_storage_get('required_plugins'))) {
			$path = jardiwinery_get_file_dir('plugins/install/booked.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booked', 'jardiwinery'),
					'slug' 		=> 'booked',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'jardiwinery_booked_frontend_scripts' ) ) {
	//add_action( 'jardiwinery_action_add_styles', 'jardiwinery_booked_frontend_scripts' );
	function jardiwinery_booked_frontend_scripts() {
		if (file_exists(jardiwinery_get_file_dir('css/plugin.booked.css')))
			wp_enqueue_style( 'jardiwinery-plugin.booked-style',  jardiwinery_get_file_url('css/plugin.booked.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'jardiwinery_booked_importer_required_plugins' ) ) {
	//add_filter( 'jardiwinery_filter_importer_required_plugins',	'jardiwinery_booked_importer_required_plugins', 10, 2);
	function jardiwinery_booked_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('booked', jardiwinery_storage_get('required_plugins')) && !jardiwinery_exists_booked() )
		if (jardiwinery_strpos($list, 'booked')!==false && !jardiwinery_exists_booked() )
			$not_installed .= '<br>' . esc_html__('Booked Appointments', 'jardiwinery');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'jardiwinery_booked_importer_set_options' ) ) {
	//add_filter( 'jardiwinery_filter_importer_options',	'jardiwinery_booked_importer_set_options', 10, 1 );
	function jardiwinery_booked_importer_set_options($options=array()) {
		if (in_array('booked', jardiwinery_storage_get('required_plugins')) && jardiwinery_exists_booked()) {
			$options['additional_options'][] = 'booked_%';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}

// Check if the row will be imported
if ( !function_exists( 'jardiwinery_booked_importer_check_row' ) ) {
	//add_filter('jardiwinery_filter_importer_import_row', 'jardiwinery_booked_importer_check_row', 9, 4);
	function jardiwinery_booked_importer_check_row($flag, $table, $row, $list) {
		if ($flag || strpos($list, 'booked')===false) return $flag;
		if ( jardiwinery_exists_booked() ) {
			if ($table == 'posts')
				$flag = $row['post_type']=='booked_appointments';
		}
		return $flag;
	}
}


// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'jardiwinery_get_list_booked_calendars' ) ) {
	function jardiwinery_get_list_booked_calendars($prepend_inherit=false) {
		return jardiwinery_exists_booked() ? jardiwinery_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}



// Register plugin's shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('jardiwinery_booked_reg_shortcodes')) {
	//add_filter('jardiwinery_action_shortcodes_list',	'jardiwinery_booked_reg_shortcodes');
	function jardiwinery_booked_reg_shortcodes() {
		if (jardiwinery_storage_isset('shortcodes')) {

			$booked_cals = jardiwinery_get_list_booked_calendars();

			jardiwinery_sc_map('booked-appointments', array(
				"title" => esc_html__("Booked Appointments", "jardiwinery"),
				"desc" => esc_html__("Display the currently logged in user's upcoming appointments", "jardiwinery"),
				"decorate" => true,
				"container" => false,
				"params" => array()
				)
			);

			jardiwinery_sc_map('booked-calendar', array(
				"title" => esc_html__("Booked Calendar", "jardiwinery"),
				"desc" => esc_html__("Insert booked calendar", "jardiwinery"),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"calendar" => array(
						"title" => esc_html__("Calendar", "jardiwinery"),
						"desc" => esc_html__("Select booked calendar to display", "jardiwinery"),
						"value" => "0",
						"type" => "select",
						"options" => jardiwinery_array_merge(array(0 => esc_html__('- Select calendar -', 'jardiwinery')), $booked_cals)
					),
					"year" => array(
						"title" => esc_html__("Year", "jardiwinery"),
						"desc" => esc_html__("Year to display on calendar by default", "jardiwinery"),
						"value" => date("Y"),
						"min" => date("Y"),
						"max" => date("Y")+10,
						"type" => "spinner"
					),
					"month" => array(
						"title" => esc_html__("Month", "jardiwinery"),
						"desc" => esc_html__("Month to display on calendar by default", "jardiwinery"),
						"value" => date("m"),
						"min" => 1,
						"max" => 12,
						"type" => "spinner"
					)
				)
			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('jardiwinery_booked_reg_shortcodes_vc')) {
	//add_filter('jardiwinery_action_shortcodes_list_vc',	'jardiwinery_booked_reg_shortcodes_vc');
	function jardiwinery_booked_reg_shortcodes_vc() {

		$booked_cals = jardiwinery_get_list_booked_calendars();

		// Booked Appointments
		vc_map( array(
				"base" => "booked-appointments",
				"name" => esc_html__("Booked Appointments", "jardiwinery"),
				"description" => esc_html__("Display the currently logged in user's upcoming appointments", "jardiwinery"),
				"category" => esc_html__('Content', 'jardiwinery'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_appointments",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array()
			) );
			
		class WPBakeryShortCode_Booked_Appointments extends JARDIWINERY_VC_ShortCodeSingle {}

		// Booked Calendar
		vc_map( array(
				"base" => "booked-calendar",
				"name" => esc_html__("Booked Calendar", "jardiwinery"),
				"description" => esc_html__("Insert booked calendar", "jardiwinery"),
				"category" => esc_html__('Content', 'jardiwinery'),
				'icon' => 'icon_trx_booked',
				"class" => "trx_sc_single trx_sc_booked_calendar",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "calendar",
						"heading" => esc_html__("Calendar", "jardiwinery"),
						"description" => esc_html__("Select booked calendar to display", "jardiwinery"),
						"admin_label" => true,
						"class" => "",
						"std" => "0",
						"value" => array_flip(jardiwinery_array_merge(array(0 => esc_html__('- Select calendar -', 'jardiwinery')), $booked_cals)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "year",
						"heading" => esc_html__("Year", "jardiwinery"),
						"description" => esc_html__("Year to display on calendar by default", "jardiwinery"),
						"admin_label" => true,
						"class" => "",
						"std" => date("Y"),
						"value" => date("Y"),
						"type" => "textfield"
					),
					array(
						"param_name" => "month",
						"heading" => esc_html__("Month", "jardiwinery"),
						"description" => esc_html__("Month to display on calendar by default", "jardiwinery"),
						"admin_label" => true,
						"class" => "",
						"std" => date("m"),
						"value" => date("m"),
						"type" => "textfield"
					)
				)
			) );
			
		class WPBakeryShortCode_Booked_Calendar extends JARDIWINERY_VC_ShortCodeSingle {}

	}
}
?>