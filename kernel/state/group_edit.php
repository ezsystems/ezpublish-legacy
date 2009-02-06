<?php

$GroupID = $Params['GroupID'];
$Module = $Params['Module'];

$stateGroup = $GroupID === 'new' ? new eZContentObjectStateGroup() : eZContentObjectStateGroup::fetchById( $GroupID );

if ( !is_object( $stateGroup ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

if ( $stateGroup->isInternal() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

require_once( 'kernel/common/template.php' );

$tpl = templateInit();


if ( $Module->isCurrentAction( 'Cancel' ) )
{
    return $Module->redirectTo( 'state/groups' );
}
else if ( $Module->isCurrentAction( 'Store' ) )
{
    $stateGroup->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $stateGroup->isValid( $messages );

    if ( $isValid )
    {
        $stateGroup->store();
        return $Module->redirectTo( 'state/groups' );
    }

    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}

$tpl->setVariable( 'group', $stateGroup );

$Result = array(
    'content' => $tpl->fetch( 'design:state/group_edit.tpl' ),
    'path'    => array(
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'Group edit' ) )
    )
);

?>