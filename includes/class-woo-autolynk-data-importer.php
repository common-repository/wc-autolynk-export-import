<?php
/**
 * This file is for Data Import form CSV
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
 * This class is to import the CSV file and add the customer note for individual order
 *
 * @category   Woo_Autolynk_Export_Import
 * @package    Woo_Autolynk_Export_Import
 * @subpackage Woo_Autolynk_Export_Import/includes
 * @author     VGP Media <mattb@vgpmedia.ie>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://vgpmedia.ie
 * @since      1.0.0
 */

class WC_Autolynk_Order_Importer_Ajax {


	/**
	 * This function will save the Tracking Number and Customer Note settings form Import Tab.
	 *
	 * @return JSON
	 */
	public function ajax_import_tracking() {

		if ( ! empty( $_POST['customer_note'] ) ) {
			update_option( 'woei_customer_tracking_note', wp_kses( $_POST['customer_note'], true ) );
		}
		if ( ! empty( $_POST['tracking_url'] ) ) {
			update_option( 'woei_tracking_url', esc_url_raw( $_POST['tracking_url'] ) );
		}
		echo json_encode( array( 'status' => true ) );
		exit();
	}

	/**
	 * This function will Start Importing the CSV uploaded from Import Tab.
	 * This function will upload the CSV in Upload Directory/waei-import-csv/ Folder
	 * Function will return the full path of that uploaded csv with total rows.
	 *
	 * @return JSON
	 */
	public function ajax_import_csv() {

		$tracking_url  = get_option( 'woei_tracking_url' );
		$customer_note = get_option( 'woei_customer_tracking_note', $default_msg );
		if ( ! empty( trim( $tracking_url ) ) ) {

			$fileExtensions = array( 'csv' );
			$fileName       = sanitize_file_name( $_FILES['order_csv']['name'] );
			$fileSize       = sanitize_text_field( $_FILES['order_csv']['size'] );
			$fileTmpName    = sanitize_text_field( $_FILES['order_csv']['tmp_name'] );
			$fileType       = sanitize_text_field( $_FILES['order_csv']['type'] );
			$fileExtension  = strtolower( end( explode( '.', $fileName ) ) );

			if ( ! in_array( $fileExtension, $fileExtensions ) ) {
				$errors = '<p>This file extension is not allowed. Please upload a CSV file</p>';
				echo json_encode(
					array(
						'success' => false,
						'msg'     => $errors,
					)
				);
				exit();
			}

			$uploadDir      = wp_upload_dir();
			$uploadDir      = $uploadDir['basedir'] . '/waei-import-csv/';
			$uploadfilepath = $uploadDir . $fileName;
			@mkdir( $uploadDir, '0777', true );
			$didUpload = move_uploaded_file( $fileTmpName, $uploadfilepath );

			if ( $didUpload ) {
				$file_content = file( $uploadfilepath, FILE_SKIP_EMPTY_LINES );
				$total_line   = count( $file_content );

				echo json_encode(
					array(
						'success'   => true,
						'file'      => $fileName,
						'total_row' => $total_line - 2,
						'file_id'   => current_time( 'timestamp' ),
					)
				);
				exit();
			} else {
				echo json_encode(
					array(
						'success' => false,
						'msg'     => '<p>error in file upload</p>',
					)
				);
				exit();
			}
		} else {
			echo json_encode(
				array(
					'success' => false,
					'msg'     => 'you have not set the tracking url in Admin.',
				)
			);
			exit();
		}

	}

