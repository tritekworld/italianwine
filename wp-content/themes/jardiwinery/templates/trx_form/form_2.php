<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_template_form_2_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_template_form_2_theme_setup', 1 );
	function jardiwinery_template_form_2_theme_setup() {
		jardiwinery_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'jardiwinery')
			));
	}
}

// Template output
if ( !function_exists( 'jardiwinery_template_form_2_output' ) ) {
	function jardiwinery_template_form_2_output($post_options, $post_data) {
		$address_1 = jardiwinery_get_theme_option('contact_address_1');
		$address_2 = jardiwinery_get_theme_option('contact_address_2');
		$phone = jardiwinery_get_theme_option('contact_phone');
		$fax = jardiwinery_get_theme_option('contact_fax');
		$email = jardiwinery_get_theme_option('contact_email');
		$open_hours = jardiwinery_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_address column-1_3">
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'jardiwinery'); ?></span>
					<span class="sc_form_address_data"><?php jardiwinery_show_layout($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('We are open', 'jardiwinery'); ?></span>
					<span class="sc_form_address_data"><?php jardiwinery_show_layout($open_hours); ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Phone', 'jardiwinery'); ?></span>
					<span class="sc_form_address_data"><?php jardiwinery_show_layout($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('E-mail', 'jardiwinery'); ?></span>
					<span class="sc_form_address_data"><?php jardiwinery_show_layout($email); ?></span>
				</div>
				<?php echo do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]'); ?>
			</div><div class="sc_form_fields column-2_3">
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php jardiwinery_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'jardiwinery'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'jardiwinery'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'jardiwinery'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'jardiwinery'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'jardiwinery'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'jardiwinery'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'jardiwinery'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'jardiwinery'); ?>"></textarea></div>
                    <?php
                    $privacy = trx_utils_get_privacy_text();
                    if (!empty($privacy)) {
                        ?><div class="sc_form_item sc_form_field_checkbox"><?php
                        ?><input type="checkbox" id="i_agree_privacy_policy_sc_form_2" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
                        <label for="i_agree_privacy_policy_sc_form_2"><?php trx_utils_show_layout($privacy); ?></label>
                        </div><?php
                    }
                    ?><div class="sc_form_item sc_form_button"><?php
                        ?><button <?php
                        if (!empty($privacy)) echo ' disabled="disabled"'
                        ?> ><?php
                            if (!empty($args['button_caption']))
                                echo esc_html($args['button_caption']);
                            else
                                esc_html_e('Send message', 'jardiwinery');
                            ?></button>
                    </div>
                    <div class="result sc_infobox"></div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>