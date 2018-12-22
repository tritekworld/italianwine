<?php
/**
 * JardiWinery Framework: messages subsystem
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('jardiwinery_messages_theme_setup')) {
	add_action( 'jardiwinery_action_before_init_theme', 'jardiwinery_messages_theme_setup' );
	function jardiwinery_messages_theme_setup() {
		// Core messages strings
		add_action('jardiwinery_action_add_scripts_inline', 'jardiwinery_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('jardiwinery_get_error_msg')) {
	function jardiwinery_get_error_msg() {
		return jardiwinery_storage_get('error_msg');
	}
}

if (!function_exists('jardiwinery_set_error_msg')) {
	function jardiwinery_set_error_msg($msg) {
		$msg2 = jardiwinery_get_error_msg();
		jardiwinery_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('jardiwinery_get_success_msg')) {
	function jardiwinery_get_success_msg() {
		return jardiwinery_storage_get('success_msg');
	}
}

if (!function_exists('jardiwinery_set_success_msg')) {
	function jardiwinery_set_success_msg($msg) {
		$msg2 = jardiwinery_get_success_msg();
		jardiwinery_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('jardiwinery_get_notice_msg')) {
	function jardiwinery_get_notice_msg() {
		return jardiwinery_storage_get('notice_msg');
	}
}

if (!function_exists('jardiwinery_set_notice_msg')) {
	function jardiwinery_set_notice_msg($msg) {
		$msg2 = jardiwinery_get_notice_msg();
		jardiwinery_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('jardiwinery_set_system_message')) {
	function jardiwinery_set_system_message($msg, $status='info', $hdr='') {
		update_option(jardiwinery_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('jardiwinery_get_system_message')) {
	function jardiwinery_get_system_message($del=false) {
		$msg = get_option(jardiwinery_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			jardiwinery_del_system_message();
		return $msg;
	}
}

if (!function_exists('jardiwinery_del_system_message')) {
	function jardiwinery_del_system_message() {
		delete_option(jardiwinery_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('jardiwinery_messages_add_scripts_inline')) {
	function jardiwinery_messages_add_scripts_inline() {
        echo '<'.'script'.'>'
			
			. "if (typeof JARDIWINERY_STORAGE == 'undefined') var JARDIWINERY_STORAGE = {};"
			
			// Strings for translation
			. 'JARDIWINERY_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'jardiwinery')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'jardiwinery')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'jardiwinery')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'jardiwinery')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'jardiwinery')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'jardiwinery')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'jardiwinery')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'jardiwinery')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'jardiwinery')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'jardiwinery')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'jardiwinery')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'jardiwinery')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'jardiwinery')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'jardiwinery')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'jardiwinery')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'jardiwinery')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'jardiwinery')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'jardiwinery')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'jardiwinery')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'jardiwinery')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'jardiwinery')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'jardiwinery')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'jardiwinery')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'jardiwinery')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'jardiwinery')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'jardiwinery')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'jardiwinery')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'jardiwinery')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'jardiwinery')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'jardiwinery')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'jardiwinery')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'jardiwinery')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'jardiwinery')) . '"'
				. '};'

            . '<'.'/script'.'>';
	}
}
?>