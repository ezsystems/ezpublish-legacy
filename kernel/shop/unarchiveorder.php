<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$orderIDArray = $http->sessionVariable( "OrderIDArray" );

$db = eZDB::instance();
$db->begin();
foreach ( $orderIDArray as $archiveID )
{
    eZOrder::unarchiveOrder( $archiveID );
}
$db->commit();
$Module->redirectTo( '/shop/archivelist/' );
?>
