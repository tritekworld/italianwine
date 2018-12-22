<?php
/**
 * JardiWinery Framework: theme variables storage
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('jardiwinery_storage_get')) {
	function jardiwinery_storage_get($var_name, $default='') {
		global $JARDIWINERY_STORAGE;
		return isset($JARDIWINERY_STORAGE[$var_name]) ? $JARDIWINERY_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('jardiwinery_storage_set')) {
	function jardiwinery_storage_set($var_name, $value) {
		global $JARDIWINERY_STORAGE;
		$JARDIWINERY_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('jardiwinery_storage_empty')) {
	function jardiwinery_storage_empty($var_name, $key='', $key2='') {
		global $JARDIWINERY_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($JARDIWINERY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($JARDIWINERY_STORAGE[$var_name][$key]);
		else
			return empty($JARDIWINERY_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('jardiwinery_storage_isset')) {
	function jardiwinery_storage_isset($var_name, $key='', $key2='') {
		global $JARDIWINERY_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($JARDIWINERY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($JARDIWINERY_STORAGE[$var_name][$key]);
		else
			return isset($JARDIWINERY_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('jardiwinery_storage_inc')) {
	function jardiwinery_storage_inc($var_name, $value=1) {
		global $JARDIWINERY_STORAGE;
		if (empty($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = 0;
		$JARDIWINERY_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('jardiwinery_storage_concat')) {
	function jardiwinery_storage_concat($var_name, $value) {
		global $JARDIWINERY_STORAGE;
		if (empty($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = '';
		$JARDIWINERY_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('jardiwinery_storage_get_array')) {
	function jardiwinery_storage_get_array($var_name, $key, $key2='', $default='') {
		global $JARDIWINERY_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($JARDIWINERY_STORAGE[$var_name][$key]) ? $JARDIWINERY_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($JARDIWINERY_STORAGE[$var_name][$key][$key2]) ? $JARDIWINERY_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('jardiwinery_storage_set_array')) {
	function jardiwinery_storage_set_array($var_name, $key, $value) {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if ($key==='')
			$JARDIWINERY_STORAGE[$var_name][] = $value;
		else
			$JARDIWINERY_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('jardiwinery_storage_set_array2')) {
	function jardiwinery_storage_set_array2($var_name, $key, $key2, $value) {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if (!isset($JARDIWINERY_STORAGE[$var_name][$key])) $JARDIWINERY_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$JARDIWINERY_STORAGE[$var_name][$key][] = $value;
		else
			$JARDIWINERY_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('jardiwinery_storage_set_array_after')) {
	function jardiwinery_storage_set_array_after($var_name, $after, $key, $value='') {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if (is_array($key))
			jardiwinery_array_insert_after($JARDIWINERY_STORAGE[$var_name], $after, $key);
		else
			jardiwinery_array_insert_after($JARDIWINERY_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('jardiwinery_storage_set_array_before')) {
	function jardiwinery_storage_set_array_before($var_name, $before, $key, $value='') {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if (is_array($key))
			jardiwinery_array_insert_before($JARDIWINERY_STORAGE[$var_name], $before, $key);
		else
			jardiwinery_array_insert_before($JARDIWINERY_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('jardiwinery_storage_push_array')) {
	function jardiwinery_storage_push_array($var_name, $key, $value) {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($JARDIWINERY_STORAGE[$var_name], $value);
		else {
			if (!isset($JARDIWINERY_STORAGE[$var_name][$key])) $JARDIWINERY_STORAGE[$var_name][$key] = array();
			array_push($JARDIWINERY_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('jardiwinery_storage_pop_array')) {
	function jardiwinery_storage_pop_array($var_name, $key='', $defa='') {
		global $JARDIWINERY_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($JARDIWINERY_STORAGE[$var_name]) && is_array($JARDIWINERY_STORAGE[$var_name]) && count($JARDIWINERY_STORAGE[$var_name]) > 0) 
				$rez = array_pop($JARDIWINERY_STORAGE[$var_name]);
		} else {
			if (isset($JARDIWINERY_STORAGE[$var_name][$key]) && is_array($JARDIWINERY_STORAGE[$var_name][$key]) && count($JARDIWINERY_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($JARDIWINERY_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('jardiwinery_storage_inc_array')) {
	function jardiwinery_storage_inc_array($var_name, $key, $value=1) {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if (empty($JARDIWINERY_STORAGE[$var_name][$key])) $JARDIWINERY_STORAGE[$var_name][$key] = 0;
		$JARDIWINERY_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('jardiwinery_storage_concat_array')) {
	function jardiwinery_storage_concat_array($var_name, $key, $value) {
		global $JARDIWINERY_STORAGE;
		if (!isset($JARDIWINERY_STORAGE[$var_name])) $JARDIWINERY_STORAGE[$var_name] = array();
		if (empty($JARDIWINERY_STORAGE[$var_name][$key])) $JARDIWINERY_STORAGE[$var_name][$key] = '';
		$JARDIWINERY_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('jardiwinery_storage_call_obj_method')) {
	function jardiwinery_storage_call_obj_method($var_name, $method, $param=null) {
		global $JARDIWINERY_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($JARDIWINERY_STORAGE[$var_name]) ? $JARDIWINERY_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($JARDIWINERY_STORAGE[$var_name]) ? $JARDIWINERY_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('jardiwinery_storage_get_obj_property')) {
	function jardiwinery_storage_get_obj_property($var_name, $prop, $default='') {
		global $JARDIWINERY_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($JARDIWINERY_STORAGE[$var_name]->$prop) ? $JARDIWINERY_STORAGE[$var_name]->$prop : $default;
	}
}
?>