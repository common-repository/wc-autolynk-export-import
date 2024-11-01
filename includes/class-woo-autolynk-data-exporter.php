<?php

/**
 * This class is to get the order data, product data, generate csv and download csv
 * functions
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

// $order_fields = array();

/**
 * This class is to get the order data, product data, generate csv and download csv
 * functions
 *
 * @category   Woo_Autolynk_Export_Import
 * @package    Woo_Autolynk_Export_Import
 * @subpackage Woo_Autolynk_Export_Import/includes
 * @author     VGP Media <mattb@vgpmedia.ie>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://vgpmedia.ie
 * @since      1.0.0
 */
class WC_Autolynk_Order_Exporter_Ajax {


	/**
	 * This function will return the Product on Export Page Product Search Field
	 *
	 * @return array with Product Data
	 */
	public function ajax_get_woei_products() {

		global $wpdb;
		$like  = $wpdb->esc_like( sanitize_text_field($_REQUEST['q']) );
		$query = '
                SELECT      post.ID as id,post.post_title as text,att.ID as photo_id,att.guid as photo_url
                FROM        ' . $wpdb->posts . ' as post
                LEFT JOIN  ' . $wpdb->posts . " AS att ON post.ID=att.post_parent AND att.post_type='attachment'
                WHERE       post.post_title LIKE %s
                AND         post.post_type = 'product'
                AND         post.post_status <> 'trash'
                GROUP BY    post.ID
                ORDER BY    post.post_title
                LIMIT " . intval( 10 );

		$products = $wpdb->get_results( $wpdb->prepare( $query, '%' . $like . '%' ) );
		foreach ( $products as $key => $product ) {
			if ( $product->photo_id ) {
				$photo                       = wp_get_attachment_image_src( $product->photo_id, 'thumbnail' );
				$products[ $key ]->photo_url = $photo[0];
			} else {
				unset( $products[ $key ]->photo_url );
			}
		}
		echo json_encode( $products );
	}

	/**
	 * This function will be called from ajax when we click on export the order button
	 *
	 * @return json array with generated tmp csv file with full path.
	 */
	public function ajax_order_exporter() {
		global $wpdb;

		$generator = new WC_Autolynk_Csv_Data_Generator();
		$tmp       = self::get_filename( sanitize_text_field($_POST['settings']['format']) );

		$formater = fopen( $tmp, 'w' );

		$sql       = self::get_sql_order_ids( $_POST['settings'] );
		$order_ids = $wpdb->get_col( $sql );

		$headerRow = $generator::generate_order_header();
		
		$orderMsgNew = '';

		fputcsv( $formater, $headerRow );
		foreach ( $order_ids as $order_id ) {
		    
		    $order        = new WC_Order( $order_id );
            $iteams_count = $order->get_item_count();
            if($iteams_count > 4){
                $orderMsgNew .= 'Order number '. $order_id .' contains more than four items, please manually check the
customs declaration in Autolynk';
            }
            
			$row_data = $generator::generate_order_row_data( $order );
			
			
        
			// $clear_data = self::generateCleanArr($row_data);
			fputcsv( $formater, $row_data );
		}
		fclose( $formater );

		echo json_encode( array( 'tmpname' => $tmp, 'orderMsgNew' => $orderMsgNew ) );
		exit();

	}

