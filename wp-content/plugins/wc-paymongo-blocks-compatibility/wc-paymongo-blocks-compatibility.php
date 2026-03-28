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

    $wcpmbc_clear_stale_notices = function () {
        if (!function_exists('WC')) {
            return;
        }

        $wc = WC();
        if (!is_object($wc) || !isset($wc->session) || !$wc->session) {
            return;
        }

        // Clear both in-memory and session-backed notices to avoid
        // old payment API errors being rethrown as cart validation conflicts.
        if (function_exists('wc_clear_notices')) {
            wc_clear_notices();
        }

        $wc->session->set('wc_notices', null);
    };

    add_filter('rest_request_before_callbacks', function ($response, $handler, $request) {
        if (!($request instanceof WP_REST_Request) || !function_exists('WC') || !function_exists('wc_clear_notices')) {
            return $response;
        }

        if (strtoupper($request->get_method()) !== 'POST') {
            return $response;
        }

        if ($request->get_route() !== '/wc/store/v1/checkout') {
            return $response;
        }

        $wcpmbc_clear_stale_notices = $GLOBALS['wcpmbc_clear_stale_notices'] ?? null;
        if (is_callable($wcpmbc_clear_stale_notices)) {
            $wcpmbc_clear_stale_notices();
            wcpmbc_log('Cleared stale Woo notices before Store API checkout callback.');
        }

        return $response;
    }, 1, 3);

    $GLOBALS['wcpmbc_clear_stale_notices'] = $wcpmbc_clear_stale_notices;

    add_action('woocommerce_store_api_cart_errors', function () {
        $wcpmbc_clear_stale_notices = $GLOBALS['wcpmbc_clear_stale_notices'] ?? null;
        if (is_callable($wcpmbc_clear_stale_notices)) {
            $wcpmbc_clear_stale_notices();
            wcpmbc_log('Cleared stale Woo notices during Store API cart validation.');
        }
    }, 1, 2);

    // PayMongo gateways are legacy gateways. Some error paths return null instead of
    // an array, which causes Woo's legacy bridge to fatal when merging results.
    add_action('woocommerce_rest_checkout_process_payment_with_context', function ($context, &$result) {
        if (!is_object($result) || !empty($result->status)) {
            return;
        }

        $method = '';
        if (is_object($context)) {
            try {
                $method = (string) $context->payment_method;
            } catch (Throwable $e) {
                $method = '';
            }
        }

        if (strpos($method, 'paymongo') !== 0) {
            return;
        }

        $payment_method_object = is_object($context) && method_exists($context, 'get_payment_method_instance')
            ? $context->get_payment_method_instance()
            : null;

        if (!($payment_method_object instanceof WC_Payment_Gateway)) {
            $result->set_status('failure');
            $result->set_payment_details(array('result' => 'failure', 'message' => 'PayMongo gateway is not available.'));
            return;
        }

        // phpcs:ignore WordPress.Security.NonceVerification
        $post_data = $_POST;

        wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);

        // phpcs:ignore WordPress.Security.NonceVerification
        $context_payment_data = null;
        if (is_object($context)) {
            try {
                $context_payment_data = $context->payment_data;
            } catch (Throwable $e) {
                $context_payment_data = null;
            }
        }

        $_POST = is_array($context_payment_data) ? $context_payment_data : array();

        $gateway_result = null;

        try {
            if (function_exists('cynder_paymongo_create_intent')) {
                $context_order_for_intent = null;
                if (is_object($context)) {
                    try {
                        $context_order_for_intent = $context->order;
                    } catch (Throwable $e) {
                        $context_order_for_intent = null;
                    }
                }

                $intent_order_id = is_object($context_order_for_intent)
                    ? $context_order_for_intent->get_id()
                    : 0;

                if ($intent_order_id > 0) {
                    try {
                        // Regenerate intent for each attempt to avoid using stale IDs.
                        cynder_paymongo_create_intent($intent_order_id);
                        wcpmbc_log('Refreshed PayMongo payment intent before process_payment.', array(
                            'payment_method' => $method,
                            'order_id' => $intent_order_id,
                        ));
                    } catch (Throwable $e) {
                        wcpmbc_log('Failed to refresh PayMongo payment intent before process_payment.', array(
                            'payment_method' => $method,
                            'order_id' => $intent_order_id,
                            'error' => $e->getMessage(),
                        ));
                    }
                }
            }

            $payment_method_object->validate_fields();

            if (
                class_exists('Automattic\\WooCommerce\\StoreApi\\Utilities\\NoticeHandler') &&
                function_exists('WC') && is_object(WC()) && isset(WC()->session) && WC()->session
            ) {
                Automattic\WooCommerce\StoreApi\Utilities\NoticeHandler::convert_notices_to_exceptions('woocommerce_rest_payment_error');
            }

            $context_order = null;
            if (is_object($context)) {
                try {
                    $context_order = $context->order;
                } catch (Throwable $e) {
                    $context_order = null;
                }
            }

            $order_id = is_object($context_order) ? $context_order->get_id() : 0;

            $gateway_result = $payment_method_object->process_payment($order_id);
        } catch (Automattic\WooCommerce\StoreApi\Exceptions\RouteException $e) {
            $gateway_result = array(
                'result' => 'failure',
                'message' => $e->getMessage(),
            );
        } catch (Throwable $e) {
            wcpmbc_log('PayMongo payment pre-processor caught exception.', array(
                'payment_method' => $method,
                'error' => $e->getMessage(),
            ));

            $gateway_result = array(
                'result' => 'failure',
                'message' => 'Payment processing failed. Please try again.',
            );
        }

        // phpcs:ignore WordPress.Security.NonceVerification
        $_POST = $post_data;

        if (!is_array($gateway_result)) {
            wcpmbc_log('Normalized null/invalid gateway result from PayMongo process_payment.', array(
                'payment_method' => $method,
                'gateway_result_type' => gettype($gateway_result),
            ));

            $gateway_result = array(
                'result' => 'failure',
                'message' => 'Payment processing failed. Please try another payment method.',
            );
        }

        $result_status = isset($gateway_result['result']) ? $gateway_result['result'] : 'failure';
        $valid_status = array('success', 'failure', 'pending', 'error');

        $result->set_status(in_array($result_status, $valid_status, true) ? $result_status : 'failure');

        if (function_exists('wc_clear_notices')) {
            wc_clear_notices();
        }

        $payment_details = is_array($result->payment_details) ? $result->payment_details : array();
        $result->set_payment_details(array_merge($payment_details, $gateway_result));
        $result->set_redirect_url(isset($gateway_result['redirect']) ? $gateway_result['redirect'] : '');
    }, 998, 2);

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

    add_action('woocommerce_rest_checkout_process_payment_with_context', function ($context, &$payment_result) {
        $method = '';
        $order_id = 0;
        $payment_data = null;

        if (is_object($context)) {
            try {
                $method = (string) $context->payment_method;
            } catch (Throwable $e) {
                $method = '';
            }

            try {
                $order = $context->order;
                if (is_object($order)) {
                    $order_id = $order->get_id();
                }
            } catch (Throwable $e) {
                $order_id = 0;
            }

            try {
                $payment_data_candidate = $context->payment_data;
                $payment_data = is_array($payment_data_candidate) ? $payment_data_candidate : null;
            } catch (Throwable $e) {
                $payment_data = null;
            }
        }

        wcpmbc_log(
            'Store API payment context (before gateway processing).',
            array(
                'payment_method' => $method,
                'order_id' => $order_id,
                'payment_data_type' => gettype($payment_data),
                'payment_data_keys' => is_array($payment_data) ? array_keys($payment_data) : array(),
            )
        );
    }, 5, 2);

    add_action('woocommerce_rest_checkout_process_payment_with_context', function ($context, &$payment_result) {
        $status = is_object($payment_result) && isset($payment_result->status)
            ? $payment_result->status
            : null;

        $details = is_object($payment_result) && isset($payment_result->payment_details)
            ? $payment_result->payment_details
            : null;

        wcpmbc_log(
            'Store API payment context (after gateway processing).',
            array(
                'status' => $status,
                'payment_details_type' => gettype($details),
                'payment_details_is_array' => is_array($details),
                'payment_details_keys' => is_array($details) ? array_keys($details) : array(),
            )
        );
    }, 1005, 2);
}, 100);
