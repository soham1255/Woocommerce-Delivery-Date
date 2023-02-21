<?php
/**
 * Manage WPS Delivery option frontend settings
 */


if( ! class_exists( 'WPS_woo_delivery_frontend' ) ) :

    class WPS_woo_delivery_frontend {

        function __construct() {
            $this->event_handler();
        }

        public function event_handler() {
            add_action( 'wp_enqueue_scripts', [ $this, 'wps_enquqeue_scripts' ], 999 );

            add_action( 'woocommerce_before_order_notes', [ $this, 'wps_custom_checkout_field' ], 50 );
            add_action( 'woocommerce_cart_calculate_fees', [ $this, 'wps_checkout_delivery_date_fee' ] );     
            add_action( 'woocommerce_checkout_update_order_review', [ $this, 'wps_checkout_delivery_date_set_session' ] );

            add_action( 'woocommerce_checkout_process', [ $this, 'wps_validate_delivery_date_field' ] );
            add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'wps_save_delivery_date_checkout_field' ] );
            add_action( 'woocommerce_thankyou', [ $this, 'wps_show_delivery_date_field_thankyou' ] );    
        }

        public function wps_enquqeue_scripts() {
            wp_enqueue_style( 'jquery-ui', WPS_WOO_DELIVERY_MANAGER_URL . 'assets/css/jquery-ui.css' );
            wp_enqueue_style( 'checkout-css', WPS_WOO_DELIVERY_MANAGER_URL . 'assets/css/checkout.css' );

            wp_enqueue_script( 'jquery-ui', WPS_WOO_DELIVERY_MANAGER_URL . 'assets/js/jquery-ui.js' );
            wp_enqueue_script( 'checkout-js', WPS_WOO_DELIVERY_MANAGER_URL . 'assets/js/checkout.js' );
            wp_localize_script( 'checkout-js', 'wpsParam',
                array( 
                    'show_weekend' => get_option( 'wc_woo_delivery_manager_enable_disable_weekebd' )
                )
            );
        }

        public function wps_custom_checkout_field( $checkout ) {
            $specific_delivery_date = get_option( 'wc_woo_delivery_manager_enable_disable' );

            if( $specific_delivery_date ) :
                echo '<div id="my_custom_checkout_field"><h4>' . __( 'Delivery Date', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ) . '</h4><p style="margin: 0 0 8px;">Do you need delivery on a Specific Date( ' . get_woocommerce_currency_symbol() . '10 )?</p>';
                    woocommerce_form_field( 'wps_delivery_checkbox', array(
                        'type'  => 'checkbox',
                        'class' => array( 'woo-delivery-checkbox form-row-wide', 'update_totals_on_change' ),
                        'label' => __( 'Yes' ),
                    ), $checkout->get_value( 'wps_delivery_checkbox' ) );
            
                    woocommerce_form_field( 'wps_delivery_date', array(
                        'type'          => 'text',
                        'id'            => 'datepicker',
                        'required'      => 'required',
                        'class'         => array( 'wps-delivery-field form-row-first' ),
                        'label'         => __( 'Select Delivery Date', WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN ),
                        'placeholder'   => 'DD-MM-YYYY'   
                    ), $checkout->get_value( 'wpk_delivery_date' ) );
                        
                echo '</div>';
            endif;
        }

        public function wps_checkout_delivery_date_set_session( $posted_data  ) {
            parse_str( $posted_data, $output );
            $specific_date = isset( $output['wps_delivery_checkbox'] ) ? true : false;
            WC()->session->set( 'specific_date', $specific_date );
        }

        public function wps_checkout_delivery_date_fee( $cart ){
            if ( is_admin() && ! defined('DOING_AJAX') )
                return;
            
            if ( WC()->session->get('specific_date') == true ) :
                $fee_label   = __( "Specific delivery date fee", WPS_WOO_DELIVERY_MANAGER_TEXTDOMAIN );
                $fee_amount  = get_option( 'wc_woo_delivery_manager_price' );
                $cart->add_fee( $fee_label, $fee_amount );
            endif;
        }

        public function wps_validate_delivery_date_field() {    
            if ( ! $_POST['wps_delivery_date'] ) :
                wc_add_notice( 'Please choose delviery date', 'error' );
            endif;
        }

        public function wps_save_delivery_date_checkout_field( $order_id ) { 
            if ( $_POST['wps_delivery_date'] ) 
                update_post_meta( $order_id, 'wps_delivery_date', esc_attr( $_POST['wps_delivery_date'] ) );
        }
        
        public function wps_show_delivery_date_field_thankyou( $order_id ) { 
            if ( get_post_meta( $order_id, 'wps_delivery_date', true ) ) :
                echo '<p><strong>Delivery date:</strong> ' . get_post_meta( $order_id, 'wps_delivery_date', true ) . '</p>';
            endif;
        }
            }
    new WPS_woo_delivery_frontend();
endif;

?>