	/**
	 * This function will be called from ajax to download the csv file
	 *
	 * @return csv file force download.
	 */
	public function ajax_order_download() {
		$tmp = sanitize_text_field( $_GET['tmp'] );

		$downloadfilename = 'order-' . date( 'Y-m-d-H-i-s' ) . '.csv';
		header( 'Content-Description: File Transfer' );
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename=' . $downloadfilename );
		header( 'Expires: 0' );
		header( 'Cache-Control: no-cache must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . filesize( $tmp ) );
		readfile( $tmp );
		unlink( $tmp );
		exit();
	}

	/**
	 * This function will generate the sql query string to get the order ids based on
	 * filter data from export tab form.
	 *
	 * @param $settings array
	 *
	 * @return order query.
	 */
	public static function get_sql_order_ids( $settings ) {
		global $wpdb;

		/* Get Filter Query by Order filter */
		$where = array( 1 );
		self::apply_order_filters_to_sql( $where, $settings );
		$order_sql = join( ' AND ', $where );

		/*Get Filter Query by Product Filter*/
		$product_where = self::apply_product_filters_to_sql( $settings );

		$wc_order_items_meta        = "{$wpdb->prefix}woocommerce_order_itemmeta";
		$left_join_order_items_meta = $order_items_meta_where = array();
		// filter by product
		if ( $product_where ) {
			$left_join_order_items_meta[] = "LEFT JOIN $wc_order_items_meta  AS orderitemmeta_product ON orderitemmeta_product.order_item_id = order_items.order_item_id";
			$order_items_meta_where[]     = " (orderitemmeta_product.meta_key IN ('_variation_id', '_product_id') $product_where)";
		}

		$order_items_meta_where = join( ' AND ', $order_items_meta_where );
		if ( $order_items_meta_where ) {
			$order_items_meta_where = ' AND ' . $order_items_meta_where;
		}
		$left_join_order_items_meta = join( '  ', $left_join_order_items_meta );

		$order_items_where = '';
		if ( $order_items_meta_where ) {
			$order_items_where = " AND orders.ID IN (SELECT DISTINCT order_items.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_items
				$left_join_order_items_meta
				WHERE order_item_type='line_item' $order_items_meta_where )";
		}

		$sql  = "SELECT ID AS order_id from {$wpdb->posts} AS orders where orders.post_type in ( 'shop_order') AND $order_sql $order_meta_where $order_items_where";
		$sql .= ' ORDER BY ' . $settings['sort'] . ' ' . $settings['sort_direction'];
		return $sql;
	}

	/**
	 * This function will generate the sql where query for getting the order ids.
	 *
	 * @param $where    string
	 * @param $settings array
	 *
	 * @return order query.
	 */
	private static function apply_order_filters_to_sql( &$where, $settings ) {
		global $wpdb;

		// default filter by date
		if ( ! isset( $settings['export_rule_field'] ) ) {
			$settings['export_rule_field'] = 'modified';
		}

		$date_field     = 'date';
		$use_timestamps = false;
		$where_meta     = array();

		// export and date rule

		foreach ( self::get_date_range( $settings, true, $use_timestamps ) as $date ) {
			self::add_date_filter( $where, $where_meta, $date_field, $date );
		}

		// end export and date rule

		if ( $settings['statuses'] ) {
			$values = self::sql_subset( $settings['statuses'] );
			if ( $values ) {
				$where[] = "orders.post_status in ($values)";
			}
		}

		// for date_paid or date_completed
		if ( $where_meta ) {
			$where_meta = join( ' AND ', $where_meta );
			$where[]    = "orders.ID  IN ( SELECT post_id FROM {$wpdb->postmeta} AS order_$date_field WHERE order_$date_field.meta_key ='_$date_field' AND $where_meta)";
		}

		// skip child orders?
		if ( $settings['skip_suborders'] and ! $settings['export_refunds'] ) {
			$where[] = 'orders.post_parent=0';
		}

		// Skip drafts and deleted
		$where[] = "orders.post_status NOT in ('auto-draft','trash')";
	}

	/**
	 * This function will apply the filter by selected products
	 *
	 * @param $settings array
	 *
	 * @return order query.
	 */
	public static function apply_product_filters_to_sql( $settings ) {

		// deep level still
		$exact_product_where = '';
		if ( $settings['products'] ) {
			$values = self::sql_subset( $settings['products'] );
			if ( $values ) {
				$exact_product_where = "AND orderitemmeta_product.meta_value IN ($values)";
			}
		}
		return $exact_product_where;
	}

	/**
	 * This function will return the timestemp
	 *
	 * @param $ts int
	 *
	 * @return int
	 */
	public static function is_datetime_timestamp( $ts ) {
		return $ts % ( 24 * 3600 ) > 0;
	}

