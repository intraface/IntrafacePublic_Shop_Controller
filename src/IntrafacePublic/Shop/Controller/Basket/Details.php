<?php
class IntrafacePublic_Shop_Controller_Basket_Details extends IntrafacePublic_Controller_Pluggable
{
    private $error = array();

    function getErrors()
    {
        return $this->error;
    }

    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'details';
        $this->document->title = 'Details';
        $this->document->description = '';
        $this->document->meta = '';
    }

    function GET()
    {
        $client = $this->context->getShop();

        $basket = $client->getBasket();

        if (empty($basket['items'])) {
            throw new IntrafacePublic_Shop_Exception_NotAllowed('There is nothing in the basket, so you cannot order.');
        }

        $values = array_merge($client->getAddress(), $client->getCustomerCoupon(), $client->getCustomerComment(), $client->getCustomerEan(), $client->getPaymentMethod());

        $data = array();

        $countries = new Ilib_Countries('iso-8859-1', array($this, '__'));
        // If available country regions is set in root
        if (is_callable(array($this->context->context->context, 'getAvailableCountryRegions'))) {
            $values['countries'] = $countries->getCountriesByRegionName(
                $this->context->context->context->getAvailableCountryRegions()
            );
        } else {
            $values['countries'] = $countries->getAll();
        }

        $data['details'] = $this->render('IntrafacePublic/Shop/templates/details-customer-tpl.php', $values);
        $data['payment_method'] = $this->render('IntrafacePublic/Shop/templates/details-payment-method-tpl.php', array_merge($values, array('payment_methods' => $client->getPaymentMethods())));
        //$data['ean'] = $this->render('IntrafacePublic/Shop/templates/details-customer-ean-tpl.php', $values);
        $data['coupon'] = $this->render('IntrafacePublic/Shop/templates/details-customer-coupon-tpl.php', $values);
        $data['comment'] = $this->render('IntrafacePublic/Shop/templates/details-customer-comment-tpl.php', $values);
        if ($this->context->getNewsletter()) {
            $data['newsletter'] = $this->render('IntrafacePublic/Shop/templates/details-newsletter-tpl.php');
        }

        $content = $this->render('IntrafacePublic/Shop/templates/details-form-container-tpl.php', $data);

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
        $values = $this->POST->getArrayCopy();
        $error_message = IntrafacePublic_Shop_Tools_ValidateDetails::validate($values);

        foreach ($error_message as $key => $value) {
            $error_message[$key] = $this->__($value);
        }

        // At this point we save information without validating it.
        // That means if you navigate back and forth the data is
        // kept in the form. Data is first validated when order is placed
        if (!$this->context->getShop()->saveAddress($values)) {
            $error_message[] = $this->__('Information could not be saved - try again later.');
        }

        if (isset($values['customer_comment']) && $values['customer_comment'] != '') {
            if (!$this->context->getShop()->saveCustomerComment($values['customer_comment'])) {
                $error_message[] = $this->__('Your comment could not be saved');
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
                $error_message[] = $this->__('Your customer coupon could not be saved');
            }
        }

        if (empty($values['payment_method']) || $values['payment_method'] == '0') {
            $error_message[] = $this->__('You need to select a payment method');
        } else {
            if (!$this->context->getShop()->savePaymentMethod($values['payment_method'])) {
                $error_message[] = $this->__('Your payment method could not be saved');
            }
        }

        if (count($error_message) > 0) {
            $this->error = $error_message;
            return $this->GET();
        }

        if ($this->context->getNewsletter()) {
            if (!empty($this->POST['email']) AND !empty($this->POST['customer_newsletter'])) {
                try {
                    $this->context->getNewsletter()->subscribe($this->POST['email'], $this->POST['name'], $_SERVER['REMOTE_ADDR']);
                } catch (Exception $e) {
                    // throw $e;
                }

            }
        }

        if (isset($values['payment_method'])
            && $values['payment_method'] == 'EAN') {
            throw new k_http_Redirect($this->url('ean'));
        }

        throw new k_http_Redirect($this->url('../order'));
    }


    function forward($name)
    {
        if ($name == 'ean') {
            $next = new IntrafacePublic_Shop_Controller_Basket_EAN($this, $name);
            return $next->handleRequest();
        }

        parent::forward($name);
    }

    function getShop()
    {
        return $this->context->getShop();
    }
}