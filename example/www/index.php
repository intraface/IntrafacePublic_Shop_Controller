<?php
require_once 'config.local.php';
require_once 'konstrukt/konstrukt.inc.php';
require_once 'bucket.inc.php';
require_once 'Ilib/ClassLoader.php';

class Intraface_LanguageLoader implements k_LanguageLoader {
    // @todo The language will often not be set on runtime, e.g. an
    //       intranet where the user can chose him or her own language?
    //       How could one accommodate for this?
    function load(k_Context $context)
    {
        $supported = array("da" => true, "en-US" => true);

        if ($context->identity()->anonymous()) {
            $language = HTTP::negotiateLanguage($supported);
            if (PEAR::isError($language)) {
                // fallback language in case of unable to negotiate
                return new DanishLanguage();
            }

            if ($language == 'da') {
                return new DanishLanguage();
            }

        } elseif ($context->identity()->language() == 'da') {
            return new DanishLanguage();
        }

        // @todo at the moment the system does not take the
        //       settings in the system into account - only
        //       the way the browser is setup.
        $language = HTTP::negotiateLanguage($supported);
        if (PEAR::isError($language)) {
            // fallback language in case of unable to negotiate
            return new DanishLanguage();
        }

        if ($language == 'da') {
            return new DanishLanguage();
        }

        // fallback language
        return new EnglishLanguage();
    }
}

class k_Translation2Translator implements k_Translator
{
    protected $translation2;
    protected $page_id;
    protected $page;

    function __construct($lang, $page_id = NULL)
    {
        $factory = new Intraface_Factory;
        $cache = $factory->new_Translation2_Cache();

        if($page_id == NULL) {
            $cache_key = 'common';
        } else {
            $cache_key = $page_id;
        }

        if($data = $cache->get($cache_key, 'translation-'.$lang)) {
            $this->page = unserialize($data);
        } else {
            $translation2 = $factory->new_Translation2();
            $res = $translation2->setLang($lang);
            if (PEAR::isError($res)) {
                throw new Exception('Could not setLang()');
            }

            $this->page = $translation2->getPage('common');
            if($page_id != NULL) {
                $this->page = array_merge($this->page, $translation2->getPage($page_id));
            }

            $cache->save(serialize($this->page), $cache_key, 'translation-'.$lang);
        }

        $this->page_id = $page_id;
        $this->lang = $lang;
    }

    function translate($phrase, k_Language $language = null)
    {
        /*
        $lang = $this->translation2->getLang();
        if (PEAR::isError($lang)) {
            $res = $this->translation2->setLang($language->isoCode());
        }
        */
        /*
        if ($this->page_id !== null) {
            if ($phrase != $this->translation2->get($phrase, $this->page_id)) {
                return utf8_encode($this->translation2->get($phrase, $this->page_id));
            }
        }

        return utf8_encode($this->translation2->get($phrase, 'common'));
        */

        if(isset($this->page[$phrase])) {
            return utf8_encode($this->page[$phrase]);
        }

        $logger = new ErrorHandler_Observer_File(ERROR_LOG);
        $details = array(
                'date' => date('r'),
                'type' => 'Translation2',
                'message' => 'Missing translation for "'.$phrase.'" on pageID: "'.$this->page_id.'", LangID: "'.$this->lang.'"',
                'file' => '[unknown]',
                'line' => '[unknown]'
            );

        $logger->update($details);

        return $phrase;

    }

    public function get($phrase)
    {
        return $this->translate($phrase);
    }
}

class Intraface_TranslatorLoader implements k_TranslatorLoader
{
    function load(k_Context $context)
    {
        $subspace = explode('/', $context->subspace());
        if (count($subspace) > 3 && $subspace[1] == 'restricted' && $subspace[2] == 'module' && !empty($subspace[3])) {
            $module = $subspace[3];
        } else {
            $module = NULL;
        }
        return new k_Translation2Translator($context->language()->isoCode(), $module);
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

/*
$application = new Root();

$application->registry->registerConstructor('shop', create_function(
  '$className, $args, $registry',
  'return new IntrafacePublic_Shop($registry->get("client"), $registry->get("cache"));'
));

$application->registry->registerConstructor('client', create_function(
  '$className, $args, $registry',
  '$session_id = $registry->SESSION->getSessionId();
   $options = array("private_key" => INTRAFACE_PRIVATE_KEY,
                    "session_id" => md5($session_id));
   $debug = false;
   return new IntrafacePublic_Shop_Client_XMLRPC2($options, SITE_ID, $debug, INTRAFACE_XMLSERVER);'
));

$application->registry->registerConstructor('cache', create_function(
  '$className, $args, $registry',
  '
   $options = array(
       "cacheDir" => dirname(__FILE__) . "/",
       "lifeTime" => 3600,
       "pearErrorMode" => CACHE_LITE_ERROR_DIE
   );
   return new Cache_Lite($options);'
));

$application->dispatch();
*/


class Factory
{
    function new_IntrafacePublic_Shop()
    {
        return new IntrafacePublic_Shop($this->new_IntrafacePublic_Shop_Client_XMLRPC(), $this->new_Cache_Lite());
    }

    function new_IntrafacePublic_Shop_Client_XMLRPC()
    {
         $session_id = uniqid();
         $options = array("private_key" => INTRAFACE_PRIVATE_KEY,
                            "session_id" => md5($session_id));
         $debug = false;
         return new IntrafacePublic_Shop_Client_XMLRPC($options, SITE_ID, $debug);
    }

    function new_Cache_Lite()
    {
        $options = array(
           "cacheDir" => dirname(__FILE__) . "/",
           "lifeTime" => 3600,
           "pearErrorMode" => CACHE_LITE_ERROR_DIE
       );
       return new Cache_Lite($options);

    }
}

$bucket = new bucket_Container(new Factory);
$components = new k_InjectorAdapter($bucket);

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
        //->setIdentityLoader(new Intraface_IdentityLoader())
        //->setLanguageLoader(new Intraface_LanguageLoader())
        //->setTranslatorLoader(new Intraface_TranslatorLoader())
        ->run('IntrafacePublic_Shop_Controller_Index')
        ->out();
    } catch (ErrorException $e) {
        die($e->getMessage());
    }
}