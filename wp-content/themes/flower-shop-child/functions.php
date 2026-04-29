<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop Child
 * @version		1.0.0
 * 
 * Child Theme Functions File
 * Created by CMSMasters
 * 
 */


function flower_shop_child_asset_version($relative_path) {
	$file_path = get_stylesheet_directory() . $relative_path;

	if (file_exists($file_path)) {
		return (string) filemtime($file_path);
	}

	$theme = wp_get_theme();
	return $theme->get('Version');
}

function flower_shop_child_allowed_ph_states() {
	return array(
		'BUL' => __('Bulacan', 'flower-shop-child'),
		// '00'  => __('Metro Manila', 'flower-shop-child'),
	);
}

function flower_shop_child_limit_ph_states($states) {
	if (isset($states['PH'])) {
		$states['PH'] = flower_shop_child_allowed_ph_states();
	}

	return $states;
}

function flower_shop_child_enqueue_styles() {
	wp_enqueue_style(
		'flower-shop-child-style',
		get_stylesheet_uri(),
		array(),
		flower_shop_child_asset_version('/style.css'),
		'screen, print'
	);
}

add_filter('woocommerce_states', 'flower_shop_child_limit_ph_states');
add_action('wp_enqueue_scripts', 'flower_shop_child_enqueue_styles', 11);

function flower_shop_child_enqueue_checkout_scripts() {
	if (!function_exists('is_checkout') || !is_checkout()) {
		return;
	}

	if (!class_exists('WooCommerce') || !function_exists('WC') || !WC()->countries) {
		return;
	}

	wp_enqueue_script(
		'flower-shop-child-checkout-province-city',
		get_stylesheet_directory_uri() . '/assets/js/checkout-province-city.js',
		array('jquery'),
		flower_shop_child_asset_version('/assets/js/checkout-province-city.js'),
		true
	);

	wp_localize_script(
		'flower-shop-child-checkout-province-city',
		'flowerShopCheckoutData',
		array(
			'cityDataUrl' => add_query_arg('ver', flower_shop_child_asset_version('/assets/data/ph-addresses.json'), get_stylesheet_directory_uri() . '/assets/data/ph-addresses.json'),
			'provinceOptions' => WC()->countries->get_states('PH'),
			'i18n' => array(
				'selectCity' => __('Select City / Municipality', 'flower-shop-child'),
			),
		)
	);
}

add_action('wp_enqueue_scripts', 'flower_shop_child_enqueue_checkout_scripts', 20);

function flower_shop_child_enqueue_product_scripts() {
	if (!function_exists('is_product') || !is_product()) {
		return;
	}

	wp_enqueue_script(
		'flower-shop-child-single-product-sticky',
		get_stylesheet_directory_uri() . '/assets/js/single-product-sticky.js',
		array(),
		flower_shop_child_asset_version('/assets/js/single-product-sticky.js'),
		true
	);
}

add_action('wp_enqueue_scripts', 'flower_shop_child_enqueue_product_scripts', 20);

function flower_shop_child_customize_checkout_location_fields($fields) {
	if (!function_exists('WC') || !WC()->countries) {
		return $fields;
	}

	$province_options = array('' => __('Select Province', 'flower-shop-child')) + flower_shop_child_allowed_ph_states();
	$city_options = array('' => __('Select City / Municipality', 'flower-shop-child'));

	foreach (array('billing', 'shipping') as $group) {
		$state_key = $group . '_state';
		$city_key = $group . '_city';

		if (isset($fields[$group][$state_key])) {
			$fields[$group][$state_key]['type'] = 'select';
			$fields[$group][$state_key]['label'] = __('Province', 'flower-shop-child');
			$fields[$group][$state_key]['options'] = $province_options;
			$fields[$group][$state_key]['required'] = true;
			$fields[$group][$state_key]['priority'] = 65;
			$fields[$group][$state_key]['class'] = array('form-row-wide', 'address-field', 'update_totals_on_change');
		}

		if (isset($fields[$group][$city_key])) {
			$fields[$group][$city_key]['type'] = 'select';
			$fields[$group][$city_key]['label'] = __('City / Municipality', 'flower-shop-child');
			$fields[$group][$city_key]['options'] = $city_options;
			$fields[$group][$city_key]['required'] = true;
			$fields[$group][$city_key]['priority'] = 66;
			$fields[$group][$city_key]['class'] = array('form-row-wide', 'address-field', 'update_totals_on_change');
		}
	}

	$fields['order']['special_message'] = array(
		'type' => 'textarea',
		'label' => __('Special Message', 'flower-shop-child'),
		'placeholder' => __('Add a note or message for your order (optional)', 'flower-shop-child'),
		'required' => false,
		'class' => array('form-row-wide'),
		'priority' => 110,
	);

	return $fields;
}

