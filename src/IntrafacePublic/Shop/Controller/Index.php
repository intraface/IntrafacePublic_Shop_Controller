<?php
class IntrafacePublic_Shop_Controller_Index extends k_Component
{
    public $map = array('products' => 'IntrafacePublic_Shop_Controller_Products',
                        'product' => 'IntrafacePublic_Shop_Controller_Product',
                        'basket'   => 'IntrafacePublic_Shop_Controller_Basket',
                        'catalogue' => 'IntrafacePublic_Shop_Controller_Catalogue',
                        'keyword' => 'IntrafacePublic_Shop_Controller_Keyword');

    private $categories;
    private $currency;

    /*
    public function __construct($context, $name)
    {
        parent::__construct($context, $name);
        $this->document->menu = $this->render('IntrafacePublic/Shop/templates/menu-categories-tpl.php', array('url_root' => $this->url('.'), 'categories' => $this->getCategories()));

        # We set locale to en_US as default.
        if(empty($this->document->locale)) $this->document->locale = 'en_US';
    }
    */

    protected $shop;

    function __construct(IntrafacePublic_Shop $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Returns IntrafacePublic_Shop
     *
     * @return object IntrafacePublic_Shop
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Returns IntrafacePublic_Onlinepayment
     *
     * @return object IntrafacePublic_Onlinepayment
     */
    public function getOnlinePayment()
    {
        if(is_callable(array($this->context, 'getOnlinePayment'))) {
            return $this->context->getOnlinePayment();
        }
        return false;
    }

    /**
     * Returns Ilib_Payment_Authorize
     *
     * @return object Ilib_Payment_Authorize
     */
    public function getOnlinePaymentAuthorize()
    {
        if(is_callable(array($this->context, 'getOnlinePaymentAuthorize'))) {
            return $this->context->getOnlinePaymentAuthorize();
        }
        return false;
    }

    public function getNewsletter()
    {
        if (method_exists($this->context, 'getNewsletter')) {
            return $this->context->getNewsletter();
        }

        return false;
    }

    function getCategories()
    {
        if (!$this->categories) {
            $this->categories = $this->getShop()->getProductCategories();
        }
        return $this->categories;
    }

    function getCurrency()
    {
        if (!$this->currency) {
            $this->currency = $this->getShop()->getCurrency();
        }

        /**
         * The possibility to change between currencies.
         */

        return $this->currency['default'];
    }

    function getBreadcrumpTrail()
    {
        if(is_callable(array($this->context, 'getBreadcrumpTrail'))) {
            return $this->context->getBreadcrumpTrail();
        }
        return array();
    }

    function getBreadcrumpTrailPageTitles()
    {
        if(is_callable(array($this->context, 'getBreadcrumpTrailPageTitles'))) {
            return $this->context->getBreadcrumpTrailPageTitles();
        }
        return array();
    }

    public function setCurrency($currency)
    {
        if (!$this->currency) {
            $this->currency = $this->getShop()->getCurrency();
        }

        if(!array_key_exists($currency, $this->currency['currencies'])) {
            throw new Exception('Invalid currency selection '.$currency);
        }

        $this->currency['default'] = $currency;
    }

    function GET()
    {
        if(isset($this->GET['update_all'])) {
            $this->getShop()->clearCache();
        }

        if(isset($this->GET['update'])) {
            $this->getShop()->clearFeaturedProductsCache();
        }
        $result = $this->getShop()->getFeaturedProducts();

        $this->document->setTitle($this->t('Featured products'));

        $html = '';
        foreach ($result as $featured) {
            $data = array('products' => $featured['products'],
                          'headline' => $featured['title'],
                          'currency' => $this->getCurrency());
            $html .= $this->render('IntrafacePublic/Shop/templates/products-featured-tpl.php', $data);
        }

        return $this->render('IntrafacePublic/Shop/templates/frontpage-tpl.php', array('content' => $html));
    }

    function forward($name)
    {
        if (!isset($this->map[$name])) {
            throw new Exception($name . ' is not a qualified mapping');
        }
        $next = new $this->map[$name]($this, $name);
        return $next->handleRequest();
    }
}
