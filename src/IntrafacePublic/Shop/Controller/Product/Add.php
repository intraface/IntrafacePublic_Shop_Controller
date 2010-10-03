<?php
class IntrafacePublic_Shop_Controller_Product_Add extends IntrafacePublic_Controller_Pluggable
{
    function postForm()
    {
        $result = $this->context->getProduct();

        $this->triggerEvent('preProductAddPost');

        if ($result['product']['has_variation']) { // with variation
            if (!is_array($result['variations'])) {
                throw new Exception('The product does not have any variations defined');
            }

            if (!$this->body('attribute') || !is_array($this->body('attribute')) || count($this->body('attribute')) != count($result['attribute_groups'])) {
                throw new Exception('The correct number of attributes is not set');
            }

            $empty_attribute = array();
            $attribute = $this->body('attribute');
            foreach ($result['attribute_groups'] AS $key => $group) {
                if ($attribute[$key] == '0') {
                    $empty_attribute[] = $group['name'];
                }
            }

            if (count($empty_attribute) > 0) {
                return new k_SeeOther($this->url('../', array('message' => 'You need to select '.strtolower(implode(' and ', $empty_attribute)))));
            }

            $identifier = implode('-', $this->body('attribute'));

            $variation = false;
            foreach ($result['variations'] AS $tmp_variation) {
                if ($tmp_variation['variation']['identifier'] == $identifier) {
                    $variation = $tmp_variation;
                    break;
                }
            }

            if (!$variation) {
                return new k_SeeOther($this->url('../', array('message' => 'The selected variation does not exist. Please select another.')));
            }

            if ($result['product']['stock'] && $variation['stock']['for_sale'] < 1) {
                return new k_SeeOther($this->url('../', array('message' => 'Variation is sold out')));
            }

            $this->context->getShop()->addProductToBasket(intval($result['product']['id']), intval($variation['variation']['id']));
        } else {
            $this->context->getShop()->addProductToBasket(intval($this->context->name()));
        }

        $this->triggerEvent('postProductAddPost');

        return new k_SeeOther($this->url('../../../basket'));
    }
}
