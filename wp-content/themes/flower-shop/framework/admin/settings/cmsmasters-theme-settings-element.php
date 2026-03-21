<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.1.6
 * 
 * Admin Panel Element Options
 * Created by CMSMasters
 * 
 */


function flower_shop_options_element_tabs() {
	$tabs = array();
	
	$tabs['sidebar'] = esc_attr__('Sidebars', 'flower-shop');
	
	if (class_exists('Cmsmasters_Content_Composer')) {
		$tabs['icon'] = esc_attr__('Social Icons', 'flower-shop');
	}
	
	$tabs['lightbox'] = esc_attr__('Lightbox', 'flower-shop');
	$tabs['sitemap'] = esc_attr__('Sitemap', 'flower-shop');
	$tabs['error'] = esc_attr__('404', 'flower-shop');
	$tabs['code'] = esc_attr__('Custom Codes', 'flower-shop');
	
	if (class_exists('Cmsmasters_Form_Builder')) {
		$tabs['recaptcha'] = esc_attr__('reCAPTCHA', 'flower-shop');
	}
	
	return apply_filters('cmsmasters_options_element_tabs_filter', $tabs);
}


function flower_shop_options_element_sections() {
	$tab = flower_shop_get_the_tab();
	
	switch ($tab) {
	case 'sidebar':
		$sections = array();
		
		$sections['sidebar_section'] = esc_attr__('Custom Sidebars', 'flower-shop');
		
		break;
	case 'icon':
		$sections = array();
		
		$sections['icon_section'] = esc_attr__('Social Icons', 'flower-shop');
		
		break;
	case 'lightbox':
		$sections = array();
		
		$sections['lightbox_section'] = esc_attr__('Theme Lightbox Options', 'flower-shop');
		
		break;
	case 'sitemap':
		$sections = array();
		
		$sections['sitemap_section'] = esc_attr__('Sitemap Page Options', 'flower-shop');
		
		break;
	case 'error':
		$sections = array();
		
		$sections['error_section'] = esc_attr__('404 Error Page Options', 'flower-shop');
		
		break;
	case 'code':
		$sections = array();
		
		$sections['code_section'] = esc_attr__('Custom Codes', 'flower-shop');
		
		break;
	case 'recaptcha':
		$sections = array();
		
		$sections['recaptcha_section'] = esc_attr__('Form Builder Plugin reCAPTCHA Keys', 'flower-shop');
		
		break;
	default:
		$sections = array();
		
		
		break;
	}
	
	return apply_filters('cmsmasters_options_element_sections_filter', $sections, $tab);	
} 


