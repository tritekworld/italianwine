<?php
// Get template args
extract(jardiwinery_template_get_args('counters'));

$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';

// Views
if ($show_all_counters || jardiwinery_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php jardiwinery_show_layout($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo esc_attr( sprintf(__('Views - %s', 'jardiwinery'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo esc_attr($post_data['post_views']); ?></span><?php if (jardiwinery_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Views', 'jardiwinery'); ?></<?php jardiwinery_show_layout($counters_tag); ?>>
	<?php
}

// Comments
if ($show_all_counters || jardiwinery_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'jardiwinery'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo esc_attr($post_data['post_comments']); echo esc_html__(' Comment(s)', 'jardiwinery');?></span><?php if (jardiwinery_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Comments', 'jardiwinery'); ?></a>
	<?php 
}
 
// Rating
$rating = $post_data['post_reviews_'.(jardiwinery_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || jardiwinery_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php jardiwinery_show_layout($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'jardiwinery'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo esc_attr($rating); ?></span></<?php jardiwinery_show_layout($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters || jardiwinery_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	jardiwinery_enqueue_messages();
	$likes = isset($_COOKIE['jardiwinery_likes']) ? $_COOKIE['jardiwinery_likes'] : '';
	$allow = jardiwinery_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'jardiwinery') : esc_attr__('Dislike', 'jardiwinery'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'jardiwinery'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'jardiwinery'); ?>"><span class="post_counters_number"><?php echo esc_attr($post_data['post_likes']); ?></span><?php if (jardiwinery_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Likes', 'jardiwinery'); ?></a>
	<?php
}

// Edit page link
if (jardiwinery_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'jardiwinery' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && jardiwinery_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(jardiwinery_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(jardiwinery_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>