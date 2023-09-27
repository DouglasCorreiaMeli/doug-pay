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
 * @package DougPay
 */
if (!defined('ABSPATH')) { 
    exit;
}
include_once dirname(__FILE__) . '/src/WocommerceDougPay.php';
include_once dirname(__FILE__) . '/src/Autoloader.php';

use DougPay\Woocommerce\Autoloader;
use DougPay\Woocommerce\WoocommerceDougPay;

if (!Autoloader::init()) {
    return false;
}

$GLOBALS['dougpay'] = new WoocommerceDougPay();