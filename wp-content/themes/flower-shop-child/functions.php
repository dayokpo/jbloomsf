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
		'00'  => __('Metro Manila', 'flower-shop-child'),
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
