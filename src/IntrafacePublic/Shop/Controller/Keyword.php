<?php
class IntrafacePublic_Shop_Controller_Keyword extends k_Component
{
    function map($name)
    {
        return 'IntrafacePublic_Shop_Controller_Keyword_Show';
    }

    function renderHtml()
    {
        throw new k_PageNotFound();
    }

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
        return $this->context->url('product/'.$id);
    }
}