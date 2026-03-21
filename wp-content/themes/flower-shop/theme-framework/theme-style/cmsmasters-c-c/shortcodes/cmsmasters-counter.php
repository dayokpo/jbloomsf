<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.0.0
 * 
 * Content Composer Counter Shortcode
 * Created by CMSMasters
 * 
 */


extract(shortcode_atts($new_atts, $atts));


$unique_id = $shortcode_id;


$this->counters_atts['style_counters'] .= "\n" . '#cmsmasters_counter_' . esc_attr($unique_id) . ' .cmsmasters_counter_inner:before { ' . 
	(($icon_color != '') ? "\n\t" . cmsmasters_color_css('color', $icon_color) : '') . 
	(($icon_bg_color != '') ? "\n\t" . cmsmasters_color_css('background-color', $icon_bg_color) : '') . 
	(($icon_bd_color != '') ? "\n\t" . cmsmasters_color_css('border-color', $icon_bd_color) : '') . 
"\n" . '} ' . "\n\n";


if ($icon_type == 'image' && $image != '') {
	$image_id = explode('|', $image);
	
	
	$image_url_array = wp_get_attachment_image_src($image_id[0], 'full');
	
	
	if (is_numeric($image_id[0]) && is_array($image_url_array)) {
		$image_url = $image_url_array[0];
	} else if ($image_id[0] != '') {
		$image_url = $image_id[0];
	} else {
		$image_url = $image_id[1];
	}
	
	
	$this->counters_atts['style_counters'] .= '#cmsmasters_counter_' . esc_attr($unique_id) . ' .cmsmasters_counter_inner:before { ' . 
		"\n\t" . "content:''; " . 
		"\n\t" . 'background-image:url(' . esc_url($image_url) . '); ' . 
	"\n" . '} ' . "\n";
}


if ($color != '') {
	$this->counters_atts['style_counters'] .= '#cmsmasters_counter_' . esc_attr($unique_id) . ' .cmsmasters_counter_counter_wrap { ' . 
		"\n\t" . cmsmasters_color_css('color', $color) . 
	"\n" . '} ' . "\n";
}


$out = '<div class="cmsmasters_counter_wrap' . esc_attr($this->counters_atts['counters_count']) . '">' . "\n" . 
	'<div id="cmsmasters_counter_' . esc_attr($unique_id) . '" class="cmsmasters_counter' . 
	(($classes != '') ? ' ' . esc_attr($classes) : '') . 
	(($icon_type == 'icon' && $icon != '') ? ' counter_has_icon' : '') . 
	(($icon_type == 'image' && $image != '') ? ' counter_has_image' : '') . 
	'" data-percent="' . esc_attr($value) . '">' . "\n" . 
		'<div class="cmsmasters_counter_inner' . 
		(($icon != '') ? ' ' . esc_attr($icon) : '') . 
		'">' . "\n" . 
			'<span class="cmsmasters_counter_counter_wrap">' . "\n" . 
				'<span class="cmsmasters_counter_prefix">' . esc_html($value_prefix) . '</span>' . 
				'<span class="cmsmasters_counter_counter">0</span>' . 
				'<span class="cmsmasters_counter_suffix">' . esc_html($value_suffix) . '</span>' . "\n" . 
			'</span>' . "\n" . 
			(($content != '') ? '<span class="cmsmasters_counter_title">' . esc_html($content) . '</span>' . "\n" : '') . 
		'</div>' . "\n" . 
		(($subtitle != '') ? '<span class="cmsmasters_counter_subtitle">' . esc_html($subtitle) . '</span>' . "\n" : '') . 
	'</div>' . "\n" . 
'</div>';


echo flower_shop_return_content($out);