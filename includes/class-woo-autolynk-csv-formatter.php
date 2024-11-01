<?php

/**
 * This class is to generate csv from given Data
 *
 * @category   Woo_Autolynk_Export_Import
 * @package    Woo_Autolynk_Export_Import
 * @subpackage Woo_Autolynk_Export_Import/includes
 * @author     VGP Media <mattb@vgpmedia.ie>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://vgpmedia.ie
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_autolynk_Csv_Formatter
 *
 * @category   Woo_Autolynk_Export_Import
 * @package    Woo_Autolynk_Export_Import
 * @subpackage Woo_Autolynk_Export_Import/includes
 * @author     VGP Media <mattb@vgpmedia.ie>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://vgpmedia.ie
 * @since      1.0.0
 */

class WC_autolynk_Csv_Formatter {

	var $formater;
	public function __construct( $filename ) {
		$this->formater = fopen( $filename, 'wb' );
	}

	public function autolynk_add_csv_row_data( $header ) {
		fputcsv( $this->formater, $header );
	}

	public function autolynk_finish() {
		fclose( $this->formater );
	}
}
