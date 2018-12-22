<?php
/**
 * The template for displaying the footer.
 */

				jardiwinery_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (jardiwinery_get_custom_option('body_style')!='fullscreen') jardiwinery_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (jardiwinery_get_custom_option('show_testimonials_in_footer')=='yes') { 
				$count = max(1, jardiwinery_get_custom_option('testimonials_count'));
				$data = jardiwinery_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(jardiwinery_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php jardiwinery_show_layout($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}
			
			// Footer sidebar
			$footer_show  = jardiwinery_get_custom_option('show_sidebar_footer');
			$sidebar_name = jardiwinery_get_custom_option('sidebar_footer');
			if (!jardiwinery_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				jardiwinery_storage_set('current_sidebar', 'footer');
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(jardiwinery_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							jardiwinery_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
				<?php
			}


			// Footer Twitter stream
			if (jardiwinery_get_custom_option('show_twitter_in_footer')=='yes') { 
				$count = max(1, jardiwinery_get_custom_option('twitter_count'));
				$data = jardiwinery_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(jardiwinery_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php jardiwinery_show_layout($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Google map
			if ( jardiwinery_get_custom_option('show_googlemap')=='yes' ) { 
				$map_address = jardiwinery_get_custom_option('googlemap_address');
				$map_latlng  = jardiwinery_get_custom_option('googlemap_latlng');
				$map_zoom    = jardiwinery_get_custom_option('googlemap_zoom');
				$map_style   = jardiwinery_get_custom_option('googlemap_style');
				$map_height  = jardiwinery_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					jardiwinery_show_layout(jardiwinery_sc_googlemap($args));
				}
			}

			// Footer contacts
			if (jardiwinery_get_custom_option('show_contacts_in_footer')=='yes') { 
				$address_1 = jardiwinery_get_theme_option('contact_address_1');
				$address_2 = jardiwinery_get_theme_option('contact_address_2');
				$phone = jardiwinery_get_theme_option('contact_phone');
				$contact_email = jardiwinery_get_theme_option('contact_email');

				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<footer class="contacts_wrap scheme_<?php echo esc_attr(jardiwinery_get_custom_option('contacts_scheme')); ?>">
						<div class="contacts_wrap_inner">
							<div class="content_wrap">
                                <div class="columns_wrap columns_fluid">
                                    <div class="column-5_12 footer_address">
                                        <div class="contacts_address">
                                            <address>
                                                <?php if (!empty($address_1)) echo '<span>'.esc_html__('Address:', 'jardiwinery') . '</span><br>' . esc_html($address_1) . '<br>'; ?>
                                                <?php if (!empty($address_2)) echo esc_html($address_2); ?>
                                            </address>
                                        </div>
                                    </div><div class="column-1_6">
                                        <?php jardiwinery_show_logo(false, false, true); ?>
                                        <?php jardiwinery_show_layout(jardiwinery_sc_socials(array('size'=>"tiny"))); ?>
                                    </div><div class="column-5_12 footer_phone">
                                        <div class="contacts_address">
                                            <address>
                                                <?php if (!empty($phone)) echo '<span>'.esc_html__('Phone:', 'jardiwinery') . '</span> <a href="tel:' . esc_html($phone) . '">' . esc_html($phone) . '</a><br><br>'; ?>
                                                <?php if (!empty($contact_email)) echo '<span>'.esc_html__('Email:', 'jardiwinery') . '</span><a href="mailto:' . esc_html($contact_email) . '"> ' . esc_html($contact_email). '</a>';?>
                                            </address>
                                        </div>
                                    </div>
                                </div>
							</div>	<!-- /.content_wrap -->
						</div>	<!-- /.contacts_wrap_inner -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
			}

			// Copyright area
			$copyright_style = jardiwinery_get_custom_option('show_copyright_in_footer');
			if (!jardiwinery_param_is_off($copyright_style)) {
				?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(jardiwinery_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (($menu = jardiwinery_get_nav_menu('menu_footer'))!='') {
									jardiwinery_show_layout($menu);
								}
							} else if ($copyright_style == 'socials') {
								jardiwinery_show_layout(jardiwinery_sc_socials(array('size'=>"tiny")));
							}
							?>
							<div class="copyright_text">
                                <?php
                                $jardiwinery_copyright = jardiwinery_get_custom_option('footer_copyright');
                                $jardiwinery_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $jardiwinery_copyright);
                                echo wp_kses_post($jardiwinery_copyright);
                                ?>
                            </div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !jardiwinery_param_is_off(jardiwinery_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>