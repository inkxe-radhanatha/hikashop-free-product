<?php
/**
 * @HikaShop add free product for Joomla!
 * @version	1.0.0
 * @author	rick@r2h.nl
 * @copyright	(C) 2010-2016 R2H B.V.. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemCustom_freeproduct extends JPlugin{

	function plgSystemCustom_freeproduct(&$subject, $config){
		parent::__construct($subject, $config);
	}

	// Call a trigger, in this example: onBeforeOrderCreate
	function onAfterCartProductsLoad(&$cart) {

		// Actions to do when my trigger is called
		$number_of_items    = 0;
		$total              = 0;
        $product_id         = $this->params->get('free_prod_id','');
        $quantity           = $this->params->get('free_prod_quantity','1');
        $product_min_price  = $this->params->get('free_prod_price','0');

        /*
        // Debug info
        echo '<pre>';
        print_r($cart->products);
        echo '</pre>';
        */

        $productClass       = hikashop_get('class.product');
        $productinfo        = $productClass->get($product_id); // Load free product info to check if product exist

        if (isset($productinfo))
        {
            foreach($cart->products as $product)
            {
                $number_of_items+=$product->cart_product_quantity;

                If (isset($product->prices[0]->price_value_with_tax))
                {
                    $total = $total + $product->prices[0]->price_value_with_tax;
                }
            }

            /*
            // Debug info
            echo $number_of_items . ' - ' . $total . '<br>';
            */

            if ($total > $product_min_price)
            {
                $class = hikashop_get('class.cart');
                $class->update($product_id, $quantity);
            }
            else
            {
                $class = hikashop_get('class.cart');
                $class->update($product_id, 0);
            }
        }
	}
}