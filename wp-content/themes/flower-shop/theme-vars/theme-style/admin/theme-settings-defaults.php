<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.1.6
 * 
 * Theme Settings Defaults
 * Created by CMSMasters
 * 
 */


/* Theme Settings General Default Values */
if (!function_exists('flower_shop_settings_general_defaults')) {

function flower_shop_settings_general_defaults($id = false) {
	$settings = array( 
		'general' => array( 
			'flower-shop' . '_theme_layout' => 		'liquid', 
			'flower-shop' . '_logo_type' => 			'image', 
			'flower-shop' . '_logo_url' => 			'|' . get_template_directory_uri() . '/theme-vars/theme-style' . CMSMASTERS_THEME_STYLE . '/img/logo.png', 
			'flower-shop' . '_logo_url_retina' => 		'|' . get_template_directory_uri() . '/theme-vars/theme-style' . CMSMASTERS_THEME_STYLE . '/img/logo_retina.png', 
			'flower-shop' . '_logo_title' => 			get_bloginfo('name') ? get_bloginfo('name') : 'Flower Shop', 
			'flower-shop' . '_logo_subtitle' => 		'', 
			'flower-shop' . '_logo_custom_color' => 	0, 
			'flower-shop' . '_logo_title_color' => 	'', 
			'flower-shop' . '_logo_subtitle_color' => 	'' 
		), 
		'bg' => array( 
			'flower-shop' . '_bg_col' => 			'#ffffff', 
			'flower-shop' . '_bg_img_enable' => 	0, 
			'flower-shop' . '_bg_img' => 			'', 
			'flower-shop' . '_bg_rep' => 			'no-repeat', 
			'flower-shop' . '_bg_pos' => 			'top center', 
			'flower-shop' . '_bg_att' => 			'scroll', 
			'flower-shop' . '_bg_size' => 			'cover' 
		), 
		'header' => array( 
			'flower-shop' . '_fixed_header' => 				1, 
			'flower-shop' . '_header_overlaps' => 				1, 
			'flower-shop' . '_header_top_line' => 				0, 
			'flower-shop' . '_header_top_height' => 			'32', 
			'flower-shop' . '_header_top_line_short_info' => 	'', 
			'flower-shop' . '_header_top_line_add_cont' => 	'social', 
			'flower-shop' . '_header_styles' => 				'fullwidth', 
			'flower-shop' . '_header_mid_height' => 			'136', 
			'flower-shop' . '_header_bot_height' => 			'60', 
			'flower-shop' . '_header_search' => 				1, 
			'flower-shop' . '_header_add_cont' => 				'none', 
			'flower-shop' . '_header_add_cont_cust_html' => 	'', 
			'flower-shop' . '_woocommerce_cart_dropdown' => 	0
		), 
		'content' => array( 
			'flower-shop' . '_layout' => 					'r_sidebar', 
			'flower-shop' . '_archives_layout' => 			'r_sidebar', 
			'flower-shop' . '_search_layout' => 			'r_sidebar', 
			'flower-shop' . '_other_layout' => 				'r_sidebar', 
			'flower-shop' . '_heading_alignment' => 		'center', 
			'flower-shop' . '_heading_scheme' => 			'default', 
			'flower-shop' . '_heading_bg_image_enable' => 	0, 
			'flower-shop' . '_heading_bg_image' => 		'', 
			'flower-shop' . '_heading_bg_repeat' => 		'no-repeat', 
			'flower-shop' . '_heading_bg_attachment' => 	'scroll', 
			'flower-shop' . '_heading_bg_size' => 			'cover', 
			'flower-shop' . '_heading_bg_color' => 		'#ffffff', 
			'flower-shop' . '_heading_height' => 			'190', 
			'flower-shop' . '_breadcrumbs' => 				1, 
			'flower-shop' . '_bottom_scheme' => 			'footer', 
			'flower-shop' . '_bottom_sidebar' => 			0, 
			'flower-shop' . '_bottom_sidebar_layout' => 	'131313' 
		), 
		'footer' => array( 
			'flower-shop' . '_footer_scheme' => 				'footer', 
			'flower-shop' . '_footer_type' => 					'small', 
			'flower-shop' . '_footer_additional_content' => 	'social', 
			'flower-shop' . '_footer_logo' => 					1, 
			'flower-shop' . '_footer_logo_url' => 				'|' . get_template_directory_uri() . '/theme-vars/theme-style' . CMSMASTERS_THEME_STYLE . '/img/logo_footer.png', 
			'flower-shop' . '_footer_logo_url_retina' => 		'|' . get_template_directory_uri() . '/theme-vars/theme-style' . CMSMASTERS_THEME_STYLE . '/img/logo_footer_retina.png', 
			'flower-shop' . '_footer_nav' => 					0, 
			'flower-shop' . '_footer_social' => 				1, 
			'flower-shop' . '_footer_html' => 					'', 
			'flower-shop' . '_footer_copyright' => 			'&copy; ' . date('Y') . ' ' . 'Flower Shop' 
		) 
	);
	
	
	if ($id) {
		return $settings[$id];
	} else {
		return $settings;
	}
}

}



