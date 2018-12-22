<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_gutenberg_theme_setup')) {
    add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_gutenberg_theme_setup', 1 );
    function jardiwinery_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'jardiwinery_filter_required_plugins', 'jardiwinery_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jardiwinery_exists_gutenberg' ) ) {
    function jardiwinery_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_gutenberg_required_plugins' ) ) {
    //add_filter('jardiwinery_filter_required_plugins',    'jardiwinery_gutenberg_required_plugins');
    function jardiwinery_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)jardiwinery_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('Gutenberg', 'jardiwinery'),
                'slug'         => 'gutenberg',
                'required'     => false
            );
        return $list;
    }
}