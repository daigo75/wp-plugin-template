<?php
if(!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Checks that plugin's requirements are met.
 */
class Aelia_EDD_RequirementsChecks {
	// @var string The namespace for the messages displayed by the class.
	protected $text_domain = 'edd_aelia';
	// @var string The plugin for which the requirements are being checked. Change it in descendant classes.
	protected $plugin_name = 'EDD Template Plugin';

	// @var array An array of PHP extensions required by the plugin
	protected $required_extensions = array(
		//'curl',
	);

	// @var array An array of WordPress plugins (name => version) required by the plugin.
	protected $required_plugins = array(
		'Easy Digital Downloads' => '1.9.8',
		'Aelia Foundation Classes for EDD' => '0.2',
	);

	// @var array Holds a list of the errors related to missing requirements
	protected $requirements_errors = array();

	public function factory() {
		$instance = new self();
		return $instance;
	}

	/**
	 * Checks that one or more PHP extensions are loaded.
	 *
	 * @return array An array of error messages containing one entry for each
	 * extension that is not loaded.
	 */
	protected function check_required_extensions() {
		foreach($this->required_extensions as $extension) {
			if(!extension_loaded($extension)) {
				$this->requirements_errors[] = sprintf(__('Plugin requires "%s" PHP extension.', $this->text_domain),
																							 $extension);
			}
		}
	}

	/**
	 * Checks that the necessary plugins are installed, and that their version is
	 * the expected one.
	 */
	protected function check_required_plugins() {
		foreach($this->required_plugins as $plugin_name => $plugin_version) {
			$plugin_info = $this->is_plugin_active($plugin_name);

			if(is_array($plugin_info)) {
				if(version_compare($plugin_info['Version'], $plugin_version, '<')) {
					$this->requirements_errors[] = sprintf(__('Plugin "%s" must be version "%s" or later.', $this->text_domain),
																								 $plugin_name,
																								 $plugin_version);
				}
			}
			else {
				$this->requirements_errors[] = sprintf(__('Plugin "%s" must be installed and activated.', $this->text_domain),
																							 $plugin_name);
			}
		}
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

		$this->check_required_extensions();
		$this->check_required_plugins();

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
	public static function is_edd_active() {
		if(defined('EDD_ACTIVE')) {
			return EDD_ACTIVE;
		}

		// Test if EDD is installed and active
		if(self::factory()->is_plugin_active('Easy Digital Downloads')) {
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
		foreach($plugins as $path => $plugin_info){
			if((strcasecmp($plugin_info['Name'], $plugin_name) === 0) && is_plugin_active($path)) {
				return $plugin_info;
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
		echo sprintf(__('Plugin "%s" could not be loaded due to missing requirements.', $this->text_domain),
								 $this->plugin_name);
		echo '</h4>';
		echo '<div class="info">';
		echo __('<b>Note</b>: even though the plugin might be showing as "<b><i>active</i></b>", it will not load ' .
						'and its features will not be available until its requirements are met. If you need assistance, ' .
						'on this matter, please <a href="https://aelia.freshdesk.com/helpdesk/tickets/new">contact our ' .
						'Support team</a>.',
						$this->text_domain);
		echo '</div>';
		echo '<ul style="list-style: disc inside">';
		echo '<li>';
		echo implode('</li><li>', $this->requirements_errors);
		echo '</li>';
		echo '</ul>';
		echo '</div>';
	}
}