add_filter('woocommerce_checkout_fields', 'flower_shop_child_customize_checkout_location_fields');

function flower_shop_child_default_checkout_country($country) {
	return empty($country) ? 'PH' : $country;
}

function flower_shop_child_default_checkout_state($state) {
	$allowed_states = flower_shop_child_allowed_ph_states();

	return isset($allowed_states[$state]) ? $state : '';
}

function flower_shop_child_validate_allowed_shipping_states() {
	if (!function_exists('WC')) {
		return;
	}

	$allowed_states = array_keys(flower_shop_child_allowed_ph_states());
	$shipping_country = isset($_POST['shipping_country']) ? wc_clean(wp_unslash($_POST['shipping_country'])) : 'PH';
	$shipping_state = isset($_POST['shipping_state']) ? wc_clean(wp_unslash($_POST['shipping_state'])) : '';
	$billing_country = isset($_POST['billing_country']) ? wc_clean(wp_unslash($_POST['billing_country'])) : 'PH';
	$billing_state = isset($_POST['billing_state']) ? wc_clean(wp_unslash($_POST['billing_state'])) : '';

	if (('PH' === $shipping_country && $shipping_state && !in_array($shipping_state, $allowed_states, true)) || ('PH' === $billing_country && $billing_state && !in_array($billing_state, $allowed_states, true))) {
		wc_add_notice(__('We currently ship only to Bulacan and Metro Manila.', 'flower-shop-child'), 'error');
	}
}

add_filter('default_checkout_billing_country', 'flower_shop_child_default_checkout_country');
add_filter('default_checkout_shipping_country', 'flower_shop_child_default_checkout_country');
add_filter('default_checkout_billing_state', 'flower_shop_child_default_checkout_state');
add_filter('default_checkout_shipping_state', 'flower_shop_child_default_checkout_state');
add_action('woocommerce_after_checkout_validation', 'flower_shop_child_validate_allowed_shipping_states');

function flower_shop_child_save_special_message($order, $data) {
	if (!empty($data['special_message'])) {
		$order->update_meta_data('_special_message', sanitize_textarea_field($data['special_message']));
	}
}

function flower_shop_child_get_special_message($order) {
	$message = $order->get_meta('flower-shop-child/special-message');
	if (empty($message)) {
		$message = $order->get_meta('_special_message');
	}
	return $message;
}

function flower_shop_child_display_special_message_admin($order) {
	$special_message = flower_shop_child_get_special_message($order);

	if (!empty($special_message)) {
		echo '<p><strong>' . esc_html__('Special Message:', 'flower-shop-child') . '</strong><br>' . nl2br(esc_html($special_message)) . '</p>';
	}
}

function flower_shop_child_add_special_message_to_email($fields, $sent_to_admin, $order) {
	$special_message = flower_shop_child_get_special_message($order);

	if (!empty($special_message)) {
		$fields['special_message'] = array(
			'label' => __('Special Message', 'flower-shop-child'),
			'value' => $special_message,
		);
	}

	return $fields;
}

add_action('woocommerce_checkout_create_order', 'flower_shop_child_save_special_message', 10, 2);
add_action('woocommerce_admin_order_data_after_billing_address', 'flower_shop_child_display_special_message_admin');
add_filter('woocommerce_email_order_meta_fields', 'flower_shop_child_add_special_message_to_email', 10, 3);

// Block-based checkout: register additional field (WooCommerce 8.6+)
function flower_shop_child_register_block_checkout_fields() {
	if (!function_exists('woocommerce_register_additional_checkout_field')) {
		return;
	}

	woocommerce_register_additional_checkout_field(
		array(
			'id'         => 'flower-shop-child/special-message',
			'label'      => __('Special Message', 'flower-shop-child'),
			'location'   => 'order',
			'type'       => 'text',
			'required'   => false,
			'attributes' => array(
				'placeholder' => __('Add a note or message for your order (optional)', 'flower-shop-child'),
			),
		)
	);
}

add_action('woocommerce_init', 'flower_shop_child_register_block_checkout_fields');


// Woo Delivery customizations Start Here

function flower_shop_child_woo_delivery_hpos_enabled() {
	if (!class_exists(\Automattic\WooCommerce\Utilities\OrderUtil::class)) {
		return false;
	}

	return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
}

