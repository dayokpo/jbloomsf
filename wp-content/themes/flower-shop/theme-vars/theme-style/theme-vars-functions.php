<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.1.3
 * 
 * Theme Vars Functions
 * Created by CMSMasters
 * 
 */


/* Register CSS Styles */
function flower_shop_vars_register_css_styles() {
	wp_enqueue_style('flower-shop-theme-vars-style', get_template_directory_uri() . '/theme-vars/theme-style' . CMSMASTERS_THEME_STYLE . '/css/vars-style.css', array('flower-shop-retina'), '1.0.0', 'screen, print');
}

//add_action('wp_enqueue_scripts', 'flower_shop_vars_register_css_styles');

