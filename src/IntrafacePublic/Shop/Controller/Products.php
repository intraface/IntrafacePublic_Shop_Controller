<?php
class IntrafacePublic_Shop_Controller_Products extends IntrafacePublic_Controller_Pluggable
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return 'IntrafacePublic_Shop_Controller_Product_Show';
    }

    function renderHtml()
    {
        $search               = array();
        $search['area']       = 'webshop';
        if ($this->numberOfProductsPerPage() == 0) {
            $search['use_paging'] = 'false';
        } else {
            $search['use_paging'] = 'true';
        }
        $search['search']     = '';

        $headline             = $this->t('All products');

        if ($this->query('start')) {
            $search['offset'] = (int)$this->query('start');
        } elseif ($this->query('q')) {
            $search['search'] = $this->query('q');
            $headline         = $this->t('Search: ') . $this->query('q');
        } elseif ($this->query('keyword')) {
            $search['keywords'] = $this->query('keyword');
            $headline           = $this->t('Search by keywords');
        } elseif ($this->query('category')) {
            $search['category'] = $this->query('category');
            $headline           = $this->t('Category');
        }

        $search = $this->triggerEvent('preProductsGet', $search);

        if ($this->query('update')) {
            $this->getShop()->clearProductsCache($search);
        }

        $products = $this->getShop()->getProducts($search);
        $products['currency'] = $this->getCurrency();

        $this->document->setTitle($headline);

        $products = $this->triggerEvent('postProductsGet', $products);

        if (isset($products['products']) && is_array($products['products']) && count($products['products']) == 0) {
            $tpl = $this->template->create('IntrafacePublic/Shop/templates/products-no-results');
            $products_html = $tpl->render($this, $products);
        } else {
            $tpl = $this->template->create('IntrafacePublic/Shop/templates/products');
            $products_html = $tpl->render($this, $products);
        }

        $tpl_products = $this->template->create('IntrafacePublic/Shop/templates/products-headline');
        $tpl_search = $this->template->create('IntrafacePublic/Shop/templates/products-search');
        $tpl_paging = $this->template->create('IntrafacePublic/Shop/templates/products-paging');
        return $tpl_products->render($this)
            . $tpl_search->render($this, array('search' => $this->getSearch()))
            . $products_html . $tpl_paging->render($this, $products);
    }

    function getSearch()
    {
        return $this->query('q');
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
