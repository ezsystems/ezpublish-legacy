<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$http = eZHTTPTool::instance();

$Module = $Params['Module'];

$user = eZUser::currentUser();

$availableHandlers = eZNotificationEventFilter::availableHandlers();


$db = eZDB::instance();
$db->begin();
if ( $http->hasPostVariable( 'Store' ) )
{
    foreach ( $availableHandlers as $handler )
    {
        $handler->storeSettings( $http, $Module );
    }

}

foreach ( $availableHandlers as $handler )
{
    $handler->fetchHttpInput( $http, $Module );
}
$db->commit();

$viewParameters = array( 'offset' => $Params['Offset'] );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'user', $user );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:notification/settings.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/notification', 'Notification settings' ) ) );


?>