function flower_shop_child_maybe_persist_delivery_test_time() {
	$cookie_key = 'flower_shop_delivery_test_time';

	if (empty($_GET['delivery_test_time'])) {
		return;
	}

	$raw_time = sanitize_text_field(wp_unslash($_GET['delivery_test_time']));

	if ('off' === strtolower($raw_time)) {
		if (!headers_sent()) {
			setcookie($cookie_key, '', time() - HOUR_IN_SECONDS, '/');
		}
		unset($_COOKIE[$cookie_key]);

		if (function_exists('WC') && WC() && WC()->session) {
			WC()->session->set($cookie_key, '');
		}

		return;
	}

	if (!preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $raw_time)) {
		return;
	}

	if (!headers_sent()) {
		setcookie($cookie_key, $raw_time, time() + DAY_IN_SECONDS, '/');
	}
	$_COOKIE[$cookie_key] = $raw_time;

	if (function_exists('WC') && WC() && WC()->session) {
		WC()->session->set($cookie_key, $raw_time);
	}
}

add_action('init', 'flower_shop_child_maybe_persist_delivery_test_time', 1);

function flower_shop_child_woo_delivery_test_minutes() {
	$cookie_key = 'flower_shop_delivery_test_time';

	if (!empty($_REQUEST['delivery_test_time'])) {
		$raw_time = sanitize_text_field(wp_unslash($_REQUEST['delivery_test_time']));
		if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $raw_time, $matches)) {
			return ((int) $matches[1] * 60) + (int) $matches[2];
		}
	}

	if (!empty($_GET['delivery_test_time'])) {
		$raw_time = sanitize_text_field(wp_unslash($_GET['delivery_test_time']));

		if ('off' === strtolower($raw_time)) {
			return null;
		}

		if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $raw_time, $matches)) {
			return ((int) $matches[1] * 60) + (int) $matches[2];
		}

		return null;
	}

	// Read persisted override from session/cookie for all request paths
	// so frontend/AJAX use the same simulated time.
	if (function_exists('WC') && WC() && WC()->session) {
		$session_time = WC()->session->get($cookie_key);
		if (!empty($session_time) && preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $session_time, $matches)) {
			return ((int) $matches[1] * 60) + (int) $matches[2];
		}
	}

	if (empty($_COOKIE[$cookie_key])) {
		return null;
	}

	$raw_time = sanitize_text_field(wp_unslash($_COOKIE[$cookie_key]));

	if (!preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $raw_time, $matches)) {
		return null;
	}

	return ((int) $matches[1] * 60) + (int) $matches[2];
}

function flower_shop_child_woo_delivery_current_minutes() {
	$test_minutes = flower_shop_child_woo_delivery_test_minutes();

	if (null !== $test_minutes) {
		return $test_minutes;
	}

	$current_timestamp = current_time('timestamp', true);

	return ((int) wp_date('G', $current_timestamp) * 60) + (int) wp_date('i', $current_timestamp);
}

function flower_shop_child_woo_delivery_test_time_display() {
	$cookie_key = 'flower_shop_delivery_test_time';

	if (!empty($_GET['delivery_test_time'])) {
		$raw_time = sanitize_text_field(wp_unslash($_GET['delivery_test_time']));
		if ('off' === strtolower($raw_time)) {
			return 'off';
		}

		if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $raw_time)) {
			return $raw_time;
		}
	}

	if (!empty($_COOKIE[$cookie_key])) {
		$raw_time = sanitize_text_field(wp_unslash($_COOKIE[$cookie_key]));
		if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $raw_time)) {
			return $raw_time;
		}
	}

	return 'off';
}

function flower_shop_child_woo_delivery_slot_duration($delivery_time_settings = null) {
	if (null === $delivery_time_settings) {
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
	}

	$slot_duration = isset($delivery_time_settings['each_time_slot']) && !empty($delivery_time_settings['each_time_slot'])
		? (int) $delivery_time_settings['each_time_slot']
		: 0;

	if ($slot_duration <= 0) {
		$start_time = isset($delivery_time_settings['delivery_time_starts']) ? (int) $delivery_time_settings['delivery_time_starts'] : 0;
		$end_time = isset($delivery_time_settings['delivery_time_ends']) ? (int) $delivery_time_settings['delivery_time_ends'] : 0;
		$slot_duration = max($end_time - $start_time, 0);
	}

	return $slot_duration;
}

function flower_shop_child_woo_delivery_effective_current_time($delivery_time_settings = null) {
	$current_time = flower_shop_child_woo_delivery_current_minutes();

	// Explicit business rules:
	// before 8:00 AM -> keep only 11:00 AM - 2:00 PM and later
	// 8:00 AM to 11:59 AM -> keep only 2:00 PM - 5:00 PM
	// 12:00 PM and above -> no same-day slots
	if ($current_time < 480) {
		return 659; // 10:59 AM
	}

	if ($current_time < 720) {
		return 839; // 1:59 PM
	}

	return 1439; // End of day, effectively disables same-day slots.
}

