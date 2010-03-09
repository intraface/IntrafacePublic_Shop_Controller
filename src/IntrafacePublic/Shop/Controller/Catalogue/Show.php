<?php
class IntrafacePublic_Shop_Controller_Catalogue_Show extends IntrafacePublic_Controller_Pluggable
{

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
            if ($category['identifier'] == $this->name) {
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

    function GET()
    {
        $category = $this->getCategory();
        if ($category === false) {
            throw new k_http_Response(404);
        }

        $this->document->title = $category['name'];

        if (!empty($this->GET['start'])) {
            $offset = (int)$this->GET['start'];
        } else {
            $offset = 0;
        }

        if(isset($this->GET['update'])) {
            $this->getShop()->clearProductsInCategoryIdCache($category['id'], $this->numberOfProductsPerPage(), $offset);
        }

        $products = $this->getShop()->getProductsInCategoryId($category['id'], $this->numberOfProductsPerPage(), $offset);
        $products['currency'] = $this->getCurrency();

        $data = $this->getCategory();
        $data['bread_crump'] = $this->getBreadcrumpTrail();
        
        $products = $this->triggerEvent('postProductsInCategoryGet', $products);

        return $this->render('IntrafacePublic/Shop/templates/catalogue-breadcrumptrail-tpl.php', $data)
          . $this->render('IntrafacePublic/Shop/templates/catalogue-category-tpl.php', $data)
          . $this->render('IntrafacePublic/Shop/templates/catalogue-products-tpl.php', $products)
          . $this->render('IntrafacePublic/Shop/templates/products-paging-tpl.php', $products);
    }

    function getCategoryPicture($category_id)
    {
        return $this->context->getCategoryPicture($category_id);
    }

    function forward($name)
    {
        $next = new IntrafacePublic_Shop_Controller_Catalogue_Show($this, $name);
        return $next->handleRequest();
    }
}