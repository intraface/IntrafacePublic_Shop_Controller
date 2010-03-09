<?php
class IntrafacePublic_Shop_Controller_Product_Variation extends k_Controller
{
    function getShop()
    {
        return $this->context->getShop();
    }

    function GET()
    {
        $result = $this->getShop()->getProduct($this->context->name);
        
        if ($result['product']['id'] == 0) {
            throw new k_http_Response(404);
        }
        
        if(!is_array($result['variations'])) {
            throw new Exception('The product does not have variations');
        }
        
        $variation = false;
        foreach($result['variations'] AS $tmp_variation) {
            if($tmp_variation['variation']['identifier'] == $this->name) {
                $variation = $tmp_variation;
                break;
            }
        }
        
        $this->document->title = $result['product']['name'];
        if($variation) $this->document->title .= ' - '.$variation['variation']['name'];
        
        $data = $this->context->getProductDataArray($result);
        $data['product_variation_buy'] = $this->render('IntrafacePublic/Shop/templates/product-variation-buy-tpl.php', array_merge($result, array('variation' => $variation)));
        
        $content = $this->render('IntrafacePublic/Shop/templates/product-tpl.php', $data);
        return $content;
    }
    
    function POST()
    {
        if(isset($this->POST['select_variation'])) {
            if(isset($this->POST['attribute']) && is_array($this->POST['attribute']) && count($this->POST['attribute']) > 0) {
                throw new k_http_Redirect($this->url('../'.implode('-',$this->POST['attribute'])));
            }
            
            return $this->GET();
        }
    }

    function forward($name)
    {
        if ($name == 'add') {
            $next = new IntrafacePublic_Shop_Controller_Products_Add($this, $name);
            return $next->handleRequest();
        }
        
        
    }
}