function flower_shop_child_woo_delivery_last_slot_start($delivery_time_settings = null) {
	if (null === $delivery_time_settings) {
		$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
	}

	$start_time = isset($delivery_time_settings['delivery_time_starts']) ? (int) $delivery_time_settings['delivery_time_starts'] : 0;
	$end_time = isset($delivery_time_settings['delivery_time_ends']) ? (int) $delivery_time_settings['delivery_time_ends'] : 0;
	$slot_duration = flower_shop_child_woo_delivery_slot_duration($delivery_time_settings);

	if ($slot_duration <= 0 || $end_time <= $start_time) {
		return $start_time;
	}

	$last_slot_start = $start_time;
	$slot_start = $start_time;

	while ($slot_start < $end_time) {
		$slot_end = $slot_start + $slot_duration;
		if ($slot_end > $end_time) {
			$slot_end = $end_time;
		}

		$last_slot_start = $slot_start;
		$slot_start = $slot_end;
	}

	return $last_slot_start;
}

function flower_shop_child_woo_delivery_should_disable_today($delivery_time_settings = null) {
	$current_time = flower_shop_child_woo_delivery_current_minutes();

	return $current_time >= 720;
}

function flower_shop_child_woo_delivery_pickup_disable_today($pickup_time_settings = null) {
	if (null === $pickup_time_settings) {
		$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
	}

	$highest_pickupslot_end = isset($pickup_time_settings['pickup_time_ends']) ? (int) $pickup_time_settings['pickup_time_ends'] : 0;
	$current_time = flower_shop_child_woo_delivery_current_minutes();

	return $current_time > $highest_pickupslot_end;
}

function flower_shop_child_woo_delivery_get_delivery_order_ids($selected_date, $only_delivery_time = false) {
	$use_hpos = flower_shop_child_woo_delivery_hpos_enabled();

	if ($only_delivery_time) {
		if ($use_hpos) {
			$args = array(
				'limit' => -1,
				'type' => array('shop_order'),
				'date_created' => $selected_date,
				'meta_query' => array(
					array(
						'key' => 'delivery_type',
						'value' => 'delivery',
						'compare' => '==',
					),
				),
				'return' => 'ids',
			);
		} else {
			$args = array(
				'limit' => -1,
				'date_created' => $selected_date,
				'delivery_type' => 'delivery',
				'return' => 'ids',
			);
		}
	} else {
		if ($use_hpos) {
			$args = array(
				'limit' => -1,
				'type' => array('shop_order'),
				'meta_query' => array(
					array(
						'key' => 'delivery_date',
						'value' => $selected_date,
						'compare' => '==',
					),
				),
				'return' => 'ids',
			);
		} else {
			$args = array(
				'limit' => -1,
				'delivery_date' => $selected_date,
				'return' => 'ids',
			);
		}
	}

	return wc_get_orders($args);
}

function flower_shop_child_woo_delivery_get_orders() {
	check_ajax_referer('coderockz_woo_delivery_nonce');

	$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
	$selected_date = isset($_POST['date']) ? sanitize_text_field(wp_unslash($_POST['date'])) : '';
	$only_delivery_time = !empty($_POST['onlyDeliveryTime']);
	$order_ids = flower_shop_child_woo_delivery_get_delivery_order_ids($selected_date, $only_delivery_time);
	$delivery_times = array();
	$use_hpos = flower_shop_child_woo_delivery_hpos_enabled();

	foreach ($order_ids as $order_id) {
		$order = wc_get_order($order_id);
		if (!$order) {
			continue;
		}

		if ($use_hpos) {
			$delivery_time = $order->get_meta('delivery_time', true);
		} else {
			$delivery_time = get_post_meta($order_id, 'delivery_time', true);
		}

		if (!empty($delivery_time)) {
			$delivery_times[] = $delivery_time;
		}
	}

	$response = array(
		'delivery_times' => $delivery_times,
		'max_order_per_slot' => isset($delivery_time_settings['max_order_per_slot']) && !empty($delivery_time_settings['max_order_per_slot']) ? $delivery_time_settings['max_order_per_slot'] : 0,
		'disabled_current_time_slot' => true,
		'current_time' => flower_shop_child_woo_delivery_effective_current_time($delivery_time_settings),
	);

	wp_send_json_success(wp_json_encode($response));
}

function flower_shop_child_woo_delivery_option_delivery_time_pickup() {
	check_ajax_referer('coderockz_woo_delivery_nonce');

	$delivery_option = isset($_POST['deliveryOption']) ? sanitize_text_field(wp_unslash($_POST['deliveryOption'])) : '';
	setcookie('coderockz_woo_delivery_option_time_pickup', $delivery_option, time() + DAY_IN_SECONDS, '/');

	if (function_exists('WC') && null !== WC()->session) {
		WC()->session->set('coderockz_woo_delivery_option_time_pickup', $delivery_option);
	}

	$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
	$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
	$disable_delivery_date_passed_time = array();
	$disable_pickup_date_passed_time = array();

	if (!empty($delivery_time_settings['enable_delivery_time']) && flower_shop_child_woo_delivery_should_disable_today($delivery_time_settings)) {
		$disable_delivery_date_passed_time[] = wp_date('Y-m-d', current_time('timestamp', 1));
	}

	if (!empty($pickup_time_settings['enable_pickup_time']) && flower_shop_child_woo_delivery_pickup_disable_today($pickup_time_settings)) {
		$disable_pickup_date_passed_time[] = wp_date('Y-m-d', current_time('timestamp', 1));
	}

	wp_send_json_success(
		wp_json_encode(
			array(
				'disable_delivery_date_passed_time' => $disable_delivery_date_passed_time,
				'disable_pickup_date_passed_time' => $disable_pickup_date_passed_time,
			)
		)
	);
}

