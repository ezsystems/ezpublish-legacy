<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$LanguageCode = $Params['Language'];
$http = eZHTTPTool::instance();
$ClassID = null;
$validation = array( 'processed' => false,
                     'groups' => array() );

if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];
$ClassVersion = null;

if ( !is_numeric( $ClassID ) )
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

$class = eZContentClass::fetch( $ClassID, true, eZContentClass::VERSION_STATUS_DEFINED );

if ( !$class )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$LanguageCode)
    $LanguageCode = $class->attribute( 'top_priority_language_locale' );

if ( $http->hasPostVariable( 'AddGroupButton' ) && $http->hasPostVariable( 'ContentClass_group' ) )
{
    eZClassFunctions::addGroup( $ClassID, $ClassVersion, $http->postVariable( 'ContentClass_group' ) );
}

if ( $http->hasPostVariable( 'RemoveGroupButton' ) && $http->hasPostVariable( 'group_id_checked' ) )
{
    if ( !eZClassFunctions::removeGroup( $ClassID, $ClassVersion, $http->postVariable( 'group_id_checked' ) ) )
    {
        $validation['groups'][] = array( 'text' => ezpI18n::tr( 'kernel/class', 'You have to have at least one group that the class belongs to!' ) );
        $validation['processed'] = true;
    }
}

$attributes = $class->fetchAttributes();
$datatypes = eZDataType::registeredDataTypes();

$mainGroupID = false;
$mainGroupName = false;
$groupList = $class->fetchGroupList();
if ( count( $groupList ) > 0 )
{
    $mainGroupID = $groupList[0]->attribute( 'group_id' );
    $mainGroupName = $groupList[0]->attribute( 'group_name' );
}

$Module->setTitle( "Edit class " . $class->attribute( "name" ) );

$tpl = eZTemplate::factory();
$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'class', $class->attribute( "id" ) ),
                      array( 'class_identifier', $class->attribute( 'identifier' ) ) ) );

$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'language_code', $LanguageCode );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'attributes', $attributes );
$tpl->setVariable( 'datatypes', $datatypes );
$tpl->setVariable( 'validation', $validation );
$tpl->setVariable( 'scheduled_script_id', (int) $Params['ScheduledScriptID'] );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:class/view.tpl' );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ) );
if ( $mainGroupID !== false )
{
    $Result['path'][] = array( 'url' => '/class/classlist/' . $mainGroupID,
                               'text' => $mainGroupName );
}
$Result['path'][] = array( 'url' => false,
                           'text' => $class->attribute( 'name' ) );

?>
