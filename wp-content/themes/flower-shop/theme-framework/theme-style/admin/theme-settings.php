<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.0.5
 * 
 * Theme Admin Settings
 * Created by CMSMasters
 * 
 */


/* General Settings */
function flower_shop_theme_options_general_fields($options, $tab) {
	if ($tab == 'header') {
		$new_options = array();
		
		foreach ($options as $option) {
			if ($option['id'] == 'flower-shop_header_styles') {
				$option['choices'][] = esc_html__('Fullwidth Header Style', 'flower-shop') . '|fullwidth';
				
				$new_options[] = $option;
			} else {
				$new_options[] = $option;
			}
		}
		
		$options = $new_options;
	}
	
	
	return $options;
}

add_filter('cmsmasters_options_general_fields_filter', 'flower_shop_theme_options_general_fields', 10, 2);

