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
        if (empty($this->document->locale)) $this->document->locale = 'en_US';
    }
    */

    protected $shop;
    protected $template;

    function __construct(IntrafacePublic_Shop $shop, k_TemplateFactory $template)
    {
        $this->shop = $shop;
        $this->template = $template;
    }

    function map($name)
    {
        if (!isset($this->map[$name])) {
            throw new Exception($name . ' is not a qualified mapping');
        }
        return $this->map[$name];
    }

    function wrapHtml($content)
    {
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/menu-categories');
        $menu = $tpl->render($this, array('url_root' => $this->url('.'), 'categories' => $this->getCategories()));
        return $menu . $content;
    }

    function renderHtml()
    {
        if ($this->query('update_all')) {
            $this->getShop()->clearCache();
        }

        if ($this->query('update')) {
            $this->getShop()->clearFeaturedProductsCache();
        }
        $result = $this->getShop()->getFeaturedProducts();

        $this->document->setTitle('Featured products');

        $html = '';

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/products-featured');

        foreach ($result as $featured) {
            $data = array('products' => $featured['products'],
                          'headline' => $featured['title'],
                          'currency' => $this->getCurrency());
            $html .= $tpl->render($this, $data);
        }

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/frontpage');
        return $tpl->render($this, array('content' => $html));
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
        if (is_callable(array($this->context, 'getOnlinePayment'))) {
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
        if (is_callable(array($this->context, 'getOnlinePaymentAuthorize'))) {
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
        if (is_callable(array($this->context, 'getBreadcrumpTrail'))) {
            return $this->context->getBreadcrumpTrail();
        }
        return array();
    }

    function getBreadcrumpTrailPageTitles()
    {
        if (is_callable(array($this->context, 'getBreadcrumpTrailPageTitles'))) {
            return $this->context->getBreadcrumpTrailPageTitles();
        }
        return array();
    }

    public function setCurrency($currency)
    {
        if (!$this->currency) {
            $this->currency = $this->getShop()->getCurrency();
        }

        if (!array_key_exists($currency, $this->currency['currencies'])) {
            throw new Exception('Invalid currency selection '.$currency);
        }

        $this->currency['default'] = $currency;
    }
}
