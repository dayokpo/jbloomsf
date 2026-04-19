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


function flower_shop_child_enqueue_styles() {
	wp_enqueue_style('flower-shop-child-style', get_stylesheet_uri(), array(), '1.0.0', 'screen, print');
}

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
		'1.0.0',
		true
	);

	wp_localize_script(
		'flower-shop-child-checkout-province-city',
		'flowerShopCheckoutData',
		array(
			'cityDataUrl' => get_stylesheet_directory_uri() . '/assets/data/ph-addresses.json',
			'provinceOptions' => WC()->countries->get_states('PH'),
			'i18n' => array(
				'selectCity' => __('Select City / Municipality', 'flower-shop-child'),
			),
		)
	);
}

add_action('wp_enqueue_scripts', 'flower_shop_child_enqueue_checkout_scripts', 20);

function flower_shop_child_customize_checkout_location_fields($fields) {
	if (!function_exists('WC') || !WC()->countries) {
		return $fields;
	}

	$province_options = array('' => __('Select Province', 'flower-shop-child')) + WC()->countries->get_states('PH');
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

add_filter('default_checkout_billing_country', 'flower_shop_child_default_checkout_country');
add_filter('default_checkout_shipping_country', 'flower_shop_child_default_checkout_country');
