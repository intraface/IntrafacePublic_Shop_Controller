<?php
class IntrafacePublic_Shop_Controller_Catalogue extends IntrafacePublic_Controller_Pluggable
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

    function renderHtml()
    {
        $this->document->setTitle($this->getPageTitle());
        $data = $this->getCategory();
        $data['bread_crump'] = $this->getBreadcrumpTrail();

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/catalogue');
        return $tpl->render($this, $data);
    }

    function getCurrency()
    {
        return $this->context->getCurrency();
    }

    public function getShop()
    {
        return $this->context->getShop();
    }

    public function getPageTitle()
    {
        $page_names = $this->context->getBreadcrumpTrailPageTitles();
        if (isset($page_names['catalogue'])) {
            return $page_names['catalogue'];
        }
        return 'Catalogue';
    }

    function getCategory()
    {
        return array(
            'name' => $this->getPageTitle(),
            'categories' => $this->context->getCategories()
        );
    }

    function getCategoryPicture($category_id)
    {
        return $this->getShop()->getProductCategoryPicture($category_id);
    }

    function getBreadcrumpTrail()
    {
        $bread_crump = array();
        if (is_callable(array($this->context, 'getBreadcrumpTrail'))) {
            $bread_crump = $this->context->getBreadcrumpTrail();
        }
        if ($this->getPageTitle() != '') {
            $bread_crump[] = array('name' => $this->getPageTitle(), 'url' => $this->url());
        }
        return $bread_crump;
    }

    function numberOfProductsPerPage()
    {
        if (is_callable(array($this->context, 'numberOfProductsPerPage'))) {
            return $this->context->numberOfProductsPerPage();
        }

        return 20;
    }

    function urlToProductId($id)
    {
        return $this->context->url('product/'.$id);
    }
}