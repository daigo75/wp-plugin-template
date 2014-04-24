<?php
if(!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Checks that plugin's requirements are met.
 */
class EDD_AeliaRequirementsChecks {
	// @var string The namespace for the messages displayed by the class.
	protected $text_domain = 'aelia';
	// @var string The plugin for which the requirements are being checked
	protected $plugin_name;
	// @var string The plugin file.
	protected $plugin_file;

	// @var array Holds a list of the errors related to missing requirements
	protected $requirements_errors = array();

	public function __construct($plugin_name, $plugin_file) {
		$this->plugin_name = $plugin_name;
		$this->plugin_file = $plugin_file;
	}

	public function factory($plugin_name, $plugin_file) {
		$instance = new self($plugin_name, $plugin_file);
	}

	/**
	 * Checks that one or more PHP extensions are loaded.
	 *
	 * @param array required_extensions An array of extension names.
	 * @return array An array of error messages containing one entry for each
	 * extension that is not loaded.
	 */
	protected function check_required_extensions(array $required_extensions) {
		$errors = array();
		foreach($required_extensions as $extension) {
			if(!extension_loaded($extension)) {
				$errors[] = sprintf(__('Plugin requires "%s" PHP extension.', AELIA_CS_PLUGIN_TEXTDOMAIN),
														$extension);
			}
		}

		return $errors;
	}

	/**
	 * Checks that plugin requirements are satisfied.
	 *
	 * @return bool
	 */
	public function check_requirements() {
		$this->requirements_errors = array();
		if(PHP_VERSION < '5.3') {
			$this->requirements_errors[] = __('Plugin requires PHP 5.3 or greater.', $this->text_domain);
		}

		// Check for EDD presence
		if(!$this->is_edd_active()) {
			$this->requirements_errors[] = __('Easy Digital Downloads plugin must be installed and activated.', $this->text_domain);
		}

		// Check that all required extensions are loaded
		$required_extensions = array(
			//'curl',
		);
		$extension_errors = $this->check_required_extensions($required_extensions);

		$this->requirements_errors = array_merge($this->requirements_errors, $extension_errors);

		$result = empty($this->requirements_errors);

		if(!$result) {
			// If requirements are missing, display the appropriate notices
			add_action('admin_notices', array($this, 'plugin_requirements_notices'));
		}
	}

	/**
	 * Checks if EDD plugin is active, either for the single site or, in
	 * case of WPMU, for the whole network.
	 *
	 * @return bool
	 */
	public function is_edd_active() {
		if(defined('EDD_ACTIVE')) {
			return EDD_ACTIVE;
		}

		// Test if EDD is installed and active
		if($this->is_plugin_active('Easy Digital Downloads')) {
			define('EDD_ACTIVE', true);
			return true;
		}

		return false;
	}

	/**
	 * Checks if a plugin is active and returns a value to indicate it.
	 *
	 * @param string plugin_key The key of the plugin to check.
	 * @return bool
	 */
	public function is_plugin_active($plugin_name) {
		// Require necessary WP Core files
		if(!function_exists('get_plugins')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		$plugins = get_plugins();
		foreach($plugins as $path => $plugin){
			if((strcasecmp($plugin['Name'], $plugin_name) === 0) && is_plugin_active($path)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Display requirements errors that prevented the plugin from being loaded.
	 */
	public function plugin_requirements_notices() {
		if(empty($this->requirements_errors)) {
			return;
		}

		// Inline CSS styles have to be used because plugin is not loaded if
		// requirements are missing, therefore the plugin's CSS files are ignored
		echo '<div class="error fade">';
		echo '<h4 class="edd_aeliamessage_header" style="margin: 1em 0 0 0">';
		echo sprintf(__('Plugin %s could not be loaded due to missing requirements', $this->text_domain),
								 $this->plugin_name);
		echo '</h4>';
		echo '<ul style="list-style: disc inside">';
		echo '<li>';
		echo implode('</li><li>', $this->requirements_errors);
		echo '</li>';
		echo '</ul>';
		echo '</div>';
	}
}
