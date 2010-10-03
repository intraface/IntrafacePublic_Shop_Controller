<?php
class IntrafacePublic_Shop_Controller_Product extends IntrafacePublic_Controller_Pluggable
{
    protected $product;

    function map($name)
    {
        return 'IntrafacePublic_Shop_Controller_Product_Show';
    }

    public function renderHtml()
    {
        return new k_PageNotFound();
    }

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
        if (is_callable(array($this->context, 'numberOfProductsPerPage'))) {
            return $this->context->numberOfProductsPerPage();
        }
        return 20;
    }
}
