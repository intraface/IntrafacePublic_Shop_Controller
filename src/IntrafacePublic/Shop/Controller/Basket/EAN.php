<?php
class IntrafacePublic_Shop_Controller_Basket_EAN extends k_Controller
{
    protected $error_message = array();

    function getErrors()
    {
        return $this->error_message;
    }

    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'details';
        $this->document->title = 'Payment via EAN';
        $this->document->description = '';
        $this->document->meta = '';
    }

    function GET()
    {
        $values = array();
        $content = $this->render('IntrafacePublic/Shop/templates/ean.tpl.php', $values);

        $data = array();
        $data['content'] = $content;
        $data['button_back_label'] = $this->__('Back');
        $data['button_back_link'] = $this->url('../');
        $data['button_continue_label'] = $this->__('Continue...');
        $data['button_continue_name'] = 'send';

        return $this->render('IntrafacePublic/Shop/templates/form-container-tpl.php', $data);

    }

    function POST()
    {
        $input = $this->POST->getArrayCopy();

        if (isset($input['customer_ean']) && strlen($input['customer_ean']) != 13) {
            $this->error_message[] = 'EAN must be 13 characters long';
        }

        if (!$this->context->getShop()->saveCustomerEan($input['customer_ean'])) {
            $this->error_message[] = $this->__('Your EAN number could not be saved');
        }

        if (count($this->error_message) > 0) {

            return $this->GET();
        }

        throw new k_http_Redirect($this->url('../../order'));
    }
}