function flower_shop_child_woo_delivery_disable_max_delivery_pickup_date() {
	check_ajax_referer('coderockz_woo_delivery_nonce');

	$delivery_time_settings = get_option('coderockz_woo_delivery_time_settings');
	$pickup_time_settings = get_option('coderockz_woo_delivery_pickup_settings');
	$disable_delivery_date_passed_time = array();
	$disable_pickup_date_passed_time = array();

	if (!empty($delivery_time_settings['enable_delivery_time']) && flower_shop_child_woo_delivery_should_disable_today($delivery_time_settings)) {
		$disable_delivery_date_passed_time[] = wp_date('Y-m-d', current_time('timestamp', 1));
	}

	if (!empty($pickup_time_settings['enable_pickup_time']) && flower_shop_child_woo_delivery_pickup_disable_today($pickup_time_settings)) {
		$disable_pickup_date_passed_time[] = wp_date('Y-m-d', current_time('timestamp', 1));
	}

	wp_send_json_success(
		wp_json_encode(
			array(
				'disable_delivery_date_passed_time' => $disable_delivery_date_passed_time,
				'disable_pickup_date_passed_time' => $disable_pickup_date_passed_time,
			)
		)
	);
}

function flower_shop_child_override_woo_delivery_ajax_actions() {
	remove_all_actions('wp_ajax_coderockz_woo_delivery_get_orders');
	remove_all_actions('wp_ajax_nopriv_coderockz_woo_delivery_get_orders');
	remove_all_actions('wp_ajax_coderockz_woo_delivery_option_delivery_time_pickup');
	remove_all_actions('wp_ajax_nopriv_coderockz_woo_delivery_option_delivery_time_pickup');
	remove_all_actions('wp_ajax_coderockz_woo_delivery_disable_max_delivery_pickup_date');
	remove_all_actions('wp_ajax_nopriv_coderockz_woo_delivery_disable_max_delivery_pickup_date');

	add_action('wp_ajax_coderockz_woo_delivery_get_orders', 'flower_shop_child_woo_delivery_get_orders');
	add_action('wp_ajax_nopriv_coderockz_woo_delivery_get_orders', 'flower_shop_child_woo_delivery_get_orders');
	add_action('wp_ajax_coderockz_woo_delivery_option_delivery_time_pickup', 'flower_shop_child_woo_delivery_option_delivery_time_pickup');
	add_action('wp_ajax_nopriv_coderockz_woo_delivery_option_delivery_time_pickup', 'flower_shop_child_woo_delivery_option_delivery_time_pickup');
	add_action('wp_ajax_coderockz_woo_delivery_disable_max_delivery_pickup_date', 'flower_shop_child_woo_delivery_disable_max_delivery_pickup_date');
	add_action('wp_ajax_nopriv_coderockz_woo_delivery_disable_max_delivery_pickup_date', 'flower_shop_child_woo_delivery_disable_max_delivery_pickup_date');
}

add_action('wp_loaded', 'flower_shop_child_override_woo_delivery_ajax_actions', 20);

function flower_shop_child_validate_woo_delivery_timeslot($data, $errors) {
	if (isset($_POST['coderockz_woo_delivery_delivery_selection_box']) && 'pickup' === wc_clean(wp_unslash($_POST['coderockz_woo_delivery_delivery_selection_box']))) {
		return;
	}

	if (empty($_POST['coderockz_woo_delivery_date_field']) || empty($_POST['coderockz_woo_delivery_time_field'])) {
		return;
	}

	$selected_date = date('Y-m-d', strtotime(sanitize_text_field(wp_unslash($_POST['coderockz_woo_delivery_date_field']))));
	$today = wp_date('Y-m-d', current_time('timestamp', 1));

	if ($selected_date !== $today) {
		return;
	}

	$time_parts = explode(' - ', sanitize_text_field(wp_unslash($_POST['coderockz_woo_delivery_time_field'])));
	if (count($time_parts) !== 2) {
		return;
	}

	$slot_start_parts = explode(':', $time_parts[0]);
	if (count($slot_start_parts) !== 2) {
		return;
	}

	$slot_start = ((int) $slot_start_parts[0] * 60) + (int) $slot_start_parts[1];
	$effective_current_time = flower_shop_child_woo_delivery_effective_current_time(get_option('coderockz_woo_delivery_time_settings'));

	if ($effective_current_time >= $slot_start) {
		$errors->add('flower_shop_child_delivery_time', __('Selected delivery time is no longer available today. Please choose a later date.', 'flower-shop-child'));
	}
}

