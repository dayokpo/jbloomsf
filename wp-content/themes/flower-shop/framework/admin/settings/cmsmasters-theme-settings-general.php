<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.0.9
 * 
 * Admin Panel General Options
 * Created by CMSMasters
 * 
 */


function flower_shop_options_general_tabs() {
	$cmsmasters_option = flower_shop_get_global_options();
	
	$tabs = array();
	
	$tabs['general'] = esc_attr__('General', 'flower-shop');
	
	if ($cmsmasters_option['flower-shop' . '_theme_layout'] === 'boxed') {
		$tabs['bg'] = esc_attr__('Background', 'flower-shop');
	}
	
	if (CMSMASTERS_THEME_STYLE_COMPATIBILITY) {
		$tabs['theme_style'] = esc_attr__('Theme Style', 'flower-shop');
	}
	
	$tabs['header'] = esc_attr__('Header', 'flower-shop');
	$tabs['content'] = esc_attr__('Content', 'flower-shop');
	$tabs['footer'] = esc_attr__('Footer', 'flower-shop');
	
	return apply_filters('cmsmasters_options_general_tabs_filter', $tabs);
}


function flower_shop_options_general_sections() {
	$tab = flower_shop_get_the_tab();
	
	switch ($tab) {
	case 'general':
		$sections = array();
		
		$sections['general_section'] = esc_attr__('General Options', 'flower-shop');
		
		break;
	case 'bg':
		$sections = array();
		
		$sections['bg_section'] = esc_attr__('Background Options', 'flower-shop');
		
		break;
	case 'theme_style':
		$sections = array();
		
		$sections['theme_style_section'] = esc_attr__('Theme Design Style', 'flower-shop');
		
		break;
	case 'header':
		$sections = array();
		
		$sections['header_section'] = esc_attr__('Header Options', 'flower-shop');
		
		break;
	case 'content':
		$sections = array();
		
		$sections['content_section'] = esc_attr__('Content Options', 'flower-shop');
		
		break;
	case 'footer':
		$sections = array();
		
		$sections['footer_section'] = esc_attr__('Footer Options', 'flower-shop');
		
		break;
	default:
		$sections = array();
		
		
		break;
	}
	
	return apply_filters('cmsmasters_options_general_sections_filter', $sections, $tab);
} 


