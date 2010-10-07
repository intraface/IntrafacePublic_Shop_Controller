<?php
class IntrafacePublic_Shop_Controller_Keyword_Show extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Keyword');

        if ($this->query('start')) {
            $offset = (int)$this->query('start');
        } else {
            $offset = 0;
        }

        if ($this->query('update')) {
            $this->getShop()->clearProductsWithKeywordIdCache($this->name, 20, $offset);
        }

        $products = $this->getShop()->getProductsWithKeywordId($this->name, 20, $offset);
        $products['currency'] = $this->getCurrency();

        $tpl_products = $this->template->create('IntrafacePublic/Shop/templates/keyword-products');
        $tpl_paging = $this->template->create('IntrafacePublic/Shop/templates/products-paging');
        return $tpl_products->render($this, $products)
          . $tpl_paging->render($this, $products);
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
        return $this->context->urlToProductId($id);
    }
}