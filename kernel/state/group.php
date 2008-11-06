<?php

$Module = $Params['Module'];
$GroupID = $Params['GroupID'];
$LanguageCode = $Params['Language'];

$stateGroup = eZContentObjectStateGroup::fetchById( $GroupID );

$languages = eZContentLanguage::fetchList();

require_once 'kernel/common/template.php';

$tpl = templateInit();

$currentAction = $Module->currentAction();

if ( $currentAction == 'Remove' && $Module->hasActionParameter( 'RemoveIDList' ) )
{
    $removeIDList = $Module->actionParameter( 'RemoveIDList' );
    $stateGroup->removeStatesByID( $removeIDList );
}
else if ( $currentAction =='Edit' )
{
    return $Module->redirectTo( 'state/group_edit/' . $GroupID );
}
else if ( $currentAction == 'Create' )
{
    $state = new eZContentObjectState( array( 'group_id' => $GroupID ) );

    $state->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $state->isValid( $messages );

    if ( $isValid )
    {
        $state->store();
        // new object instance for creating new state
        $state = new eZContentObjectState();
    }

    $tpl->setVariable( 'new_state', $state );
    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}
else if ( $currentAction == 'UpdateOrder' && $Module->hasActionParameter( 'Order' ) )
{
    $orderArray = $Module->actionParameter( 'Order' );
    asort( $orderArray );
    $stateIDList = array_keys( $orderArray );

    $stateGroup->reorderStates( $stateIDList );
}

if ( $LanguageCode )
{
    $stateGroup->setCurrentLanguage( $LanguageCode );
}

$tpl->setVariable( 'state_group', $stateGroup );
$tpl->setVariable( 'languages', $languages );

$Result = array(
    'path' => array(
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'State' ) ),
        array( 'url' => 'state/groups', 'text' => ezi18n( 'kernel/state', 'Groups' ) ),
        array( 'url' => false, 'text' => $stateGroup->attribute( 'current_translation' )->attribute( 'name' ) )
    ),
    'content' => $tpl->fetch( 'design:state/group.tpl' )
)
?>