<?php
/**
 * Plugin Name: PayMongo QR Ph Add-on for WooCommerce
 * Description: Adds QR Ph payment support via PayMongo as a separate WooCommerce gateway add-on.
 * Version: 1.0.0
 * Author: Local Add-on
 * Requires Plugins: woocommerce
 * Requires at least: 6.0
 * WC requires at least: 8.3
 * WC tested up to: 10.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WCPM_QRPH_VERSION', '1.0.0');
define('WCPM_QRPH_FILE', __FILE__);
define('WCPM_QRPH_PATH', plugin_dir_path(__FILE__));
define('WCPM_QRPH_URL', plugin_dir_url(__FILE__));

function wcpm_qrph_log($level, $message, $context = array())
{
    if (!function_exists('wc_get_logger')) {
        return;
    }

    $logger = wc_get_logger();
    $payload = array_merge(array('source' => 'wc-paymongo-qrph-addon'), $context);
    $logger->log($level, $message, $payload);
}

add_action('before_woocommerce_init', function () {
    if (class_exists('Automattic\\WooCommerce\\Utilities\\FeaturesUtil')) {
        Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        return;
    }

    if (!defined('CYNDER_PAYMONGO_MAIN_FILE') || !class_exists('Paymongo\\Phaymongo\\Phaymongo')) {
        add_action('admin_notices', function () {
            if (!current_user_can('activate_plugins')) {
                return;
            }

            echo '<div class="notice notice-warning"><p>'
                . esc_html__('PayMongo QR Ph Add-on requires the parent plugin "Payments via PayMongo for WooCommerce" to be active.', 'wc-paymongo-qrph-addon')
                . '</p></div>';
        });
        return;
    }

    require_once WCPM_QRPH_PATH . 'includes/class-wc-paymongo-qrph-gateway.php';

    add_filter('woocommerce_payment_gateways', function ($methods) {
        $methods[] = 'WC_PayMongo_QRPH_Gateway';
        return $methods;
    });

    if (class_exists('Automattic\\WooCommerce\\Blocks\\Payments\\Integrations\\AbstractPaymentMethodType')) {
        require_once WCPM_QRPH_PATH . 'includes/class-wc-paymongo-qrph-blocks-integration.php';

        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {
                $payment_method_registry->register(new WC_PayMongo_QRPH_Blocks_Integration());
            }
        );
    }
});