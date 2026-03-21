<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.1.3
 * 
 * Content Composer Quote Shortcode
 * Created by CMSMasters
 * 
 */


extract(shortcode_atts($new_atts, $atts));


if ($content == null || $content == "<br />\n") {
	$this->quotes_atts['quote_content'] = esc_html__('Enter quote text here', 'flower-shop');
} else {
	$this->quotes_atts['quote_content'] = $content;
}

$this->quotes_atts['quote_image'] = 	$image;
$this->quotes_atts['quote_name'] = 		$name;
$this->quotes_atts['quote_subtitle'] = 	$subtitle;
$this->quotes_atts['quote_link'] = 		$link;
$this->quotes_atts['quote_website'] = 	$website;


$quote_out = '';


if ($this->quotes_atts['quote_mode'] == 'grid' && ($this->quotes_atts['quote_counter'] == $this->quotes_atts['column_count'])) {
	$quote_out .= '</div><div class="cmsmasters_quotes_list">';
	
	$this->quotes_atts['quote_counter'] = 0;
}

$this->quotes_atts['quote_counter']++;


$quote_out .= '<div class="cmsmasters_quote' . 
	($this->quotes_atts['quote_mode'] == 'slider' ? ' cmsmasters_owl_slider_item' : '') . 
	(($classes != '') ? ' ' . esc_attr($classes) : '') . 
'">' . "\n" . 

	cmsmasters_composer_ob_load_template('theme-framework/theme-style' . CMSMASTERS_CONTENT_COMPOSER_THEME_STYLE . '/post-type/quote/quote-' . $this->quotes_atts['quote_mode'] . (($this->quotes_atts['quote_mode'] != 'grid') ? '-' . $this->quotes_atts['quote_type'] : '') . '.php', $this->quotes_atts) . 
	
'</div>' . "\n";


echo flower_shop_return_content($quote_out);