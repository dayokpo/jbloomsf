<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.0.5
 * 
 * Admin Panel Fonts Options
 * Created by CMSMasters
 * 
 */


function flower_shop_options_font_tabs() {
	$tabs = array();
	
	$tabs['content'] = esc_attr__('Content', 'flower-shop');
	$tabs['link'] = esc_attr__('Links', 'flower-shop');
	$tabs['nav'] = esc_attr__('Navigation', 'flower-shop');
	$tabs['heading'] = esc_attr__('Heading', 'flower-shop');
	$tabs['other'] = esc_attr__('Other', 'flower-shop');
	$tabs['google'] = esc_attr__('Google Fonts', 'flower-shop');
	
	return apply_filters('cmsmasters_options_font_tabs_filter', $tabs);
}


function flower_shop_options_font_sections() {
	$tab = flower_shop_get_the_tab();
	
	switch ($tab) {
	case 'content':
		$sections = array();
		
		$sections['content_section'] = esc_html__('Content Font Options', 'flower-shop');
		
		break;
	case 'link':
		$sections = array();
		
		$sections['link_section'] = esc_html__('Links Font Options', 'flower-shop');
		
		break;
	case 'nav':
		$sections = array();
		
		$sections['nav_section'] = esc_html__('Navigation Font Options', 'flower-shop');
		
		break;
	case 'heading':
		$sections = array();
		
		$sections['heading_section'] = esc_html__('Headings Font Options', 'flower-shop');
		
		break;
	case 'other':
		$sections = array();
		
		$sections['other_section'] = esc_html__('Other Fonts Options', 'flower-shop');
		
		break;
	case 'google':
		$sections = array();
		
		$sections['google_section'] = esc_html__('Serving Google Fonts from CDN', 'flower-shop');
		
		break;
	default:
		$sections = array();
		
		
		break;
	}
	
	return apply_filters('cmsmasters_options_font_sections_filter', $sections, $tab);
} 


