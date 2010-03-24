<?php
class IntrafacePublic_Shop_Controller_Product_Variation_Add extends k_Component
{
    function POST()
    {
        $result = $this->context->context->getShop()->getProduct($this->context->context->name);

        if (is_array($result['variations'])) {
            $variation = false;
            foreach ($result['variations'] AS $variation) {
                if ($variation['variation']['identifier'] == $this->context->name) {
                    $this->context->context->getShop()->addProductToBasket(intval($this->context->context->name), intval($variation['variation']['id']));
                    throw new k_http_Redirect($this->url('../../../../basket'));
                }
            }
        }
        throw new Exception('Unable to add product to basket. Not a valid variation');
    }
}
