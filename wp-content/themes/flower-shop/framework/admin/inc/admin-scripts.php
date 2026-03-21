<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.1.3
 * 
 * Admin Panel Scripts & Styles
 * Created by CMSMasters
 * 
 */


function flower_shop_admin_register($hook) {
	global $pagenow;
	
	$screen = get_current_screen();
	
	
	wp_enqueue_style('wp-color-picker');
	
	wp_enqueue_script('wp-color-picker');
	
	wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
		'clear' => 					esc_attr__('Clear', 'flower-shop'),
		'clearAriaLabel' => 		esc_attr__('Clear color', 'flower-shop'),
		'defaultLabel' => 			esc_attr__('Color value', 'flower-shop'),
		'defaultString' => 			esc_attr__('Default', 'flower-shop'),
		'defaultAriaLabel' => 		esc_attr__('Select default color', 'flower-shop'),
		'pick' => 					esc_attr__('Select Color', 'flower-shop'),
	) ); 
	
	wp_enqueue_script('wp-color-picker-alpha', get_template_directory_uri() . '/framework/admin/inc/js/wp-color-picker-alpha.js', array('jquery', 'wp-color-picker'), '2.1.4', true);
	
	
	wp_enqueue_style('flower-shop-admin-icons-font', get_template_directory_uri() . '/framework/admin/inc/css/admin-icons-font.css', array(), '1.0.0', 'screen');
	
	wp_enqueue_style('flower-shop-lightbox', get_template_directory_uri() . '/framework/admin/inc/css/jquery.cmsmastersLightbox.css', array(), '1.0.0', 'screen');
	
	if (is_rtl()) {
		wp_enqueue_style('flower-shop-lightbox-rtl', get_template_directory_uri() . '/framework/admin/inc/css/jquery.cmsmastersLightbox-rtl.css', array(), '1.0.0', 'screen');
	}
	
	
	wp_enqueue_script('flower-shop-uploader-js', get_template_directory_uri() . '/framework/admin/inc/js/jquery.cmsmastersUploader.js', array('jquery'), '1.0.0', true);
	
	wp_localize_script('flower-shop-uploader-js', 'cmsmasters_admin_uploader', array( 
		'choose' => 				esc_attr__('Choose image', 'flower-shop'), 
		'insert' => 				esc_attr__('Insert image', 'flower-shop'), 
		'remove' => 				esc_attr__('Remove', 'flower-shop'), 
		'edit_gallery' => 			esc_attr__('Edit gallery', 'flower-shop') 
	));
	
	
	wp_enqueue_script('flower-shop-lightbox-js', get_template_directory_uri() . '/framework/admin/inc/js/jquery.cmsmastersLightbox.js', array('jquery'), '1.0.0', true);
	
	wp_localize_script('flower-shop-lightbox-js', 'cmsmasters_admin_lightbox', array( 
		'cancel' => 				esc_attr__('Cancel', 'flower-shop'), 
		'insert' => 				esc_attr__('Insert', 'flower-shop'), 
		'deselect' => 				esc_attr__('Deselect', 'flower-shop'), 
		'choose_icon' => 			esc_attr__('Choose Icon', 'flower-shop'), 
		'find_icons' => 			esc_attr__('Find icons', 'flower-shop'), 
		'min_length' => 			esc_attr__('min 2 symbols', 'flower-shop'), 
		'choose_font' => 			esc_attr__('Choose icons font', 'flower-shop'), 
		'error_on_page' => 			esc_attr__("Error on page!\nReload page and try again.", 'flower-shop') 
	));
	
	
	if ( 
		$hook == 'post.php' || 
		$hook == 'post-new.php' || 
		$hook == 'widgets.php' || 
		$hook == 'term.php' || 
		$hook == 'edit-tags.php' || 
		$hook == 'nav-menus.php' || 
		str_replace('cmsmasters-settings-element', '', $screen->id) != $screen->id 
	) {
		wp_enqueue_style('flower-shop-icons', get_template_directory_uri() . '/css/fontello.css', array(), '1.0.0', 'screen');
		
		wp_enqueue_style('flower-shop-icons-custom', get_template_directory_uri() . '/theme-vars/theme-style' . CMSMASTERS_THEME_STYLE . '/css/fontello-custom.css', array(), '1.0.0', 'screen');
	}
	
	
	if ( 
		$hook == 'widgets.php' || 
		$hook == 'nav-menus.php' 
	) {
		wp_enqueue_media();
	}
	
	
	wp_enqueue_style('flower-shop-admin-styles', get_template_directory_uri() . '/framework/admin/inc/css/admin-theme-styles.css', array(), '1.0.0', 'screen');
	
	if (is_rtl()) {
		wp_enqueue_style('flower-shop-admin-styles-rtl', get_template_directory_uri() . '/framework/admin/inc/css/admin-theme-styles-rtl.css', array(), '1.0.0', 'screen');
	}
	
	
	wp_enqueue_script('flower-shop-admin-scripts', get_template_directory_uri() . '/framework/admin/inc/js/admin-theme-scripts.js', array('jquery'), '1.0.0', true);
	
	
	if ($hook == 'widgets.php') {
		wp_enqueue_style('flower-shop-widgets-styles', get_template_directory_uri() . '/framework/admin/inc/css/widgets-styles.css', array(), '1.0.0', 'screen');
		
		wp_enqueue_script('flower-shop-widgets-scripts', get_template_directory_uri() . '/framework/admin/inc/js/widgets-scripts.js', array('jquery'), '1.0.0', true);
	}
}

add_action('admin_enqueue_scripts', 'flower_shop_admin_register');

add_action('admin_enqueue_scripts', 'cmsmasters_composer_icons');

