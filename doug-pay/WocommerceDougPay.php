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

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (is_plugin_active('woocommerce/woocommerce.php')) {
  add_action('plugins_loaded', 'dougPayInit');
}

function dougPayInit()
{

  if (class_exists('WC_Payment_Gateway')) {
    class DougPayGateway extends WC_Payment_Gateway
    {
      public const ID = 'woo-doug-pay';

      public function __construct()
      {

        $this->id    = self::ID;
        $this->icon  = 'https://cdn-icons-png.flaticon.com/128/10200/10200975.png';
        $this->has_fields = true;
        $this->method_title       = 'DougPay Checkout';
        $this->method_description = 'Use o DougPay para pagar via Mercado Pago';

        $this->init_settings();
        $this->init_form_fields();
        add_action( 'woocommerce_update_options_payment_gateways_' . self::ID, array( $this, 'process_admin_options' ) );
      }

      public function init_form_fields(): void
      {
        $this->form_fields = array(
          'enabled' => array(
              'title' => __( 'Enable/Disable', 'woocommerce' ),
              'type' => 'checkbox',
              'label' => __( 'Enable', 'woocommerce' ),
              'default' => 'yes'
          ),
          'title' => array(
              'title' => __( 'Title', 'woocommerce' ),
              'type' => 'text',
              'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
              'default' => __( 'DougPay', 'woocommerce' ),
              'desc_tip'      => true,
          ),
          'description' => array(
              'title' => __( 'Customer Message', 'woocommerce' ),
              'type' => 'textarea',
              'default' => ''
          )
      );
      }

    public function payment_fields(): void
    {
        wc_get_template(
            'DougPayTemplate.php',
            array(),
            null,
            dirname(__FILE__) . './'
        );
    }

      
    }
  }

  add_filter('woocommerce_payment_gateways', 'addDougPayGateway');
}

function addDougPayGateway($methods)
{
  $methods[] = 'DougPayGateway';
  return $methods;
}
