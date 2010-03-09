<?php
class IntrafacePublic_Shop_Controller_Basket extends IntrafacePublic_Controller_Pluggable
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

        $this->document->current_step = 'basket';

        $this->document->purchase_steps = array();
        $this->document->purchase_steps[] = 'basket';
        $this->document->purchase_steps[] = 'details';
        $this->document->purchase_steps[] = 'order';
        if ($this->getOnlinePaymentAuthorize()) {
            $this->document->purchase_steps[] = 'payment';
        }
        $this->document->purchase_steps[] = 'receipt';
        $this->document->title = $this->__('Basket');
        $this->document->keywords = '';
        $this->document->description = '';
    }

    /**
     * Returns IntrafacePublic_Shop
     *
     * @return object IntrafacePublic_Shop
     */
    function getShop()
    {
        return $this->context->getShop();
    }

    /**
     * Returns Ilib_Payment_Authorize
     *
     * @return object Ilib_Payment_Authorize
     */
    public function getOnlinePaymentAuthorize()
    {
        return $this->context->getOnlinePaymentAuthorize();
    }

    /**
     * Returns IntrafacePublic_Onlinepayment
     *
     * @return object IntrafacePublic_Onlinepayment
     */
    public function getOnlinePayment()
    {
        return $this->context->getOnlinePayment();
    }

    function GET()
    {
        $basket = $this->context->getShop()->getBasket();
        $basket = $this->triggerEvent('preBasketGet', $basket);

        $items = $basket['items'];

        if (!empty($items) AND is_array($items) AND count($items) > 0) {
            $data = array('items' => $basket['items'],
                          'total_price' => $basket['total_price'],
                          'currency' => $this->getCurrency());
            $data = $this->triggerEvent('onBasketGetItems', $data);
            $content = $this->render('IntrafacePublic/Shop/templates/basket-tpl.php', $data);
        } else {
            $content = $this->render('IntrafacePublic/Shop/templates/basket-empty-tpl.php');
        }

        $data = array(
                'content' => $content,
                'error' => $this->getErrorHtml($this),
                'headline' => $this->__($this->document->current_step));
        $data = $this->triggerEvent('postBasketGet', $data);
        return $this->render(
            'IntrafacePublic/Shop/templates/basket-container-tpl.php',
            $data);
    }

    function POST()
    {
        $this->triggerEvent('preBasketPost');

        if (!empty($this->POST['update']) AND is_array($this->POST['items'])) {
            foreach ($this->POST['items'] as $item) {
                if (!$this->getShop()->changeBasket(intval($item['product_id']), intval($item['product_variation_id']), abs(intval($item['quantity'])))) {
                    $product = $this->getShop()->getProduct(intval($item['product_id']));
                    if ($product['product']['has_variation'] && !empty($product['variation'])) {
                        // @todo: need to implement variation name here also!
                        $name = $product['product']['name'];
                    } else {
                        $name = $product['product']['name'];
                    }
                    $this->error[$item['product_id']] =  $name. ' ' . $this->__('is not in stock in that quantity');
                }
            }
        }

        $this->triggerEvent('postBasketPost');

        return $this->GET();
    }

    function getCompanyInformation()
    {
    	return $this->getShop()->getCompanyInformation();
    }

    function forward($name)
    {
        try {
            if ($name == 'details') {
                $next = new IntrafacePublic_Shop_Controller_Basket_Details($this, $name);
            } elseif ($name == 'order') {
                $next = new IntrafacePublic_Shop_Controller_Basket_Order($this, $name);
            } elseif ($name == 'placeorder') {
                $next = new IntrafacePublic_Shop_Controller_Basket_PlaceOrder($this, $name);
            } elseif ($name == 'onlinepayment') {
                $next = new IntrafacePublic_Shop_Controller_Basket_OnlinePayment($this, $name);
            } /*elseif ($name == 'ean') {
                $next = new IntrafacePublic_Shop_Controller_Basket_EAN($this, $name);
            } */ elseif ($name == 'receipt') {
                $next = new IntrafacePublic_Shop_Controller_Basket_Receipt($this, $name);
            } else {
                throw new k_http_Response(404);
            }
            $response = $next->handleRequest();
            $headline = $next->document->current_step;
            $error = $this->getErrorHtml($next);
        } catch (IntrafacePublic_Shop_Exception_NotAllowed $e) {
            $response = $this->render('IntrafacePublic/Shop/templates/basket-novalidorder-tpl.php', array('msg' => $e->getMessage()));
            $headline = $this->document->current_step;
            $error = '';
        }

        $data = array(
            'content' => $response,
            'error' => $error,
            'headline' => $headline);
        return $this->render('IntrafacePublic/Shop/templates/basket-container-tpl.php', $data);

    }

    function getErrorHtml($context)
    {
        return $this->render('IntrafacePublic/Shop/templates/error-tpl.php', array('error' => $context->getErrors()));
    }

    function getNewsletter()
    {
        return $this->context->getNewsletter();
    }
}
