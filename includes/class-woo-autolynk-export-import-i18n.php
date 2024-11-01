<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link  hb.com
 * @since 1.0.0
 *
 * @package    Woo_autolynk_Export_Import
 * @subpackage Woo_autolynk_Export_Import/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_autolynk_Export_Import
 * @subpackage Woo_autolynk_Export_Import/includes
 * @author     HB <id.email303@gmail.com>
 */
class Woo_autolynk_Export_Import_i18n {



	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-autolynk-export-import',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
