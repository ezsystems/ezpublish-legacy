<?php

include_once( 'lib/ezutils/classes/ezsys.php' );
$sys =& eZSys::instance();

$sys->AccessPath = array( 'blog' );
$sys->SiteDir = '/var/www/html';
$sys->WWWDir = '/ezpublish-3.4';
$sys->IndexFile = '/index.php';
$sys->RequestURI = '/admin/content/view/full/2';

include_once( 'lib/ezutils/classes/ezhttptool.php' );
$http =& eZHTTPTool::instance();
$http->setPostVariable( 'SearchText', 'content management system' );
$http->setGetVariable( 'SearchText', 'content management framework' );

?>
