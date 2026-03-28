<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bridges a legacy PayMongo gateway into WooCommerce Checkout Blocks.
 */
class WC_PayMongo_Blocks_Integration extends AbstractPaymentMethodType
{
    /**
     * Fallback labels used when gateway settings are missing.
     *
     * @var array<string, string>
     */
    private $default_titles = array(
        'paymongo' => 'Credit Card via PayMongo',
        'paymongo_card_installment' => 'Card Installment via PayMongo',
        'paymongo_gcash' => 'GCash via PayMongo',
        'paymongo_grab_pay' => 'GrabPay via PayMongo',
        'paymongo_paymaya' => 'Maya via PayMongo',
        'paymongo_atome' => 'Atome via PayMongo',
        'paymongo_bpi' => 'BPI Direct Online Banking via PayMongo',
        'paymongo_unionbank' => 'UnionBank Direct Online Banking via PayMongo',
        'paymongo_billease' => 'BillEase via PayMongo',
    );

    /**
     * Fallback descriptions used when gateway settings are missing.
     *
     * @var array<string, string>
     */
    private $default_descriptions = array(
        'paymongo' => 'Simple and easy payments with Credit/Debit Card.',
        'paymongo_card_installment' => 'Simple and easy installment payments via PayMongo.',
        'paymongo_gcash' => 'Simple and easy payments via GCash.',
        'paymongo_grab_pay' => 'Simple and easy payments via GrabPay.',
        'paymongo_paymaya' => 'Simple and easy payments via Maya.',
        'paymongo_atome' => 'Simple and easy payments via Atome.',
        'paymongo_bpi' => 'Simple and easy payments via BPI online banking.',
        'paymongo_unionbank' => 'Simple and easy payments via UnionBank online banking.',
        'paymongo_billease' => 'Simple and easy payments via BillEase.',
    );

    /**
     * Gateway ID used by legacy checkout.
     *
     * @var string
     */
    protected $name;

    /**
     * Legacy gateway instance.
     *
     * @var WC_Payment_Gateway|null
     */
    private $gateway;

    /**
     * Cached gateway settings.
     *
     * @var array<string, mixed>
     */
    protected $settings = array();

    /**
     * @param string $gateway_id Gateway ID.
     */
    public function __construct($gateway_id)
    {
        $this->name = $gateway_id;
    }

    /**
     * Initialize integration.
     */
    public function initialize()
    {
        $this->settings = get_option('woocommerce_' . $this->name . '_settings', array());

        $payment_gateways = WC()->payment_gateways();
        if (!$payment_gateways) {
            return;
        }

        $registered_gateways = $payment_gateways->payment_gateways();
        $this->gateway = $registered_gateways[$this->name] ?? null;

        if (function_exists('wcpmbc_log')) {
            wcpmbc_log(
                'Initialized PayMongo block integration.',
                array(
                    'gateway_id' => $this->name,
                    'settings_enabled' => isset($this->settings['enabled']) ? $this->settings['enabled'] : null,
                    'has_gateway_object' => $this->gateway instanceof WC_Payment_Gateway,
                )
            );
        }
    }

    /**
     * Determine if gateway should be exposed in blocks.
     *
     * @return bool
     */
    public function is_active()
    {
        if ($this->gateway instanceof WC_Payment_Gateway && isset($this->gateway->enabled)) {
            $active = filter_var($this->gateway->enabled, FILTER_VALIDATE_BOOLEAN);

            if (function_exists('wcpmbc_log')) {
                wcpmbc_log('Evaluated gateway object active state.', array('gateway_id' => $this->name, 'active' => $active));
            }

            return $active;
        }

        $active = filter_var($this->get_setting('enabled', false), FILTER_VALIDATE_BOOLEAN);

        if (function_exists('wcpmbc_log')) {
            wcpmbc_log('Evaluated settings active state.', array('gateway_id' => $this->name, 'active' => $active));
        }

        return $active;
    }

    /**
     * Register script handles required by blocks checkout.
     *
     * @return array
     */
    public function get_payment_method_script_handles()
    {
        wp_register_script(
            'wc-paymongo-blocks-compatibility',
            WCPMBC_PLUGIN_URL . 'assets/js/paymongo-blocks.js',
            array('wc-blocks-registry', 'wc-settings', 'wp-element', 'wp-html-entities'),
            WCPMBC_VERSION,
            true
        );

        if (function_exists('wcpmbc_log')) {
            wcpmbc_log('Registered PayMongo block script handle.', array('gateway_id' => $this->name));
        }

        return array('wc-paymongo-blocks-compatibility');
    }

    /**
     * Data shared with blocks frontend.
     *
     * @return array
     */
    public function get_payment_method_data()
    {
        $is_test_mode = 'yes' === get_option('woocommerce_cynder_paymongo_test_mode');
        $public_key_option = $is_test_mode
            ? 'woocommerce_cynder_paymongo_test_public_key'
            : 'woocommerce_cynder_paymongo_public_key';

        $title = $this->gateway
            ? $this->gateway->get_title()
            : (isset($this->settings['title']) ? $this->settings['title'] : ($this->default_titles[$this->name] ?? $this->name));

        $description = $this->gateway
            ? $this->gateway->get_description()
            : (isset($this->settings['description']) ? $this->settings['description'] : ($this->default_descriptions[$this->name] ?? ''));

        $supports = $this->gateway && is_array($this->gateway->supports)
            ? array_values($this->gateway->supports)
            : array('products');

        return array(
            'title' => wp_kses_post($title),
            'description' => wp_kses_post($description),
            'supports' => $supports,
            'isActive' => $this->is_active(),
            'isCardMethod' => 'paymongo' === $this->name,
            'isInstallmentMethod' => 'paymongo_card_installment' === $this->name,
            'testMode' => $is_test_mode,
            'publicKey' => (string) get_option($public_key_option, ''),
        );
    }
}