/* Theme Settings Fonts Default Values */
if (!function_exists('flower_shop_settings_font_defaults')) {

function flower_shop_settings_font_defaults($id = false) {
	$settings = array( 
		'content' => array( 
			'flower-shop' . '_content_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic', 
				'font_size' => 			'17', 
				'line_height' => 		'24', 
				'font_weight' => 		'300', 
				'font_style' => 		'normal' 
			) 
		), 
		'link' => array( 
			'flower-shop' . '_link_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic',  
				'font_size' => 			'15', 
				'line_height' => 		'24', 
				'font_weight' => 		'600', 
				'font_style' => 		'normal', 
				'text_transform' => 	'none', 
				'text_decoration' => 	'none' 
			), 
			'flower-shop' . '_link_hover_decoration' => 	'none' 
		), 
		'nav' => array( 
			'flower-shop' . '_nav_title_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Fjalla+One', 
				'font_size' => 			'13', 
				'line_height' => 		'24', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'uppercase' 
			), 
			'flower-shop' . '_nav_dropdown_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic',  
				'font_size' => 			'15', 
				'line_height' => 		'20', 
				'font_weight' => 		'600', 
				'font_style' => 		'normal', 
				'text_transform' => 	'none' 
			) 
		), 
		'heading' => array( 
			'flower-shop' . '_h1_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Petit+Formal+Script', 
				'font_size' => 			'36', 
				'line_height' => 		'48', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'none', 
				'text_decoration' => 	'none' 
			), 
			'flower-shop' . '_h2_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Fjalla+One', 
				'font_size' => 			'28', 
				'line_height' => 		'40', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'uppercase', 
				'text_decoration' => 	'none' 
			), 
			'flower-shop' . '_h3_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Petit+Formal+Script', 
				'font_size' => 			'20', 
				'line_height' => 		'30', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'none', 
				'text_decoration' => 	'none' 
			), 
			'flower-shop' . '_h4_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Fjalla+One', 
				'font_size' => 			'18', 
				'line_height' => 		'30', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'uppercase', 
				'text_decoration' => 	'none' 
			), 
			'flower-shop' . '_h5_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Fjalla+One', 
				'font_size' => 			'16', 
				'line_height' => 		'22', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'uppercase', 
				'text_decoration' => 	'none' 
			), 
			'flower-shop' . '_h6_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic', 
				'font_size' => 			'15', 
				'line_height' => 		'24', 
				'font_weight' => 		'600', 
				'font_style' => 		'normal', 
				'text_transform' => 	'none', 
				'text_decoration' => 	'none' 
			) 
		), 
		'other' => array( 
			'flower-shop' . '_button_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Fjalla+One', 
				'font_size' => 			'13', 
				'line_height' => 		'40', 
				'font_weight' => 		'normal', 
				'font_style' => 		'normal', 
				'text_transform' => 	'uppercase' 
			), 
			'flower-shop' . '_small_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Fjalla+One', 
				'font_size' => 			'14', 
				'line_height' => 		'22', 
				'font_weight' => 		'400', 
				'font_style' => 		'normal', 
				'text_transform' => 	'uppercase' 
			), 
			'flower-shop' . '_input_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic', 
				'font_size' => 			'17', 
				'line_height' => 		'20', 
				'font_weight' => 		'300', 
				'font_style' => 		'normal' 
			), 
			'flower-shop' . '_quote_font' => array( 
				'system_font' => 		"Arial, Helvetica, 'Nimbus Sans L', sans-serif", 
				'google_font' => 		'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic', 
				'font_size' => 			'20', 
				'line_height' => 		'32', 
				'font_weight' => 		'normal', 
				'font_style' => 		'italic' 
			) 
		),
		'google' => array( 
			'flower-shop' . '_google_web_fonts' => array( 
				'Titillium+Web:300,300italic,400,400italic,600,600italic,700,700italic|Titillium Web', 
				'Roboto:300,300italic,400,400italic,500,500italic,700,700italic|Roboto', 
				'Roboto+Condensed:400,400italic,700,700italic|Roboto Condensed', 
				'Open+Sans:300,300italic,400,400italic,700,700italic|Open Sans', 
				'Open+Sans+Condensed:300,300italic,700|Open Sans Condensed', 
				'Droid+Sans:400,700|Droid Sans', 
				'Droid+Serif:400,400italic,700,700italic|Droid Serif', 
				'PT+Sans:400,400italic,700,700italic|PT Sans', 
				'PT+Sans+Caption:400,700|PT Sans Caption', 
				'PT+Sans+Narrow:400,700|PT Sans Narrow', 
				'PT+Serif:400,400italic,700,700italic|PT Serif', 
				'Ubuntu:400,400italic,700,700italic|Ubuntu', 
				'Ubuntu+Condensed|Ubuntu Condensed', 
				'Headland+One|Headland One', 
				'Source+Sans+Pro:300,300italic,400,400italic,700,700italic|Source Sans Pro', 
				'Lato:400,400italic,700,700italic|Lato', 
				'Cuprum:400,400italic,700,700italic|Cuprum', 
				'Oswald:300,400,700|Oswald', 
				'Yanone+Kaffeesatz:300,400,700|Yanone Kaffeesatz', 
				'Lobster|Lobster', 
				'Lobster+Two:400,400italic,700,700italic|Lobster Two', 
				'Questrial|Questrial', 
				'Raleway:300,400,500,600,700|Raleway', 
				'Dosis:300,400,500,700|Dosis', 
				'Cutive+Mono|Cutive Mono', 
				'Quicksand:300,400,700|Quicksand', 
				'Montserrat:400,700|Montserrat', 
				'Cookie|Cookie',
				'Crimson+Text:400,400i,600,600i,700,700i|Crimson Text',
				'Josefin+Sans:300,300italic,400,400italic,600,600italic,700,700italic|Josefin Sans',
				'Fjalla+One|Fjalla One',
				'Petit+Formal+Script|Petit Formal Script'
			) 
		)  
	);
	
	
	if ($id) {
		return $settings[$id];
	} else {
		return $settings;
	}
}

}



