<?php
class IntrafacePublic_Shop_Controller_Basket_EAN extends k_Component
{
    protected $error_message = array();
    protected $template;

    /*
    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'details';
        $this->document->title = 'Payment via EAN';
        $this->document->description = '';
        $this->document->meta = '';
    }
    */

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }

    function renderHtml()
    {
        $this->document->setTitle('Payment via EAN');
        $this->document->setCurrentStep('details');

        $values = array();
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/ean');
        $content = $tpl->render($this, $values);

        $data = array();
        $data['content'] = $content;
        $data['button_back_label'] = 'Back';
        $data['button_back_link'] = $this->url('../');
        $data['button_continue_label'] = 'Continue...';
        $data['button_continue_name'] = 'send';

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/form-container');
        return $tpl->render($this, $data);
    }

    function postForm()
    {
        $input = $this->body();

        if (isset($input['customer_ean']) && strlen($input['customer_ean']) != 13) {
            $this->error_message[] = 'EAN must be 13 characters long';
        }

        if (!$this->context->getShop()->saveCustomerEan($input['customer_ean'])) {
            $this->error_message[] = 'Your EAN number could not be saved';
        }

        if (count($this->error_message) > 0) {
            return $this->render();
        }

        return new k_SeeOther($this->url('../../order'));
    }

    function getErrors()
    {
        return $this->error_message;
    }
}

