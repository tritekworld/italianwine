<?php
/**
 * JardiWinery Framework: Supported post types settings
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Theme init
if (!function_exists('jardiwinery_post_type_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_post_type_theme_setup', 9 );
	function jardiwinery_post_type_theme_setup() {
		if ( !jardiwinery_options_is_used() ) return;
		$post_type = jardiwinery_admin_get_current_post_type();
		if (empty($post_type)) $post_type = 'post';
		$override_key = jardiwinery_get_override_key($post_type, 'post_type');
		if ($override_key) {
			// Set post type action
			add_action('save_post',				'jardiwinery_post_type_save_options');
			add_action('trx_utils_filter_override_options',		'jardiwinery_post_type_add_override_options');
			add_action('admin_enqueue_scripts', 'jardiwinery_post_type_admin_scripts');
			// Create override options
			jardiwinery_storage_set('post_override_options', array(
				'id' => 'post-override-options',
				'title' => esc_html__('Post Options', 'jardiwinery'),
				'page' => $post_type,
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array()
				)
			);
		}
	}
}


// Admin scripts
if (!function_exists('jardiwinery_post_type_admin_scripts')) {
	//add_action('admin_enqueue_scripts', 'jardiwinery_post_type_admin_scripts');
	function jardiwinery_post_type_admin_scripts() {
	}
}


// Add override options
if (!function_exists('jardiwinery_post_type_add_override_options')) {
    //add_filter('trx_utils_filter_override_options', 'jardiwinery_post_type_add_override_options');
    function jardiwinery_post_type_add_override_options($boxes = array()) {
        $boxes[] = array_merge(jardiwinery_storage_get('post_override_options'), array('callback' => 'jardiwinery_post_type_show_override_options'));
        return $boxes;
    }
}


// Callback function to show fields in override options
if (!function_exists('jardiwinery_post_type_show_override_options')) {
	function jardiwinery_post_type_show_override_options() {
		global $post;
		
		$post_type = jardiwinery_admin_get_current_post_type();
		$override_key = jardiwinery_get_override_key($post_type, 'post_type');
		
		// Use nonce for verification
		echo '<input type="hidden" name="override_options_post_nonce" value="' .esc_attr(wp_create_nonce(admin_url())).'" />';
		echo '<input type="hidden" name="override_options_post_type" value="'.esc_attr($post_type).'" />';
	
		$custom_options = apply_filters('jardiwinery_filter_post_load_custom_options', get_post_meta($post->ID, jardiwinery_storage_get('options_prefix') . '_post_options', true), $post_type, $post->ID);

		$mb = jardiwinery_storage_get('post_override_options');
		$post_options = jardiwinery_array_merge(jardiwinery_storage_get('options'), $mb['fields']);

		do_action('jardiwinery_action_post_before_show_override_options', $post_type, $post->ID);
	
		jardiwinery_options_page_start(array(
			'data' => $post_options,
			'add_inherit' => true,
			'create_form' => false,
			'buttons' => array('import', 'export'),
			'override' => $override_key
		));

		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$option) { 
				if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;

				$option = apply_filters('jardiwinery_filter_post_show_custom_field_option', $option, $id, $post_type, $post->ID);
				$meta = isset($custom_options[$id]) 
								? apply_filters('jardiwinery_filter_post_show_custom_field_value', $custom_options[$id], $option, $id, $post_type, $post->ID) 
								: (isset($option['inherit']) && !$option['inherit'] ? $option['std'] : '');

				do_action('jardiwinery_action_post_before_show_custom_field', $post_type, $post->ID, $option, $id, $meta);

				jardiwinery_options_show_field($id, $option, $meta);

				do_action('jardiwinery_action_post_after_show_custom_field', $post_type, $post->ID, $option, $id, $meta);
			}
		}
	
		jardiwinery_options_page_stop();
		
		do_action('jardiwinery_action_post_after_show_override_options', $post_type, $post->ID);
		
	}
}


// Save data from override options
if (!function_exists('jardiwinery_post_type_save_options')) {
	//add_action('save_post', 'jardiwinery_post_type_save_options');
	function jardiwinery_post_type_save_options($post_id) {

		// verify nonce
		if ( !wp_verify_nonce( jardiwinery_get_value_gp('override_options_post_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		$post_type = isset($_POST['override_options_post_type']) ? $_POST['override_options_post_type'] : $_POST['post_type'];
		$override_key = jardiwinery_get_override_key($post_type, 'post_type');

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		$custom_options = array();

		$post_options = array_merge(jardiwinery_storage_get('options'), jardiwinery_storage_get_array('post_override_options', 'fields'));

		if (jardiwinery_options_merge_new_values($post_options, $custom_options, $_POST, 'save', $override_key)) {
			update_post_meta($post_id, jardiwinery_storage_get('options_prefix') . '_post_options', apply_filters('jardiwinery_filter_post_save_custom_options', $custom_options, $post_type, $post_id));
		}

		// Init post counters
		global $post;
		if ( !empty($post->ID) && $post_id==$post->ID ) {
			jardiwinery_get_post_views($post_id);
			jardiwinery_get_post_likes($post_id);
		}
	}
}
?>