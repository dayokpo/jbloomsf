<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.0.9
 * 
 * Header Bottom Template
 * Created by CMSMasters
 * 
 */


$cmsmasters_option = flower_shop_get_global_options();


if (
	$cmsmasters_option['flower-shop' . '_header_styles'] != 'default' &&
	$cmsmasters_option['flower-shop' . '_header_styles'] != 'fullwidth'
) {
	echo '<div class="header_bot" data-height="' . esc_attr($cmsmasters_option['flower-shop' . '_header_bot_height']) . '">' . 
		'<div class="header_bot_outer">' . 
			'<div class="header_bot_inner">';
				do_action('cmsmasters_before_header_bot', $cmsmasters_option);
				
				
				echo '<!-- Start Navigation -->' . 
				'<div class="bot_nav_wrap">' . 
					'<nav>';
						
						$nav_args = array( 
							'theme_location' => 	'primary', 
							'menu_id' => 			'navigation', 
							'menu_class' => 		'bot_nav navigation', 
							'link_before' => 		'<span class="nav_item_wrap">', 
							'link_after' => 		'</span>', 
							'fallback_cb' => 		false
						);
						
						
						if (class_exists('Walker_Cmsmasters_Nav_Mega_Menu')) {
							$nav_args['walker'] = new Walker_Cmsmasters_Nav_Mega_Menu();
						}
						
						
						wp_nav_menu($nav_args);
						
					echo '</nav>' . 
				'</div>' . 
				'<!-- Finish Navigation -->';

				if ($cmsmasters_option['flower-shop' . '_header_styles'] == 'c_nav') {
					do_action('cmsmasters_after_search', $cmsmasters_option);
				}
				
				do_action('cmsmasters_after_header_bot', $cmsmasters_option);
			echo '</div>' . 
		'</div>' . 
	'</div>';
}

