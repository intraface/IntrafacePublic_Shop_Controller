<?php
require_once 'config.local.php';

set_include_path(INTRAFACEPUBLIC_SHOP_INCLUDE_PATH);

require_once 'k.php';
require_once 'Ilib/ClassLoader.php';

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