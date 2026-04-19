<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

if (!defined('ABSPATH')) {
    exit;
}

class WC_PayMongo_QRPH_Blocks_Integration extends AbstractPaymentMethodType
{
    protected $name = WC_PayMongo_QRPH_Gateway::GATEWAY_ID;
    protected $gateway;

    public function initialize()
    {
        $gateways = WC()->payment_gateways()->payment_gateways();
        $this->gateway = isset($gateways[$this->name]) ? $gateways[$this->name] : null;
    }

    public function is_active()
    {
        return $this->gateway && 'yes' === $this->gateway->enabled;
    }

    public function get_payment_method_script_handles()
    {
        wp_register_script(
            'wcpm-qrph-blocks',
            WCPM_QRPH_URL . 'assets/js/paymongo-qrph-blocks.js',
            array('wc-blocks-registry', 'wc-settings', 'wp-element', 'wp-html-entities'),
            WCPM_QRPH_VERSION,
            true
        );

        return array('wcpm-qrph-blocks');
    }

    public function get_payment_method_data()
    {
        return array(
            'title' => $this->gateway ? $this->gateway->title : 'QR Ph via PayMongo',
            'description' => $this->gateway ? $this->gateway->description : 'Scan and pay using QR Ph supported banks and e-wallets.',
            'supports' => array('products'),
        );
    }
}
