<?php
/**
 * @HikaShop add free product for Joomla!
 * @version	1.0.2
 * @author	rick@r2h.nl
 * @copyright	(C) 2010-2016 R2H B.V.. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemCustom_freeproduct extends JPlugin
{
	/**
     * onAfterCartProductsLoad
     * @param  object $cart The Hikashop cart object.
     * @access public
     * @return void
     */
	public function onAfterCartProductsLoad(&$cart)
    {
        $product_id         = $this->params->get('free_prod_id', '');
        $quantity           = $this->params->get('free_prod_quantity', '1');
        $product_min_price  = $this->params->get('free_prod_price', '0');
        $usecoupon          = (bool) $this->params->get('usecoupon', false);
        $free_couponcode    = $this->params->get('free_couponcode', 'Empty');
        $productClass       = hikashop_get('class.product');
        $productinfo        = $productClass->get($product_id, ''); // Load free product info.

        // Load free product info to check if product exist.
        if (isset($productinfo)) {
			// Get total from cart.
	        $total = $cart->full_total->prices[0]->price_value_with_tax;

            // Instantiate cart class.
	        $class = hikashop_get('class.cart');

            // Check to use coupon code.
            if ($usecoupon) {
                // Load cart data from Hikashop cart class.
                $cartData = $class->loadCart($cart->cart_id);

                // Check if coupon code from the Hikashop cart class.
                // is equal to selected coupon code in plugin.
                if ($cartData->cart_coupon == $free_couponcode) {
                	// Check if cart total is larger than te plugin minimum price.
                    if ($total > $product_min_price) {
                        // Add the free product.
                        $class->update($product_id, $quantity);
                    } else {
                        // Remove the free product.
                        $class->update($product_id, 0);
                    }
                } else {
                    // Remove the free product.
                    $class->update($product_id, 0);
                }
            } else {
                // Check if cart total is larger than te plugin minimum price.
                if ($total > $product_min_price) {
                    // Add the free product.
                    $class->update($product_id, $quantity);
                } else {
                    // Remove the free product.
                    $class->update($product_id, 0);
                }
            }
        }
    }
}
