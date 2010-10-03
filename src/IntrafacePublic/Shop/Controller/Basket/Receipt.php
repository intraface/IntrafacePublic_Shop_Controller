<?php
class IntrafacePublic_Shop_Controller_Basket_Receipt extends k_Component
{
    protected $error = array();
    protected $template;

    /*
    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'receipt';
    }
    */

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Order confirmation');
        $this->document->setCurrentStep('receipt');

        $text = $this->context->getShop()->getReceiptText();

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/receipt');
        return $tpl->render($this, array('text' => $text));
    }

    function getErrors()
    {
        return $this->error;
    }
}