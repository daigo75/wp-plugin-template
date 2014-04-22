<?php
namespace Aelia\EDD;
if(!defined('ABSPATH')) exit; // Exit ifaccessed directly

if(class_exists('Aelia\EDD\Aelia_SessionManager')) {
	return;
}

use \Easy_Digital_Downloads;

/**
 * A simple Session handler. Compatible with both WooCommerce 2.0 and earlier.
 */
class Aelia_SessionManager {
	/**
	 * Safely store data into the session. Compatible with WooCommerce 2.0+ and
	 * backwards compatible with previous versions.
	 *
	 * @param string key The Key of the value to retrieve.
	 * @param mixed value The value to set.
	 */
	public static function set_value($key, $value) {
		Easy_Digital_Downloads::instance()->session->set($key, $value);
	}

	/**
	 * Safely retrieve data from the session. Compatible with WooCommerce 2.0+ and
	 * backwards compatible with previous versions.
	 *
	 * @param string key The Key of the value to retrieve.
	 * @param mixed default The default value to return if the key is not found.
	 * @param bool remove_after_get Indicates if the value should be removed after
	 * having been retrieved.
	 * @return mixed The value associated with the key, or the default.
	 */
	public static function get_value($key, $default = null, $remove_after_get = false) {
		$result = Easy_Digital_Downloads::instance()->session->get($key);

		if($remove_after_get) {
			self::delete_value($key);
		}

		return ($result === false) ? $default : $result;
	}

	/**
	 * Safely remove data from the session. Compatible with WooCommerce 2.0+ and
	 * backwards compatible with previous versions.
	 *
	 * @param string key The Key of the value to retrieve.
	 */
	public static function delete_value($key) {
		Easy_Digital_Downloads::instance()->session->set($key, false);
	}
}
