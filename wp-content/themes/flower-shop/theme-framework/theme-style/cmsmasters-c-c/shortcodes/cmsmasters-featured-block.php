<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.1.1
 * 
 * Content Composer Featured Block Shortcode
 * Created by CMSMasters
 * 
 */


extract(shortcode_atts($new_atts, $atts));


$unique_id = $shortcode_id;

$shortcode_styles = "\n";


if ( 
	$top_padding != '' || 
	$bottom_padding != '' || 
	$border_radius != '' || 
	$fb_bg_color != '' || 
	$bg_img != '' || 
	$border_width != '' || 
	$border_style != '' || 
	$border_color != ''
) {
	$shortcode_styles .= '#cmsmasters_fb_' . esc_attr($unique_id) . ' { ' . 
		(($top_padding != '') ? "\n\t" . 'padding-top:' . esc_attr($top_padding) . 'px; ' : '') . 
		(($bottom_padding != '') ? "\n\t" . 'padding-bottom:' . esc_attr($bottom_padding) . 'px; ' : '') . 
		(($border_radius != '') ? "\n\t" . '-webkit-border-radius:' . esc_attr($border_radius) . '; ' . "\n\t" . 'border-radius:' . esc_attr($border_radius) . '; ' : '') . 
		(($fb_bg_color != '') ? "\n\t" . cmsmasters_color_css('background-color', $fb_bg_color) : '') . 
		(($border_width != '') ? "\n\t" . 'border-width:' . esc_attr($border_width) . 'px; ' : '') . 
		(($border_style != '') ? "\n\t" . 'border-style:' . esc_attr($border_style) . '; ' : '') . 
		(($border_color != '') ? "\n\t" . cmsmasters_color_css('border-color', $border_color) : '');
	
	
	if ($bg_img != '') {
		$new_bg_img = explode('|', $bg_img);
		
		
		$new_bg_src = wp_get_attachment_image_src($new_bg_img[0], 'full');
		
		
		$shortcode_styles .= "\n\t" . 'background-image: url(' . esc_url($new_bg_src[0]) . '); ' . 
		"\n\t" . 'background-position: ' . esc_attr($bg_position) . '; ' . 
		"\n\t" . 'background-repeat: ' . esc_attr($bg_repeat) . '; ' . 
		"\n\t" . 'background-attachment: ' . esc_attr($bg_attachment) . '; ' . 
		"\n\t" . 'background-size: ' . esc_attr($bg_size) . '; ' . 
		(($bg_attachment == 'fixed' && preg_match('/Safari/', ['HTTP_USER_AGENT'])) ? "\n\t" . 'position: static; ' : '');
	}
	
	
	$shortcode_styles .= "\n" . '} ' . "\n\n";
}


$shortcode_styles .= '#cmsmasters_fb_' . esc_attr($unique_id) . ' .featured_block_inner { ' . 
		"\n\t" . 'width: ' . esc_attr($text_width) . '%; ' . 
		(($text_padding !='') ? "\n\t" . 'padding: ' . esc_attr($text_padding) . '; ' : '') . 
		"\n\t" . 'text-align: ' . esc_attr($text_align) . '; ' . 
		(($text_position == 'center') ? "\n\t" . 'margin:0 auto; ' : "\n\t" . 'float:' . esc_attr($text_position) . '; ') . 
		(($color_overlay != '') ? "\n\t" . cmsmasters_color_css('background-color', $color_overlay) : '') . 
	"\n" . '} ' . "\n\n" . 
	'#cmsmasters_fb_' . esc_attr($unique_id) . ' .featured_block_text { ' . 
		"\n\t" . 'text-align: ' . esc_attr($text_align) . '; ' . 
	"\n" . '} ' . "\n\n" . 
"\n";


if ($resp_vert_pad == 'true') {
	if ($padding_top_laptop != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 1024px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-top: " . esc_attr($padding_top_laptop) . "px;
			}
		}
		";
	}
	
	if ($padding_bottom_laptop != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 1024px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-bottom: " . esc_attr($padding_bottom_laptop) . "px;
			}
		}
		";
	}
	
	if ($padding_top_tablet != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 768px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-top: " . esc_attr($padding_top_tablet) . "px;
			}
		}
		";
	}
	
	if ($padding_bottom_tablet != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 768px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-bottom: " . esc_attr($padding_bottom_tablet) . "px;
			}
		}
		";
	}
	
	if ($padding_top_mobile_h != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 540px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-top: " . esc_attr($padding_top_mobile_h) . "px;
			}
		}
		";
	}
	
	if ($padding_bottom_mobile_h != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 540px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-bottom: " . esc_attr($padding_bottom_mobile_h) . "px;
			}
		}
		";
	}
	
	if ($padding_top_mobile_v != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 320px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-top: " . esc_attr($padding_top_mobile_v) . "px;
			}
		}
		";
	}
	
	if ($padding_bottom_mobile_v != '') {
		$shortcode_styles .= "
		@media only screen and (max-width: 320px) {
			#cmsmasters_fb_" . esc_attr($unique_id) . " {
				padding-bottom: " . esc_attr($padding_bottom_mobile_v) . "px;
			}
		}
		";
	}
}


$out = $this->cmsmasters_generate_front_css($shortcode_styles);


$out .= '<div id="cmsmasters_fb_' . esc_attr($unique_id) . '" class="cmsmasters_featured_block' . 
(($classes != '') ? ' ' . esc_attr($classes) : '') . 
(($hover != '') ? ' cmsmasters_featured_block_hover' : '') . 
'"' . 
(($animation != '') ? ' data-animation="' . esc_attr($animation) . '"' : '') . 
(($animation != '' && $animation_delay != '') ? ' data-delay="' . esc_attr($animation_delay) . '"' : '') . 
'>' . "\n" . 
	($block_link != '' ? '<a href="' . esc_url($block_link) . '" class="featured_block_link"' . ($block_link_target == 'blank' ? ' target="_blank"' : '') . '></a>' : '') . 
	'<div class="featured_block_inner">' . "\n" . 
		'<div class="featured_block_text">' . 
			cmsmasters_divpdel(do_shortcode(wpautop($content))) . 
		'</div>' . "\n" . 
	'</div>' . "\n" . 
'</div>' . "\n";


echo flower_shop_return_content($out);