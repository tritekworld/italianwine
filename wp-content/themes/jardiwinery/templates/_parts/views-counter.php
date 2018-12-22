<?php 
if (is_singular()) {
	if (jardiwinery_get_theme_option('use_ajax_views_counter')=='yes') {
		?>
		<!-- Post/page views count increment -->
        <<?php echo esc_attr(jardiwinery_storage_get('tag_open'));?>>
			jQuery(document).ready(function() {
				setTimeout(function(){
					jQuery.post(JARDIWINERY_STORAGE['ajax_url'], {
						action: 'post_counter',
						nonce: JARDIWINERY_STORAGE['ajax_nonce'],
						post_id: <?php echo (int) get_the_ID(); ?>,
						views: <?php echo (int) jardiwinery_get_post_views(get_the_ID()); ?>
					});
					}, 10);
			});
        <<?php echo esc_attr(jardiwinery_storage_get('tag_close'));?>>
		<?php
	} else
		jardiwinery_set_post_views(get_the_ID());
}
?>