// WP Color Picker Palettes
if (!function_exists('cmsmasters_color_picker_palettes')) {

function cmsmasters_color_picker_palettes() {
	$palettes = array( 
		'#000000', 
		'#ffffff', 
		'#544e48', 
		'#211f1f', 
		'#a7896a', 
		'#3c3834', 
		'#ffffff', 
		'#f4e7e0' 
	);
	
	
	return $palettes;
}

}



// Theme Settings Color Schemes Default Colors
if (!function_exists('flower_shop_color_schemes_defaults')) {

function flower_shop_color_schemes_defaults($id = false) {
	$settings = array( 
		'default' => array( // content default color scheme
			'color' => 		'#544e48', 
			'link' => 		'#211f1f', 
			'hover' => 		'#a7896a', 
			'heading' => 	'#3c3834', 
			'bg' => 		'#ffffff', 
			'alternate' => 	'#ffffff', 
			'border' => 	'#f4e7e0' 
		), 
		'header' => array( // Header color scheme
			'mid_color' => 		'#544e48', 
			'mid_link' => 		'#211f1f', 
			'mid_hover' => 		'#a7896a', 
			'mid_bg' => 		'#ffffff', 
			'mid_bg_scroll' => 	'#ffffff', 
			'mid_border' => 	'#f4e7e0', 
			'bot_color' => 		'#544e48', 
			'bot_link' => 		'#211f1f', 
			'bot_hover' => 		'#a7896a', 
			'bot_bg' => 		'#ffffff', 
			'bot_bg_scroll' => 	'#ffffff', 
			'bot_border' => 	'#f4e7e0' 
		), 
		'navigation' => array( // Navigation color scheme
			'title_link' => 			'#211f1f', 
			'title_link_hover' => 		'#a7896a', 
			'title_link_current' => 	'#211f1f', 
			'title_link_subtitle' => 	'#8e7b72', 
			'title_link_bg' => 			'#ffffff', 
			'title_link_bg_hover' => 	'#ffffff', 
			'title_link_bg_current' => 	'#ffffff', 
			'title_link_border' => 		'#ffffff', 
			'dropdown_text' => 			'#544e48', 
			'dropdown_bg' => 			'#fdeee8', 
			'dropdown_border' => 		'#fdeee8', 
			'dropdown_link' => 			'#3e3a38', 
			'dropdown_link_hover' => 	'#ae9377', 
			'dropdown_link_subtitle' => '#8e7b72', 
			'dropdown_link_highlight' => 'rgba(255,255,255,0)', 
			'dropdown_link_border' => 	'#fdeee8' 
		), 
		'header_top' => array( // Header Top color scheme
			'color' => 					'#544e48', 
			'link' => 					'#211f1f', 
			'hover' => 					'#a7896a', 
			'bg' => 					'#fcece5', 
			'border' => 				'#f4e7e0', 
			'title_link' => 			'#211f1f', 
			'title_link_hover' => 		'#a7896a', 
			'title_link_bg' => 			'#fcece5', 
			'title_link_bg_hover' => 	'#fcece5', 
			'title_link_border' => 		'#fcece5', 
			'dropdown_bg' => 			'#fdeee8', 
			'dropdown_border' => 		'#fdeee8', 
			'dropdown_link' => 			'#3e3a38', 
			'dropdown_link_hover' => 	'#ae9377', 
			'dropdown_link_highlight' => 'rgba(255,255,255,0)', 
			'dropdown_link_border' => 	'#fdeee8' 
		), 
		'footer' => array( // Footer color scheme
			'color' => 		'#544e48', 
			'link' => 		'#211f1f', 
			'hover' => 		'#a7896a', 
			'heading' => 	'#3c3834', 
			'bg' => 		'#fefbfa', 
			'alternate' => 	'#ffffff', 
			'border' => 	'#f4e7e0' 
		), 
		'first' => array( // custom color scheme 1
			'color' => 		'#ffffff', 
			'link' => 		'#ffffff', 
			'hover' => 		'#ffffff', 
			'heading' => 	'#ffffff', 
			'bg' => 		'#98433a', 
			'alternate' => 	'#ffffff', 
			'border' => 	'rgba(255,255,255,0.2)' 
		), 
		'second' => array( // custom color scheme 2
			'color' => 		'#ffffff', 
			'link' => 		'#ffffff', 
			'hover' => 		'rgba(255,255,255,0.4)', 
			'heading' => 	'#ffffff', 
			'bg' => 		'#d14f42', 
			'alternate' => 	'rgba(255,255,255,0)', 
			'border' => 	'#e4e4e4' 
		), 
		'third' => array( // custom color scheme 3
			'color' => 		'rgba(255,255,255,0.4)', 
			'link' => 		'#ffffff', 
			'hover' => 		'#e96b61', 
			'heading' => 	'#ffffff', 
			'bg' => 		'#d14f42', 
			'alternate' => 	'#d14f42', 
			'border' => 	'#e4e4e4' 
		) 
	);
	
	
	if ($id) {
		return $settings[$id];
	} else {
		return $settings;
	}
}

}



