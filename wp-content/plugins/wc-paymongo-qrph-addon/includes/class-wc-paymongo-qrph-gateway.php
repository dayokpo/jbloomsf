<?php

use Paymongo\Phaymongo\PaymongoException;
use Paymongo\Phaymongo\PaymongoUtils;
use Paymongo\Phaymongo\Phaymongo;

if (!defined('ABSPATH')) {
    exit;
}

class WC_PayMongo_QRPH_Gateway extends WC_Payment_Gateway
{
    const GATEWAY_ID = 'paymongo_qrph';
    const META_INTENT_ID = '_wcpm_qrph_payment_intent_id';
    const META_LAST_INTENT = '_wcpm_qrph_last_intent_payload';

    protected $testmode;
    protected $public_key;
    protected $secret_key;
    protected $debug_mode;

    public function __construct()
    {
        $this->id = self::GATEWAY_ID;
        $this->method_title = 'QR Ph via PayMongo';
        $this->method_description = 'Accept QR Ph payments via PayMongo.';
        $this->has_fields = false;
        $this->supports = array('products');

        $this->init_form_fields();
        $this->init_settings();

        $this->enabled = $this->get_option('enabled', 'no');
        $this->title = $this->get_option('title', 'QR Ph via PayMongo');
        $this->description = $this->get_option('description', 'Scan and pay using QR Ph supported banks and e-wallets.');

        $test_mode = get_option('woocommerce_cynder_paymongo_test_mode');
        $this->testmode = (!empty($test_mode) && $test_mode === 'yes');

        $pk_key = $this->testmode ? 'woocommerce_cynder_paymongo_test_public_key' : 'woocommerce_cynder_paymongo_public_key';
        $sk_key = $this->testmode ? 'woocommerce_cynder_paymongo_test_secret_key' : 'woocommerce_cynder_paymongo_secret_key';

        $this->public_key = get_option($pk_key);
        $this->secret_key = get_option($sk_key);

        $debug_mode = get_option('woocommerce_cynder_paymongo_debug_mode');
        $this->debug_mode = (!empty($debug_mode) && $debug_mode === 'yes');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_api_wcpm_qrph_return', array(__CLASS__, 'handle_return'));
        add_action('woocommerce_api_wcpm_qrph_show_qr', array(__CLASS__, 'handle_show_qr'));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'label' => 'Enable QR Ph via PayMongo',
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text',
                'description' => 'Title shown to customers at checkout.',
                'default' => 'QR Ph via PayMongo',
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => 'Description',
                'type' => 'textarea',
                'description' => 'Description shown to customers at checkout.',
                'default' => 'Scan and pay using QR Ph supported banks and e-wallets.',
            ),
        );
    }

    protected function get_client()
    {
        if (empty($this->public_key) || empty($this->secret_key)) {
            return null;
        }

        return new Phaymongo($this->public_key, $this->secret_key);
    }

    protected function create_intent($client, WC_Order $order)
    {
        $shop_name = get_bloginfo('name');
        $metadata = array(
            // Keep metadata compatible with parent webhook validation.
            'agent' => 'cynder_woocommerce',
            'version' => defined('CYNDER_PAYMONGO_VERSION') ? CYNDER_PAYMONGO_VERSION : WCPM_QRPH_VERSION,
            'store_name' => $shop_name,
            'order_id' => (string) $order->get_id(),
            'customer_id' => (string) $order->get_customer_id(),
        );

        $intent = $client->paymentIntent()->create(
            (float) $order->get_total(),
            array('qrph'),
            null,
            $shop_name . ' - ' . $order->get_id(),
            $metadata
        );

        if (isset($intent['id']) && $intent['id'] !== '') {
            $order->update_meta_data(self::META_INTENT_ID, $intent['id']);

            // Mirror parent plugin's meta key so its webhook handler can resolve the order.
            if (defined('PAYMONGO_PAYMENT_INTENT_META_KEY')) {
                $order->update_meta_data(PAYMONGO_PAYMENT_INTENT_META_KEY, $intent['id']);
            } else {
                $order->update_meta_data('paymongo_payment_intent_id', $intent['id']);
            }

            $order->save_meta_data();
        }

        return $intent;
    }

    protected function create_payment_method($client, WC_Order $order)
    {
        $billing = PaymongoUtils::generateBillingObject($order, 'woocommerce');
        return $client->paymentMethod()->create('qrph', null, $billing);
    }

    protected static function extract_redirect_url($intent)
    {
        $next_action = $intent['attributes']['next_action'] ?? array();

        $candidates = array(
            $next_action['redirect']['url'] ?? null,
            $next_action['url'] ?? null,
            $next_action['redirect_url'] ?? null,
            $next_action['qrph']['checkout_url'] ?? null,
            $next_action['qrph']['url'] ?? null,
            $next_action['display_qr']['url'] ?? null,
        );

        foreach ($candidates as $url) {
            if (is_string($url) && $url !== '') {
                return $url;
            }
        }

        return '';
    }

    protected static function extract_qr_image_url($intent)
    {
        $next_action = $intent['attributes']['next_action'] ?? array();

        $candidates = array(
            $next_action['display_qr']['image_url'] ?? null,
            $next_action['display_qr']['qr_image_url'] ?? null,
            $next_action['display_qr']['url'] ?? null,
            $next_action['qrph']['image_url'] ?? null,
            $next_action['qrph']['qr_image_url'] ?? null,
            $next_action['qrph']['qr_url'] ?? null,
            $next_action['qrph']['url'] ?? null,
        );

        foreach ($candidates as $url) {
            if (is_string($url) && $url !== '') {
                return $url;
            }
        }

        return '';
    }

    protected static function extract_qr_reference($intent)
    {
        $next_action = $intent['attributes']['next_action'] ?? array();

        $candidates = array(
            $next_action['display_qr']['code'] ?? null,
            $next_action['display_qr']['qr_string'] ?? null,
            $next_action['qrph']['code'] ?? null,
            $next_action['qrph']['qr_string'] ?? null,
            $next_action['qrph']['invoice_number'] ?? null,
            $intent['attributes']['reference_number'] ?? null,
        );

        foreach ($candidates as $value) {
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return '';
    }

    protected function build_qr_page_url($order_id, $intent_id)
    {
        return add_query_arg(
            array(
                'wc-api' => 'wcpm_qrph_show_qr',
                'order' => $order_id,
                'intent' => $intent_id,
            ),
            home_url('/')
        );
    }

    protected static function get_keys_for_current_mode()
    {
        $test_mode = get_option('woocommerce_cynder_paymongo_test_mode');
        $test_mode = (!empty($test_mode) && $test_mode === 'yes');
        $pk_key = $test_mode ? 'woocommerce_cynder_paymongo_test_public_key' : 'woocommerce_cynder_paymongo_public_key';
        $sk_key = $test_mode ? 'woocommerce_cynder_paymongo_test_secret_key' : 'woocommerce_cynder_paymongo_secret_key';

        return array(
            'public_key' => get_option($pk_key),
            'secret_key' => get_option($sk_key),
        );
    }

    protected static function get_first_payment($intent)
    {
        $payments = $intent['attributes']['payments'] ?? array();
        if (is_array($payments) && isset($payments[0]) && is_array($payments[0])) {
            return $payments[0];
        }

        return null;
    }

    protected static function intent_has_resolved_payment($intent)
    {
        $payment = self::get_first_payment($intent);
        if (!is_array($payment)) {
            return false;
        }

        $payment_status = strtolower((string) ($payment['attributes']['status'] ?? ''));
        return in_array($payment_status, array('paid', 'succeeded', 'processing'), true);
    }

    protected static function maybe_mark_order_on_hold(WC_Order $order, $note)
    {
        if ($order->has_status(array('completed', 'processing', 'on-hold'))) {
            return;
        }

        $order->update_status('on-hold', $note);
    }

    protected function format_exception_message(PaymongoException $e)
    {
        $messages = $e->format_errors();
        if (is_array($messages) && count($messages) > 0) {
            return implode(', ', $messages);
        }

        return 'Unable to process QR Ph payment right now.';
    }

    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);
        if (!$order) {
            wc_add_notice('Invalid order.', 'error');
            return array('result' => 'failure', 'message' => 'Invalid order.');
        }

        $client = $this->get_client();
        if (!$client) {
            $message = 'PayMongo API keys are not configured for the current environment.';
            wc_add_notice($message, 'error');
            return array('result' => 'failure', 'message' => $message);
        }

        try {
            $intent = $this->create_intent($client, $order);
            $intent_id = $intent['id'] ?? $order->get_meta(self::META_INTENT_ID);

            if (empty($intent_id)) {
                $message = 'Unable to initialize QR Ph payment intent.';
                wc_add_notice($message, 'error');
                return array('result' => 'failure', 'message' => $message);
            }

            $payment_method = $this->create_payment_method($client, $order);
            $payment_method_id = $payment_method['id'] ?? '';

            if (empty($payment_method_id)) {
                $message = 'Unable to create QR Ph payment method.';
                wc_add_notice($message, 'error');
                return array('result' => 'failure', 'message' => $message);
            }

            $return_url = add_query_arg(
                array(
                    'wc-api' => 'wcpm_qrph_return',
                    'order' => $order->get_id(),
                    'intent' => $intent_id,
                ),
                home_url('/')
            );

            $attached_intent = $client->paymentIntent()->attachPaymentMethod(
                $intent_id,
                $payment_method_id,
                null,
                $return_url
            );

            if ($this->debug_mode) {
                wcpm_qrph_log('info', 'QRPH attach response: ' . wc_print_r($attached_intent, true));
            }

            $order->update_meta_data(self::META_LAST_INTENT, wp_json_encode($attached_intent));
            $order->save_meta_data();

            $status = $attached_intent['attributes']['status'] ?? '';
            if ($status === 'succeeded' || $status === 'processing') {
                $payment_id = $attached_intent['attributes']['payments'][0]['id'] ?? $intent_id;
                $order->payment_complete($payment_id);
                if (function_exists('WC') && WC()->cart) {
                    WC()->cart->empty_cart();
                }

                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($order),
                );
            }

            if ($status === 'awaiting_next_action') {
                $redirect = self::extract_redirect_url($attached_intent);
                if (!empty($redirect)) {
                    return array(
                        'result' => 'success',
                        'redirect' => $redirect,
                    );
                }

                return array(
                    'result' => 'success',
                    'redirect' => $this->build_qr_page_url($order->get_id(), $intent_id),
                );
            }

            $message = 'QR Ph payment is currently unavailable. Please try again.';
            wc_add_notice($message, 'error');
            return array('result' => 'failure', 'message' => $message);
        } catch (PaymongoException $e) {
            $message = $this->format_exception_message($e);
            wcpm_qrph_log('error', 'QRPH process_payment error for order ' . $order_id . ': ' . $message);
            wc_add_notice($message, 'error');
            return array('result' => 'failure', 'message' => $message);
        } catch (Throwable $e) {
            $message = 'Unexpected QR Ph error. Please try again.';
            wcpm_qrph_log('error', 'QRPH unexpected process_payment error for order ' . $order_id . ': ' . $e->getMessage());
            wc_add_notice($message, 'error');
            return array('result' => 'failure', 'message' => $message);
        }
    }

    public static function handle_return()
    {
        $order_id = isset($_GET['order']) ? absint($_GET['order']) : 0;
        $intent_id = isset($_GET['intent']) ? sanitize_text_field(wp_unslash($_GET['intent'])) : '';

        $order = $order_id ? wc_get_order($order_id) : null;
        if (!$order) {
            wp_safe_redirect(wc_get_checkout_url());
            exit;
        }

        $keys = self::get_keys_for_current_mode();
        $public_key = $keys['public_key'];
        $secret_key = $keys['secret_key'];

        if (empty($intent_id)) {
            $intent_id = (string) $order->get_meta(self::META_INTENT_ID);
        }

        if (!empty($intent_id)) {
            if (defined('PAYMONGO_PAYMENT_INTENT_META_KEY')) {
                $order->update_meta_data(PAYMONGO_PAYMENT_INTENT_META_KEY, $intent_id);
            } else {
                $order->update_meta_data('paymongo_payment_intent_id', $intent_id);
            }
            $order->save_meta_data();
        }

        if (empty($public_key) || empty($secret_key) || empty($intent_id)) {
            wc_add_notice('Unable to verify QR Ph payment.', 'error');
            wp_safe_redirect($order->get_checkout_payment_url());
            exit;
        }

        try {
            $client = new Phaymongo($public_key, $secret_key);
            $intent = $client->paymentIntent()->retrieveById($intent_id);
            $status = $intent['attributes']['status'] ?? '';

            wcpm_qrph_log('info', 'QRPH return status for order ' . $order_id . ': ' . $status);

            $order->update_meta_data(self::META_LAST_INTENT, wp_json_encode($intent));
            $order->save_meta_data();

            if ($status === 'succeeded' || $status === 'processing') {
                $payment_id = $intent['attributes']['payments'][0]['id'] ?? $intent_id;
                $order->payment_complete($payment_id);
                if (function_exists('WC') && WC()->cart) {
                    WC()->cart->empty_cart();
                }

                wp_safe_redirect($order->get_checkout_order_received_url());
                exit;
            }

            if (self::intent_has_resolved_payment($intent)) {
                $payment = self::get_first_payment($intent);
                $payment_id = $payment['id'] ?? $intent_id;
                $order->payment_complete($payment_id);
                if (function_exists('WC') && WC()->cart) {
                    WC()->cart->empty_cart();
                }

                wp_safe_redirect($order->get_checkout_order_received_url());
                exit;
            }

            if ($status === 'awaiting_next_action') {
                $redirect = self::extract_redirect_url($intent);
                if (!empty($redirect)) {
                    wcpm_qrph_log('info', 'QRPH return awaiting_next_action with redirect for order ' . $order_id);
                    wp_safe_redirect($redirect);
                    exit;
                }

                wcpm_qrph_log('warning', 'QRPH return awaiting_next_action without redirect for order ' . $order_id . '. Marking on-hold and sending to order received.');
                self::maybe_mark_order_on_hold(
                    $order,
                    'QR Ph payment authorization is pending final confirmation from PayMongo.'
                );

                if (function_exists('WC') && WC()->cart) {
                    WC()->cart->empty_cart();
                }

                wp_safe_redirect($order->get_checkout_order_received_url());
                exit;
            }

            if ($status === 'awaiting_payment_method') {
                wcpm_qrph_log('warning', 'QRPH return awaiting_payment_method for order ' . $order_id . '. Marking on-hold.');
                self::maybe_mark_order_on_hold(
                    $order,
                    'QR Ph authorization returned but payment is still pending confirmation from PayMongo.'
                );
                wp_safe_redirect($order->get_checkout_order_received_url());
                exit;
            }

            wc_add_notice('QR Ph payment is not completed yet. Please try again.', 'error');
            wp_safe_redirect($order->get_checkout_payment_url());
            exit;
        } catch (PaymongoException $e) {
            $messages = $e->format_errors();
            $message = is_array($messages) && count($messages) > 0
                ? implode(', ', $messages)
                : 'Unable to verify QR Ph payment right now.';

            wcpm_qrph_log('error', 'QRPH return PaymongoException for order ' . $order_id . ': ' . $message);
            self::maybe_mark_order_on_hold(
                $order,
                'QR Ph return could not be verified instantly. Waiting for final confirmation from PayMongo/webhook.'
            );
            wp_safe_redirect($order->get_checkout_order_received_url());
            exit;
        } catch (Throwable $e) {
            wcpm_qrph_log('error', 'QRPH return handler error for order ' . $order_id . ': ' . $e->getMessage());
            self::maybe_mark_order_on_hold(
                $order,
                'QR Ph return encountered a temporary issue. Waiting for final confirmation from PayMongo/webhook.'
            );
            wp_safe_redirect($order->get_checkout_order_received_url());
            exit;
        }
    }

    public static function handle_show_qr()
    {
        $order_id = isset($_GET['order']) ? absint($_GET['order']) : 0;
        $intent_id = isset($_GET['intent']) ? sanitize_text_field(wp_unslash($_GET['intent'])) : '';

        $order = $order_id ? wc_get_order($order_id) : null;
        if (!$order) {
            wp_safe_redirect(wc_get_checkout_url());
            exit;
        }

        if (empty($intent_id)) {
            $intent_id = (string) $order->get_meta(self::META_INTENT_ID);
        }

        $keys = self::get_keys_for_current_mode();
        $public_key = $keys['public_key'];
        $secret_key = $keys['secret_key'];

        $intent = null;
        if (!empty($public_key) && !empty($secret_key) && !empty($intent_id)) {
            try {
                $client = new Phaymongo($public_key, $secret_key);
                $intent = $client->paymentIntent()->retrieveById($intent_id);
            } catch (Throwable $e) {
                wcpm_qrph_log('error', 'QRPH show_qr retrieval error for order ' . $order_id . ': ' . $e->getMessage());
            }
        }

        if (!is_array($intent)) {
            $cached = $order->get_meta(self::META_LAST_INTENT);
            if (is_string($cached) && $cached !== '') {
                $decoded = json_decode($cached, true);
                if (is_array($decoded)) {
                    $intent = $decoded;
                }
            }
        }

        if (is_array($intent)) {
            $status = $intent['attributes']['status'] ?? '';
            if ($status === 'succeeded' || $status === 'processing') {
                $payment_id = $intent['attributes']['payments'][0]['id'] ?? $intent_id;
                $order->payment_complete($payment_id);
                if (function_exists('WC') && WC()->cart) {
                    WC()->cart->empty_cart();
                }

                wp_safe_redirect($order->get_checkout_order_received_url());
                exit;
            }

            $redirect = self::extract_redirect_url($intent);
            if (!empty($redirect)) {
                wp_safe_redirect($redirect);
                exit;
            }

            $qr_image_url = self::extract_qr_image_url($intent);
            $qr_reference = self::extract_qr_reference($intent);

            nocache_headers();
            status_header(200);
            ?>
            <!doctype html>
            <html>
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <title><?php echo esc_html__('QR Ph Payment', 'wc-paymongo-qrph-addon'); ?></title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
                    .wrap { max-width: 520px; margin: 0 auto; text-align: center; }
                    .qr { margin: 16px 0; }
                    .qr img { max-width: 100%; height: auto; border: 1px solid #e5e7eb; border-radius: 8px; }
                    .ref { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; word-break: break-all; }
                    .actions a { display: inline-block; margin: 10px 6px 0; padding: 10px 14px; border-radius: 6px; text-decoration: none; }
                    .primary { background: #111827; color: #fff; }
                    .secondary { background: #f3f4f6; color: #111827; }
                </style>
            </head>
            <body>
                <div class="wrap">
                    <h1><?php echo esc_html__('Scan QR Ph to Pay', 'wc-paymongo-qrph-addon'); ?></h1>
                    <p><?php echo esc_html__('Use your banking or e-wallet app to scan the QR code and complete the payment.', 'wc-paymongo-qrph-addon'); ?></p>
                    <?php if (!empty($qr_image_url)) : ?>
                        <div class="qr">
                            <img src="<?php echo esc_url($qr_image_url); ?>" alt="QR Ph" />
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($qr_reference)) : ?>
                        <p><?php echo esc_html__('Reference', 'wc-paymongo-qrph-addon'); ?></p>
                        <div class="ref"><?php echo esc_html($qr_reference); ?></div>
                    <?php endif; ?>
                    <div class="actions">
                        <a class="primary" href="<?php echo esc_url(add_query_arg(array('wc-api' => 'wcpm_qrph_return', 'order' => $order_id, 'intent' => $intent_id), home_url('/'))); ?>"><?php echo esc_html__('I have completed payment', 'wc-paymongo-qrph-addon'); ?></a>
                        <a class="secondary" href="<?php echo esc_url($order->get_checkout_payment_url()); ?>"><?php echo esc_html__('Back to checkout', 'wc-paymongo-qrph-addon'); ?></a>
                    </div>
                </div>
            </body>
            </html>
            <?php
            exit;
        }

        wc_add_notice('Unable to load QR Ph details. Please try again.', 'error');
        wp_safe_redirect($order->get_checkout_payment_url());
        exit;
    }
}
