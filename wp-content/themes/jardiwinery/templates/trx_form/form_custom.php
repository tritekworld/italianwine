<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'jardiwinery_template_form_custom_theme_setup' ) ) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_template_form_custom_theme_setup', 1 );
	function jardiwinery_template_form_custom_theme_setup() {
		jardiwinery_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'jardiwinery')
			));
	}
}

// Template output
if ( !function_exists( 'jardiwinery_template_form_custom_output' ) ) {
	function jardiwinery_template_form_custom_output($post_options, $post_data) {
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php
			jardiwinery_sc_form_show_fields($post_options['fields']);
			jardiwinery_show_layout($post_options['content']);
			?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>