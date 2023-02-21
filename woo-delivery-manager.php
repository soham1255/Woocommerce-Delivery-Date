<?php
/**
 * Plugin Name: Woo Delivery Manager
 * Plugin URI: https://wordpress.org/plugins/woo-delivery-manager/
 * Description: A flexible and optimized wordpress plugin that allows you to add date & time field on checkout page so user can select the delivery date
 * Author: Soham Patel
 * Version: 1.0.0
 * Author URI: soham1255@gmail.com
 */

if( ! defined( 'WPK_PLUGIN_FILE' ) ) :
	define( 'WPK_PLUGIN_FILE', __FILE__ );
endif;

if( ! defined( 'WPK_PLUGIN_BASENAME' ) ) :
	define( 'WPK_PLUGIN_BASENAME', plugin_basename( WPK_PLUGIN_FILE ) );
endif;

if ( ! defined( 'WPK_WOO_DELIVERY_MANAGER' ) ) :
	define( 'WPK_WOO_DELIVERY_MANAGER', 'WPK_WOO_DELIVERY_MANAGER' );
endif;

if ( ! defined( 'WPK_WOO_DELIVERY_MANAGER_VERSION' ) ) :
	define( 'WPK_WOO_DELIVERY_MANAGER_VERSION', '1.0.0' );
endif;

if ( ! defined( 'WPK_WOO_DELIVERY_MANAGER_PATH' ) ) :
	define( 'WPK_WOO_DELIVERY_MANAGER_PATH', plugin_dir_path( __FILE__ ) );
endif;

if ( ! defined( 'WPK_WOO_DELIVERY_MANAGER_URL' ) ) :
	define( 'WPK_WOO_DELIVERY_MANAGER_URL', plugin_dir_url( __FILE__ ) );
endif;

if ( ! defined( 'WPK_WOO_DELIVERY_MANAGER_TEXTDOMAIN' ) ) :
	define( 'WPK_WOO_DELIVERY_MANAGER_TEXTDOMAIN', 'wpk-woo-delivery-manager' );
endif;

// Manage wordpress backend slider and metabox


if ( ! function_exists( 'wpk_delivery_manager_install' ) ) :

	function wpk_delivery_manager_constructor(){
		if( is_admin() ) :
			require_once "include/class-woo-delivery-manager-admin.php";

		else :
			require_once "include/class-woo-delivery-manager-checkout-manage.php";

		endif;
	}

endif;


if ( ! function_exists( 'wpk_delivery_manager_install' ) ) :
	
	function wpk_delivery_manager_install() {
		if ( ! function_exists( 'WC' ) ) :
			add_action( 'admin_notices', 'wpk_delivery_manager_install_woocommerce_admin_notice' );
		else :
			do_action( 'wpk_delivery_manager_init' );
		endif;
	}
	add_action( 'plugins_loaded', 'wpk_delivery_manager_install', 11 );
	add_action( 'wpk_delivery_manager_init', 'wpk_delivery_manager_constructor' );
endif;


if ( ! function_exists( 'wpk_delivery_manager_install_woocommerce_admin_notice' ) ) :
	
	function wpk_delivery_manager_install_woocommerce_admin_notice() {?>
		<div class="error">
			<p><?php echo esc_html( 'WPK Delivery Manager ' . __( 'is enabled but not effective. It requires WooCommerce to work.', WPK_WOO_DELIVERY_MANAGER_TEXTDOMAIN ) ); ?></p>
		</div>
		<?php
	}

endif;
