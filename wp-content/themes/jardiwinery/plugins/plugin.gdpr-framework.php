<?php
/* The GDPR Framework support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('jardiwinery_gdpr_framework_theme_setup')) {
    add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_gdpr_framework_theme_setup', 1 );
    function jardiwinery_gdpr_framework_theme_setup() {
        if (is_admin()) {
            add_filter( 'jardiwinery_filter_required_plugins', 'jardiwinery_gdpr_framework_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'jardiwinery_exists_gdpr_framework' ) ) {
    function jardiwinery_exists_gdpr_framework() {
        return defined( 'GDPR_FRAMEWORK_VERSION' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'jardiwinery_gdpr_framework_required_plugins' ) ) {
    //add_filter('jardiwinery_filter_required_plugins',    'jardiwinery_gdpr_framework_required_plugins');
    function jardiwinery_gdpr_framework_required_plugins($list=array()) {
        if (in_array('gdpr_framework', (array)jardiwinery_storage_get('required_plugins')))
            $list[] = array(
                'name'         => esc_html__('The GDPR Framework', 'jardiwinery'),
                'slug'         => 'gdpr-framework',
                'required'     => false
            );
        return $list;
    }
}