function flower_shop_options_general_fields($set_tab = false) {
	if ($set_tab) {
		$tab = $set_tab;
	} else {
		$tab = flower_shop_get_the_tab();
	}
	
	$options = array();
	
	
	$defaults = flower_shop_settings_general_defaults();
	
	
	switch ($tab) {
	case 'general':
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_theme_layout', 
			'title' => esc_html__('Theme Layout', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_theme_layout'], 
			'choices' => array( 
				esc_html__('Liquid', 'flower-shop') . '|liquid', 
				esc_html__('Boxed', 'flower-shop') . '|boxed' 
			) 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_type', 
			'title' => esc_html__('Logo Type', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_type'], 
			'choices' => array( 
				esc_html__('Image', 'flower-shop') . '|image', 
				esc_html__('Text', 'flower-shop') . '|text' 
			) 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_url', 
			'title' => esc_html__('Logo Image', 'flower-shop'), 
			'desc' => esc_html__('Choose your website logo image.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_url'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_url_retina', 
			'title' => esc_html__('Retina Logo Image', 'flower-shop'), 
			'desc' => esc_html__('Choose logo image for retina displays. Logo for Retina displays should be twice the size of the default one.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_url_retina'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_title', 
			'title' => esc_html__('Logo Title', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_title'], 
			'class' => 'nohtml' 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_subtitle', 
			'title' => esc_html__('Logo Subtitle', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_subtitle'], 
			'class' => 'nohtml' 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_custom_color', 
			'title' => esc_html__('Custom Text Colors', 'flower-shop'), 
			'desc' => esc_html__('enable', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_custom_color'] 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_title_color', 
			'title' => esc_html__('Logo Title Color', 'flower-shop'), 
			'desc' => '', 
			'type' => 'rgba', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_title_color'] 
		);
		
		$options[] = array( 
			'section' => 'general_section', 
			'id' => 'flower-shop' . '_logo_subtitle_color', 
			'title' => esc_html__('Logo Subtitle Color', 'flower-shop'), 
			'desc' => '', 
			'type' => 'rgba', 
			'std' => $defaults[$tab]['flower-shop' . '_logo_subtitle_color'] 
		);
		
		break;
	case 'bg':
		$options[] = array( 
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_col', 
			'title' => esc_html__('Background Color', 'flower-shop'), 
			'desc' => '', 
			'type' => 'color', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_col'] 
		);
		
		$options[] = array( 
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_img_enable', 
			'title' => esc_html__('Background Image Visibility', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_img_enable'] 
		);
		
		$options[] = array( 
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_img', 
			'title' => esc_html__('Background Image', 'flower-shop'), 
			'desc' => esc_html__('Choose your custom website background image url.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_img'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_rep', 
			'title' => esc_html__('Background Repeat', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_rep'], 
			'choices' => array( 
				esc_html__('No Repeat', 'flower-shop') . '|no-repeat', 
				esc_html__('Repeat Horizontally', 'flower-shop') . '|repeat-x', 
				esc_html__('Repeat Vertically', 'flower-shop') . '|repeat-y', 
				esc_html__('Repeat', 'flower-shop') . '|repeat' 
			) 
		);
		
		$options[] = array( 
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_pos', 
			'title' => esc_html__('Background Position', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_pos'], 
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
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_att', 
			'title' => esc_html__('Background Attachment', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_att'], 
			'choices' => array( 
				esc_html__('Scroll', 'flower-shop') . '|scroll', 
				esc_html__('Fixed', 'flower-shop') . '|fixed' 
			) 
		);
		
		$options[] = array( 
			'section' => 'bg_section', 
			'id' => 'flower-shop' . '_bg_size', 
			'title' => esc_html__('Background Size', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_bg_size'], 
			'choices' => array( 
				esc_html__('Auto', 'flower-shop') . '|auto', 
				esc_html__('Cover', 'flower-shop') . '|cover', 
				esc_html__('Contain', 'flower-shop') . '|contain' 
			) 
		);
		
		break;
	case 'theme_style':
		$options[] = array( 
			'section' => 'theme_style_section', 
			'id' => 'flower-shop' . '_theme_style', 
			'title' => esc_html__('Choose Theme Style', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select_theme_style', 
			'std' => '', 
			'choices' => flower_shop_all_theme_styles() 
		);
		
		break;
	case 'header':
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_fixed_header', 
			'title' => esc_html__('Fixed Header', 'flower-shop'), 
			'desc' => esc_html__('enable', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_fixed_header'] 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_overlaps', 
			'title' => esc_html__('Header Overlaps Content by Default', 'flower-shop'), 
			'desc' => esc_html__('enable', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_header_overlaps'] 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_top_line', 
			'title' => esc_html__('Top Line', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_header_top_line'] 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_top_height', 
			'title' => esc_html__('Top Height', 'flower-shop'), 
			'desc' => esc_html__('pixels', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_header_top_height'], 
			'min' => '10' 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_top_line_short_info', 
			'title' => esc_html__('Top Short Info', 'flower-shop'), 
			'desc' => '<strong>' . esc_html__('HTML tags are allowed!', 'flower-shop') . '</strong>', 
			'type' => 'textarea', 
			'std' => $defaults[$tab]['flower-shop' . '_header_top_line_short_info'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_top_line_add_cont', 
			'title' => esc_html__('Top Additional Content', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_header_top_line_add_cont'], 
			'choices' => array( 
				esc_html__('None', 'flower-shop') . '|none', 
				esc_html__('Top Line Social Icons (will be shown if Cmsmasters Content Composer plugin is active)', 'flower-shop') . '|social', 
				esc_html__('Top Line Navigation (will be shown if set in Appearance - Menus tab)', 'flower-shop') . '|nav' 
			) 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_styles', 
			'title' => esc_html__('Header Styles', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_header_styles'], 
			'choices' => array( 
				esc_html__('Default Style', 'flower-shop') . '|default', 
				esc_html__('Compact Style Left Navigation', 'flower-shop') . '|l_nav', 
				esc_html__('Compact Style Right Navigation', 'flower-shop') . '|r_nav', 
				esc_html__('Compact Style Center Navigation', 'flower-shop') . '|c_nav'
			) 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_mid_height', 
			'title' => esc_html__('Header Middle Height', 'flower-shop'), 
			'desc' => esc_html__('pixels', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_header_mid_height'], 
			'min' => '40' 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_bot_height', 
			'title' => esc_html__('Header Bottom Height', 'flower-shop'), 
			'desc' => esc_html__('pixels', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_header_bot_height'], 
			'min' => '20' 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_search', 
			'title' => esc_html__('Header Search', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_header_search'] 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_add_cont', 
			'title' => esc_html__('Header Additional Content', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_header_add_cont'], 
			'choices' => array( 
				esc_html__('None', 'flower-shop') . '|none', 
				esc_html__('Header Social Icons (will be shown if Cmsmasters Content Composer plugin is active)', 'flower-shop') . '|social', 
				esc_html__('Header Custom HTML', 'flower-shop') . '|cust_html' 
			) 
		);
		
		$options[] = array( 
			'section' => 'header_section', 
			'id' => 'flower-shop' . '_header_add_cont_cust_html', 
			'title' => esc_html__('Header Custom HTML', 'flower-shop'), 
			'desc' => '<strong>' . esc_html__('HTML tags are allowed!', 'flower-shop') . '</strong>', 
			'type' => 'textarea', 
			'std' => $defaults[$tab]['flower-shop' . '_header_add_cont_cust_html'], 
			'class' => '' 
		);
		
		break;
	case 'content':
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_layout', 
			'title' => esc_html__('Layout Type by Default', 'flower-shop'), 
			'desc' => esc_html__('Choosing layout with a sidebar please make sure to add widgets to the Sidebar in the Appearance - Widgets tab. The empty sidebar won\'t be displayed.', 'flower-shop'), 
			'type' => 'radio_img', 
			'std' => $defaults[$tab]['flower-shop' . '_layout'], 
			'choices' => array( 
				esc_html__('Right Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_r.jpg' . '|r_sidebar', 
				esc_html__('Left Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_l.jpg' . '|l_sidebar', 
				esc_html__('Full Width', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/fullwidth.jpg' . '|fullwidth' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_archives_layout', 
			'title' => esc_html__('Archives Layout Type', 'flower-shop'), 
			'desc' => esc_html__('Choosing layout with a sidebar please make sure to add widgets to the Archive Sidebar in the Appearance - Widgets tab. The empty sidebar won\'t be displayed.', 'flower-shop'), 
			'type' => 'radio_img', 
			'std' => $defaults[$tab]['flower-shop' . '_archives_layout'], 
			'choices' => array( 
				esc_html__('Right Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_r.jpg' . '|r_sidebar', 
				esc_html__('Left Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_l.jpg' . '|l_sidebar', 
				esc_html__('Full Width', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/fullwidth.jpg' . '|fullwidth' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_search_layout', 
			'title' => esc_html__('Search Layout Type', 'flower-shop'), 
			'desc' => esc_html__('Choosing layout with a sidebar please make sure to add widgets to the Search Sidebar in the Appearance - Widgets tab. The empty sidebar won\'t be displayed.', 'flower-shop'), 
			'type' => 'radio_img', 
			'std' => $defaults[$tab]['flower-shop' . '_search_layout'], 
			'choices' => array( 
				esc_html__('Right Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_r.jpg' . '|r_sidebar', 
				esc_html__('Left Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_l.jpg' . '|l_sidebar', 
				esc_html__('Full Width', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/fullwidth.jpg' . '|fullwidth' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_other_layout', 
			'title' => esc_html__('Other Layout Type', 'flower-shop'), 
			'desc' => esc_html__('Layout for pages of non-listed types. Choosing layout with a sidebar please make sure to add widgets to the Sidebar in the Appearance - Widgets tab. The empty sidebar won\'t be displayed.', 'flower-shop'), 
			'type' => 'radio_img', 
			'std' => $defaults[$tab]['flower-shop' . '_other_layout'], 
			'choices' => array( 
				esc_html__('Right Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_r.jpg' . '|r_sidebar', 
				esc_html__('Left Sidebar', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/sidebar_l.jpg' . '|l_sidebar', 
				esc_html__('Full Width', 'flower-shop') . '|' . get_template_directory_uri() . '/framework/admin/inc/img/fullwidth.jpg' . '|fullwidth' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_alignment', 
			'title' => esc_html__('Heading Alignment by Default', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_alignment'], 
			'choices' => array( 
				esc_html__('Left', 'flower-shop') . '|left', 
				esc_html__('Right', 'flower-shop') . '|right', 
				esc_html__('Center', 'flower-shop') . '|center' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_scheme', 
			'title' => esc_html__('Heading Color Scheme by Default', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select_scheme', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_scheme'], 
			'choices' => cmsmasters_color_schemes_list() 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_bg_image_enable', 
			'title' => esc_html__('Heading Background Image Visibility by Default', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_bg_image_enable'] 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_bg_image', 
			'title' => esc_html__('Heading Background Image by Default', 'flower-shop'), 
			'desc' => esc_html__('Choose your custom heading background image by default.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_bg_image'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_bg_repeat', 
			'title' => esc_html__('Heading Background Repeat by Default', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_bg_repeat'], 
			'choices' => array( 
				esc_html__('No Repeat', 'flower-shop') . '|no-repeat', 
				esc_html__('Repeat Horizontally', 'flower-shop') . '|repeat-x', 
				esc_html__('Repeat Vertically', 'flower-shop') . '|repeat-y', 
				esc_html__('Repeat', 'flower-shop') . '|repeat' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_bg_attachment', 
			'title' => esc_html__('Heading Background Attachment by Default', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_bg_attachment'], 
			'choices' => array( 
				esc_html__('Scroll', 'flower-shop') . '|scroll', 
				esc_html__('Fixed', 'flower-shop') . '|fixed' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_bg_size', 
			'title' => esc_html__('Heading Background Size by Default', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_bg_size'], 
			'choices' => array( 
				esc_html__('Auto', 'flower-shop') . '|auto', 
				esc_html__('Cover', 'flower-shop') . '|cover', 
				esc_html__('Contain', 'flower-shop') . '|contain' 
			) 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_bg_color', 
			'title' => esc_html__('Heading Background Color Overlay by Default', 'flower-shop'), 
			'desc' => '',  
			'type' => 'rgba', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_bg_color'] 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_heading_height', 
			'title' => esc_html__('Heading Height by Default', 'flower-shop'), 
			'desc' => esc_html__('pixels', 'flower-shop'), 
			'type' => 'number', 
			'std' => $defaults[$tab]['flower-shop' . '_heading_height'], 
			'min' => '0' 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_breadcrumbs', 
			'title' => esc_html__('Breadcrumbs Visibility by Default', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_breadcrumbs'] 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_bottom_scheme', 
			'title' => esc_html__('Bottom Color Scheme', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select_scheme', 
			'std' => $defaults[$tab]['flower-shop' . '_bottom_scheme'], 
			'choices' => cmsmasters_color_schemes_list() 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_bottom_sidebar', 
			'title' => esc_html__('Bottom Sidebar Visibility by Default', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop') . '<br><br>' . esc_html__('Please make sure to add widgets in the Appearance - Widgets tab. The empty sidebar won\'t be displayed.', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_bottom_sidebar'] 
		);
		
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_bottom_sidebar_layout', 
			'title' => esc_html__('Bottom Sidebar Layout by Default', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select', 
			'std' => $defaults[$tab]['flower-shop' . '_bottom_sidebar_layout'], 
			'choices' => array( 
				'1/1|11', 
				'1/2 + 1/2|1212', 
				'1/3 + 2/3|1323', 
				'2/3 + 1/3|2313', 
				'1/4 + 3/4|1434', 
				'3/4 + 1/4|3414', 
				'1/3 + 1/3 + 1/3|131313', 
				'1/2 + 1/4 + 1/4|121414', 
				'1/4 + 1/2 + 1/4|141214', 
				'1/4 + 1/4 + 1/2|141412', 
				'1/4 + 1/4 + 1/4 + 1/4|14141414' 
			) 
		);
		
		break;
	case 'footer':
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_scheme', 
			'title' => esc_html__('Footer Color Scheme', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select_scheme', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_scheme'], 
			'choices' => cmsmasters_color_schemes_list() 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_type', 
			'title' => esc_html__('Footer Type', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_type'], 
			'choices' => array( 
				esc_html__('Default', 'flower-shop') . '|default', 
				esc_html__('Small', 'flower-shop') . '|small' 
			) 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_additional_content', 
			'title' => esc_html__('Footer Additional Content', 'flower-shop'), 
			'desc' => '', 
			'type' => 'radio', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_additional_content'], 
			'choices' => array( 
				esc_html__('None', 'flower-shop') . '|none', 
				esc_html__('Footer Navigation (will be shown if set in Appearance - Menus tab)', 'flower-shop') . '|nav', 
				esc_html__('Social Icons (will be shown if Cmsmasters Content Composer plugin is active)', 'flower-shop') . '|social', 
				esc_html__('Custom HTML', 'flower-shop') . '|text' 
			) 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_logo', 
			'title' => esc_html__('Footer Logo', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_logo'] 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_logo_url', 
			'title' => esc_html__('Footer Logo', 'flower-shop'), 
			'desc' => esc_html__('Choose your website footer logo image.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_logo_url'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_logo_url_retina', 
			'title' => esc_html__('Footer Logo for Retina', 'flower-shop'), 
			'desc' => esc_html__('Choose your website footer logo image for retina.', 'flower-shop'), 
			'type' => 'upload', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_logo_url_retina'], 
			'frame' => 'select', 
			'multiple' => false 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_nav', 
			'title' => esc_html__('Footer Navigation', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_nav'] 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_social', 
			'title' => esc_html__('Footer Social Icons (will be shown if Cmsmasters Content Composer plugin is active)', 'flower-shop'), 
			'desc' => esc_html__('show', 'flower-shop'), 
			'type' => 'checkbox', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_social'] 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_html', 
			'title' => esc_html__('Footer Custom HTML', 'flower-shop'), 
			'desc' => '<strong>' . esc_html__('HTML tags are allowed!', 'flower-shop') . '</strong>', 
			'type' => 'textarea', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_html'], 
			'class' => '' 
		);
		
		$options[] = array( 
			'section' => 'footer_section', 
			'id' => 'flower-shop' . '_footer_copyright', 
			'title' => esc_html__('Copyright Text', 'flower-shop'), 
			'desc' => '', 
			'type' => 'text', 
			'std' => $defaults[$tab]['flower-shop' . '_footer_copyright'], 
			'class' => '' 
		);
		
		break;
	}
	
	return apply_filters('cmsmasters_options_general_fields_filter', $options, $tab);
}

