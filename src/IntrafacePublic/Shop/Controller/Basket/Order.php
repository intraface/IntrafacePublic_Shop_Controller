<?php
class IntrafacePublic_Shop_Controller_Basket_Order extends IntrafacePublic_Controller_Pluggable
{
    private $error = array();

    function getCurrency()
    {
        return $this->context->getCurrency();
    }

    function getErrors()
    {
        return $this->error;
    }

    function __construct($context, $name)
    {
        parent::__construct($context, $name);

        $this->document->current_step = 'order';
        $this->document->title = 'Order';
        $this->document->description = '';
        $this->document->meta = '';
    }

    function GET()
    {
        $values = array_merge($this->context->getShop()->getAddress(), $this->context->getShop()->getCustomerCoupon(), $this->context->getShop()->getCustomerComment(), $this->context->getShop()->getCustomerEan(), $this->context->getShop()->getPaymentMethod());

        // Basket is getting values to perform basket evaluation
        $basket = $this->context->getShop()->getBasket($values);

        if (empty($basket['items'])) {
            throw new IntrafacePublic_Shop_Exception_NotAllowed('The cart is empty, so you are not allowed on this page.');
        }

        $data = array('items'       => $basket['items'],
                      'total_price' => $basket['total_price'],
                      'currency' => $this->getCurrency()
                      );

        $basket = $this->render('IntrafacePublic/Shop/templates/order-basket-tpl.php', $data);

        $data = array('value' => $values);

        $address = $this->render('IntrafacePublic/Shop/templates/order-details-tpl.php', $data);

        try {
            $terms_url = $this->context->getShop()->getTermsOfTradeUrl();
        } catch (Exception $e) {
            $terms_url = 'terms/';
        }

        $data = array('terms_url' => $terms_url);
        $terms = $this->render('IntrafacePublic/Shop/templates/order-accept-terms-tpl.php', $data);

        $newsletter = '';

        $data['content'] = $address.$basket.$terms;
        $data['button_continue_label'] = $this->__('Send');
        $data['button_continue_name'] = $this->__('Send');
        $data['button_back_label'] = $this->__('Back');
        $data['button_back_link'] = $this->url('../details');

        return $this->render('IntrafacePublic/Shop/templates/form-container-tpl.php', $data);

    }

    function POST()
    {
        $values = array_merge(
            $this->context->getShop()->getAddress(),
            $this->context->getShop()->getCustomerCoupon(),
            $this->context->getShop()->getCustomerComment(),
            $this->context->getShop()->getCustomerEan(),
            $this->context->getShop()->getPaymentMethod()
        );

        if (empty($values)) {
            throw new Exception('There is no valid order');
        }

        if (!isset($this->POST['accept_terms_of_trade']) || $this->POST['accept_terms_of_trade'] != '1') {
            $this->error[] = $this->__('You need to accept the terms of trade');
        } else {
            // any internal note can be given which is not visible for the customer.
            $values['internal_note'] = '';
            $values['currency'] = $this->getCurrency();

            $values = $this->triggerEvent('onBasketOrderPlaceOrder', $values);

            // we validate before we place the order
            // @todo Måske kan vi overveje at bruge
            $this->error = IntrafacePublic_Shop_Tools_ValidateDetails::validate($values);

            if (count($this->error) == 0) {
                try {
                    $order_identifier = $this->context->getShop()->placeOrder($values);
                } catch (Exception $e) {
                    // @todo Denne skal nok håndteres lidt bedre
                    throw $e;
                }

                if ($order_identifier == '') {
                    throw new Exception('An error occured while placing the order. We did not recieve the expected result.');
                }

                // If onlinepayment authorizing is set, and selected payment method is onlinepayment.
                if (false !== ($payment_authorize = $this->getOnlinePaymentAuthorize())
                    && isset($values['payment_method'])
                    && is_array($values['payment_method'])
                    && isset($values['payment_method']['identifier'])
                    && $values['payment_method']['identifier'] == 'OnlinePayment') {

                    if (false !== ($url = $payment_authorize->getRedirectUrlToPayment($order_identifier, $this->url('../receipt')))) {
                        if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://') {
                             $url = $this->url('../'.$url);
                        }
                        throw new k_http_Redirect($url);
                    }

                    throw new k_http_Redirect($this->url('../onlinepayment/'.$order_identifier));
                }

                throw new k_http_Redirect($this->url('../receipt'));
            }
        }

        return $this->GET();
    }

    public function getShop()
    {
        return $this->context->getShop();
    }

    public function getOnlinePaymentAuthorize()
    {
        return $this->context->getOnlinePaymentAuthorize();
    }

}
