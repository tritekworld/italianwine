<?php
if (!function_exists('jardiwinery_theme_shortcodes_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_theme_shortcodes_setup', 1 );
	function jardiwinery_theme_shortcodes_setup() {
		add_filter('jardiwinery_filter_googlemap_styles', 'jardiwinery_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'jardiwinery_theme_shortcodes_googlemap_styles' ) ) {
	function jardiwinery_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'jardiwinery');
		$list['greyscale']	= esc_html__('Greyscale', 'jardiwinery');
		$list['inverse']	= esc_html__('Inverse', 'jardiwinery');
		return $list;
	}
}
?>