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
    //This checks to see if the ABSPATH constant was defined earlier in the code. 
    //In the context of WordPress, ABSPATH is usually defined as the absolute path to the WordPress root directory.
    //This type of code is used as a security measure to ensure that a PHP file is only executed within the correct context. 
    //In WordPress, this is often used to prevent plugin or theme files from being directly accessed by external browsers or scripts, 
    //ensuring they are only loaded when WordPress is active
    exit;
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');
//used to include the "plugin.php" file that is part of the WordPress core. 
//This file contains functions and utilities related to plugins, which means that
//it provides functionality that can be useful for developing or managing plugins in WordPress.

if (is_plugin_active('woocommerce/woocommerce.php')) {
    //When WordPress has loaded all active plugins, execute the dougPayInit function."
    add_action('plugins_loaded', 'dougPayInit');
}

function dougPayInit()
{

    if (class_exists('WC_Payment_Gateway')) { 
        class DougPayGateway extends WC_Payment_Gateway //extends the WooCommerce base gateway class, to have access to important methods and the settings API:
        {
            public const ID = 'woo-doug-pay';

            public function __construct()
            {

                $this->id    = self::ID;
                $this->icon  = 'https://cdn-icons-png.flaticon.com/128/10200/10200975.png';
                $this->has_fields = true;
                $this->title = 'DougPay';
                $this->method_title       = 'DougPay Checkout';
                $this->method_description = 'Use o DougPay para pagar via Mercado Pago';

                $this->init_settings();
                $this->init_form_fields();
                add_action('woocommerce_update_options_payment_gateways_' . self::ID, array($this, 'process_admin_options')); //save hook for my settings
            }

            public function init_form_fields(): void
            {
                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Enable DougPay', 'woocommerce'),
                        'default' => 'yes'
                    ),
                    'title' => array(
                        'title' => __('Title', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                        'default' => __('DougPay', 'woocommerce'),
                        'desc_tip'      => true,
                    ),
                    'description' => array(
                        'title' => __('Customer Message', 'woocommerce'),
                        'type' => 'textarea',
                        'default' => ''
                    )
                );
            }

            public function payment_fields(): void
            {
                echo '<div class="form-row form-row-wide">';
                echo '<h3>This is an example Gateway</h3>';
                echo '<p>Click in place order</p>';
                echo '</div>';
            }

            public function process_payment($order_id): array
            {
                global $woocommerce;
                $order = new WC_Order($order_id);
                $order = wc_get_order($order_id);

                // Mark as on-hold (we're awaiting the cheque)
                $order->update_status('on-hold', __('Awaiting cheque payment', 'woocommerce'));

                // Remove cart
                $woocommerce->cart->empty_cart();

                // Return thankyou redirect
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order)
                );
            }
        }
    }

    add_filter('woocommerce_payment_gateways', 'addDougPayGateway'); //tell WooCommerce (WC) that DougPay exists. Do this by filtering
}

function addDougPayGateway($methods)
{
    $methods[] = 'DougPayGateway';
    return $methods;
}
