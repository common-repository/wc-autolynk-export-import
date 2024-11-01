<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * PHP version 7
 *
 * @category Woo_Autolynk_Export_Import
 * @package  Woo_Autolynk_Export_Import
 * @author   VGP Media <mattb@vgpmedia.ie>
 * @license  GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link     https://vgpmedia.ie/
 * @since    1.0.6
 *
 * @wordpress-plugin
 * Plugin Name:       VGP Import Export for Autolynk in WooCommerce
 * Plugin URI:        https://vgpmedia.ie/
 * Description:       A toolkit that helps to import exports Woo Order For AutoLynk
 * Version:           1.0.6
 * Author:            VGP Media
 * Author URI:        https://profiles.wordpress.org/vgpmedia/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vgp-import-export-for-autolynk-in-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    deactivate_plugins(plugin_basename( __FILE__ ));
}

/**
 * Currently plugin version.
 * Start at version 1.0.6
 */
define( 'WOO_AUTOLYNK_EXPORT_IMPORT_VERSION', '1.0.6' );

/**
 * The code that runs during plugin activation.
 * This is documented in includes/class-woo-autolynk-export-import-activator.php
 *
 * @return Woo WC Autolynk Export Import Actication
 */
function Activate_Woo_Autolynk_Export_import() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-autolynk-export-import-activator.php';
	Woo_autolynk_Export_Import_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This is documented in includes/class-woo-autolynk-export-import-deactivator.php
 *
 * @return Woo WC Autolynk Export Import Deactivation
 */
function Deactivate_Woo_Autolynk_Export_import() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-autolynk-export-import-deactivator.php';
	Woo_autolynk_Export_Import_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'Activate_Woo_Autolynk_Export_import' );
register_deactivation_hook( __FILE__, 'Deactivate_Woo_Autolynk_Export_import' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-autolynk-export-import.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @return Woo WC Autolynk Export Import Object
 * @since  1.0.6
 */
function Run_Woo_Autolynk_Export_import() {
	$plugin = new Woo_autolynk_Export_Import();
	$plugin->run();

}
Run_Woo_Autolynk_Export_import();