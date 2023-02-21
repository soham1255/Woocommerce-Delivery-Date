<?php
/**
 * Manage WPS Delivery option admin settings
 */


if( ! class_exists( 'WPS_woo_delivery_admin' ) ) :

    class WPS_woo_delivery_admin {

        function __construct() {
            $this->event_handler();
        }

        public function event_handler() {
            add_filter( 'plugin_action_links_' . WPS_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
            add_filter( 'woocommerce_settings_tabs_array', [ $this, 'WPS_add_settings_tab' ], 50 );

            add_action( 'woocommerce_settings_tabs_woo_delivery_manager', [ $this, 'settings_tab' ] );
            add_action( 'woocommerce_update_options_woo_delivery_manager', [ $this, 'update_settings' ] );

            add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'wps_show_delivery_date_field_order' ], 20 );
            add_action( 'woocommerce_email_after_order_table', [ $this, 'wps_show_delivery_date_field_emails' ], 20, 4 );
        }

        public function WPS_add_settings_tab($tabs) {
            $tabs['woo_delivery_manager'] = __( 'Woo Delivery Manager', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN );
            return $tabs;
        }

        public static function settings_tab() {
            woocommerce_admin_fields( self::get_settings() );
        }

        public static function get_settings() {
            $settings = array(
                'section_title' => array(
                    'name'      => __( 'Woo Delivery Manager ', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'type'      => 'title',
                    'id'        => 'wc_woo_delivery_manager_section_title'
                ),
                'enable_disable' => array(
                    'name'      => __( 'Enable/Disable', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'type'      => 'checkbox',
                    'desc'      => __( 'Enable delivery date', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'id'        => 'wc_woo_delivery_manager_enable_disable'
                ),
                'delivery_date' => array(
                    'name'      => __( 'Enable/Disable Weekend dates', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'type'      => 'checkbox',
                    'desc'      => __( 'If checked user will able to select the weekend( Saturday + Sunday ) dates', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'id'        => 'wc_woo_delivery_manager_enable_disable_weekebd'
                ),
                'delivery_cost' => array(
                    'name'      => __( 'Delivery date cost', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'type'      => 'number',
                    'default'   => 10,
                    'desc'      => __( 'Enter the amount for addional fees if delivery date choosen.. set 0 for no cost', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                    'id'        => 'wc_woo_delivery_manager_price'
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_woo_delivery_manager_section_end'
                )
            );
            return apply_filters( 'woo_delivery_manager_settings', $settings );
        }

        public static function update_settings() {
            woocommerce_update_options( self::get_settings() );
        }

        public function wps_show_delivery_date_field_order( $order ) {    
            $order_id = $order->get_id();
            if ( get_post_meta( $order_id, 'wps_delivery_date', true ) ) :
                echo '<p><strong>Delivery date:</strong> ' . get_post_meta( $order_id, 'wps_delivery_date', true ) . '</p>';
            endif;
        }
        
        public function wps_show_delivery_date_field_emails( $order, $sent_to_admin, $plain_text, $email ) {
            if ( get_post_meta( $order->get_id(), 'wps_delivery_date', true ) ) echo '<p><strong>Delivery date:</strong> ' . get_post_meta( $order->get_id(), 'wps_delivery_date', true ) . '</p>';
        }

        public static function plugin_action_links( $links ) {
            $action_links = array(
                'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=woo_delivery_manager' ) . '" aria-label="' . esc_attr__( 'View WooCommerce settings', 'woocommerce' ) . '">' . esc_html__( 'Settings', 'woocommerce' ) . '</a>',
            );
    
            return array_merge( $action_links, $links );
        }
    }
    new WPS_woo_delivery_admin();
endif;
