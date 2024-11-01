<?php

/**
 * This class is to generate the export csv order data
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
 * Admin CSV Data Generator Class to generate CSV data
 * This function will gether the required data for CSV like Header and column from
 * Order and product
 *
 * @category   Woo_Autolynk_Export_Import
 * @package    Woo_Autolynk_Export_Import
 * @subpackage Woo_Autolynk_Export_Import/includes
 * @author     VGP Media <mattb@vgpmedia.ie>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://vgpmedia.ie
 * @since      1.0.0
 */
class WC_Autolynk_Csv_Data_Generator {

	/**
	 * Initialize the Order Fields List for CSV
	 *
	 * @return array with Default Fields for CSV
	 */
	public static function get_field_list() {

		return $order_fields = array(
			'order_number'         => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Order Number',
			),
			'order_status'         => array(
				'checked' => 1,
				'format'  => 'string',
                'label'=>'Order Status',
			),
			'order_date'           => array(
				'checked' => 1,
				'format'  => 'date',
				'label'=>'Order Date',
			),
			
			'shipping_first_name'  => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Shipping First Name',
			),
			'shipping_last_name'   => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Shipping Last Name',
			),
			'shipping_company'   => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Shipping Company',
			),
			'shipping_address_1' => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Shipping Address 1',
			),
			'shipping_address_2' => array(
				'checked' => 1,
				'format'  => 'string',
			    'label'=>'Shipping Address 2',
			),
			'shipping_city'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Shipping City',
			),
			'shipping_state'       => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Shipping State',
			),
			'shipping_postcode'    => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Shipping Postcode',
			),
			'shipping_country'     => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Shipping Country',
			),
			'billing_email'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing Email',
			),
			'billing_phone'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing Phone',
			),
			'billing_first_name'   => array(
				
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Billing First Name',
			),
			'billing_last_name'    => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Billing Last Name',
			),
			'billing_company'    => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Billing Company',
			),
			'billing_address_1'  => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing Address 1',
			),
			'billing_address_2'  => array(
				'checked' => 1,
				'format'  => 'Billing Address_2',
				'label'=>'Billing Address 2',
			),
			'billing_city'         => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing City',
			),
			'billing_state'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing State',
			),
			'billing_postcode'     => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing Postcode',

			),
			'billing_country'      => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Billing Country',

			),
			'payment_method'       => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Payment Method',
			),
			
			'shipping_method'      => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Shipping Method',
			),
			'total_weight_items'   => array(
				'checked' => 0,
				'format'  => 'number',
				'label'=>'Total Weight Items',
			),
			'product_name'         => array(
				'checked' => 0,
				'format'  => 'string',
				'label'=>'Product Name',
			),
			'order_total'          => array(
				'checked' => 1,
				'format'  => 'money',
				'label'=>'Order Total',
			),
			'order_shipping'       => array(
				'checked' => 1,
				'format'  => 'money',
				'label'=>'Order Shipping',
			),
			'customer_note'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Customer Note',
			),
			'contents1_country_origin'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents1 Country Origin',
			),
			'contents1_customs_tariff'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents1 Customs Tariff',
			),
			'contents1_item_description'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents1 Item Description',
			),
			'contents1_item_value'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents1 Item Value',
			),
			'contents1_weight'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents1 Weight',
			),
			'contents1_no_items'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents1 No Items',
			),
			'contents2_country_origin'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents2 Country Origin',
			),
			'contents2_customs_tariff'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents2 Customs Tariff',
			),
			'contents2_item_description'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents2 Item Description',
			),
			'contents2_item_value'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents2 Item Value',
			),
			'contents2_weight'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents2 Weight',
			),
			'contents2_no_items'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents2 No Items',
			),
			'contents3_country_origin'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents3 Country Origin',
			),
			'contents3_customs_tariff'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents3 Customs Tariff',
			),
			'contents3_item_description'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents3 Item Description',
			),
			'contents3_item_value'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents3 Item Value',
			),
			'contents3_weight'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents3 Weight',
			),
			'contents3_no_items'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents3 No Items',
			),
			'contents4_country_origin'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents4 Country Origin',
			),
			'contents4_customs_tariff'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents4 Customs Tariff',
			),
			'contents4_item_description'        => array(
				'checked' => 1,
				'format'  => 'string',
				'label'=>'Contents4 Item Description',
			),
			'contents4_item_value'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents4 Item Value',
			),
			'contents4_weight'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents4 Weight',
			),
			'contents4_no_items'        => array(
				'checked' => 1,
				'format'  => 'number',
				'label'=>'Contents4 No Items',
			)

		);
	}

	/**
	 * Initialize the product Fields List for CSV
	 *
	 * @return array with the Product fields for csv
	 */
	public static function get_product_field_list() {
		return $product_fields = array(
			'id'     => array(
				'label'  => __( 'Product Id', 'vgp-import-export-for-autolynk-in-woocommerce' ),
				'format' => 'string',
			),
			'sku'    => array(
				'label'  => __( 'Product SKU', 'vgp-import-export-for-autolynk-in-woocommerce' ),
				'format' => 'string',
			),
			'name'   => array(
				'label'  => __( 'Product Name', 'vgp-import-export-for-autolynk-in-woocommerce' ),
				'format' => 'string',
			),
			'price'  => array(
				'label'  => __( 'Product price', 'vgp-import-export-for-autolynk-in-woocommerce' ),
				'format' => 'string',
			),
			'qty'    => array(
				'label'  => __( 'Product Qty', 'vgp-import-export-for-autolynk-in-woocommerce' ),
				'format' => 'string',
			),
			'weight' => array(
				'label'  => __( 'Product weight', 'vgp-import-export-for-autolynk-in-woocommerce' ),
				'format' => 'string',
			),
		);
	}
	
    /**
	 * Generate the Header Column
	 *
	 * @return array with the header columns for csv
	 */
	public function generate_order_header() {
		$header_row = array();

		$order_fields = self::get_field_list();

		foreach ( $order_fields as $key => $label ) {
			array_push( $header_row, $label['label'] );
		}
		return $header_row;
	}
	

	/**
	 * Generate All Orders Data
	 *
	 * @param $order_id int
	 *
	 * @return array with the row data for csv
	 */
	public static function generate_order_row_data( $order ) {

		global $wpdb;
		$row          = array();
		$order_fields = self::get_field_list();
		$order_product_data = self::get_related_product_data( $order );
		foreach ( $order_fields as $key => $field ) {
			$product_values=self::get_field_value( $order, $row, $key, $order_product_data );		
			
		}
	    
		return $row;
	}

	/**
	 * Get one order data
	 *
	 * @param $order    obj
	 * @param $row      array
	 * @param $field    string
	 * @param $products array
	 *
	 * @return string with perticular field value for row
	 */
	public static function get_field_value( $order, &$row, $field, $products ) {

	        $ins=1;	
			$orderid = $order->get_id();		
			$order_data = wc_get_order( $orderid );
			$weight_unit = get_option('woocommerce_weight_unit');
			$iteams_count=$order_data->get_item_count();
			$discount_amount=$order->get_total_discount();
			$discount_amount_org=$discount_amount/$iteams_count;
			//$exclude_contr_array = array('AT','BE','BG','HR','CY','CZ','DK','EE','FI','FR','DE','GR','HU','IE','IT','LV','LT','LU','MT','NL','PL','PT','RO','SK','SI','ES','SE');
			
			foreach ( $order_data->get_items() as $related_item_id => $related_item ) {
	            
	            $order_info_id=$related_item->get_order_id();
				$related_iteam_data=$related_item;
				$product_id=$related_iteam_data->get_product_id();
				$products_data=wc_get_product( $product_id );
				$products_count=$related_iteam_data->get_quantity();
                $order_info_id=$related_item->get_order_id();
				if ( $field == 'contents'.$ins.'_country_origin' ) {
					$scv = $order->get_shipping_country();
					$fscv=str_replace(",", " ",$scv);
					if ( empty( $fscv ) ) {
						$scv = $order->get_billing_country();
						$fscv=str_replace(",", " ",$scv);
					}

				
				  	else if(empty($products_data->get_attribute( 'country-of-origin' ))){

						$fvalue = ' ';

					}else{

						$contents1_country_origin_value = $products_data->get_attribute( 'country-of-origin' );
						$contents1_country_origin_value_str=str_replace(",", " ",$contents1_country_origin_value);
					 	$fvalue=str_replace(' ', '', $contents1_country_origin_value_str);
					 }				 	
				 }
				 elseif ( $field == 'contents'.$ins.'_customs_tariff' ) {
					$scv = $order->get_shipping_country();
					$fscv=str_replace(",", " ",$scv);
					if ( empty( $fscv ) ) {
						$scv = $order->get_billing_country();
						$fscv=str_replace(",", " ",$scv);
					}

				
				  	else if(empty($products_data->get_attribute( 'hscode' ))){

						$fvalue = ' ';

					}else{
						$contents1_customs_tariff_value = $products_data->get_attribute( 'hscode' );
						$contents1_customs_tariff_value_str=str_replace(",", " ",$contents1_customs_tariff_value);
					 	$fvalue=str_replace(' ', '', $contents1_customs_tariff_value_str);
				 		
					 }

				  }
				elseif ( $field == 'contents'.$ins.'_item_description' ) {
					$scv = $order->get_shipping_country();
					$fscv=str_replace(",", " ",$scv);
					if ( empty( $fscv ) ) {
						$scv = $order->get_billing_country();
						$fscv=str_replace(",", " ",$scv);
					}elseif (empty($products_data->get_attribute( 'customs-description' ))) {
					 	$contents1_item_description_value = $related_iteam_data->get_name();
					 	$fvalue=str_replace(",", " ",$contents1_item_description_value);
				 	}else
				 	{

				 		$contents1_item_description_value = $products_data->get_attribute( 'customs-description' );
					 	$fvalue=str_replace(",", " ",$contents1_item_description_value);	
				 	}

				 }
				 elseif ( $field == 'contents'.$ins.'_item_value' ) {
				 	    $product_qty_count=$related_iteam_data->get_quantity();
				 	    //$contents1_item_value_value = $related_item->get_subtotal();
                        //$total_value1=$contents1_item_value_value-$discount_amount_org;
                        $total_value1 = $related_item->get_total();
                        $fvalue=$total_value1;
                   
				 }
				 elseif ( $field == 'contents'.$ins.'_weight' ) {
				 	if(empty( $products_data->get_weight())){
                        
                        $product_qty_count=$related_iteam_data->get_quantity();
					   
						if ($products_data->is_type( 'variable' )) 
                        {   
                            $available_variations = $products_data->get_available_variations();
                           foreach ( $products_data->get_available_variations() as $key => $variation_data ) {
                            $variation_id= $related_iteam_data->get_variation_id();
                            $variation    = new WC_Product_Variation( $variation_id );
                            $contents2_weight_value= $variation->get_weight();
                            $attributes   = $variation->get_attributes;
                           
                            if(empty($contents2_weight_value)){
                                
                                $contents2_weight_value=$product['weight'];
                                
                            }
                            $total_weight=$contents2_weight_value*$product_qty_count;
                           }
                        
                        }
                        else{
                            
                            $fvalue = ' ';
                            
                        }
						
							
						if($weight_unit=='kg')
						{
							$decimal_weight = sprintf('%.3f', $total_weight);
				 			$fvalue=strval($decimal_weight);
				 		}elseif ($weight_unit=='g') {
				 			
				 			$total_weight_g=$total_weight / 1000;
				 			$decimal_weight = sprintf('%.3f', $total_weight_g);
				 			$fvalue=strval($decimal_weight);
				 		}elseif ($weight_unit=='lbs') {
				 			
				 			$total_weight_lbs=$total_weight /2.2046;
				 			$decimal_weight = sprintf('%.3f', $total_weight_lbs);
				 			$fvalue=strval($decimal_weight);
				 		}elseif ($weight_unit=='oz') {
				 			
				 			$total_weight_oz=$total_weight *0.0283495231;
				 			$decimal_weight = sprintf('%.3f', $total_weight_oz);
				 			$fvalue=strval($decimal_weight);
				 		}
                        
						

					}else{
					    
						$product_qty_count=$related_iteam_data->get_quantity();
					   
						$contents1_weight_value = $products_data->get_weight();
						if ($products_data->is_type( 'variable' )) 
                        {   
                            $available_variations = $products_data->get_available_variations();
                           foreach ( $products_data->get_available_variations() as $key => $variation_data ) {
                            $variation_id= $related_iteam_data->get_variation_id();
                            $variation    = new WC_Product_Variation( $variation_id );
                            $contents2_weight_value= $variation->get_weight();
                            $attributes   = $variation->get_attributes;
                            if(empty($contents2_weight_value)){
                                
                                $contents2_weight_value=$product['weight'];
                                ;
                            }
                            $total_weight=$contents2_weight_value*$product_qty_count;
                           }
                        
                        }
                        else{
                            $total_weight=$contents1_weight_value*$product_qty_count;
                            
                        }
						
							
						if($weight_unit=='kg')
						{
							$decimal_weight = sprintf('%.3f', $total_weight);
				 			$fvalue=strval($decimal_weight);
				 		}elseif ($weight_unit=='g') {
				 			
				 			$total_weight_g=$total_weight / 1000;
				 			$decimal_weight = sprintf('%.3f', $total_weight_g);
				 			$fvalue=strval($decimal_weight);
				 		}elseif ($weight_unit=='lbs') {
				 			
				 			$total_weight_lbs=$total_weight /2.2046;
				 			$decimal_weight = sprintf('%.3f', $total_weight_lbs);
				 			$fvalue=strval($decimal_weight);
				 		}elseif ($weight_unit=='oz') {
				 			
				 			$total_weight_oz=$total_weight *0.0283495231;
				 			$decimal_weight = sprintf('%.3f', $total_weight_oz);
				 			$fvalue=strval($decimal_weight);
				 		}
					 		
				 	}
				 }
				 elseif ( $field == 'contents'.$ins.'_no_items' ) {
				 	$contents1_no_items_value = $related_iteam_data->get_quantity();
				 	$fvalue=$contents1_no_items_value;

				 }

					
					$ins++;
				}
		if ( $field == 'order_id' ) {
			$fvalue = $order->order_id;
		} elseif ( $field == 'order_status' ) {
			$fvalue = $order->get_status();
		} elseif ( $field == 'order_date' ) {
			$fvalue = $order->get_date_created()->format( 'Y-m-d H:i:s' );
		} elseif ( $field == 'order_number' ) {
			$fvalue = $order->get_order_number(); // use parent order number
		} elseif ( $field == 'shipping_first_name' ) {
			$shipping_first_name_value = remove_accents( $order->get_shipping_first_name() );
			$fvalue=str_replace(",", " ",$shipping_first_name_value);
			if ( empty( $fvalue ) ) {
				$shipping_first_name_value = $order->get_billing_first_name();
				$fvalue=str_replace(",", " ",$shipping_first_name_value);
			}
		} elseif ( $field == 'shipping_last_name' ) {
			$shipping_last_name_value = remove_accents( $order->get_shipping_last_name() );
			$fvalue=str_replace(",", " ",$shipping_last_name_value);
			if ( empty( $fvalue ) ) {
				$shipping_last_name_value = $order->get_billing_last_name();
				$fvalue=str_replace(",", " ",$shipping_last_name_value);
			}
		} elseif ( $field == 'shipping_city' ) {
			$city_value = remove_accents( $order->get_shipping_city() );
			$fvalue=str_replace(",", " ",$city_value);
			if ( empty( $fvalue ) ) {
				$city_value = $order->get_billing_city();
				$fvalue=str_replace(",", " ",$city_value);
			}
		} elseif ( $field == 'shipping_state' ) {
			$shipping_state_value = remove_accents( $order->get_shipping_state() );
			$fvalue=str_replace(",", " ",$shipping_state_value);
			if ( empty( $fvalue ) ) {
				$fvalue = $order->get_billing_state();
			}
		} elseif ( $field == 'shipping_postcode' ) {
			$fvalue = remove_accents( $order->get_shipping_postcode() );
			if ( empty( $fvalue ) ) {
				$fvalue = $order->get_billing_postcode();
			}
		} elseif ( $field == 'shipping_company' ) {
			$shipping_company_value = remove_accents( $order->get_shipping_company() );
			$fvalue=str_replace(",", " ",$shipping_company_value);
			if ( empty( $fvalue ) ) {
				$fvalue = '';
			}
		} elseif ( $field == 'shipping_address_1' ) {
			$add1   = $order->get_shipping_address_1();
			$shipp_value = remove_accents( $add1 );
			$fvalue=str_replace(",", " ",$shipp_value);
			
		} elseif ( $field == 'shipping_address_2' ) {
		
			$add2   = $order->get_shipping_address_2();
			$shipp_value = remove_accents( $add2 );
			$fvalue=str_replace(",", " ",$shipp_value);
			
			
		} elseif ( $field == 'shipping_country' ) {
			$shipping_country_value = $order->get_shipping_country();
			if($shipping_country_value=='XI'){
			    $new_country_code='XA';
			    $shipping_country_value=$new_country_code;
			}
			
			$fvalue=str_replace(",", " ",$shipping_country_value);
			if ( empty( $fvalue ) ) {
				$shipping_country_value = $order->get_billing_country();
				$fvalue=str_replace(",", " ",$shipping_country_value);
			}
		} elseif ( $field == 'shipping_method' ) {
			$shipping = $order->get_shipping_methods();
			$fvalue   = '';
			foreach ( $shipping as $key => $value ) {
				$shipping_field = 'woocommerce_' . $value['method_id'] . '_' . $value['instance_id'] . '_settings';
				$ship_data      = get_option( $shipping_field, $default = false );
				$shipcode       = $ship_data['shipping_custom_code'];
				$fvalue         = $shipcode;
			}
			if ( empty( $fvalue ) ) {
				$fvalue ='UNK';			}
		} elseif ( $field == 'billing_phone' ) {
			$fvalue = remove_accents( $order->get_billing_phone() );
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_email' ) {
			$fvalue = $order->get_billing_email();
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_first_name' ) {
			$billing_first_name_value = remove_accents( $order->get_billing_first_name() );
			$fvalue=str_replace(",", " ",$billing_first_name_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_last_name' ) {
			$billing_last_name_value = remove_accents( $order->get_billing_last_name() );
			$fvalue=str_replace(",", " ",$billing_last_name_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_city' ) {
			$billing_city_value = remove_accents( $order->get_billing_city() );
			$fvalue=str_replace(",", " ",$billing_city_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_state' ) {
			$billing_state_value = remove_accents( $order->get_billing_state() );
			$fvalue=str_replace(",", " ",$billing_state_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_postcode' ) {
			$billing_postcode_value = remove_accents( $order->get_billing_postcode() );
			$fvalue=str_replace(",", " ",$billing_postcode_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_company' ) {
			$billing_company_value = remove_accents( $order->get_billing_company() );
			$fvalue=str_replace(",", " ",$billing_company_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_address_1' ) {
			$add1   = $order->get_billing_address_1();
			// $add2   = $order->get_billing_address_2();
			$billing_value = remove_accents( $add1 );
			$fvalue=str_replace(",", " ",$billing_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_address_2' ) {
			// $add1   = $order->get_billing_address_1();
			$add2   = $order->get_billing_address_2();
			$billing_value = remove_accents( $add2 );
			$fvalue=str_replace(",", " ",$billing_value);
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'billing_country' ) {
			$billing_country_value = $order->get_billing_country();
			$fvalue=$billing_country_value;
			if($billing_country_value=='XI'){
			    $new_country_code='XA';
			    $fvalue=$new_country_code;
			}
			if ( empty( $fvalue ) ) {
				$fvalue = ' ';
			}
		} elseif ( $field == 'total_weight_items' ) {
			$total_weight_main = 0;
			foreach ( $products as $product ) {
				$total_weight_main += (float) $product['qty'] * (float) $product['weight'];
			}
			$total_weight_value=str_replace(",", " ",$total_weight_main);
			$fvalue = $total_weight_value;
			
		} elseif ( $field == 'payment_method' ) {
			$payment_method_value = $order->get_payment_method_title();
			$fvalue=str_replace(",", " ",$payment_method_value);
			
			
		} elseif ( $field == 'product_name' ) {
		    $product_name_value = $products[0]['name'];
			$fvalue=str_replace(",", " ",$product_name_value);
			
		} elseif ( $field == 'order_total' ) {
			$order_total_value = $order->get_total();
			$fvalue=str_replace(",", " ",$order_total_value);
			
			
		} elseif ( $field == 'order_shipping' ) {
			$order_shipping_value = $order->get_shipping_total();
			$fvalue=str_replace(",", " ",$order_shipping_value);
			
		} elseif ( $field == 'customer_note' ) {
			// $notes  = $order->get_customer_order_notes();
			// $fvalue_str = remove_accents( $notes[0]->comment_content );

			$orderid = $order->get_id();
			$post = get_post($orderid);
			$excerpt=$post->post_excerpt;
			$fvalue_str = remove_accents( $excerpt );
			$fvalue = str_replace(array(',',"\n", "\t", "\r"), ' ', $fvalue_str);
		} 

		

		elseif ( strpos( $field, 'empty_field' ) !== false ) {
			$fvalue = ' ';
		}
		array_push( $row, $fvalue );
	}


	/**
	 * Get product data related to perticular order id
	 *
	 * @param $order obj
	 *
	 * @return string with perticular field value for row
	 */
	public static function get_related_product_data( $order ) {
		$row = array();
		$in  = 0;

		foreach ( $order->get_items() as $item_id => $item ) {
		
		
			$product        = $item->get_product();
			$product_fields = self::get_product_field_list();
			$item_meta      = get_metadata( 'order_item', $item_id );

			foreach ( $product_fields as $key => $field ) {
				if ( $key == 'qty' ) {
					$row[ $in ][ $key ] = $item_meta['_qty'][0];
				} else {
					$fn                 = 'get_' . $key;
					$row[ $in ][ $key ] = $product->{$fn}();
				}
			}
			$in++;
		}
		return $row;
	}


		
}
