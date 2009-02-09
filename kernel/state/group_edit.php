<?php

$GroupIdentifier = $Params['GroupIdentifier'];
$Module = $Params['Module'];

$group = is_null( $GroupIdentifier ) ? new eZContentObjectStateGroup() : eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

if ( $group->isInternal() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

require_once( 'kernel/common/template.php' );

$tpl = templateInit();

$currentAction = $Module->currentAction();

if ( $currentAction == 'Cancel' )
{
    return $Module->redirectTo( 'state/groups' );
}
else if ( $currentAction == 'Store' )
{
    $group->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $group->isValid( $messages );

    if ( $isValid )
    {
        $group->store();
        if ( is_null( $GroupIdentifier ) )
        {
            return $Module->redirectTo( 'state/group/' . $group->attribute( 'identifier' ) );
        }
        else
        {
            return $Module->redirectTo( 'state/groups' );
        }
    }

    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}

$tpl->setVariable( 'group', $group );

if ( is_null( $GroupIdentifier ) )
{
    $path = array(
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'New group' ) )
    );
}
else
{
    $path = array(
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'Group edit' ) ),
        array( 'url' => false, 'text' => $group->attribute( 'identifier' ) )
    );
}

$Result = array(
    'content' => $tpl->fetch( 'design:state/group_edit.tpl' ),
    'path'    => $path
);

?>