<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @category   Woo_Autolynk_Export_Import
 * @package    Woo_Autolynk_Export_Import
 * @subpackage Woo_Autolynk_Export_Import/admin
 * @author     VGP Media <mattb@vgpmedia.ie>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://vgpmedia.ie
 * @since      1.0.0
 *
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */

class Woo_Autolynk_Export_Import_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $_plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $_version;

	protected $tabs;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @return Admin Object with include the Ajax action
	 */
	public function __construct( $plugin_name, $version ) {

		$this->_plugin_name = $plugin_name;
		$this->_version     = $version;

		$this->url_plugin         = dirname( plugin_dir_url( __FILE__ ) ) . '/';
		$this->path_plugin        = dirname( plugin_dir_path( __FILE__ ) ) . '/';
		$this->path_views_default = dirname( plugin_dir_path( __FILE__ ) ) . '/public/view/';
	
       

		add_action( 'wp_ajax_woei_order_exporter', array( $this, 'ajax_export_gate' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return Include required css files
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_autolynk_Export_Import_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_autolynk_Export_Import_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			'jquery-style',
			$this->url_plugin . 'assets/css/jquery-ui.css'
		);

		wp_enqueue_style( 'select2-css', $this->url_plugin . 'assets/css/select2/select2.min.css', array(), '' );

		wp_enqueue_style( 'export', $this->url_plugin . 'assets/css/export.css', array(), '' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return Include required js files
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_autolynk_Export_Import_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_autolynk_Export_Import_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-autolynk-order-export' ) {

			wp_enqueue_script( 'jquery-ui-datepicker' );

			wp_enqueue_script( 'select2', $this->url_plugin . 'assets/js/select2/select2.full.js', array( 'jquery' ), '' );

			wp_enqueue_script( 'export_order', $this->url_plugin . 'assets/js/export.js', array( 'jquery' ), '' );

			wp_enqueue_script( 'select2-i18n', $this->url_plugin . 'assets/js/select2-i18n.js', array( 'jquery', 'select2' ), '' );

			$script_data = array(
				'locale'         => get_locale(),
				'select2_locale' => 'en',
				'active_tab'     => 'export',
			);

			wp_localize_script( 'select2-i18n', 'script_data', $script_data );

			wp_enqueue_script( 'serializejson', $this->url_plugin . 'assets/js/jquery.serializejson.js', array( 'jquery' ), WOO_AUTOLYNK_EXPORT_IMPORT_VERSION );

		}

	}

	/**
	 * Register Admin Menu in left sidebar.
	 *
	 * @return Include SubMenu in Left Sidebar
	 */
	public function add_menu() {

		     $attributes = wc_get_attribute_taxonomies();

		     $slugs = wp_list_pluck( $attributes, 'attribute_name' );

		     if ( ! in_array( 'customs-description', $slugs ) && ! in_array( 'hscode', $slugs )&& ! in_array( 'country-of-origin', $slugs )) {

		        $args = array(
		            'slug'    => 'customs-description',
		            'name'   => __( 'Customs Description', 'customs-description' ),
		            'type'    => 'select',
		            'orderby' => 'menu_order',
		            'has_archives'  => false,
		        );
		         $args_hscode = array(
		            'slug'    => 'hscode',
		            'name'   => __( 'Hscode', 'hscode' ),
		            'type'    => 'select',
		            'orderby' => 'menu_order',
		            'has_archives'  => false,
		        );
		         $args_origin = array(
		            'slug'    => 'country-of-origin',
		            'name'   => __( 'Country Of Origin', 'country-of-origin' ),
		            'type'    => 'select',
		            'orderby' => 'menu_order',
		            'has_archives'  => false,
		        );
		         wc_create_attribute( $args );
		         wc_create_attribute( $args_hscode );
		         wc_create_attribute( $args_origin );

		    }
		
		if ( apply_filters( 'woe_current_user_can_export', true ) ) {
			if ( current_user_can( 'manage_woocommerce' ) ) {
				add_submenu_page(
					'woocommerce',
					__( 'Autolynk Orders', 'woo-autolynk-export-import' ),
					__( 'Autolynk Orders', 'woo-autolynk-export-import' ),
					'view_woocommerce_reports',
					'wc-autolynk-order-export',
					array( $this, 'render_menu' )
				);
			} else {
				add_menu_page(
					__( 'Export Orders', 'woo-autolynk-export-import' ),
					__( 'Export Orders', 'woo-autolynk-export-import' ),
					'view_woocommerce_reports',
					'wc-autolynk-order-export',
					array( $this, 'render_menu' ),
					null,
					'55.7'
				);
			}
		}

       
	}

	/**
	 * This function will render the Tab HTML based on the Tab ID provided by Ajax Call
	 *
	 * @return html of perticular tab
	 */
	function render_menu() {

		$active_tab = sanitize_text_field( isset( $_REQUEST['tab'] ) ) ? sanitize_text_field( $_REQUEST['tab'] ) : 'export';
		$this->render(
			'main',
			array(
				'active_tab' => $active_tab,
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			)
		);

		include $this->path_views_default . "/tab/$active_tab.php";
	}





	/**
	 * This function will render the Tab HTML based on the Tab ID provided by Ajax Call
	 *
	 * @param $view       string
	 * @param $params     array
	 * @param $path_views string
	 *
	 * @return html of perticular tab
	 */
	public function render( $view, $params = array(), $path_views = null ) {
		extract( $params );
		include $this->path_views_default . "$view.php";
	}

	/**
	 * This function will create a Shipping method Custom Field For Shipping Code
	 * which will be used in Export CSV
	 *
	 * @return html of perticular tab
	 */
	public function waei_shipping_instance_form_fields_filters() {
		$shipping_methods = WC()->shipping->get_shipping_methods();
		foreach ( $shipping_methods as $shipping_method ) {
			add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_method->id, array( $this, 'waei_shipping_instance_form_add_extra_fields' ) );
		}
	}

	/**
	 * This function will declare the Extra Field
	 *
	 * @param $settings array
	 *
	 * @return array of Extra Field Setting
	 */
	public function waei_shipping_instance_form_add_extra_fields( $settings ) {
		$settings['shipping_custom_code'] = array(
			'title'       => 'Shipping Code',
			'type'        => 'text',
			'placeholder' => 'shipping Code',
			'description' => '',
		);
		return $settings;
	}

	/**
	 * This function will be called via Ajax to call perticular Function for Export or Import
	 *
	 * @return it will return the complete csv file
	 */
	public function ajax_export_gate() {

		if ( ! current_user_can( 'view_woocommerce_reports' ) ) {
			die( __( 'You can not do it', 'woo-autolynk-export-import' ) );
		}

		if ( ! isset( $_REQUEST['method'] ) ) {
			die( __( 'Empty method', 'woo-autolynk-export-import' ) );
		}
		$method = 'ajax_' . sanitize_text_field( $_REQUEST['method']);
		
		if ( strpos( sanitize_text_field( $_REQUEST['method']), 'import' ) !== false ) {
			$ajax_handler = new WC_Autolynk_Order_Importer_Ajax();
		} else {
			$ajax_handler = new WC_Autolynk_Order_Exporter_Ajax();
		}

		if ( ! method_exists( $ajax_handler, $method ) ) {
			die( sprintf( __( 'Unknown AJAX method %s', 'woo-autolynk-export-import' ), $method ) );
		}

		if ( $_POST && ! check_admin_referer( 'waei_nonce', 'waei_nonce' ) ) {
			die( __( 'Wrong nonce', 'woo-autolynk-export-import' ) );
		}
		
		// $sanitize_post=sanitize_text_field($_POST['json']);
		// // parse json to arrays?
		// if ( ! empty( sanitize_text_field($_POST['json']) ) ) {
		// 	$json = json_decode( sanitize_text_field($_POST['json']), true );
		// 	if ( is_array( $json ) ) {
		// 		$_POST = sanitize_text_field( $_POST + $json );
		// 		unset( $_POST['json'] );
		// 	}
		// }

		$_POST = stripslashes_deep($_POST);

		if (! empty($_POST['json']) ) {
		    $json = json_decode($_POST['json'], true);
		    if (is_array($json) ) {
		        $_POST = $_POST + $json;
		        unset($_POST['json']);
		    }
		}
		
		$ajax_handler->$method();
		die();
	}
public function valid_attribute_name( $attribute_name ) {
    if ( strlen( $attribute_name ) >= 28 ) {
            return new WP_Error( 'error', sprintf( __( 'Slug "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce' ), sanitize_title( $attribute_name ) ) );
    } elseif ( wc_check_if_attribute_name_is_reserved( $attribute_name ) ) {
            return new WP_Error( 'error', sprintf( __( 'Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce' ), sanitize_title( $attribute_name ) ) );
    }

    return true;
}
	

}
