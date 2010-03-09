<?php
class IntrafacePublic_Shop_Controller_Product_Add extends IntrafacePublic_Controller_Pluggable
{
    function POST()
    {
        $result = $this->context->context->getShop()->getProduct($this->context->name);
        
        $this->triggerEvent('preProductAddPost');
        
        if ($result['product']['id'] == 0) {
            throw new Exception('Invalid product '.$this->context->name.' to add to basket');
        }

        if ($result['product']['has_variation']) { // with variation
            if (!is_array($result['variations'])) {
                throw new Exception('The product does not have any variations defined');
            }

            if (!isset($this->POST['attribute']) || !is_array($this->POST['attribute']) || count($this->POST['attribute']) != count($result['attribute_groups'])) {
                throw new Exception('The correct number of attributes is not set');
            }
            
            $empty_attribute = array();
            foreach($result['attribute_groups'] AS $key => $group) {
                if($this->POST['attribute'][$key] == '0') {
                    $empty_attribute[] = $group['name'];
                }
            }
            
            if (count($empty_attribute) > 0) {
                throw new k_http_Redirect($this->url('../', array('message' => 'You need to select '.strtolower(implode(' and ', $empty_attribute)))));
            }

            $identifier = implode('-', $this->POST['attribute']);

            $variation = false;
            foreach ($result['variations'] AS $tmp_variation) {
                if ($tmp_variation['variation']['identifier'] == $identifier) {
                    $variation = $tmp_variation;
                    break;
                }
            }

            if (!$variation) {
                throw new k_http_Redirect($this->url('../', array('message' => 'The selected variation does not exist. Please select another.')));
            }

            if ($result['product']['stock'] && $variation['stock']['for_sale'] < 1) {
                throw new k_http_Redirect($this->url('../', array('message' => 'Variation is sold out')));
            }

            $this->context->getShop()->addProductToBasket(intval($result['product']['id']), intval($variation['variation']['id']));
        } else {
            $this->context->getShop()->addProductToBasket(intval($this->context->name));
        }
        
        $this->triggerEvent('postProductAddPost');
        
        throw new k_http_Redirect($this->url('../../../basket'));
    }
}