// Theme Settings Elements Default Values
if (!function_exists('flower_shop_settings_element_defaults')) {

function flower_shop_settings_element_defaults($id = false) {
	$settings = array( 
		'sidebar' => array( 
			'flower-shop' . '_sidebar' => 	'' 
		), 
		'icon' => array( 
			'flower-shop' . '_social_icons' => array( 
				'cmsmasters-icon-linkedin|#|' . esc_html__('Linkedin', 'flower-shop') . '|true||', 
				'cmsmasters-icon-facebook|#|' . esc_html__('Facebook', 'flower-shop') . '|true||', 
				'cmsmasters-icon-gplus|#|' . esc_html__('Google', 'flower-shop') . '|true||', 
				'cmsmasters-icon-twitter|#|' . esc_html__('Twitter', 'flower-shop') . '|true||', 
				'cmsmasters-icon-skype|#|' . esc_html__('Skype', 'flower-shop') . '|true||' 
			) 
		), 
		'lightbox' => array( 
			'flower-shop' . '_ilightbox_skin' => 					'dark', 
			'flower-shop' . '_ilightbox_path' => 					'vertical', 
			'flower-shop' . '_ilightbox_infinite' => 				0, 
			'flower-shop' . '_ilightbox_aspect_ratio' => 			1, 
			'flower-shop' . '_ilightbox_mobile_optimizer' => 		1, 
			'flower-shop' . '_ilightbox_max_scale' => 				1, 
			'flower-shop' . '_ilightbox_min_scale' => 				0.2, 
			'flower-shop' . '_ilightbox_inner_toolbar' => 			0, 
			'flower-shop' . '_ilightbox_smart_recognition' => 		0, 
			'flower-shop' . '_ilightbox_fullscreen_one_slide' => 	0, 
			'flower-shop' . '_ilightbox_fullscreen_viewport' => 	'center', 
			'flower-shop' . '_ilightbox_controls_toolbar' => 		1, 
			'flower-shop' . '_ilightbox_controls_arrows' => 		0, 
			'flower-shop' . '_ilightbox_controls_fullscreen' => 	1, 
			'flower-shop' . '_ilightbox_controls_thumbnail' => 	1, 
			'flower-shop' . '_ilightbox_controls_keyboard' => 		1, 
			'flower-shop' . '_ilightbox_controls_mousewheel' => 	1, 
			'flower-shop' . '_ilightbox_controls_swipe' => 		1, 
			'flower-shop' . '_ilightbox_controls_slideshow' => 	0 
		), 
		'sitemap' => array( 
			'flower-shop' . '_sitemap_nav' => 			1, 
			'flower-shop' . '_sitemap_categs' => 		1, 
			'flower-shop' . '_sitemap_tags' => 		1, 
			'flower-shop' . '_sitemap_month' => 		1, 
			'flower-shop' . '_sitemap_pj_categs' => 	1, 
			'flower-shop' . '_sitemap_pj_tags' => 		1 
		), 
		'error' => array( 
			'flower-shop' . '_error_color' => 				'#292929', 
			'flower-shop' . '_error_bg_color' => 			'#fcfcfc', 
			'flower-shop' . '_error_bg_img_enable' => 		0, 
			'flower-shop' . '_error_bg_image' => 			'', 
			'flower-shop' . '_error_bg_rep' => 			'no-repeat', 
			'flower-shop' . '_error_bg_pos' => 			'top center', 
			'flower-shop' . '_error_bg_att' => 			'scroll', 
			'flower-shop' . '_error_bg_size' => 			'cover', 
			'flower-shop' . '_error_search' => 			1, 
			'flower-shop' . '_error_sitemap_button' => 	1, 
			'flower-shop' . '_error_sitemap_link' => 		'' 
		), 
		'code' => array( 
			'flower-shop' . '_custom_css' => 			'', 
			'flower-shop' . '_custom_js' => 			'', 
			'flower-shop' . '_gmap_api_key' => 		'', 
			'flower-shop' . '_twitter_access_token' => 	'', 
		), 
		'recaptcha' => array( 
			'flower-shop' . '_recaptcha_public_key' => 	'', 
			'flower-shop' . '_recaptcha_private_key' => 	'' 
		) 
	);
	
	
	if ($id) {
		return $settings[$id];
	} else {
		return $settings;
	}
}

}



