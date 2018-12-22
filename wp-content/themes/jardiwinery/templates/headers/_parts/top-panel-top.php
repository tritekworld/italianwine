<?php 
// Get template args
extract(jardiwinery_template_get_args('top-panel-top'));

?>

<div class="top_panel_top_user_area">
	<?php
	if (in_array('socials', $top_panel_top_components) && jardiwinery_get_custom_option('show_socials')=='yes') {
		?>
		<div class="top_panel_top_socials">
			<?php jardiwinery_show_layout(jardiwinery_sc_socials(array('size'=>'tiny'))); ?>
		</div>
		<?php
	}

	$menu_user = jardiwinery_get_nav_menu('menu_user');
	if (empty($menu_user)) {
		?>
		<ul id="<?php echo (!empty($menu_user_id) ? esc_attr($menu_user_id) : 'menu_user'); ?>" class="menu_user_nav">
		<?php
	} else {
		$menu = jardiwinery_substr($menu_user, 0, jardiwinery_strlen($menu_user)-5);
		$pos = jardiwinery_strpos($menu, '<ul');
		if ($pos!==false && jardiwinery_strpos($menu, 'menu_user_nav')===false)
			$menu = jardiwinery_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . jardiwinery_substr($menu, $pos+3);
		if (!empty($menu_user_id))
			$menu = jardiwinery_set_tag_attrib($menu, '<ul>', 'id', $menu_user_id);
		echo str_replace('class=""', '', $menu);
	}

        if (in_array('currency', $top_panel_top_components) && function_exists('jardiwinery_is_woocommerce_page') && jardiwinery_is_woocommerce_page() && jardiwinery_get_custom_option('show_currency')=='yes') {
            ?>
            <li class="menu_user_currency">
                <a href=“#”>$</a>
                <ul>
                    <li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'jardiwinery'); ?></a></li>
                    <li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'jardiwinery'); ?></a></li>
                    <li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'jardiwinery'); ?></a></li>
                </ul>
            </li>
            <?php
        }

	if (in_array('language', $top_panel_top_components) && jardiwinery_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php jardiwinery_show_layout($lang_active); ?></span></a>
				<ul><?php jardiwinery_show_layout($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && jardiwinery_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		jardiwinery_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'jardiwinery'); ?>"><?php esc_html_e('Bookmarks', 'jardiwinery'); ?></a>
		<?php 
			$list = jardiwinery_get_value_gpc('jardiwinery_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'jardiwinery'); ?>"><?php esc_html_e('Add bookmark', 'jardiwinery'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'jardiwinery').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}
	?>

	</ul>

</div>