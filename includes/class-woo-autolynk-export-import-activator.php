<?php

/**
 * Fired during plugin activation
 *
 * @link  hb.com
 * @since 1.0.0
 *
 * @package    Woo_autolynk_Export_Import
 * @subpackage Woo_autolynk_Export_Import/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_autolynk_Export_Import
 * @subpackage Woo_autolynk_Export_Import/includes
 * @author     HB <id.email303@gmail.com>
 */
class Woo_autolynk_Export_Import_Activator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		$upload     = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/waei-import-csv/';
		if ( ! is_dir( $upload_dir ) ) {
			mkdir( $upload_dir, 0700 );
		}

			
	}
}