add_action('woocommerce_after_checkout_validation', 'flower_shop_child_validate_woo_delivery_timeslot', 20, 2);

function flower_shop_child_enforce_delivery_slots_frontend() {
	if (!function_exists('is_checkout') || !is_checkout()) {
		return;
	}

	$current_timestamp = current_time('timestamp', true);
	$server_current_minutes = flower_shop_child_woo_delivery_current_minutes();
	$server_today_date = wp_date('Y-m-d', $current_timestamp);
	$test_minutes = flower_shop_child_woo_delivery_test_minutes();
	$debug_wp_datetime = wp_date('Y-m-d H:i:s', $current_timestamp);
	$debug_test_time = flower_shop_child_woo_delivery_test_time_display();

	echo '<div id="flower-shop-delivery-debug" style="position:fixed;top:0;left:0;right:0;z-index:999999;background:#111;color:#fff;padding:8px 12px;font:12px/1.4 monospace;">';
	echo '<strong>Delivery Debug</strong> | WP datetime: ' . esc_html($debug_wp_datetime) . ' | delivery_test_time: ' . esc_html($debug_test_time);
	echo '</div>';
	?>
	<script>
		(function($) {
			var serverCurrentMinutes = <?php echo (int) $server_current_minutes; ?>;
			var serverTodayDate = '<?php echo esc_js($server_today_date); ?>';
			var forcedTestMinutes = <?php echo null === $test_minutes ? 'null' : (int) $test_minutes; ?>;
			var debugWpDatetime = '<?php echo esc_js($debug_wp_datetime); ?>';
			var debugDeliveryTestTime = '<?php echo esc_js($debug_test_time); ?>';
			var enforceTimer = null;
			var isProgrammaticUpdate = false;
			var observerDebounce = null;

			if (window.console && typeof window.console.log === 'function') {
				console.log('[Delivery Debug] WP datetime:', debugWpDatetime, '| delivery_test_time:', debugDeliveryTestTime, '| forcedTestMinutes:', forcedTestMinutes);
			}

			if (debugDeliveryTestTime && debugDeliveryTestTime !== 'off') {
				document.cookie = 'flower_shop_delivery_test_time=' + encodeURIComponent(debugDeliveryTestTime) + '; path=/';

				$(document).ajaxSend(function(event, jqXHR, ajaxOptions) {
					if (!ajaxOptions || !ajaxOptions.url || ajaxOptions.url.indexOf('admin-ajax.php') === -1) {
						return;
					}

					var data = ajaxOptions.data;
					if (typeof data === 'string') {
						if (data.indexOf('action=coderockz_woo_delivery_') !== -1 && data.indexOf('delivery_test_time=') === -1) {
							ajaxOptions.data += '&delivery_test_time=' + encodeURIComponent(debugDeliveryTestTime);
						}
						return;
					}

					if (data && typeof data === 'object' && typeof data.action === 'string' && data.action.indexOf('coderockz_woo_delivery_') === 0) {
						if (typeof data.delivery_test_time === 'undefined') {
							data.delivery_test_time = debugDeliveryTestTime;
						}
					}
				});
			}

			function getBrowserCurrentMinutes() {
				if (forcedTestMinutes !== null) {
					return forcedTestMinutes;
				}

				var now = new Date();
				return (now.getHours() * 60) + now.getMinutes();
			}

			function getBrowserTodayYmd() {
				var now = new Date();
				var year = now.getFullYear();
				var month = ('0' + (now.getMonth() + 1)).slice(-2);
				var day = ('0' + now.getDate()).slice(-2);
				return year + '-' + month + '-' + day;
			}

			function getBrowserNextDayYmd() {
				var now = new Date();
				now.setDate(now.getDate() + 1);
				var year = now.getFullYear();
				var month = ('0' + (now.getMonth() + 1)).slice(-2);
				var day = ('0' + now.getDate()).slice(-2);
				return year + '-' + month + '-' + day;
			}

			function isAfterTodayYmd(candidateYmd, todayYmd) {
				if (!candidateYmd || !todayYmd) {
					return false;
				}

				return candidateYmd > todayYmd;
			}

			function setNativeInputValue(el, value) {
				if (!el) {
					return;
				}

				var prototype = Object.getPrototypeOf(el);
				var descriptor = Object.getOwnPropertyDescriptor(prototype, 'value');
				if (descriptor && descriptor.set) {
					descriptor.set.call(el, value);
				} else {
					el.value = value;
				}

				el.dispatchEvent(new Event('input', { bubbles: true }));
				el.dispatchEvent(new Event('change', { bubbles: true }));
			}

			function selectNextDayForBlockCheckout($dateInput, $hiddenDateInput, fp) {
				var nextDayYmd = getBrowserNextDayYmd();
				var currentValue = $hiddenDateInput && $hiddenDateInput.length ? $.trim($hiddenDateInput.val() || '') : '';

				if (currentValue === nextDayYmd) {
					return false;
				}

				isProgrammaticUpdate = true;

				if (fp) {
					fp.setDate(nextDayYmd, true, 'Y-m-d');
					setTimeout(function() {
						isProgrammaticUpdate = false;
					}, 300);
					return true;
				}

				if ($hiddenDateInput && $hiddenDateInput.length) {
					setNativeInputValue($hiddenDateInput.get(0), nextDayYmd);
				}

				if ($dateInput && $dateInput.length) {
					setNativeInputValue($dateInput.get(0), nextDayYmd);
				}

				setTimeout(function() {
					isProgrammaticUpdate = false;
				}, 300);

				return true;
			}

			function parseMinutes(timePart) {
				var match = $.trim(timePart).match(/^(\d{1,2})\s*:\s*(\d{2})(?:\s*([AaPp][Mm]))?$/);
				if (!match) {
					return null;
				}

				var hour = parseInt(match[1], 10);
				var minute = parseInt(match[2], 10);
				var suffix = match[3] ? match[3].toUpperCase() : null;

				if (suffix) {
					if (hour === 12) {
						hour = 0;
					}
					if (suffix === 'PM') {
						hour += 12;
					}
				}

				return (hour * 60) + minute;
			}

			function getStartMinutes($option) {
				var value = $.trim($option.val() || '');
				var text = $.trim($option.text() || '');
				var source = value || text;
				var range = source.split(' - ');

				if (range.length < 2) {
					range = text.split(' - ');
				}

				if (range.length < 2) {
					return null;
				}

				return parseMinutes(range[0]);
			}

			function isMaxLimitDisabledOption($option) {
				var text = ($.trim($option.text() || '')).toLowerCase();

				return text.indexOf('maximum') !== -1 || text.indexOf('limit exceed') !== -1 || text.indexOf('limit exceeded') !== -1;
			}

			function mustDisableTodaySlot(startMinutes, currentMinutes) {
				if (currentMinutes < 480) {
					return startMinutes < 660;
				}

				if (currentMinutes < 720) {
					return startMinutes < 840;
				}

				return true;
			}

			function applyDeliverySlotRules() {
				if (isProgrammaticUpdate) {
					return;
				}

				var $wrapper = $('#coderockz_woo_delivery_setting_wrapper');
				var $dateInput = $('#coderockz_woo_delivery_date').first();
				var $timeSelect = $('#coderockz_woo_delivery_time_field, #coderockz_woo_delivery_time').first();
				var $hiddenDateInput = $("input:hidden[name='coderockz_woo_delivery_date']").first();

				if (!$dateInput.length && $hiddenDateInput.length) {
					$dateInput = $hiddenDateInput;
				}

				if (!$dateInput.length) {
					return;
				}

				var browserTodayDate = getBrowserTodayYmd();
				var todayDate = $.trim(browserTodayDate || $wrapper.data('today_date') || serverTodayDate || '');
				var selectedDate = $.trim($dateInput.val() || '');
				var currentMinutes = getBrowserCurrentMinutes();
				var dateInputElement = $dateInput.get(0);
				var fp = dateInputElement && dateInputElement._flatpickr ? dateInputElement._flatpickr : null;

				if (!selectedDate && $hiddenDateInput.length) {
					selectedDate = $.trim($hiddenDateInput.val() || '');
				}

				if (fp && fp.selectedDates && fp.selectedDates.length) {
					selectedDate = fp.formatDate(fp.selectedDates[0], 'Y-m-d');
				}

				if (todayDate && currentMinutes >= 720) {
					if (fp) {
						var enableConfig = Array.isArray(fp.config.enable) ? fp.config.enable.slice() : [];
						var disableConfig = Array.isArray(fp.config.disable) ? fp.config.disable.slice() : [];

						if (enableConfig.length) {
							enableConfig = enableConfig.filter(function(item) {
								if (item instanceof Date) {
									return fp.formatDate(item, 'Y-m-d') !== todayDate;
								}
								if (typeof item !== 'string') {
									return true;
								}
								return $.trim(item) !== todayDate;
							});
							fp.set('enable', enableConfig);
						}

						if (disableConfig.indexOf(todayDate) === -1) {
							disableConfig.push(todayDate);
							fp.set('disable', disableConfig);
						}

						if (selectedDate === todayDate) {
							if (selectNextDayForBlockCheckout($dateInput, $hiddenDateInput, fp) && $timeSelect.length) {
								$timeSelect.val('');
							}
							return;
						}
					} else if (selectedDate === todayDate) {
						if (selectNextDayForBlockCheckout($dateInput, $hiddenDateInput, fp) && $timeSelect.length) {
							$timeSelect.val('');
						}
						return;
					}
				} else if (todayDate && currentMinutes < 720) {
					if (fp) {
						var preNoonEnableConfig = Array.isArray(fp.config.enable) ? fp.config.enable.slice() : [];
						var preNoonDisableConfig = Array.isArray(fp.config.disable) ? fp.config.disable.slice() : [];

						if (preNoonEnableConfig.length) {
							var hasTodayEnabled = preNoonEnableConfig.some(function(item) {
								if (item instanceof Date) {
									return fp.formatDate(item, 'Y-m-d') === todayDate;
								}
								return typeof item === 'string' && $.trim(item) === todayDate;
							});

							if (!hasTodayEnabled) {
								preNoonEnableConfig.unshift(todayDate);
							}

							fp.set('enable', preNoonEnableConfig);
						}

						if (preNoonDisableConfig.length) {
							preNoonDisableConfig = preNoonDisableConfig.filter(function(item) {
								if (item instanceof Date) {
									return fp.formatDate(item, 'Y-m-d') !== todayDate;
								}
								if (typeof item !== 'string') {
									return true;
								}
								return $.trim(item) !== todayDate;
							});

							fp.set('disable', preNoonDisableConfig);
						}
					}

					if (!selectedDate || isAfterTodayYmd(selectedDate, todayDate)) {
						isProgrammaticUpdate = true;

						if (fp) {
							fp.setDate(todayDate, true, 'Y-m-d');
						} else {
							if ($hiddenDateInput.length) {
								setNativeInputValue($hiddenDateInput.get(0), todayDate);
							}

							if ($dateInput.length) {
								setNativeInputValue($dateInput.get(0), todayDate);
							}
						}

						if ($timeSelect.length) {
							$timeSelect.val('');
						}

						setTimeout(function() {
							isProgrammaticUpdate = false;
						}, 300);

						return;
					}
				}

				if (!$timeSelect.length || !todayDate || selectedDate !== todayDate) {
					return;
				}
				var selectedWasDisabled = false;

				$timeSelect.find('option').each(function() {
					var $option = $(this);
					var optionValue = $.trim($option.val() || '');

					if (!optionValue) {
						return;
					}

					if (!isMaxLimitDisabledOption($option)) {
						$option.prop('disabled', false);
					}

					var startMinutes = getStartMinutes($option);
					if (startMinutes === null) {
						return;
					}

					if (mustDisableTodaySlot(startMinutes, currentMinutes)) {
						if ($option.is(':selected')) {
							selectedWasDisabled = true;
						}
						$option.prop('disabled', true);
					}
				});

				if (selectedWasDisabled) {
					$timeSelect.val('');
				}
			}

			function scheduleApplyDeliverySlotRules() {
				if (enforceTimer) {
					clearTimeout(enforceTimer);
					enforceTimer = null;
				}

				applyDeliverySlotRules();
				setTimeout(applyDeliverySlotRules, 200);
				setTimeout(applyDeliverySlotRules, 600);
				enforceTimer = setTimeout(function() {
					applyDeliverySlotRules();
					enforceTimer = null;
				}, 1200);
			}

			function watchBlockDateContainer() {
				var container = document.querySelector('.coderockz-woo-delivery-date-container');
				if (!container || !window.MutationObserver) {
					return;
				}

				var observer = new MutationObserver(function() {
					if (observerDebounce) {
						clearTimeout(observerDebounce);
					}

					observerDebounce = setTimeout(function() {
						scheduleApplyDeliverySlotRules();
					}, 150);
				});

				observer.observe(container, {
					attributes: true,
					childList: true,
					subtree: true,
				});
			}

			$(document).ready(scheduleApplyDeliverySlotRules);
			$(document).ready(watchBlockDateContainer);
			$(document.body).on('updated_checkout', scheduleApplyDeliverySlotRules);
			$(document).on('change input', '#coderockz_woo_delivery_date, input[name="coderockz_woo_delivery_date"]', scheduleApplyDeliverySlotRules);
			$(document).on('change', '#coderockz_woo_delivery_delivery_selection_box', scheduleApplyDeliverySlotRules);
		})(jQuery);
	</script>
	<?php
}

add_action('wp_footer', 'flower_shop_child_enforce_delivery_slots_frontend', 99);

// End of Woo Delivery customizations