// Theme Settings Single Posts Default Values
if (!function_exists('flower_shop_settings_single_defaults')) {

function flower_shop_settings_single_defaults($id = false) {
	$settings = array( 
		'post' => array( 
			'flower-shop' . '_blog_post_layout' => 		'r_sidebar', 
			'flower-shop' . '_blog_post_title' => 			0, 
			'flower-shop' . '_blog_post_date' => 			1, 
			'flower-shop' . '_blog_post_cat' => 			1, 
			'flower-shop' . '_blog_post_author' => 		1, 
			'flower-shop' . '_blog_post_comment' => 		1, 
			'flower-shop' . '_blog_post_tag' => 			1, 
			'flower-shop' . '_blog_post_like' => 			1, 
			'flower-shop' . '_blog_post_nav_box' => 		1, 
			'flower-shop' . '_blog_post_nav_order_cat' => 		0, 
			'flower-shop' . '_blog_post_share_box' => 		1, 
			'flower-shop' . '_blog_post_author_box' => 	1, 
			'flower-shop' . '_blog_more_posts_box' => 		'popular', 
			'flower-shop' . '_blog_more_posts_count' => 	'4', 
			'flower-shop' . '_blog_more_posts_pause' => 	'5' 
		), 
		'project' => array( 
			'flower-shop' . '_portfolio_project_title' => 			1, 
			'flower-shop' . '_portfolio_project_details_title' => 	esc_html__('Project details', 'flower-shop'), 
			'flower-shop' . '_portfolio_project_date' => 			1, 
			'flower-shop' . '_portfolio_project_cat' => 			1, 
			'flower-shop' . '_portfolio_project_author' => 		1, 
			'flower-shop' . '_portfolio_project_comment' => 		1, 
			'flower-shop' . '_portfolio_project_tag' => 			0, 
			'flower-shop' . '_portfolio_project_like' => 			1, 
			'flower-shop' . '_portfolio_project_link' => 			0, 
			'flower-shop' . '_portfolio_project_share_box' => 		1, 
			'flower-shop' . '_portfolio_project_nav_box' => 		1, 
			'flower-shop' . '_portfolio_project_nav_order_cat' => 		0, 
			'flower-shop' . '_portfolio_project_author_box' => 	0, 
			'flower-shop' . '_portfolio_more_projects_box' => 		'popular', 
			'flower-shop' . '_portfolio_more_projects_count' => 	'4', 
			'flower-shop' . '_portfolio_more_projects_pause' => 	'5', 
			'flower-shop' . '_portfolio_project_slug' => 			'project', 
			'flower-shop' . '_portfolio_pj_categs_slug' => 		'pj-categs', 
			'flower-shop' . '_portfolio_pj_tags_slug' => 			'pj-tags' 
		), 
		'profile' => array( 
			'flower-shop' . '_profile_post_title' => 			1, 
			'flower-shop' . '_profile_post_details_title' => 	esc_html__('Profile details', 'flower-shop'), 
			'flower-shop' . '_profile_post_cat' => 			1, 
			'flower-shop' . '_profile_post_comment' => 		1, 
			'flower-shop' . '_profile_post_like' => 			1, 
			'flower-shop' . '_profile_post_nav_box' => 		1, 
			'flower-shop' . '_profile_post_nav_order_cat' => 		0, 
			'flower-shop' . '_profile_post_share_box' => 		0, 
			'flower-shop' . '_profile_post_slug' => 			'profile', 
			'flower-shop' . '_profile_pl_categs_slug' => 		'pl-categs' 
		) 
	);
	
	
	if ($id) {
		return $settings[$id];
	} else {
		return $settings;
	}
}

}



