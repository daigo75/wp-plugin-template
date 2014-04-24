<?php
namespace Aelia\EDD;
if(!defined('ABSPATH')) exit; // Exit if accessed directly

//define('SCRIPT_DEBUG', 1);
//error_reporting(E_ALL);

require_once('lib/classes/base/plugin/aelia-plugin.php');

/**
 * Template plugin.
 **/
class Template_Plugin extends Aelia_Plugin {
	public static $version = '0.1.0';

	public static $plugin_slug = 'edd-aelia-template-plugin';
	public static $text_domain = 'edd-aelia-template-plugin';
	public static $plugin_name = 'Template Plugin';

	public static function factory() {
		// Load Composer autoloader
		require_once(__DIR__ . '/vendor/autoload.php');

		$settings_key = self::$plugin_slug;

		$settings_controller = null;
		$messages_controller = null;
		// Example on how to initialise a settings controller and a messages controller
		//$settings_page_renderer = new Aelia\EDD\Settings_Renderer();
		//$settings_controller = new Aelia\EDD\Settings($settings_key,
		//																						 self::$text_domain,
		//																						 $settings_page_renderer);
		//$messages_controller = new Aelia\EDD\Messages();

		$plugin_instance = new self($settings_controller, $messages_controller);
		return $plugin_instance;
	}

	/**
	 * Constructor.
	 *
	 * @param Aelia\EDD\Settings settings_controller The controller that will handle
	 * the plugin settings.
	 * @param Aelia\EDD\Messages messages_controller The controller that will handle
	 * the messages produced by the plugin.
	 */
	public function __construct($settings_controller,
															$messages_controller) {
		// Load Composer autoloader
		require_once(__DIR__ . '/vendor/autoload.php');

		parent::__construct($settings_controller, $messages_controller);
	}
}

// Instantiate plugin and add it to the set of globals
$GLOBALS[Template_Plugin::$plugin_slug] = Template_Plugin::factory();
