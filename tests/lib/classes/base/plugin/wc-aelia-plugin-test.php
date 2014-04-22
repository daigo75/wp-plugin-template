<?php

use Aelia\EDD\Aelia_Plugin;
use Aelia\EDD\Settings_Renderer;
use Aelia\EDD\Settings;
use Aelia\EDD\Messages;

/**
 * Tests for the base plugin class.
 */
class Aelia_Plugin_Test extends WP_UnitTestCase {
	const SETTINGS_KEY = 'edd-aelia-plugin-test';
	const TEXT_DOMAIN = 'edd-aelia-plugin-test';

	public function setUp() {
		parent::setUp();

		$settings_page_renderer = new Settings_Renderer();
		$this->settings = new Settings(self::SETTINGS_KEY,
																						self::TEXT_DOMAIN,
																						$settings_page_renderer);
		$this->messages = new Messages();

		$this->plugin = new Aelia_Plugin($this->settings, $this->messages);

		$plugin_class = get_class($this->plugin);
		$GLOBALS[$plugin_class::$plugin_slug] = $this->plugin;
	}

	public function test_settings_controller() {
		$controller = $this->plugin->settings_controller();
		$this->assertSame($controller, $this->settings);
	}

	public function test_messages_controller() {
		$controller = $this->plugin->messages_controller();
		$this->assertSame($controller, $this->messages);
	}

	public function test_instance() {
		$plugin_instance = $this->plugin->instance();
		$this->assertSame($plugin_instance, $this->plugin);
	}

	public function test_settings() {
		$controller = Aelia_Plugin::settings();
		$this->assertSame($controller, $this->settings);
	}

	public function test_messages() {
		$controller = Aelia_Plugin::messages();
		$this->assertSame($controller, $this->messages);
	}

	public function test_get_error_message() {
		$message = $this->plugin->get_error_message(Messages::ERR_FILE_NOT_FOUND);
		$this->assertTrue(!empty($message));
	}

	/* The tests below simply check that the methods run without errors. */
	public function test_wordpress_loaded() {
		$this->plugin->wordpress_loaded();

		$frontend_script_registered = wp_script_is(Aelia_Plugin::$plugin_slug . '-frontend', 'registered');
		$this->assertTrue($frontend_script_registered);

		$frontend_styles_registered = wp_style_is(Aelia_Plugin::$plugin_slug . '-frontend', 'registered');
		$this->assertTrue($frontend_styles_registered);

		$this->assertTrue(true);
	}

	public function test_plugins_loaded() {
		$this->plugin->plugins_loaded();
		$this->assertTrue(true);
	}

	public function test_register_widgets() {
		$this->plugin->register_widgets();
		$this->assertTrue(true);
	}

	public function test_load_admin_scripts() {
		$this->plugin->load_admin_scripts();

		// Base plugin should NOT enqueue Admin styles
		$admin_styles_enqueued = wp_style_is(Aelia_Plugin::$plugin_slug . '-admin', 'enqueued');
		$this->assertFalse($admin_styles_enqueued);
	}

	public function test_load_frontend_scripts() {
		$this->plugin->load_frontend_scripts();

		// Base plugin should NOT enqueue Admin scripts
		$frontend_styles_enqueued = wp_style_is(Aelia_Plugin::$plugin_slug . '-frontend', 'enqueued');
		$this->assertFalse($frontend_styles_enqueued);
	}

	public function test_setup() {
		$this->plugin->setup();
		$this->assertTrue(true);
	}

	public function test_cleanup() {
		$this->plugin->cleanup();
		$this->assertTrue(true);
	}

	public function test_is_edd_active() {
		$this->assertTrue(is_bool(Aelia_Plugin::is_edd_active()));
	}

	public function test_path() {
		$plugin_path = $this->plugin->path('plugin');
		$this->assertTrue(is_string($plugin_path) && !empty($plugin_path));
	}

	public function test_url() {
		$plugin_url = $this->plugin->url('plugin');
		$this->assertTrue(is_string($plugin_url) && !empty($plugin_url));
	}

	/**
	 * @expectedException Aelia_NotImplementedException
	 */
	public function test_factory() {
		Aelia_Plugin::factory();
	}

	public function test_plugin_dir() {
		$plugin_dir = $this->plugin->plugin_dir();
		$this->assertTrue(!empty($plugin_dir));
	}
}
