<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.0.0
 * 
 * Theme Content Composer Functions
 * Created by CMSMasters
 * 
 */


/* Register JS Scripts */
function flower_shop_theme_register_c_c_scripts() {
	global $pagenow;
	
	
	if ( 
		$pagenow == 'post-new.php' || 
		($pagenow == 'post.php' && isset($_GET['post']) && get_post_type($_GET['post']) != 'attachment') 
	) {
		wp_enqueue_script('flower-shop-composer-shortcodes-extend', get_template_directory_uri() . '/theme-framework/theme-style' . CMSMASTERS_THEME_STYLE . '/cmsmasters-c-c/js/cmsmasters-c-c-theme-extend.js', array('cmsmasters_composer_shortcodes_js'), '1.0.0', true);
		
		wp_localize_script('flower-shop-composer-shortcodes-extend', 'cmsmasters_theme_shortcodes', array( 
			'blog_field_layout_mode_puzzle' => 						esc_attr__('Puzzle', 'flower-shop'), 
			'quotes_field_slider_type_title' => 					esc_attr__('Slider Type', 'flower-shop'), 
			'quotes_field_slider_type_descr' => 					esc_attr__('Choose your quotes slider style type', 'flower-shop'), 
			'quotes_field_type_choice_box' => 						esc_attr__('Boxed', 'flower-shop'), 
			'quotes_field_type_choice_center' => 					esc_attr__('Centered', 'flower-shop'), 
			'quotes_field_item_color_title' => 						esc_attr__('Color', 'flower-shop'), 
			'quotes_field_item_color_descr' => 						esc_attr__('Enter this quote custom color', 'flower-shop'), 
			'featured_block_hover' =>	 							esc_attr__('Featured Block Hover', 'flower-shop'), 
			'featured_block_hover_descr' =>	 						esc_attr__('Add hover for this featured block', 'flower-shop'), 
			'featured_block_border_width_title' => 					esc_attr__('Featured Block Border Width', 'flower-shop'),
			'featured_block_border_style_title' => 					esc_attr__('Featured Block Border Style', 'flower-shop'),
			'featured_block_border_color_title' => 					esc_attr__('Featured Block Border Color', 'flower-shop'),
			'featured_block_border_color_descr' => 					esc_attr__('Choose your custom featured block border color', 'flower-shop')
		));
	}
}

add_action('admin_enqueue_scripts', 'flower_shop_theme_register_c_c_scripts');



// Quotes Shortcode Attributes Filter
add_filter('cmsmasters_quotes_atts_filter', 'cmsmasters_quotes_atts');

function cmsmasters_quotes_atts() {
	return array( 
		'shortcode_id' => 		'', 
		'mode' => 				'grid', 
		'type' => 				'boxed', 
		'columns' => 			'3', 
		'speed' => 				'10', 
		'animation' => 			'', 
		'animation_delay' => 	'', 
		'classes' => 			'' 
	);
}



// Featured Block Shortcode Attributes Filter
add_filter('cmsmasters_featured_block_atts_filter', 'cmsmasters_featured_block_atts');

function cmsmasters_featured_block_atts() {
	return array( 
		'shortcode_id' => 					'', 
		'text_width' => 					'100', 
		'text_position' => 					'center', 
		'text_padding' => 					'', 
		'text_align' => 					'left', 
		'block_link' => 					'', 
		'block_link_target' => 				'', 
		'color_overlay' => 					'', 
		'fb_bg_color' => 					'', 
		'bg_img' => 						'', 
		'bg_position' => 					'', 
		'bg_repeat' => 						'', 
		'bg_attachment' => 					'', 
		'bg_size' => 						'', 
		'hover' => 							'', 
		'top_padding' => 					'', 
		'bottom_padding' => 				'', 
		'resp_vert_pad' => 					'', 
		'padding_top_laptop' => 			'', 
		'padding_bottom_laptop' => 			'', 
		'padding_top_tablet' => 			'', 
		'padding_bottom_tablet' => 			'', 
		'padding_top_mobile_h' => 			'', 
		'padding_bottom_mobile_h' =>		'', 
		'padding_top_mobile_v' => 			'', 
		'padding_bottom_mobile_v' => 		'', 
		'border_radius' => 					'', 
		'border_width' =>					'',	
		'border_style' =>					'',	
		'border_color' =>					'',	
		'animation' => 						'', 
		'animation_delay' => 				'', 
		'classes' => 						'' 
	);
}
