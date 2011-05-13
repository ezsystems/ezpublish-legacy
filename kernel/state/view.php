<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$GroupIdentifier = $Params['GroupIdentifier'];
$StateIdentifier = $Params['StateIdentifier'];
$LanguageCode = $Params['Language'];

$group = eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$state = $group->stateByIdentifier( $StateIdentifier );

if ( !is_object( $state ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$currentAction = $Module->currentAction();

if ( $currentAction == 'Edit' )
{
    return $Module->redirectTo( "state/edit/$GroupIdentifier/$StateIdentifier" );
}

if ( $LanguageCode )
{
    $state->setCurrentLanguage( $LanguageCode );
}



$tpl = eZTemplate::factory();
$tpl->setVariable( 'group', $group );
$tpl->setVariable( 'state', $state );

$Result = array(
    'content' => $tpl->fetch( 'design:state/view.tpl' ),
    'path' => array(
        array( 'url' => false,
               'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => 'state/group/' . $group->attribute( 'identifier' ),
               'text' => $group->attribute( 'identifier' ) ),
        array( 'url' => false,
               'text' => $state->attribute( 'identifier' ) )
    )
);

?>
