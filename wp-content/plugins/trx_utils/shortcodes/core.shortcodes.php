<?php
/**
 * JardiWinery Framework: shortcodes manipulations
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jardiwinery_sc_theme_setup')) {
	add_action( 'jardiwinery_action_init_theme', 'jardiwinery_sc_theme_setup', 1 );
	function jardiwinery_sc_theme_setup() {
		// Add sc stylesheets
		add_action('jardiwinery_action_add_styles', 'jardiwinery_sc_add_styles', 1);
	}
}

if (!function_exists('jardiwinery_sc_theme_setup2')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_sc_theme_setup2' );
	function jardiwinery_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'jardiwinery_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('jardiwinery_sc_prepare_content')) jardiwinery_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('jardiwinery_shortcode_output', 'jardiwinery_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'jardiwinery_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'jardiwinery_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'jardiwinery_sc_selector_add_in_toolbar', 11);

	}
}


// Register shortcodes styles
if ( !function_exists( 'jardiwinery_sc_add_styles' ) ) {
	//add_action('jardiwinery_action_add_styles', 'jardiwinery_sc_add_styles', 1);
	function jardiwinery_sc_add_styles() {
		// Shortcodes
		wp_enqueue_style( 'jardiwinery-shortcodes-style',	trx_utils_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Register shortcodes init scripts
if ( !function_exists( 'jardiwinery_sc_add_scripts' ) ) {
	//add_filter('jardiwinery_shortcode_output', 'jardiwinery_sc_add_scripts', 10, 4);
	function jardiwinery_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		if (jardiwinery_storage_empty('shortcodes_scripts_added')) {
			jardiwinery_storage_set('shortcodes_scripts_added', true);
			wp_enqueue_script( 'jardiwinery-shortcodes-script', trx_utils_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('jardiwinery_sc_prepare_content')) {
	function jardiwinery_sc_prepare_content() {
		if (function_exists('jardiwinery_sc_clear_around')) {
			$filters = array(
				array('jardiwinery', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('jardiwinery_exists_woocommerce') && jardiwinery_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'jardiwinery_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('jardiwinery_sc_excerpt_shortcodes')) {
	function jardiwinery_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('jardiwinery_sc_clear_around')) {
	function jardiwinery_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// JardiWinery shortcodes load scripts
if (!function_exists('jardiwinery_sc_load_scripts')) {
	function jardiwinery_sc_load_scripts() {
		wp_enqueue_script( 'jardiwinery-shortcodes_admin-script', trx_utils_get_file_url('shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		wp_enqueue_script( 'jardiwinery-selection-script',  jardiwinery_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
		wp_localize_script( 'jardiwinery-shortcodes_admin-script', 'JARDIWINERY_SHORTCODES_DATA', jardiwinery_storage_get('shortcodes') );
	}
}

// JardiWinery shortcodes prepare scripts
if (!function_exists('jardiwinery_sc_prepare_scripts')) {
	function jardiwinery_sc_prepare_scripts() {
		if (!jardiwinery_storage_isset('shortcodes_prepared')) {
			jardiwinery_storage_set('shortcodes_prepared', true);
			?>
            <<?php echo esc_attr(jardiwinery_storage_get('tag_open'));?>>
				jQuery(document).ready(function(){
					JARDIWINERY_STORAGE['shortcodes_cp'] = '<?php echo is_admin() ? (!jardiwinery_storage_empty('to_colorpicker') ? jardiwinery_storage_get('to_colorpicker') : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
            <<?php echo esc_attr(jardiwinery_storage_get('tag_close'));?>>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('jardiwinery_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','jardiwinery_sc_selector_add_in_toolbar', 11);
	function jardiwinery_sc_selector_add_in_toolbar(){

		if ( !jardiwinery_options_is_used() ) return;

		jardiwinery_sc_load_scripts();
		jardiwinery_sc_prepare_scripts();

		$shortcodes = jardiwinery_storage_get('shortcodes');
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'jardiwinery').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		jardiwinery_show_layout($shortcodes_list);
	}
}

// JardiWinery shortcodes builder settings
require_once trx_utils_get_file_dir( 'shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
    require_once trx_utils_get_file_dir( 'shortcodes/shortcodes_vc.php');
}

// JardiWinery shortcodes implementation
// Using require_once trx_utils_get_file_dir(), because shortcodes can be replaced in the child theme
require_once trx_utils_get_file_dir('shortcodes/trx_basic/anchor.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/audio.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/blogger.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/br.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/call_to_action.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/chat.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/columns.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/content.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/form.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/googlemap.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/hide.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/image.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/infobox.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/line.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/list.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/price_block.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/promo.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/quote.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/reviews.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/search.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/section.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/skills.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/slider.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/socials.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/table.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/title.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/twitter.php');
require_once trx_utils_get_file_dir('shortcodes/trx_basic/video.php');

require_once trx_utils_get_file_dir('shortcodes/trx_optional/accordion.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/button.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/countdown.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/dropcaps.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/gap.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/highlight.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/icon.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/number.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/parallax.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/popup.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/price.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/tabs.php');
require_once trx_utils_get_file_dir('shortcodes/trx_optional/tooltip.php');
?>