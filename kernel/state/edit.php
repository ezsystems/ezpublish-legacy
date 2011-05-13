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

$group = eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

if ( $group->isInternal() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

$state = $StateIdentifier ? $group->stateByIdentifier( $StateIdentifier ) : $group->newState();

if ( !is_object( $state ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$redirectUrl = "state/group/$GroupIdentifier";


$tpl = eZTemplate::factory();

$currentAction = $Module->currentAction();

if ( $currentAction == 'Cancel' )
{
    return $Module->redirectTo( $redirectUrl );
}
else if ( $currentAction == 'Store' )
{
    $state->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $state->isValid( $messages );

    if ( $isValid )
    {
        $state->store();
        return $Module->redirectTo( $redirectUrl );
    }

    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}

$tpl->setVariable( 'state', $state );
$tpl->setVariable( 'group', $group );

if ( $StateIdentifier === null )
{
    $path = array(
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'New' ) ),
        array( 'url' => false, 'text' => $GroupIdentifier )
    );
}
else
{
    $path = array(
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'Edit' ) ),
        array( 'url' => false, 'text' => $GroupIdentifier ),
        array( 'url' => false, 'text' => $StateIdentifier ),
    );
}

$Result = array(
    'path' => $path,
    'content' => $tpl->fetch( 'design:state/edit.tpl' )
);

?>
