<?php
require_once 'config.local.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';
require_once 'Ilib/ClassLoader.php';

class EnglishLanguage implements k_Language
{
    function name()
    {
        return 'English';
    }

    function isoCode()
    {
        return 'en';
    }
}

class MyLanguageLoader implements k_LanguageLoader
{
    function load(k_Context $context)
    {
        if ($context->query('lang') == 'en') {
            return new EnglishLanguage();
        }
        return new EnglishLanguage();
    }
}

class SimpleTranslator implements k_Translator
{
    protected $phrases;

    function __construct($phrases = array())
    {
        $this->phrases = $phrases;
    }

    function translate($phrase, k_Language $language = null)
    {
        return isset($this->phrases[$phrase]) ? $this->phrases[$phrase] : $phrase;
    }
}

class SimpleTranslatorLoader implements k_TranslatorLoader
{
    function load(k_Context $context)
    {
        $phrases = array();
        return new SimpleTranslator($phrases);
    }
}

class Intraface_TemplateFactory extends k_DefaultTemplateFactory
{
    function create($filename)
    {
        $filename = $filename . '.tpl.php';
        $__template_filename__ = k_search_include_path($filename);
        if (!is_file($__template_filename__)) {
            throw new Exception("Failed opening '".$filename."' for inclusion. (include_path=".ini_get('include_path').")");
        }
        return new k_Template($__template_filename__);
    }
}

class Factory
{
    function new_IntrafacePublic_Shop()
    {
        return new IntrafacePublic_Shop($this->new_IntrafacePublic_Shop_Client_XMLRPC(), $this->new_Cache_Lite());
    }

    function new_IntrafacePublic_Shop_Client_XMLRPC()
    {
        $session_id = session_id();
        $options = array(
            "private_key" => $GLOBALS['intraface_private_key'],
            "session_id" => md5($session_id));
        $debug = false;
        return new IntrafacePublic_Shop_Client_XMLRPC($options, $GLOBALS['intraface_shop_id'], $debug);
    }

    function new_Cache_Lite()
    {
        $options = array(
           "cacheDir" => dirname(__FILE__) . "/",
           "lifeTime" => 3600
        );
        return new Cache_Lite($options);
    }

    function new_k_TemplateFactory()
    {
        return new Intraface_TemplateFactory(dirname(__FILE__) . '/../../src/IntrafacePublic/Shop/templates/');
    }
}

class IntrafacePublic_Shop_Document extends k_Document
{
    protected $current_step;

    function locale()
    {
        return 'enus';
    }

    function currentStep()
    {
        return $this->current_step;
    }

    function purchaseSteps()
    {
        return array('details', 'order', 'payment', 'receipt');
    }

    function setCurrentStep($step)
    {
        $this->current_step = $step;
    }
}

$bucket = new bucket_Container(new Factory);
$components = new k_InjectorAdapter($bucket, new IntrafacePublic_Shop_Document);

if (realpath($_SERVER['SCRIPT_FILENAME']) == __FILE__) {
    try {
        k()
        // Use container for wiring of components
        ->setComponentCreator($components)
        // Enable file logging
        //->setLog(K2_LOG)
        // Uncomment the next line to enable in-browser debugging
        //->setDebug(K2_DEBUG)
        // Dispatch request
        ->setLanguageLoader(new MyLanguageLoader())->setTranslatorLoader(new SimpleTranslatorLoader())
        //->setIdentityLoader(new Intraface_IdentityLoader())
        //->setLanguageLoader(new Intraface_LanguageLoader())
        //->setTranslatorLoader(new Intraface_TranslatorLoader())
        ->run('IntrafacePublic_Shop_Controller_Index')
        ->out();
    } catch (ErrorException $e) {
        die($e->getMessage());
    }
}
