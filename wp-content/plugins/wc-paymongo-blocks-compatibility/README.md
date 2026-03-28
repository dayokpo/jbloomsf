# PayMongo WooCommerce Blocks Compatibility

This companion plugin adds Checkout Block compatibility for the existing **Payments via PayMongo for WooCommerce** plugin.

## What it does

- Registers PayMongo gateways in WooCommerce Checkout Block.
- Reuses existing gateway IDs so order payment methods remain consistent.
- Adds card payment method tokenization support for the `paymongo` method in block checkout.

## Important limitation

- `paymongo_card_installment` is intentionally disabled for Checkout Block.
- Card installment in the source PayMongo plugin relies on a complex classic-checkout UI flow that is not directly portable to current block checkout without deeper custom React components and backend data flow.

## Requirements

- WooCommerce installed and active.
- Payments via PayMongo for WooCommerce installed and active.
- WooCommerce Blocks / Checkout Block available (bundled with modern WooCommerce).

## Installation

1. Keep your existing `wc-paymongo-payment-gateway` plugin active.
2. Activate this plugin: `PayMongo WooCommerce Blocks Compatibility`.
3. Ensure your checkout page uses the Checkout Block.
4. In WooCommerce payment settings, enable your desired PayMongo methods.

## Files

- `wc-paymongo-blocks-compatibility.php`
- `includes/class-wc-paymongo-blocks-integration.php`
- `assets/js/paymongo-blocks.js`
