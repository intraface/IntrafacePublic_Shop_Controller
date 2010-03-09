<?php
class IntrafacePublic_Shop_Controller_Product_Show extends IntrafacePublic_Controller_Pluggable
{
    private $product;
    
    public function getShop()
    {
        return $this->context->getShop();
    }
    
    public function getCurrency()
    {
        return $this->context->getCurrency();
    }
    
    private function getProduct() 
    {
        if(isset($this->product)) {
            return $this->product;
        }
        
        if(isset($this->GET['update'])) {
            $this->getShop()->clearProductCache($this->name);
        }
        
        return $this->product = $this->getShop()->getProduct($this->name);
    }
    
    public function getProductDataArray() 
    {
        
        $result = $this->getProduct();
        $result['currency'] = $this->getCurrency();
        
        $data = $result;
        
        $data['pictures'] = $this->render('IntrafacePublic/Shop/templates/product-pictures-tpl.php', $result);
        if(isset($this->GET['message']) && $this->GET['message'] != '') {
            $data['message'] = $this->render('IntrafacePublic/Shop/templates/product-message-tpl.php', array('message' => $this->GET['message']));
        }
        
        if($result['product']['has_variation']) {
            $data['product_variation_buy'] = $this->render('IntrafacePublic/Shop/templates/product-variation-buy-tpl.php', $result);
        } else {
            $data['product_buy'] = $this->render('IntrafacePublic/Shop/templates/product-buy-tpl.php', $result);
        }
        
        if(isset($this->GET['update'])) {
            $this->getShop()->clearRelatedProductsCache($this->name);
        }
        
        $data['related_products'] = $this->render('IntrafacePublic/Shop/templates/product-related-products-tpl.php', array('related_products' => $this->getShop()->getRelatedProducts($this->name), 'currency' => $this->getCurrency())); 
        $data['breadcrumptrail'] = $this->render('IntrafacePublic/Shop/templates/product-breadcrumptrail-tpl.php', array('breadcrumptrail' => $this->getBreadcrumpTrail()));
        
        $data = $this->triggerEvent('postProductGet', $data);
        
        return $data;
    }
    
    private function getBreadcrumpTrail()
    {
        $breadcrump = array();
        if(is_callable(array($this->context, 'getBreadcrumpTrail'))) {
             $breadcrump = $this->context->getBreadcrumpTrail();
        }
        
        $product = $this->getProduct();
        $breadcrump[] = array('name' => $product['product']['name'], 'url' => $this->url());
        return $breadcrump;
    }  

    public function GET()
    {
        $result = $this->getProduct();
        if ($result['product']['id'] == 0) {
            throw new k_http_Response(404);
        }
        
        $this->document->title = $result['product']['name'];
        return $this->render('IntrafacePublic/Shop/templates/product-tpl.php', $this->getProductDataArray($result));
    }
    
    public function POST()
    {
        if(isset($this->POST['select_variation'])) {
            
            if(isset($this->POST['attribute']) && is_array($this->POST['attribute']) && count($this->POST['attribute']) > 0) {
                throw new k_http_Redirect($this->url('./'.implode('-', $this->POST['attribute'])));
            }
            
            return $this->GET();
            
        }
    }

    public function forward($name)
    {
        if ($name == 'add') {
            $next = new IntrafacePublic_Shop_Controller_Product_Add($this, $name);
            return $next->handleRequest();
        }
        else {
            $next = new IntrafacePublic_Shop_Controller_Product_Variation($this, $name);
            return $next->handleRequest();
        }
    }
}