function flower_shop_options_font_fields($set_tab = false) {
	if ($set_tab) {
		$tab = $set_tab;
	} else {
		$tab = flower_shop_get_the_tab();
	}
	
	
	$options = array();
	
	
	$defaults = flower_shop_settings_font_defaults();
	
	
	switch ($tab) {
	case 'content':
		$options[] = array( 
			'section' => 'content_section', 
			'id' => 'flower-shop' . '_content_font', 
			'title' => esc_html__('Main Content Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_content_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style' 
			) 
		);
		
		break;
	case 'link':
		$options[] = array( 
			'section' => 'link_section', 
			'id' => 'flower-shop' . '_link_font', 
			'title' => esc_html__('Links Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_link_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		$options[] = array( 
			'section' => 'link_section', 
			'id' => 'flower-shop' . '_link_hover_decoration', 
			'title' => esc_html__('Links Hover Text Decoration', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select_scheme', 
			'std' => $defaults[$tab]['flower-shop' . '_link_hover_decoration'], 
			'choices' => flower_shop_text_decoration_list() 
		);
		
		break;
	case 'nav':
		$options[] = array( 
			'section' => 'nav_section', 
			'id' => 'flower-shop' . '_nav_title_font', 
			'title' => esc_html__('Navigation Title Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_nav_title_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform' 
			) 
		);
		
		$options[] = array( 
			'section' => 'nav_section', 
			'id' => 'flower-shop' . '_nav_dropdown_font', 
			'title' => esc_html__('Navigation Dropdown Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_nav_dropdown_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform' 
			) 
		);
		
		break;
	case 'heading':
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => 'flower-shop' . '_h1_font', 
			'title' => esc_html__('H1 Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_h1_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => 'flower-shop' . '_h2_font', 
			'title' => esc_html__('H2 Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_h2_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => 'flower-shop' . '_h3_font', 
			'title' => esc_html__('H3 Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_h3_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => 'flower-shop' . '_h4_font', 
			'title' => esc_html__('H4 Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_h4_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => 'flower-shop' . '_h5_font', 
			'title' => esc_html__('H5 Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_h5_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		$options[] = array( 
			'section' => 'heading_section', 
			'id' => 'flower-shop' . '_h6_font', 
			'title' => esc_html__('H6 Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_h6_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform', 
				'text_decoration' 
			) 
		);
		
		break;
	case 'other':
		$options[] = array( 
			'section' => 'other_section', 
			'id' => 'flower-shop' . '_button_font', 
			'title' => esc_html__('Button Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_button_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform' 
			) 
		);
		
		$options[] = array( 
			'section' => 'other_section', 
			'id' => 'flower-shop' . '_small_font', 
			'title' => esc_html__('Small Tag Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_small_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style', 
				'text_transform' 
			) 
		);
		
		$options[] = array( 
			'section' => 'other_section', 
			'id' => 'flower-shop' . '_input_font', 
			'title' => esc_html__('Text Fields Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_input_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style' 
			) 
		);
		
		$options[] = array( 
			'section' => 'other_section', 
			'id' => 'flower-shop' . '_quote_font', 
			'title' => esc_html__('Blockquote Font', 'flower-shop'), 
			'desc' => '', 
			'type' => 'typorgaphy', 
			'std' => $defaults[$tab]['flower-shop' . '_quote_font'], 
			'choices' => array( 
				'system_font', 
				'google_font', 
				'font_size', 
				'line_height', 
				'font_weight', 
				'font_style' 
			) 
		);
		
		break;
	case 'google':
		$options[] = array( 
			'section' => 'google_section', 
			'id' => 'flower-shop' . '_google_web_fonts', 
			'title' => esc_html__('Google Fonts', 'flower-shop'), 
			'desc' => '', 
			'type' => 'google_web_fonts', 
			'std' => $defaults[$tab]['flower-shop' . '_google_web_fonts'] 
		);
		
		$options[] = array( 
			'section' => 'google_section', 
			'id' => 'flower-shop' . '_google_web_fonts_subset', 
			'title' => esc_html__('Google Fonts Subset', 'flower-shop'), 
			'desc' => '', 
			'type' => 'select_multiple', 
			'std' => '', 
			'choices' => array( 
				esc_html__('Latin Extended', 'flower-shop') . '|' . 'latin-ext', 
				esc_html__('Arabic', 'flower-shop') . '|' . 'arabic', 
				esc_html__('Cyrillic', 'flower-shop') . '|' . 'cyrillic', 
				esc_html__('Cyrillic Extended', 'flower-shop') . '|' . 'cyrillic-ext', 
				esc_html__('Greek', 'flower-shop') . '|' . 'greek', 
				esc_html__('Greek Extended', 'flower-shop') . '|' . 'greek-ext', 
				esc_html__('Vietnamese', 'flower-shop') . '|' . 'vietnamese', 
				esc_html__('Japanese', 'flower-shop') . '|' . 'japanese', 
				esc_html__('Korean', 'flower-shop') . '|' . 'korean', 
				esc_html__('Thai', 'flower-shop') . '|' . 'thai', 
				esc_html__('Bengali', 'flower-shop') . '|' . 'bengali', 
				esc_html__('Devanagari', 'flower-shop') . '|' . 'devanagari', 
				esc_html__('Gujarati', 'flower-shop') . '|' . 'gujarati', 
				esc_html__('Gurmukhi', 'flower-shop') . '|' . 'gurmukhi', 
				esc_html__('Hebrew', 'flower-shop') . '|' . 'hebrew', 
				esc_html__('Kannada', 'flower-shop') . '|' . 'kannada', 
				esc_html__('Khmer', 'flower-shop') . '|' . 'khmer', 
				esc_html__('Malayalam', 'flower-shop') . '|' . 'malayalam', 
				esc_html__('Myanmar', 'flower-shop') . '|' . 'myanmar', 
				esc_html__('Oriya', 'flower-shop') . '|' . 'oriya', 
				esc_html__('Sinhala', 'flower-shop') . '|' . 'sinhala', 
				esc_html__('Tamil', 'flower-shop') . '|' . 'tamil', 
				esc_html__('Telugu', 'flower-shop') . '|' . 'telugu' 
			) 
		);
		
		break;
	}
	
	return apply_filters('cmsmasters_options_font_fields_filter', $options, $tab);	
}