	/**
	 * This function will Start Importing the uploaded csv with one by one record
	 * This function will add the customer note based on order id.
	 * once the customer note is added, system will send an auto Email to customer.
	 *
	 * @return JSON
	 */
	public function ajax_import_start_csv() {
	    
	    
	    $log_file = plugin_dir_path( __FILE__ ) ."error_msg.log";
	    unlink($log_file);
	    ini_set("log_errors", TRUE);  
        ini_set('error_log', $log_file); 
        

		$default_msg   = sprintf( '%s', __( 'Your Trancking No is ##TRACK-NO##', 'woo-autolynk-export-import' ) );
		$tracking_url  = get_option( 'woei_tracking_url' );
		$customer_note = get_option( 'woei_customer_tracking_note', $default_msg );
		if ( ! empty( trim( $tracking_url ) ) ) {

			$uploadDir      = wp_upload_dir();
			$uploadDir      = $uploadDir['basedir'] . '/waei-import-csv/';
			
			$uploadfilepath = $uploadDir . sanitize_file_name( $_POST['file'] );

			set_transient( 'waei-import-file-' . sanitize_text_field( $_POST['file_id'] ), 0, 600 );

			$file_data = fopen( $uploadfilepath, 'r' );

			fgetcsv( $file_data );
			fgetcsv( $file_data );
			$count    = 0;
			$scount   = 0;
			$ecount   = 0;
			$errorArr = array();
			while ( $row = fgetcsv( $file_data ) ) {
				if ( ! empty( $row[1] ) && ! empty( $row[8] ) ) {
					$order_id    = $row[8];
					$order_number  =$row[0];
					$tracking_no = sanitize_text_field( $row[1] );
					if ( $this->check_order_exist( $order_id ) ) {
						if ( $this->verify_order_data( $order_id, sanitize_text_field( $row[12] ) ) ) {
							self::add_tracking_note( $tracking_no, $tracking_url, $customer_note, $order_id );
							$scount++;
						} else {
							$ecount++;
							$error_message="Order data not match for order id: $order_id ";
							error_log("$error_message\n"); 
							array_push( $errorArr, $order_id );
						}
					} else {
						$ecount++;
						//if(){}
						$error_message="Order id: $order_id not found";
						error_log("$error_message\n"); 
						array_push( $errorArr, $order_id );
					
					}
				}
				$count ++;
				set_transient( 'waei-import-file-' . sanitize_text_field( $_POST['file_id'] ), $count, 600 );
			}
			  
			    $msg = 'total ' .$scount. ' records imported ';
			
			if ( $ecount > 0 ) {
			    $home_url=home_url();
			    $more_details_liknk="<a class='download_more' href='$home_url/wp-content/plugins/wc-autolynk-export-import/includes/error_msg.log' target='_blank'>More Details</a>";
				$msg .= 'and total ' .$ecount. ' records have issue. Click for ' .$more_details_liknk;
			    
			}
		
			echo json_encode(
				array(
					'success' => true,
					'msg'     =>  $msg ,
				)
			);
			exit();
		} else {
			echo json_encode(
				array(
					'success' => false,
					'msg'     => esc_html( 'you have not set the tracking url in Admin.' ),
				)
			);
			error_log("$error_message\n"); 
			exit();
		}
	
           
	}

	/**
	 * This function send the Import Progress like how many rows are imported
	 * This function will call while the Import Ajax function is loading.
	 * This function is for the Progressbar
	 *
	 * @return JSON
	 */
	public function ajax_import_csv_progress() {
		$count = get_transient( 'waei-import-file-' . sanitize_text_field( $_POST['file_id'] ) );
		echo $count;
		exit();
	}

	/**
	 * This function will add Customer Note to Order
	 *
	 * @param $no       int Tracking No form CSV
	 * @param $url      string Tracking URL
	 * @param $msg      string customer Note Message
	 * @param $order_id int Order Id
	 *
	 * @return JSON
	 */
	public static function add_tracking_note( $no, $url, $msg, $order_id ) {
		$customer_note = wp_kses( $msg, true );
		$customer_note = str_replace( '##URL##', $url, $customer_note );
		$customer_note = str_replace( '##TRACK-NO##', $no, $customer_note );

		$order = wc_get_order( $order_id );
		$order->add_order_note( $customer_note, 1 );
		$order->update_status('completed');
		unset( $order );
		return true;
	}

	/**
	 * This function will check if order exist with perticular Order Id
	 *
	 * @param $order_id int Order Id
	 *
	 * @return boolean
	 */
	public function check_order_exist( $order_id ) {
		if ( get_post_type( $order_id ) == 'shop_order' ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * This function will verify the order data by customer surname
	 *
	 * @param $order_id         int Order Id
	 * @param $customer_surname string Customer Last Name
	 *
	 * @return boolean
	 */
	public function verify_order_data( $order_id, $customer_surname ) {
		$order     = wc_get_order( $order_id );
		$last_name = $order->get_shipping_last_name();
		unset( $order );
		if ( remove_accents(trim( $last_name )) === trim( $customer_surname ) ) {
			return true;
		} else {
			return false;
		}
	}
}
