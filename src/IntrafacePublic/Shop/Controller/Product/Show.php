<?php
class IntrafacePublic_Shop_Controller_Product_Show extends IntrafacePublic_Controller_Pluggable
{
    protected $product;
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    public function map($name)
    {
        if ($name == 'add') {
            return 'IntrafacePublic_Shop_Controller_Product_Add';
        }

        return 'IntrafacePublic_Shop_Controller_Product_Variation';
    }

    function dispatch()
    {
        $result = $this->getProduct();
        if ($result['product']['id'] == 0) {
            return new k_PageNotFound();
        }
        return parent::dispatch();
    }

    public function renderHtml()
    {
        $result = $this->getProduct();

        $this->document->setTitle($result['product']['name']);
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/product');
        return $tpl->render($this, $this->getProductDataArray($result));
    }

    public function POST()
    {
        if (isset($this->POST['select_variation'])) {

            if (isset($this->POST['attribute']) && is_array($this->POST['attribute']) && count($this->POST['attribute']) > 0) {
                throw new k_http_Redirect($this->url('./'.implode('-', $this->POST['attribute'])));
            }

            return $this->GET();
        }
    }

    public function getShop()
    {
        return $this->context->getShop();
    }

    public function getCurrency()
    {
        return $this->context->getCurrency();
    }

    public function getProduct()
    {
        if (isset($this->product)) {
            return $this->product;
        }

        if ($this->query('update')) {
            $this->getShop()->clearProductCache($this->name());
        }

        return $this->product = $this->getShop()->getProduct($this->name());
    }

    public function getProductDataArray()
    {
        $result = $this->getProduct();
        $result['currency'] = $this->getCurrency();

        $data = $result;

        $tpl_pictures = $this->template->create('IntrafacePublic/Shop/templates/product-pictures');

        $data['pictures'] = $tpl_pictures->render($this, $result);
        if ($this->query('message') != '') {
            $tpl_message = $this->template->create('IntrafacePublic/Shop/templates/product-message');
            $data['message'] = $tpl_message->render($this, array('message' => $this->query('message')));
        }

        if ($result['product']['has_variation']) {
            $tpl_variation = $this->template->create('IntrafacePublic/Shop/templates/product-variation-buy');
            $data['product_variation_buy'] = $tpl_variation->render($this, $result);
        } else {
            $tpl_buy = $this->template->create('IntrafacePublic/Shop/templates/product-buy');
            $data['product_buy'] = $tpl_buy->render($this, $result);
        }

        if ($this->query('update')) {
            $this->getShop()->clearRelatedProductsCache($this->name());
        }

        $tpl_related = $this->template->create('IntrafacePublic/Shop/templates/product-related-products');
        $data['related_products'] = $tpl_related->render($this, array('related_products' => $this->getShop()->getRelatedProducts($this->name()), 'currency' => $this->getCurrency()));
        $tpl_breadcrumb = $this->template->create('IntrafacePublic/Shop/templates/product-breadcrumptrail');
        $data['breadcrumptrail'] = $tpl_breadcrumb->render($this, array('breadcrumptrail' => $this->getBreadcrumpTrail()));

        $data = $this->triggerEvent('postProductGet', $data);

        return $data;
    }

    protected function getBreadcrumpTrail()
    {
        $breadcrump = array();
        if (is_callable(array($this->context, 'getBreadcrumpTrail'))) {
             $breadcrump = $this->context->getBreadcrumpTrail();
        }

        $product = $this->getProduct();
        $breadcrump[] = array('name' => $product['product']['name'], 'url' => $this->url());
        return $breadcrump;
    }
}
