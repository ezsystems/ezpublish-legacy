<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$tpl = eZTemplate::factory();
$user = eZUser::currentUser();

$tpl->setVariable( "view_parameters", $Params['UserParameters'] );
$tpl->setVariable( 'last_visit_timestamp', $user->lastVisit() );

$Result['content'] = $tpl->fetch( 'design:content/newcontent.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'New content' ),
                                'url' => false ) );


?>
