<?php
/**
 * Single post
 */
get_header(); 

$single_style = jardiwinery_storage_get('single_style');
if (empty($single_style)) $single_style = jardiwinery_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	jardiwinery_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !jardiwinery_param_is_off(jardiwinery_get_custom_option('show_sidebar_main')),
			'content' => jardiwinery_get_template_property($single_style, 'need_content'),
			'terms_list' => jardiwinery_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>