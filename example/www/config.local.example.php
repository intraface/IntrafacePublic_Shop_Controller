<?php
$GLOBALS['path_include'] = dirname(__FILE__) . '../../src/IntrafacePublic/Shop/templates/' . PATH_SEPARATOR .  dirname(__FILE__) . '/../../src/' .PATH_SEPARATOR . get_include_path();

$GLOBALS['intraface_private_key'] = 'xxx';
$GLOBALS['intraface_shop_id'] = 0;
$GLOBALS["onlinepayment_merchant"] = 0;
$GLOBALS["onlinepayment_md5secret"] = 'xxx';
$GLOBALS["error_log"] = realpath('../errorlog/error.log');
$GLOBALS["cache_dir"] = dirname(__FILE__) . "/cache/";

set_include_path($GLOBALS['path_include']);
