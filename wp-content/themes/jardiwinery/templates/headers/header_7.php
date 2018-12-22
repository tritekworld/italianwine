<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_template_header_7_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_template_header_7_theme_setup', 1 );
	function jardiwinery_template_header_7_theme_setup() {
		jardiwinery_add_template(array(
			'layout' => 'header_7',
			'mode'   => 'header',
			'title'  => esc_html__('Header 7', 'jardiwinery'),
			'icon'   => jardiwinery_get_file_url('templates/headers/images/7.jpg'),
			'thumb_title'  => esc_html__('Original image', 'jardiwinery'),
			'w'		 => null,
			'h_crop' => null,
			'h'      => null
			));
	}
}

// Template output
if ( !function_exists( 'jardiwinery_template_header_7_output' ) ) {
	function jardiwinery_template_header_7_output($post_options, $post_data) {

		// Get custom image (for blog) or featured image (for single)
		$header_css = '';
		if (empty($header_image))
			$header_image = jardiwinery_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			// Uncomment next rows if you want crop image
			//$thumb_sizes = jardiwinery_get_thumb_sizes(array( 'layout' => $post_options['layout'] ));
			//$header_image = jardiwinery_get_resized_image_url($header_image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(jardiwinery_get_custom_option('top_panel_position')); ?>">
                <?php if (jardiwinery_get_custom_option('show_top_panel_top')=='yes') { ?>
                    <div class="top_panel_top">
                        <div class="content_wrap clearfix">
                            <?php
                            jardiwinery_template_set_args('top-panel-top', array(
                                'top_panel_top_components' => array('socials', 'currency', 'language', 'bookmarks', 'login')
                            ));
                            get_template_part(jardiwinery_get_file_slug('templates/headers/_parts/top-panel-top.php'));
                            ?>
                        </div>
                    </div>
                <?php } ?>
			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="column-1_4 contact_logo">
						<?php jardiwinery_show_logo(true, true); ?>
					</div>
					<div class="column-3_4 menu_main_wrap">
						<nav class="menu_main_nav_area">
							<?php
							$menu_main = jardiwinery_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = jardiwinery_get_nav_menu();
							jardiwinery_show_layout($menu_main);
							?>
						</nav>
						<?php
						if (function_exists('jardiwinery_exists_woocommerce') && jardiwinery_exists_woocommerce() && (jardiwinery_is_woocommerce_page() && jardiwinery_get_custom_option('show_cart')=='shop' || jardiwinery_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
							?>
							<div class="menu_main_cart top_panel_icon">
								<?php get_template_part(jardiwinery_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
			

			</div>
		</header>

        <?php
            if(jardiwinery_get_custom_option('show_page_title_inheader') == 'yes') {
                ?>
                <section class="top_panel_image" <?php jardiwinery_show_layout($header_css); ?>>
                    <div class="top_panel_image_hover"></div>
                    <div class="top_panel_image_header">
                        <h1 itemprop="headline"
                            class="top_panel_image_title entry-title"><?php echo strip_tags(jardiwinery_get_blog_title()); ?></h1>

                        <div class="breadcrumbs">
                            <?php if (!is_404()) jardiwinery_show_breadcrumbs(); ?>
                        </div>
                    </div>
                </section>
            <?php
            }
		jardiwinery_storage_set('header_mobile', array(
				 'open_hours' => false,
				 'login' => false,
				 'socials' => false,
				 'bookmarks' => false,
				 'contact_address' => false,
				 'contact_phone_email' => false,
				 'woo_cart' => false,
				 'search' => false
			)
		);
	}
}
?>