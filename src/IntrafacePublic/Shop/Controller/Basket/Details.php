<?php
class IntrafacePublic_Shop_Controller_Basket_Details extends IntrafacePublic_Controller_Pluggable
{
    protected $error = array();
    protected $template;

    function map($name)
    {
        if ($name == 'ean') {
            return 'IntrafacePublic_Shop_Controller_Basket_EAN';
        }
        parent::forward($name);
    }

    /*
    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'details';
        $this->document->title = 'Details';
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
        $this->document->setTitle('Details');
        $this->document->setCurrentStep('details');

        $client = $this->context->getShop();

        $basket = $client->getBasket();

        if (empty($basket['items'])) {
            throw new k_Forbidden();
        }

        $values = array_merge($client->getAddress(), $client->getCustomerCoupon(), $client->getCustomerComment(), $client->getCustomerEan(), $client->getPaymentMethod());

        $data = array();

        $countries = new Ilib_Countries('iso-8859-1', array($this, 't'));
        // If available country regions is set in root
        if (is_callable(array($this->context->context->context, 'getAvailableCountryRegions'))) {
            $values['countries'] = $countries->getCountriesByRegionName(
                $this->context->context->context->getAvailableCountryRegions()
            );
        } else {
            $values['countries'] = $countries->getAll();
        }

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/details-customer');
        $data['details'] = $tpl->render($this, $values);
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/details-payment-method');
        $data['payment_method'] = $tpl->render($this, array_merge($values, array('payment_methods' => $client->getPaymentMethods())));
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/details-customer-coupon');
        $data['coupon'] = $tpl->render($this, $values);
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/details-customer-comment');
        $data['comment'] = $tpl->render($this, $values);
        if ($this->context->getNewsletter()) {
            $tpl = $this->template->create('IntrafacePublic/Shop/templates/details-newsletter');
            $data['newsletter'] = $tpl->render($this);
        }
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/details-form-container');
        $content = $tpl->render($this, $data);

        $data = array();
        $data['content'] = $content;
        $data['button_back_label'] = $this->t('Back');
        $data['button_back_link'] = $this->url('../');
        $data['button_continue_label'] = $this->t('Continue...');
        $data['button_continue_name'] = 'send';
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/form-container');
        return $tpl->render($this, $data);
    }

    function postForm()
    {
        $values = $this->body();
        $error_message = IntrafacePublic_Shop_Tools_ValidateDetails::validate($values);

        foreach ($error_message as $key => $value) {
            $error_message[$key] = $this->t($value);
        }

        // At this point we save information without validating it.
        // That means if you navigate back and forth the data is
        // kept in the form. Data is first validated when order is placed
        if (!$this->context->getShop()->saveAddress($values)) {
            $error_message[] = $this->t('Information could not be saved - try again later.');
        }

        if (isset($values['customer_comment']) && $values['customer_comment'] != '') {
            if (!$this->context->getShop()->saveCustomerComment($values['customer_comment'])) {
                $error_message[] = $this->t('Your comment could not be saved');
            }
        }
        /*
        if (isset($values['customer_ean']) && $values['customer_ean'] != '') {
            if (!$this->context->getShop()->saveCustomerEan($values['customer_ean'])) {
                $error_message[] = $this->__('Your EAN number could not be saved');
            }
        }
        */
        if (isset($values['customer_coupon']) && $values['customer_coupon'] != '') {
            if (!$this->context->getShop()->saveCustomerCoupon($values['customer_coupon'])) {
                $error_message[] = $this->t('Your customer coupon could not be saved');
            }
        }

        if (empty($values['payment_method']) || $values['payment_method'] == '0') {
            $error_message[] = $this->t('You need to select a payment method');
        } else {
            if (!$this->context->getShop()->savePaymentMethod($values['payment_method'])) {
                $error_message[] = $this->t('Your payment method could not be saved');
            }
        }

        if (count($error_message) > 0) {
            $this->error = $error_message;
            return $this->render();
        }

        if ($this->context->getNewsletter()) {
            if ($this->body('email') AND $this->body('customer_newsletter')) {
                try {
                    $this->context->getNewsletter()->subscribe($this->body('email'), $this->body('name'), $_SERVER['REMOTE_ADDR']);
                } catch (Exception $e) {
                    // throw $e;
                }

            }
        }

        if (isset($values['payment_method'])
            && $values['payment_method'] == 'EAN') {
            return new k_SeeOther($this->url('ean'));
        }

        return new k_SeeOther($this->url('../order'));
    }

    function getShop()
    {
        return $this->context->getShop();
    }

    function getErrors()
    {
        return $this->error;
    }
}