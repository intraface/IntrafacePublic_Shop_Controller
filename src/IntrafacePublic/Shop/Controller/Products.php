<?php

class IntrafacePublic_Shop_Controller_Products extends IntrafacePublic_Controller_Pluggable
{
    function getShop()
    {
        return $this->context->getShop();
    }
    
    function getCurrency()
    {
        return $this->context->getCurrency();
    }
    
    function urlToProductId($id)
    {
        return $this->context->url('product/'.$id);
    }
    
    function numberOfProductsPerPage()
    {
        if(is_callable(array($this->context, 'numberOfProductsPerPage'))) {
            
            return $this->context->numberOfProductsPerPage();
        }
        
        return 20;
    }

    function GET()
    {
        $search               = array();
        $search['area']       = 'webshop';
        if($this->numberOfProductsPerPage() == 0) {
            $search['use_paging'] = 'false';
        } else {
            $search['use_paging'] = 'true';
        }
        $search['search']     = '';

        $headline             = $this->__('All products');

        if (!empty($this->GET['start'])) {
            $search['offset'] = (int)$this->GET['start'];
        } elseif (!empty($this->GET['q'])) {
            $search['search'] = $this->GET['q'];
            $headline         = $this->__('Search: ') . $this->GET['q'];
        } elseif (!empty($this->GET['keyword'])) {
            $search['keywords'] = $this->GET['keyword'];
            $headline           = $this->__('Search by keywords');
        } elseif (!empty($this->GET['category'])) {
            $search['category'] = $this->GET['category'];
            $headline           = $this->__('Category');
        }
        
        $search = $this->triggerEvent('preProductsGet', $search);

        if(isset($this->GET['update'])) {
            $this->getShop()->clearProductsCache($search);
        }
        
        $products = $this->getShop()->getProducts($search);
        $products['currency'] = $this->getCurrency();
        
        $this->document->title = $headline;

        $products = $this->triggerEvent('postProductsGet', $products);
        
        if (isset($products['products']) && is_array($products['products']) && count($products['products']) == 0) {
            $products_html = $this->render('IntrafacePublic/Shop/templates/products-no-results-tpl.php', $products);
        } else {

            $products_html = $this->render('IntrafacePublic/Shop/templates/products-tpl.php', $products);
        }
        
        return $this->render('IntrafacePublic/Shop/templates/products-headline-tpl.php') 
            . $this->render('IntrafacePublic/Shop/templates/products-search-tpl.php', array('search' => $this->getSearch()))
            . $products_html . $this->render('IntrafacePublic/Shop/templates/products-paging-tpl.php', $products);

    }

    function getSearch()
    {
        if (isset($this->GET['q'])) {
            return $this->GET['q'];
        }
    }

}
