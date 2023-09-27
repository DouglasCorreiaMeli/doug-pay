<?php

namespace DougPay\Woocommerce\Gateway;

if (!defined('ABSPATH')) {
    exit;
}

class DougPayGateway extends \WC_Payment_Gateway //extends the WooCommerce base gateway class, to have access to important methods and the settings API:
{
    public const ID = 'woo-doug-pay';
    public $path;

    public function __construct()
    {

        $this->id    = self::ID;
        $this->icon  = 'https://cdn-icons-png.flaticon.com/128/10200/10200975.png';
        $this->has_fields = true;
        $this->title = 'DougPay';
        $this->method_title       = 'DougPay Checkout';
        $this->method_description = 'Use o DougPay para pagar via Mercado Pago';
        $this->path =  plugin_dir_path(__FILE__) . './DougPayTemplate.php';

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
        wc_get_template("DougPayTemplate.php", null, null, $this->path);
    }

    public function process_payment($order_id): array
    {
        global $woocommerce;
        $order = new \WC_Order($order_id);
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
