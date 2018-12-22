<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_template_header_2_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_template_header_2_theme_setup', 1 );
	function jardiwinery_template_header_2_theme_setup() {
		jardiwinery_add_template(array(
			'layout' => 'header_2',
			'mode'   => 'header',
			'title'  => esc_html__('Header 2', 'jardiwinery'),
			'icon'   => jardiwinery_get_file_url('templates/headers/images/2.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'jardiwinery_template_header_2_output' ) ) {
	function jardiwinery_template_header_2_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_2 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_2 top_panel_position_<?php echo esc_attr(jardiwinery_get_custom_option('top_panel_position')); ?>">
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
                
                <div class="top_panel_middle" <?php jardiwinery_show_layout($header_css); ?>>
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid"><?php
						// Phone and email
						$contact_phone=trim(jardiwinery_get_custom_option('contact_phone'));
						$contact_info=trim(jardiwinery_get_custom_option('contact_info'));
						if (!empty($contact_phone) || !empty($contact_email)) {
							?><div class="column-5_12 contact_field contact_phone">
								<span class="contact_icon icon-icon_home"></span>
								<span class="contact_label contact_phone"><?php echo '<a href="tel:'.esc_html($contact_phone).'">'.esc_html($contact_phone).'</a>'; ?></span>
								<span class="contacts_info"><?php echo esc_html($contact_info); ?></span>
							</div><?php
						}
						?><div class="column-1_6 contact_logo">
							<?php jardiwinery_show_logo(); ?>
						</div><?php
						// Woocommerce Cart
						if (function_exists('jardiwinery_exists_woocommerce') && jardiwinery_exists_woocommerce() && (jardiwinery_is_woocommerce_page() && jardiwinery_get_custom_option('show_cart')=='shop' || jardiwinery_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
							?><div class="column-5_12 contact_field contact_cart"><?php get_template_part(jardiwinery_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?></div><?php
						}
						?></div>
				</div>
			</div>

			<div class="top_panel_bottom">
				<div class="content_wrap clearfix">
					<nav class="menu_main_nav_area">
						<?php
						$menu_main = jardiwinery_get_nav_menu('menu_main');
						if (empty($menu_main)) $menu_main = jardiwinery_get_nav_menu();
						jardiwinery_show_layout($menu_main);
						?>
					</nav>
				</div>
			</div>

			</div>
		</header>

		<?php
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