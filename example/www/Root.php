<?php
class Root extends k_Dispatcher
{
    public $map = array('shop' => 'IntrafacePublic_Shop_Controller_Index');
    public $debug = true;

    function GET()
    {
        return get_class($this) . ' intentionally left blank';
    }
    
    private function getCredentials()
    {
        return array("private_key" => '', /* replace with you private key */
            "session_id" => md5($this->registry->get("k_http_Session")->getSessionId()));
    }

    function getShop()
    {
        return $this->registry->get("shop");
    }
    
    /**
     * Implement when want to change titles on specific pages.
     */
    /*
    public function getBreadcrumpTrailPageTitles()
    {
        return array(
            'products' => 'Show all'
        );
    }
    */
    
    /**
     * Country regions used with Ilib_Countries
     * 
     * Implement when you want to reduce available countries in list
     */
    /*
    public function getAvailableCountryRegions() 
    {
        return 'Western Europe';
    }
    */
    
    /**
     * Implement if using onlinepayment
     */
    /*
    public function getOnlinePayment()
    {

        return $this->registry->get("onlinepayment");
    }
    */
    
    /**
     * Implement when using onlinepayment
     */
    /*
    public function getOnlinePaymentAuthorize()
    {        
        return $this->registry->get("onlinepayment:authorize");
    }
    */
    
    /**
     * Implement when want to use newsletter with shop
     */
    /*
    public function getNewsletter()
    {
        return $this->registry->get('newsletter');
    }
    */
    
    /**
     * Enables translation and the use of function __() in templates
     * 
     * NOTICE about encoding: Translation2 takes utf8 encoded string and returns default in utf8
     * konstrukt provides string in iso-8859-1 encoding and requires the same in return. Therefore
     * decorator UTF8 is used and utf8_encode.
     * 
     * @param string $phrase
     * @return string translated phrase
     */
    function __($phrase)
    {
       

        if (empty($this->translation)) {

            $this->translation = new Ilib_Translation_Collection;
            
            $translator = Ilib_Countries_Translation::factory();
            $translator->setLang('da');
            $translator = $translator->getDecorator('UTF8');
            $this->translation->addTranslator($translator);
            
            $translator = IntrafacePublic_Shop_Translation::factory();
            $translator->setLang('da');
            $translator = $translator->getDecorator('DefaultText');
            $translator = $translator->getDecorator('UTF8');
            $this->translation->addTranslator($translator);
            
        }

        return $this->translation->get(utf8_encode($phrase));

    }

    function execute()
    {
        throw new k_http_Redirect($this->url('shop'));
    }
}