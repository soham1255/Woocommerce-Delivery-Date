<?php
/**
 * Plugin Name: Woo Delivery Manager
 * Plugin URI: https://wordpress.org/plugins/woo-delivery-manager/
 * Description: A flexible and optimized wordpress plugin that allows you to add date & time field on checkout page so user can select the delivery date
 * Author: Soham Patel | WPSoham
 * Version: 1.0.0
 * Author URI: sohampatel1202@gmail.com
 */

if( ! defined( 'WPS_PLUGIN_FILE' ) ) :
	define( 'WPS_PLUGIN_FILE', __FILE__ );
endif;

if( ! defined( 'WPS_PLUGIN_BASENAME' ) ) :
	define( 'WPS_PLUGIN_BASENAME', plugin_basename( WPS_PLUGIN_FILE ) );
endif;

if ( ! defined( 'WPS_WOO_DELIVERY_MANAGER' ) ) :
	define( 'WPS_WOO_DELIVERY_MANAGER', 'WPS_WOO_DELIVERY_MANAGER' );
endif;

if ( ! defined( 'WPS_WOO_DELIVERY_MANAGER_VERSION' ) ) :
	define( 'WPS_WOO_DELIVERY_MANAGER_VERSION', '1.0.0' );
endif;

if ( ! defined( 'WPS_WOO_DELIVERY_MANAGER_PATH' ) ) :
	define( 'WPS_WOO_DELIVERY_MANAGER_PATH', plugin_dir_path( __FILE__ ) );
endif;

if ( ! defined( 'WPS_WOO_DELIVERY_MANAGER_URL' ) ) :
	define( 'WPS_WOO_DELIVERY_MANAGER_URL', plugin_dir_url( __FILE__ ) );
endif;

if ( ! defined( 'WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN' ) ) :
	define( 'WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN', 'wps-woo-delivery-manager' );
endif;

// Manage wordpress backend slider and metabox


if ( ! function_exists( 'wps_delivery_manager_install' ) ) :

	function wps_delivery_manager_constructor(){
		if( is_admin() ) :
			require_once "include/class-woo-delivery-manager-admin.php";

		else :
			require_once "include/class-woo-delivery-manager-checkout-manage.php";

		endif;
	}

endif;


if ( ! function_exists( 'wps_delivery_manager_install' ) ) :
	
	function wps_delivery_manager_install() {
		if ( ! function_exists( 'WC' ) ) :
			add_action( 'admin_notices', 'wps_delivery_manager_install_woocommerce_admin_notice' );
		else :
			do_action( 'wps_delivery_manager_init' );
		endif;
	}
	add_action( 'plugins_loaded', 'wps_delivery_manager_install', 11 );
	add_action( 'wps_delivery_manager_init', 'wps_delivery_manager_constructor' );
endif;


if ( ! function_exists( 'wps_delivery_manager_install_woocommerce_admin_notice' ) ) :
	
	function wps_delivery_manager_install_woocommerce_admin_notice() {?>
		<div class="error">
			<p><?php echo esc_html( 'WPS Delivery Manager ' . __( 'is enabled but not effective. It requires WooCommerce to work.', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ) ); ?></p>
		</div>
		<?php
	}

endif;
