<?php
interface IntrafacePublic_Shop_Controller_Plugin
{
   
   
    /**
     * Used before adding product to basket
     * 
     * @param object $base controller base class
     * @return void;
     */
    // public function preProductAddPost($base);
   
    /**
     * Used after adding af product to the basket
     * 
     * @param object $base controller base class
     * @return void;
     */
    // public function postProductAddPost($base);
   
   
    /**
     * Used before returning basket
     * 
     * @param object $base controller base class
     * @param array $basket basket with items
     * @return array $basket;
     */ 
    // public function preBasketGet($base, $basket);
   
    /**
     * Used on returning basket items to template
     * 
     * @param object $base controller base class
     * @param array $data template data
     * @return array $data;
     */ 
    // public function onBasketGetItems($base, $data);
    
    /**
     * Used on returning basket
     * 
     * @param object $base controller base class
     * @param array $data template data
     * @return array $data;
     */ 
    // public function postBasketGet($base, $data);
   
    /**
     * Used before posting in basket
     * 
     * @param object $base controller base class
     * @return void;
     */ 
    // public function preBasketPost($base);
   
    /**
     * Used after posting to basket
     * 
     * @param object $base controller base class
     * @return void;
     */ 
    // public function postBasketPost($base);    
    
    
    /**
     * Used on placing order to Intraface system
     * 
     * @param object $base controller base class
     * @param array $values order values
     * @return array $values;
     */ 
    // public function onBasketOrderPlaceOrder($base, $values);
    
   
}
