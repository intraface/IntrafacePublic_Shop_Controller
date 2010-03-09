<?php
class IntrafacePublic_Shop_Controller_Basket_Receipt extends k_Controller
{
    private $error = array();
    
    function getErrors()
    {
        return $this->error;
    }
    
    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'receipt';
    }

    function GET()
    {
        $this->document->title = 'Order confirmation';
        $this->document->description = '';
        $this->document->keywords = '';

        $text = $this->context->getShop()->getReceiptText();
        
        return $this->render('IntrafacePublic/Shop/templates/receipt-tpl.php', array('text' => $text));

    }
}