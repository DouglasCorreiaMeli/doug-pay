<?php
/**
* Plugin Name: Doug pay payments for WooCommerce
* Plugin URI: https://github.com/DouglasCorreiaMeli/doug-pay
* Description: Configure the payment options and accept payments with cards, ticket and money of Mercado Pago account.
* Version: 0.1
* Author: Douglas Correia
* Author URI: https://github.com/DouglasCorreiaMeli
* Text Domain: doug-pay-woo
*
*/
if (!defined('ABSPATH')) {
    exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

 if ( is_plugin_active( 'woocommerce/woocommerce.php' ))
 {
   add_action('plugins_loaded', 'doug-pay-init');
 }

 function doug_pay_init(){
   if (class_exists( 'WC_Payment_Gateway' )) {
      class Doug_Pay_Gateway extends WC_Payment_Gateway {
         //todo
      }
   }
 }