function flower_shop_options_element_fields($set_tab = false) {
	if ($set_tab) {
		$tab = $set_tab;
	} else {
		$tab = flower_shop_get_the_tab();
	}
	
	
	$options = array();
	
	
	$defaults = flower_shop_settings_element_defaults();
	
	
	switch ($tab) {
	case 'sidebar':
		$options[] = array( 
			'section' => 'sidebar_section', 
			'id' => 'flower-shop' . '_sidebar', 
			'title' => esc_html__('Custom Sidebars', 'flower-shop'), 
			'desc' => '', 
			'type' => 'sidebar', 
			'std' => $defaults[$tab]['flower-shop' . '_sidebar'] 
		);
		
		break;
	case 'icon':
		$options[] = array( 
			'section' => 'icon_section', 
			'id' => 'flower-shop' . '_social_icons', 
			'title' => esc_html__('Social Icons', 'flower-shop'), 
			'desc' => '', 
			'type' => 'social', 
			'std' => $defaults[$tab]['flower-shop' . '_social_icons'] 
		);
		
		break;
	case 'lightbox':
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_skin', 
			'title' => esc_html__('Skin', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_skin'], 
			'choices' => array( 
				esc_html__('Dark', 'flower-shop') . '|dark', 
				esc_html__('Light', 'flower-shop') . '|light', 
				esc_html__('Mac', 'flower-shop') . '|mac', 
				esc_html__('Metro Black', 'flower-shop') . '|metro-black', 
				esc_html__('Metro White', 'flower-shop') . '|metro-white', 
				esc_html__('Parade', 'flower-shop') . '|parade', 
				esc_html__('Smooth', 'flower-shop') . '|smooth' 
			) 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_path', 
			'title' => esc_html__('Path', 'flower-shop'), 
			'desc' => esc_html__('Sets path for switching windows', 'flower-shop'), 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_path'], 
			'choices' => array( 
				esc_html__('Vertical', 'flower-shop') . '|vertical', 
				esc_html__('Horizontal', 'flower-shop') . '|horizontal' 
			) 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_infinite', 
			'title' => esc_html__('Infinite', 'flower-shop'), 
			'desc' => esc_html__('Sets the ability to infinite the group', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_infinite'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_aspect_ratio', 
			'title' => esc_html__('Keep Aspect Ratio', 'flower-shop'), 
			'desc' => esc_html__('Sets the resizing method used to keep aspect ratio within the viewport', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_aspect_ratio'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_mobile_optimizer', 
			'title' => esc_html__('Mobile Optimizer', 'flower-shop'), 
			'desc' => esc_html__('Make lightboxes optimized for giving better experience with mobile devices', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_mobile_optimizer'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_max_scale', 
			'title' => esc_html__('Max Scale', 'flower-shop'), 
			'desc' => esc_html__('Sets the maximum viewport scale of the content', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_max_scale'], 
			'min' => 0.1, 
			'max' => 2, 
			'step' => 0.05 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_min_scale', 
			'title' => esc_html__('Min Scale', 'flower-shop'), 
			'desc' => esc_html__('Sets the minimum viewport scale of the content', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_min_scale'], 
			'min' => 0.1, 
			'max' => 2, 
			'step' => 0.05 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_inner_toolbar', 
			'title' => esc_html__('Inner Toolbar', 'flower-shop'), 
			'desc' => esc_html__('Bring buttons into windows, or let them be over the overlay', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_inner_toolbar'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_smart_recognition', 
			'title' => esc_html__('Smart Recognition', 'flower-shop'), 
			'desc' => esc_html__('Sets content auto recognize from web pages', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_smart_recognition'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_fullscreen_one_slide', 
			'title' => esc_html__('Fullscreen One Slide', 'flower-shop'), 
			'desc' => esc_html__('Decide to fullscreen only one slide or hole gallery the fullscreen mode', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_fullscreen_one_slide'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_fullscreen_viewport', 
			'title' => esc_html__('Fullscreen Viewport', 'flower-shop'), 
			'desc' => esc_html__('Sets the resizing method used to fit content within the fullscreen mode', 'flower-shop'), 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_fullscreen_viewport'], 
			'choices' => array( 
				esc_html__('Center', 'flower-shop') . '|center', 
				esc_html__('Fit', 'flower-shop') . '|fit', 
				esc_html__('Fill', 'flower-shop') . '|fill', 
				esc_html__('Stretch', 'flower-shop') . '|stretch' 
			) 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_toolbar', 
			'title' => esc_html__('Toolbar Controls', 'flower-shop'), 
			'desc' => esc_html__('Sets buttons be available or not', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_toolbar'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_arrows', 
			'title' => esc_html__('Arrow Controls', 'flower-shop'), 
			'desc' => esc_html__('Enable the arrow buttons', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_arrows'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_fullscreen', 
			'title' => esc_html__('Fullscreen Controls', 'flower-shop'), 
			'desc' => esc_html__('Sets the fullscreen button', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_fullscreen'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_thumbnail', 
			'title' => esc_html__('Thumbnails Controls', 'flower-shop'), 
			'desc' => esc_html__('Sets the thumbnail navigation', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_thumbnail'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_keyboard', 
			'title' => esc_html__('Keyboard Controls', 'flower-shop'), 
			'desc' => esc_html__('Sets the keyboard navigation', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_keyboard'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_mousewheel', 
			'title' => esc_html__('Mouse Wheel Controls', 'flower-shop'), 
			'desc' => esc_html__('Sets the mousewheel navigation', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_mousewheel'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_swipe', 
			'title' => esc_html__('Swipe Controls', 'flower-shop'), 
			'desc' => esc_html__('Sets the swipe navigation', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_swipe'] 
		);
		
		$options[] = array( 
			'section' => 'lightbox_section', 
			'id' => 'flower-shop' . '_ilightbox_controls_slideshow', 
			'title' => esc_html__('Slideshow Controls', 'flower-shop'), 
			'desc' => esc_html__('Enable the slideshow feature and button', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_ilightbox_controls_slideshow'] 
		);
		
		break;
	case 'sitemap':
		$options[] = array( 
			'section' => 'sitemap_section', 
			'id' => 'flower-shop' . '_sitemap_nav', 
			'title' => esc_html__('Website Pages', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_sitemap_nav'] 
		);
		
		$options[] = array( 
			'section' => 'sitemap_section', 
			'id' => 'flower-shop' . '_sitemap_categs', 
			'title' => esc_html__('Blog Archives by Categories', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_sitemap_categs'] 
		);
		
		$options[] = array( 
			'section' => 'sitemap_section', 
			'id' => 'flower-shop' . '_sitemap_tags', 
			'title' => esc_html__('Blog Archives by Tags', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_sitemap_tags'] 
		);
		
		$options[] = array( 
			'section' => 'sitemap_section', 
			'id' => 'flower-shop' . '_sitemap_month', 
			'title' => esc_html__('Blog Archives by Month', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_sitemap_month'] 
		);
		
		$options[] = array( 
			'section' => 'sitemap_section', 
			'id' => 'flower-shop' . '_sitemap_pj_categs', 
			'title' => esc_html__('Portfolio Archives by Categories', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_sitemap_pj_categs'] 
		);
		
		$options[] = array( 
			'section' => 'sitemap_section', 
			'id' => 'flower-shop' . '_sitemap_pj_tags', 
			'title' => esc_html__('Portfolio Archives by Tags', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_sitemap_pj_tags'] 
		);
		
		break;
	case 'error':
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_color', 
			'title' => esc_html__('Text Color', 'flower-shop'), 
			'desc' => '', 
			'type' => 'rgba', 
			'std' => $defaults[$tab]['flower-shop' . '_error_color'] 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_color', 
			'title' => esc_html__('Background Color', 'flower-shop'), 
			'desc' => '', 
			'type' => 'rgba', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_color'] 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_img_enable', 
			'title' => esc_html__('Background Image Visibility', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_img_enable'] 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_image', 
			'title' => esc_html__('Background Image', 'flower-shop'), 
			'desc' => esc_html__('Choose your custom error page background image.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_image'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_rep', 
			'title' => esc_html__('Background Repeat', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_rep'], 
			'choices' => array( 
				esc_html__('No Repeat', 'flower-shop') . '|no-repeat', 
				esc_html__('Repeat Horizontally', 'flower-shop') . '|repeat-x', 
				esc_html__('Repeat Vertically', 'flower-shop') . '|repeat-y', 
				esc_html__('Repeat', 'flower-shop') . '|repeat' 
			) 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_pos', 
			'title' => esc_html__('Background Position', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_pos'], 
			'choices' => array( 
				esc_html__('Top Left', 'flower-shop') . '|top left', 
				esc_html__('Top Center', 'flower-shop') . '|top center', 
				esc_html__('Top Right', 'flower-shop') . '|top right', 
				esc_html__('Center Left', 'flower-shop') . '|center left', 
				esc_html__('Center Center', 'flower-shop') . '|center center', 
				esc_html__('Center Right', 'flower-shop') . '|center right', 
				esc_html__('Bottom Left', 'flower-shop') . '|bottom left', 
				esc_html__('Bottom Center', 'flower-shop') . '|bottom center', 
				esc_html__('Bottom Right', 'flower-shop') . '|bottom right' 
			) 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_att', 
			'title' => esc_html__('Background Attachment', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_att'], 
			'choices' => array( 
				esc_html__('Scroll', 'flower-shop') . '|scroll', 
				esc_html__('Fixed', 'flower-shop') . '|fixed' 
			) 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_bg_size', 
			'title' => esc_html__('Background Size', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_error_bg_size'], 
			'choices' => array( 
				esc_html__('Auto', 'flower-shop') . '|auto', 
				esc_html__('Cover', 'flower-shop') . '|cover', 
				esc_html__('Contain', 'flower-shop') . '|contain' 
			) 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_search', 
			'title' => esc_html__('Search Line', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_error_search'] 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_sitemap_button', 
			'title' => esc_html__('Sitemap Button', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_error_sitemap_button'] 
		);
		
		$options[] = array( 
			'section' => 'error_section', 
			'id' => 'flower-shop' . '_error_sitemap_link', 
			'title' => esc_html__('Sitemap Page URL', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_error_sitemap_link'], 
			'class' => '' 
		);
		
		break;
	case 'code':
		$options[] = array( 
			'section' => 'code_section', 
			'id' => 'flower-shop' . '_custom_css', 
			'title' => esc_html__('Custom CSS', 'flower-shop'), 
			'desc' => '', 
			'type' => 'textarea', 
			'std' => $defaults[$tab]['flower-shop' . '_custom_css'], 
			'class' => 'allowlinebreaks' 
		);
		
		$options[] = array( 
			'section' => 'code_section', 
			'id' => 'flower-shop' . '_custom_js', 
			'title' => esc_html__('Custom JavaScript', 'flower-shop'), 
			'desc' => '', 
			'type' => 'textarea', 
			'std' => $defaults[$tab]['flower-shop' . '_custom_js'], 
			'class' => 'allowlinebreaks' 
		);
		
		$options[] = array( 
			'section' => 'code_section', 
			'id' => 'flower-shop' . '_gmap_api_key', 
			'title' => esc_html__('Google Maps API key', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_gmap_api_key'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'code_section', 
			'id' => 'flower-shop' . '_twitter_access_token', 
			'title' => esc_html__('Twitter Access Token', 'flower-shop'), 
			'desc' => sprintf(
				/* translators: Twitter access token. %s: Link to twitter access token generator */
				esc_html__( 'Generate %s and paste Access Token to this field.', 'flower-shop' ),
				'<a href="' . esc_url( 'https://api.cmsmasters.net/wp-json/cmsmasters-api/v1/twitter-request-token' ) . '" target="_blank">' .
					esc_html__( 'twitter access token', 'flower-shop' ) .
				'</a>'
			), 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_twitter_access_token'], 
			'class' => '' 
		);
		
		break;
	case 'recaptcha':
		$options[] = array( 
			'section' => 'recaptcha_section', 
			'id' => 'flower-shop' . '_recaptcha_public_key', 
			'title' => esc_html__('reCAPTCHA Public Key', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_recaptcha_public_key'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'recaptcha_section', 
			'id' => 'flower-shop' . '_recaptcha_private_key', 
			'title' => esc_html__('reCAPTCHA Private Key', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_recaptcha_private_key'], 
			'class' => '' 
		);
		
		break;
	}
	
	return apply_filters('cmsmasters_options_element_fields_filter', $options, $tab);	
}

