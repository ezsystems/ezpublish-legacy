<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$user = eZUser::currentUser();
$userID = $user->id();


$tpl = eZTemplate::factory();
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/pendinglist.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'My pending list' ),
                                'url' => false ) );
?>
