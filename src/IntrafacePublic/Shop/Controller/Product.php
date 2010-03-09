<?php
class IntrafacePublic_Shop_Controller_Product extends IntrafacePublic_Controller_Pluggable
{
    private $product;
    
    public function getShop()
    {
        return $this->context->getShop();
    }
    
    public function getCurrency()
    {
        return $this->context->getCurrency();
    }
    
    /**
     * Returns array of page name for the breadcrump trail identifiers
     * @return array
     */
    public function getBreadcrumpTrailPageTitles()
    {
        return $this->context->getBreadcrumpTrailPageTitles();
        
    }
    
    public function getBreadcrumpTrail()
    {
        if(is_callable(array($this->context, 'getBreadcrumpTrail'))) {
            $bread_crump = $this->context->getBreadcrumpTrail();
        }
        else {
            $bread_crump = array();
        }
        
        $page_names = $this->getBreadcrumpTrailPageTitles();
        if(count($page_names) == 0) $page_names = array('catalogue' => 'Catalogue', 'products' => 'All products');
        
        if(!empty($_SERVER['HTTP_REFERER']) && false !== strrchr($_SERVER['HTTP_REFERER'], $this->context->url())) {
            $referer = substr($_SERVER['HTTP_REFERER'], strlen($this->context->url()));
            $referer = explode('/', $referer);
            $referer = array_filter($referer); // removes empty entries. Can be the first.
            $referer = array_values($referer); // arrange keys starting from 0
            
            if(isset($referer[0]) && $referer[0] == 'catalogue') {
                array_shift($referer); // removes the catalogue entry
                $categories = $this->context->getCategories();
                $url = 'catalogue';
                if(!empty($page_names['catalogue'])) {
                    $bread_crump[] = array('name' => $this->__($page_names['catalogue']), 'url' => $this->context->url($url));
                }
                foreach($referer AS $ref) {
                    foreach($categories AS $category) {
                        if($category['identifier'] == $ref) {
                            $url .= '/'.$category['identifier'];
                            $bread_crump[] = array('name' => $category['name'], 'url' => $this->context->url($url));
                            $categories = $category['categories'];
                            break;
                        }
                    }
                }
            }
            elseif(!empty($referer[0]) && !empty($page_names[$referer[0]])) {
                $bread_crump[] = array('name' => $this->__($page_names[$referer[0]]), 'url' => $this->context->url($referer[0]));
            }
            return $bread_crump;
        }
    }

    public function GET()
    {
        throw new k_http_Response(404);
    }
    
    public function forward($name)
    {
        $next = new IntrafacePublic_Shop_Controller_Product_Show($this, $name);
        return $next->handleRequest();
    }
}
