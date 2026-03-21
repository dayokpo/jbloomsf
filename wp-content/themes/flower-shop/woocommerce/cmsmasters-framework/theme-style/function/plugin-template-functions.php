<?php 
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version		1.1.3
 * 
 * WooCommerce Template Functions
 * Created by CMSMasters
 * 
 */


/* Dynamic Cart */
function flower_shop_woocommerce_cart_dropdown($cmsmasters_option) {
	global $woocommerce;
	
	$cart_count = $woocommerce->cart->get_cart_contents_count();
	
	if (
		!isset($cmsmasters_option['flower-shop' . '_woocommerce_cart_dropdown']) ||
		(
			isset($cmsmasters_option['flower-shop' . '_woocommerce_cart_dropdown']) &&
			!$cmsmasters_option['flower-shop' . '_woocommerce_cart_dropdown']
		)
	) {
		echo '<div class="cmsmasters_dynamic_cart_wrap">' . 
			'<div class="cmsmasters_dynamic_cart">' .  
				'<a href="' . esc_url(wc_get_cart_url()) . '" class="cmsmasters_dynamic_cart_button cmsmasters_theme_icon_basket"><h6>' . esc_html($cart_count) . '</h6></a>' . 
				'<div class="widget_shopping_cart_content"></div>' . 
			'</div>' . 
		'</div>';
	}
}

add_action('cmsmasters_after_search', 'flower_shop_woocommerce_cart_dropdown');


/* Add to Cart Button */
function flower_shop_woocommerce_add_to_cart_button() {
	global $woocommerce, 
		$product;
	
	
	if ( 
		$product->is_purchasable() && 
		$product->is_type( 'simple' ) && 
		$product->is_in_stock() 
	) {
		echo '<div class="button_to_cart_wrap">' . 
			'<a href="' . esc_url($product->add_to_cart_url()) . '" data-product_id="' . esc_attr($product->get_id()) . '" data-product_sku="' . esc_attr($product->get_sku()) . '" class="button_to_cart add_to_cart_button cmsmasters_add_to_cart_button ajax_add_to_cart product_type_simple" title="' . esc_attr__('Add to Cart', 'flower-shop') . '">' . 
				'<span>' . esc_html__('Add to Cart', 'flower-shop') . '</span>' . 
			'</a>' . 
			'<a href="' . esc_url(wc_get_cart_url()) . '" class="button_to_cart added_to_cart wc-forward" title="' . esc_attr__('View Cart', 'flower-shop') . '">' . 
				'<span>' . esc_html__('View Cart', 'flower-shop') . '</span>' . 
			'</a>' . 
		'</div>';
	}
}


/* Rating */
function flower_shop_woocommerce_rating($icon_trans = '', $icon_color = '', $in_review = false, $comment_id = '', $show = true, $overall = false) {
	global $product;
	
	
	if (get_option( 'woocommerce_enable_review_rating') === 'no') {
		return;
	}
	
	
	$rating = (($in_review) ? intval(get_comment_meta($comment_id, 'rating', true)) : ($product->get_average_rating() ? $product->get_average_rating() : '0'));
	
	$itemtype = $in_review ? 'Rating' : 'AggregateRating';

	$itemprop = $in_review ? 'reviewRating' : 'aggregateRating';

	$reviewRating = 'reviewRating';
	
	
	$out = "
<div class=\"cmsmasters_star_rating\" title=\"" . sprintf(esc_html__('Rated %s out of 5', 'flower-shop'), $rating) . "\">
<div class=\"cmsmasters_star_trans_wrap\">
	<span class=\"{$icon_trans} cmsmasters_star\"></span>
	<span class=\"{$icon_trans} cmsmasters_star\"></span>
	<span class=\"{$icon_trans} cmsmasters_star\"></span>
	<span class=\"{$icon_trans} cmsmasters_star\"></span>
	<span class=\"{$icon_trans} cmsmasters_star\"></span>
</div>
<div class=\"cmsmasters_star_color_wrap\" data-width=\"width:" . (($rating / 5) * 100) . "%\">
	<div class=\"cmsmasters_star_color_inner\">
		<span class=\"{$icon_color} cmsmasters_star\"></span>
		<span class=\"{$icon_color} cmsmasters_star\"></span>
		<span class=\"{$icon_color} cmsmasters_star\"></span>
		<span class=\"{$icon_color} cmsmasters_star\"></span>
		<span class=\"{$icon_color} cmsmasters_star\"></span>
	</div>
</div>
<span class=\"rating dn\" " . (($overall == true) ? "itemprop=\"{$itemprop}\"" : "itemprop=\"reviewRating\"") . " itemscope itemtype=\"//schema.org/AggregateRating\">
<strong>
	<span itemprop=\"ratingValue\">" . esc_html('out of 5', 'flower-shop') . "</span> " 
	. (($rating == true) ? "<span itemprop=\"ratingCount\">" . esc_html(round($rating)) . "</span>" : "<span itemprop=\"ratingCount\">1</span>") . "
</strong>
</span>
</div>
";
	
	
	if ($show) {
		echo flower_shop_return_content($out);
	} else {
		return $out;
	}
}


/* Price Format */
function flower_shop_woocommerce_price_format($format, $currency_pos) {
	$format = '%2$s<span>%1$s</span>';

	switch ( $currency_pos ) {
		case 'left' :
			$format = '<span>%1$s</span>%2$s';
		break;
		case 'right' :
			$format = '%2$s<span>%1$s</span>';
		break;
		case 'left_space' :
			$format = '<span>%1$s&nbsp;</span>%2$s';
		break;
		case 'right_space' :
			$format = '%2$s<span>&nbsp;%1$s</span>';
		break;
	}
	
	return $format;
}
 
add_action('woocommerce_price_format', 'flower_shop_woocommerce_price_format', 1, 2);


/* Woocommerce Onsale Filter */
add_filter('woocommerce_sale_flash', 'flower_shop_woocommerce_change_on_sale');

function flower_shop_woocommerce_change_on_sale() {
	return '<span class="onsale"><span>' . esc_html__('Sale!', 'flower-shop') . '</span></span>';
}


/* Woocommerce Dynamic cart count update after ajax */
function flower_shop_woocommerce_header_add_to_cart_count($dynamic_count) {
	global $woocommerce;
	
	ob_start();
	
	?>
	<h6><?php echo flower_shop_return_content($woocommerce->cart->cart_contents_count); ?></h6>
	<?php
	
	$dynamic_count['.cmsmasters_dynamic_cart_button h6'] = ob_get_clean();
	
	return $dynamic_count;
}

add_filter('woocommerce_add_to_cart_fragments', 'flower_shop_woocommerce_header_add_to_cart_count');

