<?php
class IntrafacePublic_Shop_Controller_Keyword_Show extends k_Controller
{
    
    function getCurrency()
    {
        return $this->context->getCurrency();
    }
    
    public function getShop()
    {
        return $this->context->getShop();
    }
    
    function urlToProductId($id)
    {
        return $this->context->urlToProductId($id);
    }
    
    function GET()
    {
        $this->document->title = $this->__('Keyword');
        
        if (!empty($this->GET['start'])) {
            $offset = (int)$this->GET['start'];
        } else {
            $offset = 0;
        }
        
        if(isset($this->GET['update'])) {
            $this->getShop()->clearProductsWithKeywordIdCache($this->name, 20, $offset);
        }
        
        $products = $this->getShop()->getProductsWithKeywordId($this->name, 20, $offset);
        $products['currency'] = $this->getCurrency();
        
        return $this->render('IntrafacePublic/Shop/templates/keyword-products-tpl.php', $products)
          . $this->render('IntrafacePublic/Shop/templates/products-paging-tpl.php', $products);
    }
}