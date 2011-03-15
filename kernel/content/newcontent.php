<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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
