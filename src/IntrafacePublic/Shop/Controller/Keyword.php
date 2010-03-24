<?php
class IntrafacePublic_Shop_Controller_Keyword extends k_Component
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
        return $this->context->url('product/'.$id);
    }

    function GET()
    {
        throw new k_http_Response(404);
    }

    function forward($name)
    {
        $next = new IntrafacePublic_Shop_Controller_Keyword_Show($this, $name);
        return $next->handleRequest();
    }
}