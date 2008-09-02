<?php

$Module = $Params['Module'];
$StateID = $Params['StateID'];

$state = eZContentObjectState::fetchById( $StateID );

if ( !is_object( $state ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$groupID = $state->attribute( 'group_id' );
$redirectUrl = 'state/group/' . $groupID;

require_once( 'kernel/common/template.php' );

$tpl = templateInit();

if ( $Module->isCurrentAction( 'Cancel' ) )
{
    return $Module->redirectTo( $redirectUrl );
}
else if ( $Module->isCurrentAction( 'Store' ) )
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

$Result = array(
    'path' => array(
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'Edit' ) )
    ),
    'content' => $tpl->fetch( 'design:state/edit.tpl' )
)
?>