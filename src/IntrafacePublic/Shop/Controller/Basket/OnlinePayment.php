<?php
class IntrafacePublic_Shop_Controller_Basket_OnlinePayment extends IntrafacePublic_OnlinePayment_Controller_Index
{
    private $error = array();

    function getErrors()
    {
        return $this->error;
    }

    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'payment';
        $this->document->title = 'Online payment';
        $this->document->description = '';
        $this->document->meta = '';
    }

    /**
     * Returns the url to go to when payment is succeded.
     * Placed here makes it possible to overwrite the method in local contexts.
     */
    public function getOkUrl()
    {
        return $this->url('../receipt');
    }

    /*
    function GET()
    {
        $data = array('content' => parent::GET());
        return $this->render('form-container-tpl.php', $data);

    }
    */

}

