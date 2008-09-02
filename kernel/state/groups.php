<?php

$Module = $Params['Module'];
$offset = $Params['Offset'];

$listLimitPreferenceName = 'admin_state_group_list_limit';
$listLimitPreferenceValue = eZPreferences::value( $listLimitPreferenceName );

switch( $listLimitPreferenceValue )
{
    case '2': { $limit = 25; } break;
    case '3': { $limit = 50; } break;
    default:  { $limit = 10; } break;
}

$languages = eZContentLanguage::fetchList();

require_once( 'kernel/common/template.php' );

$tpl = templateInit();

eZDebug::writeDebug( $Module->currentAction() );
if ( $Module->isCurrentAction( 'Remove' ) && $Module->hasActionParameter( 'RemoveIDList' ) )
{
    $removeIDList = $Module->actionParameter( 'RemoveIDList' );

    foreach ( $removeIDList as $removeID )
    {
        eZContentObjectStateGroup::removeByID( $removeID );
    }
}
else if ( $Module->isCurrentAction( 'Create' ) )
{
    $stateGroup = new eZContentObjectStateGroup();

    $stateGroup->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $stateGroup->isValid( $messages );

    if ( $isValid )
    {
        $stateGroup->store();
        return $Module->redirectToView( 'group', array( $stateGroup->attribute( 'id' ) ) );
    }

    $tpl->setVariable( 'new_group', $stateGroup );
    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}

$groups = eZContentObjectStateGroup::fetchByOffset( $limit, $offset );
$groupCount = eZPersistentObject::count( eZContentObjectStateGroup::definition() );

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'list_limit_preference_name', $listLimitPreferenceName );
$tpl->setVariable( 'list_limit_preference_value', $listLimitPreferenceValue );
$tpl->setVariable( 'groups', $groups );
$tpl->setVariable( 'group_count', $groupCount );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'languages', $languages );

$Result = array(
    'content' => $tpl->fetch( 'design:state/groups.tpl' ),
    'path'    => array(
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezi18n( 'kernel/state', 'Groups' ) )
    )
);

?>