	/**
	 * Function will return date range for filter Query
	 *
	 * @param $settings       array
	 * @param $is_for_sql     boolean
	 * @param $use_timestamps boolean
	 *
	 * @return string
	 */
	public static function get_date_range( $settings, $is_for_sql, $use_timestamps = false ) {

		$result   = array();
		$diff_utc = current_time( 'timestamp' ) - time();

		// fixed date range
		if ( ! empty( $settings['from_date'] ) or ! empty( $settings['to_date'] ) ) {
			if ( $settings['from_date'] ) {
				$ts = strtotime( $settings['from_date'] );
				if ( self::is_datetime_timestamp( $ts ) ) {
					$from_date = date( 'Y-m-d H:i:s', $ts );
				} else {
					$from_date = date( 'Y-m-d', $ts ) . ' 00:00:00';
				}
				if ( $is_for_sql ) {
					if ( $use_timestamps ) {
						$from_date  = mysql2date( 'G', $from_date );
						$from_date -= $diff_utc;
					}
					$from_date = sprintf( ">='%s'", $from_date );
				}
				$result['from_date'] = $from_date;
			}

			if ( $settings['to_date'] ) {
				$ts = strtotime( $settings['to_date'] );
				if ( self::is_datetime_timestamp( $ts ) ) {
					$to_date = date( 'Y-m-d H:i:s', $ts );
				} else {
					$to_date = date( 'Y-m-d', $ts ) . ' 23:59:59';
				}
				if ( $is_for_sql ) {
					if ( $use_timestamps ) {
						$to_date  = mysql2date( 'G', $to_date );
						$to_date -= $diff_utc;
					}
					$to_date = sprintf( "<='%s'", $to_date );
				}
				$result['to_date'] = $to_date;
			}

			return $result;
		}

		$_time = current_time( 'timestamp', 0 );

		$export_rule = isset( $settings['export_rule'] ) ? $settings['export_rule'] : '';

		switch ( $export_rule ) {
			case 'none':
				unset( $from_date );
				unset( $to_date );
				break;
			case 'last_run':
				$last_run = isset( $settings['schedule']['last_run'] ) ? $settings['schedule']['last_run'] : '';
				if ( isset( $last_run ) and $last_run ) {
					$from_date = date( 'Y-m-d H:i:s', $last_run );
				}
				break;
			case 'today':
				$_date = date( 'Y-m-d', $_time );

				$from_date = sprintf( '%s %s', $_date, '00:00:00' );
				$to_date   = sprintf( '%s %s', $_date, '23:59:59' );
				break;
			case 'this_week':
				$day        = ( date( 'w', $_time ) + 6 ) % 7;// 0 - Sun , must be Mon = 0
				$_date      = date( 'Y-m-d', $_time );
				$week_start = date( 'Y-m-d', strtotime( $_date . ' -' . $day . ' days' ) );
				$week_end   = date( 'Y-m-d', strtotime( $_date . ' +' . ( 6 - $day ) . ' days' ) );

				$from_date = sprintf( '%s %s', $week_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $week_end, '23:59:59' );
				break;
			case 'this_month':
				$month_start = date( 'Y-m-01', $_time );
				$month_end   = date( 'Y-m-t', $_time );

				$from_date = sprintf( '%s %s', $month_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $month_end, '23:59:59' );
				break;
			case 'last_day':
				$_date    = date( 'Y-m-d', $_time );
				$last_day = strtotime( $_date . ' -1 day' );
				$_date    = date( 'Y-m-d', $last_day );

				$from_date = sprintf( '%s %s', $_date, '00:00:00' );
				$to_date   = sprintf( '%s %s', $_date, '23:59:59' );
				break;
			case 'last_week':
				$day        = ( date( 'w', $_time ) + 6 ) % 7;// 0 - Sun , must be Mon = 0
				$_date      = date( 'Y-m-d', $_time );
				$last_week  = strtotime( $_date . ' -1 week' );
				$week_start = date( 'Y-m-d', strtotime( date( 'Y-m-d', $last_week ) . ' -' . $day . ' days' ) );
				$week_end   = date( 'Y-m-d', strtotime( date( 'Y-m-d', $last_week ) . ' +' . ( 6 - $day ) . ' days' ) );

				$from_date = sprintf( '%s %s', $week_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $week_end, '23:59:59' );
				break;
			case 'last_month':
				$_date       = date( 'Y-m-d', $_time );
				$last_month  = strtotime( $_date . ' -1 month' );
				$month_start = date( 'Y-m-01', $last_month );
				$month_end   = date( 'Y-m-t', $last_month );

				$from_date = sprintf( '%s %s', $month_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $month_end, '23:59:59' );
				break;
			case 'last_quarter':
				$_date         = date( 'Y-m-d', $_time );
				$last_month    = strtotime( $_date . ' -3 month' );
				$quarter_start = date( 'Y-' . self::get_quarter_month( $last_month ) . '-01', $last_month );
				$quarter_end   = date( 'Y-' . ( self::get_quarter_month( $last_month ) + 2 ) . '-31', $last_month );

				$from_date = sprintf( '%s %s', $quarter_start, '00:00:00' );
				$to_date   = sprintf( '%s %s', $quarter_end, '23:59:59' );
				break;
			case 'this_year':
				$year_start = date( 'Y-01-01', $_time );

				$from_date = sprintf( '%s %s', $year_start, '00:00:00' );
				break;
			case 'custom':
				$export_rule_custom = isset( $settings['export_rule_custom'] ) ? $settings['export_rule_custom'] : '';
				if ( isset( $export_rule_custom ) and $export_rule_custom ) {
					$day_start = date(
						'Y-m-d',
						strtotime( date( 'Y-m-d', $_time ) . ' -' . intval( $export_rule_custom ) . ' days' )
					);
					$day_end   = date( 'Y-m-d', $_time );

					$from_date = sprintf( '%s %s', $day_start, '00:00:00' );
					$to_date   = sprintf( '%s %s', $day_end, '23:59:59' );
				}
				break;
			default:
				break;
		}

		if ( isset( $from_date ) and $from_date ) {
			if ( $is_for_sql ) {
				if ( $use_timestamps ) {
					$from_date  = mysql2date( 'G', $from_date );
					$from_date -= $diff_utc;
				}
				$from_date = sprintf( ">='%s'", $from_date );
			}
			$result['from_date'] = $from_date;
		}

		if ( isset( $to_date ) and $to_date ) {
			if ( $is_for_sql ) {
				if ( $use_timestamps ) {
					$to_date  = mysql2date( 'G', $to_date );
					$to_date -= $diff_utc;
				}
				$to_date = sprintf( "<='%s'", $to_date );
			}
			$result['to_date'] = $to_date;
		}

		return $result;
	}