/* Project Puzzle Proportion */
if (!function_exists('flower_shop_project_puzzle_proportion')) {

function flower_shop_project_puzzle_proportion() {
	return 1;
}

}



/* Project Puzzle Proportion */
if (!function_exists('flower_shop_project_puzzle_large_gar_parameters')) {

function flower_shop_project_puzzle_large_gar_parameters() {
	$parameter = array ( 
		'container_width' 		=> 1160, 
		'bottomStaticPadding' 	=> 2.4 
	);
	
	
	return $parameter;
}

}



/* Theme Image Thumbnails Size */
if (!function_exists('flower_shop_get_image_thumbnail_list')) {

function flower_shop_get_image_thumbnail_list() {
	$list = array( 
		'cmsmasters-small-thumb' => array( 
			'width' => 		70, 
			'height' => 	70, 
			'crop' => 		true 
		), 
		'cmsmasters-square-thumb' => array( 
			'width' => 		300, 
			'height' => 	300, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Square', 'flower-shop') 
		), 
		'cmsmasters-blog-masonry-thumb' => array( 
			'width' => 		580, 
			'height' => 	420, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Masonry Blog', 'flower-shop') 
		), 
		'cmsmasters-project-grid-thumb' => array( 
			'width' => 		360, 
			'height' => 	360, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Project Grid', 'flower-shop') 
		), 
		'cmsmasters-project-thumb' => array( 
			'width' => 		580, 
			'height' => 	580, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Project', 'flower-shop') 
		), 
		'cmsmasters-project-masonry-thumb' => array( 
			'width' => 		580, 
			'height' => 	9999, 
			'title' => 		esc_attr__('Masonry Project', 'flower-shop') 
		), 
		'post-thumbnail' => array( 
			'width' => 		860, 
			'height' => 	500, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Featured', 'flower-shop') 
		), 
		'cmsmasters-masonry-thumb' => array( 
			'width' => 		860, 
			'height' => 	9999, 
			'title' => 		esc_attr__('Masonry', 'flower-shop') 
		), 
		'cmsmasters-full-thumb' => array( 
			'width' => 		1160, 
			'height' => 	650, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Full', 'flower-shop') 
		), 
		'cmsmasters-project-full-thumb' => array( 
			'width' => 		1160, 
			'height' => 	700, 
			'crop' => 		true, 
			'title' => 		esc_attr__('Project Full', 'flower-shop') 
		), 
		'cmsmasters-full-masonry-thumb' => array( 
			'width' => 		1160, 
			'height' => 	9999, 
			'title' => 		esc_attr__('Masonry Full', 'flower-shop') 
		) 
	);
	
	
	return $list;
}

}



