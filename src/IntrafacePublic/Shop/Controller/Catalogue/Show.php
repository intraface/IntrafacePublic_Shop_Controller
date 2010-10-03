<?php
class IntrafacePublic_Shop_Controller_Catalogue_Show extends IntrafacePublic_Controller_Pluggable
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        return 'IntrafacePublic_Shop_Controller_Catalogue_Show';
    }

    function dispatch()
    {
        $category = $this->getCategory();
        if ($category === false) {
            throw new k_PageNotFound(404);
        }

        return parent::dispatch();
    }

    function renderHtml()
    {
        $category = $this->getCategory();

        $this->document->setTitle($category['name']);

        if ($this->query('start')) {
            $offset = (int)$this->query('start');
        } else {
            $offset = 0;
        }

        if ($this->query('update')) {
            $this->getShop()->clearProductsInCategoryIdCache($category['id'], $this->numberOfProductsPerPage(), $offset);
        }

        $products = $this->getShop()->getProductsInCategoryId($category['id'], $this->numberOfProductsPerPage(), $offset);
        $products['currency'] = $this->getCurrency();

        $data = $this->getCategory();
        $data['bread_crump'] = $this->getBreadcrumpTrail();

        $products = $this->triggerEvent('postProductsInCategoryGet', $products);

        $tpl_breadcrumb = $this->template->create('IntrafacePublic/Shop/templates/catalogue-breadcrumptrail');
        $tpl_category = $this->template->create('IntrafacePublic/Shop/templates/catalogue-category');
        $tpl_products = $this->template->create('IntrafacePublic/Shop/templates/catalogue-products');
        $tpl_paging = $this->template->create('IntrafacePublic/Shop/templates/products-paging');

        return $tpl_breadcrumb->render($this, $data)
          . $tpl_category->render($this, $data)
          . $tpl_products->render($this, $products)
          . $tpl_paging->render($this, $products);
    }

    function getCategoryPicture($category_id)
    {
        return $this->context->getCategoryPicture($category_id);
    }

    function getCurrency()
    {
        return $this->context->getCurrency();
    }

    public function getShop()
    {
        return $this->context->getShop();
    }

    function getCategory()
    {
        $context = $this->context->getCategory();
        // Finds category from identifier
        foreach($context['categories'] AS $category) {
            if ($category['identifier'] == $this->name()) {
                return $category;
            }
        }

        return false;
    }

    function getBreadcrumpTrail()
    {
        $bread_crump = $this->context->getBreadcrumpTrail();
        $category = $this->getCategory();
        $bread_crump[] = array('name' => $category['name'], 'url' => $this->url());
        return $bread_crump;
    }

    function urlToProductId($id)
    {
        return $this->context->urlToProductId($id);
    }

    function numberOfProductsPerPage()
    {
        return $this->context->numberOfProductsPerPage();
    }
}