	/**
	 * Function will generate string for date filter sql query
	 *
	 * @param $where      array
	 * @param $where_meta array
	 * @param $date_field string
	 * @param $value      string
	 *
	 * @return array
	 */
	private static function add_date_filter( &$where, &$where_meta, $date_field, $value ) {
		if ( $date_field == 'date_paid' or $date_field == 'date_completed' ) {
			$where_meta[] = "(order_$date_field.meta_value>0 AND order_$date_field.meta_value $value )";
		} elseif ( $date_field == 'paid_date' or $date_field == 'completed_date' ) {
			$where_meta[] = "(order_$date_field.meta_value<>'' AND order_$date_field.meta_value " . $value . ')';
		} else {
			$where[] = 'orders.post_' . $date_field . $value;
		}
	}

	/**
	 * Function will generate comma separted string from array
	 *
	 * @param $arr_values array
	 *
	 * @return string
	 */
	private static function sql_subset( $arr_values ) {
		$values = array();
		foreach ( $arr_values as $s ) {
			$values[] = "'$s'";
		}

		return join( ',', $values );
	}

	/**
	 * Function will generate temp filename
	 *
	 * @param $prefix   string
	 * @param $filename string
	 *
	 * @return filename
	 */
	public static function get_filename( $prefix, $filename = '' ) {
		$filename = self::tempnam( sys_get_temp_dir(), $prefix );
		return $filename;
	}

	/**
	 * Function will generate temp file
	 *
	 * @param $folder string
	 * @param $prefix string
	 *
	 * @return filename
	 */
	public static function tempnam( $folder, $prefix ) {
		$filename = @tempnam( $folder, $prefix );
		if ( ! $filename ) {
			$tmp_folder = dirname( dirname( dirname( __FILE__ ) ) ) . '/tmp';
			// kill expired tmp file
			foreach ( glob( $tmp_folder . '/*' ) as $f ) {
				if ( time() - filemtime( $f ) > 24 * 3600 ) {
					unlink( $f );
				}
			}
			$filename = tempnam( $tmp_folder, $prefix );
		}

		return $filename;
	}

	/**
	 * Function will clear array with special character,encoded values
	 *
	 * @param $array array
	 *
	 * @return array
	 */
	public static function generateCleanArr( $array ) {
		return $array = preg_replace( '/[^a-zA-Z 0-9 @]+/', ' ', $array );
	}

}
