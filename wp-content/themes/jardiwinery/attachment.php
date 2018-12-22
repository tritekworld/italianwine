<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move jardiwinery_set_post_views to the javascript - counter will work under cache system
	if (jardiwinery_get_custom_option('use_ajax_views_counter')=='no') {
		jardiwinery_set_post_views(get_the_ID());
	}

	jardiwinery_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !jardiwinery_param_is_off(jardiwinery_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>