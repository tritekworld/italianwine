<?php


// Check if shortcodes settings are now used
if ( !function_exists( 'jardiwinery_shortcodes_is_used' ) ) {
    function jardiwinery_shortcodes_is_used() {
        return jardiwinery_options_is_used() 															// All modes when Theme Options are used
            || (is_admin() && isset($_POST['action'])
                && in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
            || jardiwinery_vc_is_frontend();															// VC Frontend editor mode
    }
}

// Width and height params
if ( !function_exists( 'jardiwinery_shortcodes_width' ) ) {
	function jardiwinery_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'jardiwinery'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'jardiwinery_shortcodes_height' ) ) {
	function jardiwinery_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'jardiwinery'),
			"desc" => wp_kses_data( __("Width and height of the element", 'jardiwinery') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'jardiwinery_get_sc_param' ) ) {
	function jardiwinery_get_sc_param($prm) {
		return jardiwinery_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'jardiwinery_set_sc_param' ) ) {
	function jardiwinery_set_sc_param($prm, $val) {
		jardiwinery_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'jardiwinery_sc_map' ) ) {
	function jardiwinery_sc_map($sc_name, $sc_settings) {
		jardiwinery_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'jardiwinery_sc_map_after' ) ) {
	function jardiwinery_sc_map_after($after, $sc_name, $sc_settings='') {
		jardiwinery_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'jardiwinery_sc_map_before' ) ) {
	function jardiwinery_sc_map_before($before, $sc_name, $sc_settings='') {
		jardiwinery_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'jardiwinery_compare_sc_title' ) ) {
	function jardiwinery_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_shortcodes_settings_theme_setup' ) ) {
//	if ( jardiwinery_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'jardiwinery_action_after_init_theme', 'jardiwinery_shortcodes_settings_theme_setup' );
	function jardiwinery_shortcodes_settings_theme_setup() {
		if (jardiwinery_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = jardiwinery_storage_get('registered_templates');
			ksort($tmp);
			jardiwinery_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			jardiwinery_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'jardiwinery'),
					"desc" => wp_kses_data( __("ID for current element", 'jardiwinery') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'jardiwinery'),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'jardiwinery'),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", 'jardiwinery') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'jardiwinery'),
					'ol'	=> esc_html__('Ordered', 'jardiwinery'),
					'iconed'=> esc_html__('Iconed', 'jardiwinery')
				),

				'yes_no'	=> jardiwinery_get_list_yesno(),
				'on_off'	=> jardiwinery_get_list_onoff(),
				'dir' 		=> jardiwinery_get_list_directions(),
				'align'		=> jardiwinery_get_list_alignments(),
				'float'		=> jardiwinery_get_list_floats(),
				'hpos'		=> jardiwinery_get_list_hpos(),
				'show_hide'	=> jardiwinery_get_list_showhide(),
				'sorting' 	=> jardiwinery_get_list_sortings(),
				'ordering' 	=> jardiwinery_get_list_orderings(),
				'shapes'	=> jardiwinery_get_list_shapes(),
				'sizes'		=> jardiwinery_get_list_sizes(),
				'sliders'	=> jardiwinery_get_list_sliders(),
				'controls'	=> jardiwinery_get_list_controls(),
                    'categories'=> is_admin() && jardiwinery_get_value_gp('action')=='vc_edit_form' && substr(jardiwinery_get_value_gp('tag'), 0, 4)=='trx_' && isset($_POST['params']['post_type']) && $_POST['params']['post_type']!='post'
                        ? jardiwinery_get_list_terms(false, jardiwinery_get_taxonomy_categories_by_post_type($_POST['params']['post_type']))
                        : jardiwinery_get_list_categories(),
				'columns'	=> jardiwinery_get_list_columns(),
                    'images'	=> array_merge(array('none'=>"none"), jardiwinery_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), jardiwinery_get_list_icons()),
				'locations'	=> jardiwinery_get_list_dedicated_locations(),
				'filters'	=> jardiwinery_get_list_portfolio_filters(),
				'formats'	=> jardiwinery_get_list_post_formats_filters(),
				'hovers'	=> jardiwinery_get_list_hovers(true),
				'hovers_dir'=> jardiwinery_get_list_hovers_directions(true),
				'schemes'	=> jardiwinery_get_list_color_schemes(true),
				'animations'		=> jardiwinery_get_list_animations_in(),
				'margins' 			=> jardiwinery_get_list_margins(true),
				'blogger_styles'	=> jardiwinery_get_list_templates_blogger(),
				'forms'				=> jardiwinery_get_list_templates_forms(),
				'posts_types'		=> jardiwinery_get_list_posts_types(),
				'googlemap_styles'	=> jardiwinery_get_list_googlemap_styles(),
				'field_types'		=> jardiwinery_get_list_field_types(),
				'label_positions'	=> jardiwinery_get_list_label_positions()
				)
			);

			// Common params
			jardiwinery_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'jardiwinery'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'jardiwinery') ),
				"value" => "none",
				"type" => "select",
				"options" => jardiwinery_get_sc_param('animations')
				)
			);
			jardiwinery_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'jardiwinery'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => jardiwinery_get_sc_param('margins')
				)
			);
			jardiwinery_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'jardiwinery'),
				"value" => "inherit",
				"type" => "select",
				"options" => jardiwinery_get_sc_param('margins')
				)
			);
			jardiwinery_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'jardiwinery'),
				"value" => "inherit",
				"type" => "select",
				"options" => jardiwinery_get_sc_param('margins')
				)
			);
			jardiwinery_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'jardiwinery'),
				"desc" => wp_kses_data( __("Margins around this shortcode", 'jardiwinery') ),
				"value" => "inherit",
				"type" => "select",
				"options" => jardiwinery_get_sc_param('margins')
				)
			);

			jardiwinery_storage_set('sc_params', apply_filters('jardiwinery_filter_shortcodes_params', jardiwinery_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			jardiwinery_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('jardiwinery_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = jardiwinery_storage_get('shortcodes');
			uasort($tmp, 'jardiwinery_compare_sc_title');
			jardiwinery_storage_set('shortcodes', $tmp);
		}
	}
}
?>