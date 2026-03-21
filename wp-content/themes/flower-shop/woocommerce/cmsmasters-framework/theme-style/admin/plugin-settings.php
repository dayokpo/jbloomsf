<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.1.3
 *
 * CMSMasters WooCommerce Admin Settings
 * Created by CMSMasters
 *
 */


/* Single Settings */
function flower_shop_woocommerce_options_general_fields($options, $tab) {
	$defaults = flower_shop_settings_general_defaults();
	
	if ($tab == 'header') {
		$options[] = array(
			'section' => 'header_section',
			'id' => 'flower-shop' . '_woocommerce_cart_dropdown',
			'title' => esc_html__('Disable WooCommerce Cart', 'flower-shop'),
			'desc' => '',
			'type' => 'checkbox',
			'std' => $defaults[$tab]['flower-shop' . '_woocommerce_cart_dropdown']
		);
	}

	return $options;
}

add_filter('cmsmasters_options_general_fields_filter', 'flower_shop_woocommerce_options_general_fields', 10, 2);

