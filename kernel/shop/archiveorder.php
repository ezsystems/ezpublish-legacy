<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*! \file
*/


$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$archiveIDArray = $http->sessionVariable( "OrderIDArray" );

$db = eZDB::instance();
$db->begin();
foreach ( $archiveIDArray as $archiveID )
{
    eZOrder::archiveOrder( $archiveID );
}
$db->commit();
$Module->redirectTo( '/shop/orderlist/' );
?>
