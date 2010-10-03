<?php
class IntrafacePublic_Shop_Controller_Product_Variation extends k_Component
{
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function map($name)
    {
        if ($name == 'add') {
            return 'IntrafacePublic_Shop_Controller_Products_Add';
        }
    }

    function dispatch()
    {
        $result = $this->getShop()->getProduct($this->context->name);

        if ($result['product']['id'] == 0) {
            throw new PageNotFound();
        }
        return parent::dispatch();
    }

    function renderHtml()
    {
        $result = $this->getShop()->getProduct($this->context->name);

        if (!is_array($result['variations'])) {
            throw new Exception('The product does not have variations');
        }

        $variation = false;
        foreach($result['variations'] AS $tmp_variation) {
            if ($tmp_variation['variation']['identifier'] == $this->name) {
                $variation = $tmp_variation;
                break;
            }
        }

        $title = $result['product']['name']);
        if ($variation) {
            $title .= ' - '.$variation['variation']['name'];
        }

        $this->document->setTitle($title);

        $data = $this->context->getProductDataArray($result);
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/product-variation-buy');
        $data['product_variation_buy'] = $tpl->render($this, array_merge($result, array('variation' => $variation)));

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/product');
        $content = $tpl->render($this, $data);
        return $content;
    }

    function postForm()
    {
        if ($this->body('select_variation')) {
            if ($this->body('attribute') && is_array($this->body('attribute')) && count($this->body('attribute')) > 0) {
                return new k_SeeOther($this->url('../'.implode('-',$this->body('attribute'))));
            }
        }
        return $this->render();
    }

    function getShop()
    {
        return $this->context->getShop();
    }
}
