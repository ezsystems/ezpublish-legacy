<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
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