/* Project Post Type Registration Rename */
if (!function_exists('flower_shop_project_labels')) {

function flower_shop_project_labels() {
	return array( 
		'name' => 					esc_html__('Projects', 'flower-shop'), 
		'singular_name' => 			esc_html__('Project', 'flower-shop'), 
		'menu_name' => 				esc_html__('Projects', 'flower-shop'), 
		'all_items' => 				esc_html__('All Projects', 'flower-shop'), 
		'add_new' => 				esc_html__('Add New', 'flower-shop'), 
		'add_new_item' => 			esc_html__('Add New Project', 'flower-shop'), 
		'edit_item' => 				esc_html__('Edit Project', 'flower-shop'), 
		'new_item' => 				esc_html__('New Project', 'flower-shop'), 
		'view_item' => 				esc_html__('View Project', 'flower-shop'), 
		'search_items' => 			esc_html__('Search Projects', 'flower-shop'), 
		'not_found' => 				esc_html__('No projects found', 'flower-shop'), 
		'not_found_in_trash' => 	esc_html__('No projects found in Trash', 'flower-shop') 
	);
}

}

// add_filter('cmsmasters_project_labels_filter', 'flower_shop_project_labels');


if (!function_exists('flower_shop_pj_categs_labels')) {

function flower_shop_pj_categs_labels() {
	return array( 
		'name' => 					esc_html__('Project Categories', 'flower-shop'), 
		'singular_name' => 			esc_html__('Project Category', 'flower-shop') 
	);
}

}

// add_filter('cmsmasters_pj_categs_labels_filter', 'flower_shop_pj_categs_labels');


if (!function_exists('flower_shop_pj_tags_labels')) {

function flower_shop_pj_tags_labels() {
	return array( 
		'name' => 					esc_html__('Project Tags', 'flower-shop'), 
		'singular_name' => 			esc_html__('Project Tag', 'flower-shop') 
	);
}

}

// add_filter('cmsmasters_pj_tags_labels_filter', 'flower_shop_pj_tags_labels');



/* Profile Post Type Registration Rename */
if (!function_exists('flower_shop_profile_labels')) {

function flower_shop_profile_labels() {
	return array( 
		'name' => 					esc_html__('Profiles', 'flower-shop'), 
		'singular_name' => 			esc_html__('Profiles', 'flower-shop'), 
		'menu_name' => 				esc_html__('Profiles', 'flower-shop'), 
		'all_items' => 				esc_html__('All Profiles', 'flower-shop'), 
		'add_new' => 				esc_html__('Add New', 'flower-shop'), 
		'add_new_item' => 			esc_html__('Add New Profile', 'flower-shop'), 
		'edit_item' => 				esc_html__('Edit Profile', 'flower-shop'), 
		'new_item' => 				esc_html__('New Profile', 'flower-shop'), 
		'view_item' => 				esc_html__('View Profile', 'flower-shop'), 
		'search_items' => 			esc_html__('Search Profiles', 'flower-shop'), 
		'not_found' => 				esc_html__('No Profiles found', 'flower-shop'), 
		'not_found_in_trash' => 	esc_html__('No Profiles found in Trash', 'flower-shop') 
	);
}

}

// add_filter('cmsmasters_profile_labels_filter', 'flower_shop_profile_labels');


if (!function_exists('flower_shop_pl_categs_labels')) {

function flower_shop_pl_categs_labels() {
	return array( 
		'name' => 					esc_html__('Profile Categories', 'flower-shop'), 
		'singular_name' => 			esc_html__('Profile Category', 'flower-shop') 
	);
}

}

// add_filter('cmsmasters_pl_categs_labels_filter', 'flower_shop_pl_categs_labels');

