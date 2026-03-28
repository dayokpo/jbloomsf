<?php
/**
 * Plugin Name: PayMongo WooCommerce Blocks Compatibility
 * Description: Adds WooCommerce Checkout Block compatibility for Payments via PayMongo for WooCommerce.
 * Version: 1.0.0
 * Author: Local Compatibility Bridge
 * Requires Plugins: woocommerce
 * Requires at least: 6.0
 * WC requires at least: 8.3
 * WC tested up to: 10.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WCPMBC_VERSION', '1.0.0');
define('WCPMBC_MAIN_FILE', __FILE__);
define('WCPMBC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WCPMBC_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!defined('WCPMBC_DEBUG')) {
    define('WCPMBC_DEBUG', true);
}

/**
 * Internal logger helper for debugging registration issues.
 *
 * @param string $message Message.
 * @param array  $context Context.
 */
function wcpmbc_log($message, $context = array())
{
    if (!WCPMBC_DEBUG || !function_exists('wc_get_logger')) {
        return;
    }

    wc_get_logger()->info($message, array_merge(array('source' => 'wc-paymongo-blocks-compatibility'), $context));
}

/**
 * Declare support for WooCommerce Checkout Blocks.
 */
add_action('before_woocommerce_init', function () {
    if (class_exists('Automattic\\WooCommerce\\Utilities\\FeaturesUtil')) {
        Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);

        // Mark the legacy PayMongo gateway plugin as compatible to suppress
        // the Checkout Block editor incompatibility warning.
        $legacy_paymongo_main_file = WP_PLUGIN_DIR . '/wc-paymongo-payment-gateway/payments-paymongo-woocommerce.php';
        if (file_exists($legacy_paymongo_main_file)) {
            Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', $legacy_paymongo_main_file, true);
        }
    }
});

/**
 * Bootstrap bridge once all plugins are loaded.
 */
add_action('plugins_loaded', function () {
    wcpmbc_log('Bridge bootstrap started on plugins_loaded.');

    if (WCPMBC_DEBUG && is_admin()) {
        add_action('admin_footer', function () {
            echo '<script>console.log("[WCPMBC] Bridge plugin loaded in admin context.");</script>';
        });
    }

    if (!class_exists('WooCommerce')) {
        wcpmbc_log('WooCommerce class is missing; bridge bootstrap aborted.');
        return;
    }

    if (!defined('CYNDER_PAYMONGO_MAIN_FILE')) {
        wcpmbc_log('CYNDER_PAYMONGO_MAIN_FILE is not defined; legacy PayMongo plugin not initialized yet.');

        add_action('admin_notices', function () {
            if (!current_user_can('activate_plugins')) {
                return;
            }

            echo '<div class="notice notice-warning"><p>'
                . esc_html__('PayMongo WooCommerce Blocks Compatibility requires the "Payments via PayMongo for WooCommerce" plugin to be active.', 'wc-paymongo-blocks-compatibility')
                . '</p></div>';
        });

        return;
    }

    wcpmbc_log('Legacy PayMongo plugin detected; registering block integration hooks.');

    add_action(
        'woocommerce_blocks_payment_method_type_registration',
        function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {
            if (!class_exists('Automattic\\WooCommerce\\Blocks\\Payments\\Integrations\\AbstractPaymentMethodType')) {
                wcpmbc_log('Blocks payment integration base class not available during registration.');
                return;
            }

            require_once WCPMBC_PLUGIN_PATH . 'includes/class-wc-paymongo-blocks-integration.php';

            $gateway_ids = defined('PAYMONGO_PAYMENT_METHODS') && is_array(PAYMONGO_PAYMENT_METHODS)
                ? PAYMONGO_PAYMENT_METHODS
                : array(
                    'paymongo',
                    'paymongo_card_installment',
                    'paymongo_gcash',
                    'paymongo_grab_pay',
                    'paymongo_paymaya',
                    'paymongo_atome',
                    'paymongo_bpi',
                    'paymongo_unionbank',
                    'paymongo_billease',
                );

            foreach ($gateway_ids as $gateway_id) {
                $payment_method_registry->register(new WC_PayMongo_Blocks_Integration($gateway_id));
            }

            wcpmbc_log('Registered PayMongo payment method integrations for blocks.', array('gateway_ids' => $gateway_ids));
        }
    );
}, 100);
