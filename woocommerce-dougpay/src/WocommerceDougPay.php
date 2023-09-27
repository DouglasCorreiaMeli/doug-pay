<?php

namespace DougPay\Woocommerce;

if (!defined('ABSPATH')) { 
    exit;
}

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

class WoocommerceDougPay {

    public function __construct()
    {
        $this->registerHooks();
    }


    public function registerHooks()
    {   
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() : void
    {
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            add_filter('woocommerce_payment_gateways', array($this, 'addGateway'));
        }
    }


    public function addGateway($gateways)
    {
        $gateways[] = 'DougPay\Woocommerce\Gateway\DougPayGateway';
        return $gateways;
    }

}
