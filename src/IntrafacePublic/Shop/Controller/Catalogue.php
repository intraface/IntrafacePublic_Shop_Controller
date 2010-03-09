<?php
class IntrafacePublic_Shop_Controller_Catalogue extends IntrafacePublic_Controller_Pluggable
{
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
            'name' => $this->__($this->getPageTitle()),
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
            $bread_crump[] = array('name' => $this->__($this->getPageTitle()), 'url' => $this->url());
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

    function GET()
    {
        $this->document->title = $this->__($this->getPageTitle());
        $data = $this->getCategory();
        $data['bread_crump'] = $this->getBreadcrumpTrail();
        return $this->render('IntrafacePublic/Shop/templates/catalogue-tpl.php', $data);
    }

    function forward($name)
    {
        $next = new IntrafacePublic_Shop_Controller_Catalogue_Show($this, $name);
        return $next->handleRequest();
    }
}