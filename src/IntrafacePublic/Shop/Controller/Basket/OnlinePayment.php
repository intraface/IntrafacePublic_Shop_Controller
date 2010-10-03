<?php
class IntrafacePublic_Shop_Controller_Basket_OnlinePayment extends IntrafacePublic_OnlinePayment_Controller_Index
{
    protected $error = array();

    /*
    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'payment';
        $this->document->title = 'Online payment';
        $this->document->description = '';
        $this->document->meta = '';
    }
    */

    function renderHtml()
    {
        $this->document->setTitle('Online payment');
        $this->document->setCurrentTitle('payment');
        return parent::renderHtml();
    }

    /**
     * Returns the url to go to when payment is succeded.
     * Placed here makes it possible to overwrite the method in local contexts.
     */
    public function getOkUrl()
    {
        return $this->url('../receipt');
    }

    function getErrors()
    {
        return $this->error;
    }
}
