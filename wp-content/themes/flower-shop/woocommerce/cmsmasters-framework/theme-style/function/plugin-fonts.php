<?php
/**
 * @package 	WordPress
 * @subpackage 	Flower Shop
 * @version 	1.0.0
 * 
 * WooCommerce Fonts Rules
 * Created by CMSMasters
 * 
 */


function flower_shop_woocommerce_fonts($custom_css) {
	$cmsmasters_option = flower_shop_get_global_options();
	
	
	$custom_css .= "
/***************** Start WooCommerce Font Styles ******************/

	/* Start Navigation Title Font */
	.cmsmasters_dynamic_cart .cmsmasters_dynamic_cart_button span {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_nav_dropdown_font_google_font']) . $cmsmasters_option['flower-shop' . '_nav_dropdown_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_nav_dropdown_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_nav_dropdown_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_nav_dropdown_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_nav_dropdown_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_nav_dropdown_font_text_transform'] . ";
	}
	
	.cmsmasters_dynamic_cart .cmsmasters_dynamic_cart_button span {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_nav_dropdown_font_font_size'] - 1) . "px;
		font-weight:normal;
	}
	/* Finish Navigation Title Font */
	
	/* Start Content Font */
	.shop_table.woocommerce-checkout-review-order-table .product-name dl, 
	.shop_table.order_details .product-name dl {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_content_font_google_font']) . $cmsmasters_option['flower-shop' . '_content_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_content_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_content_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_content_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_content_font_font_style'] . ";
	}
	
	.shop_table.woocommerce-checkout-review-order-table .product-name dl, 
	.shop_table.order_details .product-name dl {
		text-transform:none;
	}
	/* Finish Content Font */
	
	
	/* Start Link Font */
	/* Finish Link Font */
	
	
	/* Start H2 Font */
	.cmsmasters_single_product .product_title {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_h2_font_google_font']) . $cmsmasters_option['flower-shop' . '_h2_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_h2_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_h2_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_h2_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_h2_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_h2_font_text_transform'] . ";
		text-decoration:" . $cmsmasters_option['flower-shop' . '_h2_font_text_decoration'] . ";
		letter-spacing:0.14em;
	}
	/* Finish H2 Font */
	
	
	/* Start H3 Font */
	.cart_totals > h2,
	div.products > h2,
	.shop_table.order_details tfoot tr:last-child th, 
	.shop_table.order_details tfoot tr:last-child td, 
	.cmsmasters_product .price, 
	.cmsmasters_product .button_to_cart,
	ul.order_details, 
	.cmsmasters_product .price del, 
	.cmsmasters_single_product .price {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_h3_font_google_font']) . $cmsmasters_option['flower-shop' . '_h3_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_h3_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_h3_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_h3_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_h3_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_h3_font_text_transform'] . ";
		text-decoration:" . $cmsmasters_option['flower-shop' . '_h3_font_text_decoration'] . ";
	}
	
	.cmsmasters_product .price {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_h3_font_font_size'] + 4) . "px;
	}
	
	.cmsmasters_single_product .price {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_h3_font_font_size'] + 6) . "px;
		letter-spacing:0;
	}
	
	ul.order_details {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_h3_font_font_size'] - 4) . "px;
	}
	
	.cmsmasters_product .price del {
		text-decoration:line-through;
	}
	/* Finish H3 Font */
	
	
	/* Start H4 Font */
	.shop_table.order_details tfoot tr th {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_h4_font_google_font']) . $cmsmasters_option['flower-shop' . '_h4_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_h4_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_h4_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_h4_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_h4_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_h4_font_text_transform'] . ";
		text-decoration:" . $cmsmasters_option['flower-shop' . '_h4_font_text_decoration'] . ";
	}
	/* Finish H4 Font */
	
	
	/* Start H5 Font */
	.woocommerce-loop-category__title, 
	.widget_layered_nav ul li, 
	.widget_layered_nav ul li a, 
	.widget_layered_nav_filters ul li, 
	.widget_layered_nav_filters ul li a, 
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .cart_list a, 
	.widget_shopping_cart .cart_list a, 
	.widget > .product_list_widget a {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_h5_font_google_font']) . $cmsmasters_option['flower-shop' . '_h5_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_h5_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_h5_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_h5_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_h5_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_h5_font_text_transform'] . ";
		text-decoration:" . $cmsmasters_option['flower-shop' . '_h5_font_text_decoration'] . ";
	}
	
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .cart_list a, 
	.widget_shopping_cart .cart_list a, 
	.widget > .product_list_widget a {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_h5_font_font_size'] - 3) . "px;
		line-height:" . ((int) $cmsmasters_option['flower-shop' . '_h5_font_line_height'] - 6) . "px;
	}
	

	/* Finish H5 Font */
	
	
	/* Start H6 Font */
	.shop_table.woocommerce-checkout-review-order-table .cart-subtotal td .amount,
	.shop_table.woocommerce-checkout-review-order-table .order-total td, 
	.shop_table.woocommerce-checkout-review-order-table .order-total th, 
	.shop_table.woocommerce-checkout-review-order-table .cart-subtotal th, 
	.shop_table.woocommerce-checkout-review-order-table th.product-name, 
	.cart_totals table .cart-subtotal th, 
	.cart_totals table .cart-subtotal .amount, 
	.cart_totals table .order-total th, 
	.cart_totals td strong > .amount,
	.cart_totals table .order-total .amount,
	.shop_table .product-name a, 
	.shop_table thead th, 
	.cmsmasters_woo_wrap_result .woocommerce-result-count, 
	.shop_table.order_details tfoot tr td, 
	ul.order_details strong, 
	.widget > .product_list_widget .reviewer,
	.widget > .product_list_widget .amount, 
	.widget_product_categories ul li, 
	.widget_product_categories ul li a, 
	.widget_price_filter .price_slider_amount .price_label, 
	.widget_shopping_cart .total, 
	.widget_shopping_cart .total strong, 
	.widget_shopping_cart .cart_list .quantity, 
	.form-row label,
	.form-row label a,
	.shop_table td > .amount, 
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .total, 
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .total strong, 
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .cart_list .quantity, 
	.shop_attributes td,
	.cmsmasters_product .cmsmasters_product_cat, 
	.cmsmasters_product .cmsmasters_product_cat a, 
	.cmsmasters_single_product .product_meta, 
	.cmsmasters_single_product .product_meta a {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_h6_font_google_font']) . $cmsmasters_option['flower-shop' . '_h6_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_h6_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_h6_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_h6_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_h6_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_h6_font_text_transform'] . ";
		text-decoration:" . $cmsmasters_option['flower-shop' . '_h6_font_text_decoration'] . ";
	}
	
	.widget > .product_list_widget .amount, 
	.widget_shopping_cart .cart_list .quantity,
	.form-row label a,
	.form-row label {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_h6_font_font_size'] - 1) . "px;
	}
	
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .cart_list .quantity {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_h6_font_font_size'] - 3) . "px;
	}
	/* Finish H6 Font */
	
	
	/* Start Button Font */
	.widget_price_filter .price_slider_amount .button,
	.widget_shopping_cart .buttons .button,
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .buttons .button {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_button_font_google_font']) . $cmsmasters_option['flower-shop' . '_button_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_button_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_button_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_button_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_button_font_font_style'] . ";
		text-transform:" . $cmsmasters_option['flower-shop' . '_button_font_text_transform'] . ";
	}
	
	.widget_price_filter .price_slider_amount .button,
	.widget_shopping_cart .buttons .button,
	.cmsmasters_dynamic_cart .widget_shopping_cart_content .buttons .button {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_button_font_font_size'] - 1) . "px;
		line-height:" . ((int) $cmsmasters_option['flower-shop' . '_button_font_line_height'] - 6) . "px;
	}
	/* Finish Button Font */
	
	
	/* Start Text Fields Font */
	.select2-dropdown {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_input_font_google_font']) . $cmsmasters_option['flower-shop' . '_input_font_system_font'] . ";
		font-size:" . $cmsmasters_option['flower-shop' . '_input_font_font_size'] . "px;
		line-height:" . $cmsmasters_option['flower-shop' . '_input_font_line_height'] . "px;
		font-weight:" . $cmsmasters_option['flower-shop' . '_input_font_font_weight'] . ";
		font-style:" . $cmsmasters_option['flower-shop' . '_input_font_font_style'] . ";
	}
	/* Finish Text Fields Font */
	
	
	/* Start Small Text Font */
	.onsale, 
	.out-of-stock, 
	.stock {
		font-family:" . flower_shop_get_google_font($cmsmasters_option['flower-shop' . '_small_font_google_font']) . $cmsmasters_option['flower-shop' . '_small_font_system_font'] . ";
			font-size:" . $cmsmasters_option['flower-shop' . '_small_font_font_size'] . "px;
			line-height:" . $cmsmasters_option['flower-shop' . '_small_font_line_height'] . "px;
			font-weight:" . $cmsmasters_option['flower-shop' . '_small_font_font_weight'] . ";
			font-style:" . $cmsmasters_option['flower-shop' . '_small_font_font_style'] . ";
			text-transform:" . $cmsmasters_option['flower-shop' . '_small_font_text_transform'] . ";
	}
	
	.onsale, 
	.out-of-stock, 
	.stock {
		font-size:" . ((int) $cmsmasters_option['flower-shop' . '_small_font_font_size'] - 1) . "px;
		line-height:" . ((int) $cmsmasters_option['flower-shop' . '_small_font_line_height'] + 2) . "px;
	}
	/* Finish Small Text Font */

/***************** Finish WooCommerce Font Styles ******************/

";
	
	
	return $custom_css;
}

add_filter('flower_shop_theme_fonts_filter', 'flower_shop_woocommerce_fonts');

