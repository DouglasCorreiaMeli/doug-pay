<?php

namespace DougPay\Woocommerce;

if (!defined('ABSPATH')) {
    exit;
}

class Autoloader
{
    /**
     * Init Autoloader
     *
     * @return mixed
     */
    public static function init()
    {
        $autoloader = dirname(__FILE__) . '/../vendor/autoload.php';
        return self::loadAutoload($autoloader);
    }

    /**
     * Start loading autoload
     *
     * @param string $autoloader
     *
     * @return mixed
     */
    public static function loadAutoload(string $autoloader)
    {
        $autoloader_result = require $autoloader;
        if (!$autoloader_result) {
            return false;
        }

        return $autoloader_result;
    }
}