<?php
class IntrafacePublic_Shop_Controller_Basket extends IntrafacePublic_Controller_Pluggable
{
    protected $error = array();
    protected $template;

    function __construct(k_TemplateFactory $template)
    {
        $this->template = $template;
    }
    /*
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
     */

    function map($name)
    {
        if ($name == 'details') {
            return 'IntrafacePublic_Shop_Controller_Basket_Details';
        } elseif ($name == 'order') {
            return 'IntrafacePublic_Shop_Controller_Basket_Order';
        } elseif ($name == 'placeorder') {
            return 'IntrafacePublic_Shop_Controller_Basket_PlaceOrder';
        } elseif ($name == 'onlinepayment') {
            return 'IntrafacePublic_Shop_Controller_Basket_OnlinePayment';
        } elseif ($name == 'receipt') {
            return 'IntrafacePublic_Shop_Controller_Basket_Receipt';
        }

        parent::map($name);
    }

    /*
    function __forward($name)
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
            } elseif ($name == 'receipt') {
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
    */

    function wrapHtml($content)
    {
        $data = array(
            'content' => $content,
            'error' => $this->getErrorHtml($this->context->getErrors()),
            'headline' => $this->document->currentStep());

        $tpl = $this->template->create('IntrafacePublic/Shop/templates/basket-container');
        return $tpl->render($this, $data);
    }

    function renderHtml()
    {
        $basket = $this->context->getShop()->getBasket();
        $basket = $this->triggerEvent('preBasketGet', $basket);

        $items = $basket['items'];

        if (!empty($items) AND is_array($items) AND count($items) > 0) {
            $data = array('items' => $basket['items'],
                          'total_price' => $basket['total_price'],
                          'currency' => $this->getCurrency());
            $data = $this->triggerEvent('onBasketGetItems', $data);
            $tpl = $this->template->create('IntrafacePublic/Shop/templates/basket');
            $content = $tpl->render($this, $data);
        } else {
            $tpl = $this->template->create('IntrafacePublic/Shop/templates/basket-empty');
            $content = $tpl->render($this);
        }

        $data = array(
                'content' => $content,
                'error' => $this->getErrorHtml(),
                'headline' => $this->document()->currentStep()
        );

        $data = $this->triggerEvent('postBasketGet', $data);
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/basket-container');
        return $tpl->render($this, $data);
    }

    function postForm()
    {
        $this->triggerEvent('preBasketPost');

        if (is_array($this->body('items'))) {
            foreach ($this->body('items') as $item) {
                if (!$this->getShop()->changeBasket(intval($item['product_id']), intval($item['product_variation_id']), abs(intval($item['quantity'])))) {
                    $product = $this->getShop()->getProduct(intval($item['product_id']));
                    if ($product['product']['has_variation'] && !empty($product['variation'])) {
                        // @todo: need to implement variation name here also!
                        $name = $product['product']['name'];
                    } else {
                        $name = $product['product']['name'];
                    }
                    $this->error[$item['product_id']] =  $name. ' ' . $this->t('is not in stock in that quantity');
                }
            }
        }

        $this->triggerEvent('postBasketPost');

        return $this->render();
    }

    function getCompanyInformation()
    {
        return $this->getShop()->getCompanyInformation();
    }

    function getCurrency()
    {
        return $this->context->getCurrency();
    }

    function getErrors()
    {
        return $this->error;
    }

    function getErrorHtml()
    {
        $tpl = $this->template->create('IntrafacePublic/Shop/templates/error');
        return $tpl->render($this, array('error' => $this->getErrors()));
    }

    function getNewsletter()
    {
        return $this->context->getNewsletter();
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
}
