<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  This file is a shared code file which is used by different parts of the system
  to edit objects. This file only implements editing of attributes and uses
  hooks to allow external code to add functionality.
  \param $Module must be set by the code which includes this file
*/



if ( isset( $Module ) )
    $Module = $Params['Module'];
$ObjectID = $Params['ObjectID'];
if ( !isset( $EditVersion ) )
    $EditVersion = $Params['EditVersion'];

if ( !isset( $EditLanguage ) and
     isset( $Params['EditLanguage'] ) )
    $EditLanguage = $Params['EditLanguage'];
if ( !isset( $EditLanguage ) or
     !is_string( $EditLanguage ) or
     strlen( $EditLanguage ) == 0 )
    $EditLanguage = false;

if ( !isset( $FromLanguage ) and
     isset( $Params['FromLanguage'] ) )
    $FromLanguage = $Params['FromLanguage'];
if ( !isset( $FromLanguage ) or
     !is_string( $FromLanguage ) or
     strlen( $FromLanguage ) == 0 )
    $FromLanguage = false;

if ( $Module->runHooks( 'pre_fetch', array( $ObjectID, $EditVersion, $EditLanguage, $FromLanguage ) ) )
    return;

$object = eZContentObject::fetch( $ObjectID );
if ( $object === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );


$version = $object->version( $EditVersion );
$classID = $object->attribute( 'contentclass_id' );

$attributeDataBaseName = 'ContentObjectAttribute';


$class = eZContentClass::fetch( $classID );
$contentObjectAttributes = $version->contentObjectAttributes( $EditLanguage );
if ( $contentObjectAttributes === null or
     count( $contentObjectAttributes ) == 0 )
{
    $contentObjectAttributes = $version->contentObjectAttributes();
    $EditLanguage = $version->initialLanguageCode();
}

$fromContentObjectAttributes = false;
$isTranslatingContent = false;
if ( $FromLanguage !== false )
{
    $isTranslatingContent = true;
    $fromContentObjectAttributes = $object->contentObjectAttributes( true, false, $FromLanguage );
    if ( $fromContentObjectAttributes === null or
         count( $fromContentObjectAttributes ) == 0 )
    {
        unset( $fromContentObjectAttributes );
        $fromContentObjectAttributes = false;
        $isTranslatingContent = false;
    }
}

$http = eZHTTPTool::instance();

$validation = array( 'processed' => false,
                     'attributes' => array(),
                     'placement' => array( ) );

if ( $Module->runHooks( 'post_fetch', array( $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage, &$validation ) ) )
    return;

// Checking if user chose placement of object from browse page (when restoring from the TRASH),
// if yes object must be published without returning to edit mode.
if ( ( $http->hasSessionVariable( 'LastCurrentAction' ) ) and
     ( $http->sessionVariable( 'LastCurrentAction' ) == 'Publish' ) and
     ( $Module->isCurrentAction( 'AddNodeAssignment' ) ||
       $Module->isCurrentAction( 'AddPrimaryNodeAssignment' ) )
   )
{
    $http->removeSessionVariable( 'LastCurrentAction' );
    // Publish object
    $Module->setCurrentAction( 'Publish' );
    if ( $http->hasSessionVariable( 'BrowseForNodes_POST' ) )
    {
        // Restore post vars
        $_POST = array_merge( $_POST, $http->sessionVariable( 'BrowseForNodes_POST' ) );
        $http->removeSessionVariable( 'BrowseForNodes_POST' );
    }
}
else
{
    // Clean up session vars
    $http->removeSessionVariable( 'LastCurrentAction' );
    $http->removeSessionVariable( 'BrowseForNodes_POST' );
}

// Checking if object has at least one placement, if not user needs to choose it from browse page
$assignments = $version->attribute( 'parent_nodes' );
$assignedNodes = $object->attribute( 'assigned_nodes' );

// Figure out how many locations it has (or will get)
$locationIDList = array();
foreach ( $assignedNodes as $node )
{
    $locationIDList[$node->attribute( 'parent_node_id' )] = true;
}
foreach ( $assignments as $assignment )
{
    if ( $assignment->attribute( 'op_code' ) == eZNodeAssignment::OP_CODE_CREATE ||
         $assignment->attribute( 'op_code' ) == eZNodeAssignment::OP_CODE_SET )
    {
        $locationIDList[$assignment->attribute( 'parent_node' )] = true;
    }
    elseif ( $assignment->attribute( 'op_code' ) == eZNodeAssignment::OP_CODE_REMOVE )
    {
        unset( $locationIDList[$assignment->attribute( 'parent_node' )] );
    }
}
$locationCount = count( $locationIDList );

// If there are no locations we need to browse for one.
if ( $locationCount < 1 && $Module->isCurrentAction( 'Publish' ) )
{
//    if ( $object->attribute( 'status' ) == eZContentObject::STATUS_DRAFT )
    {
        $Module->setCurrentAction( 'BrowseForPrimaryNodes' );
        // Store currentAction
        $http->setSessionVariable( 'LastCurrentAction', 'Publish' );
        // Store post vars
        $http->setSessionVariable( 'BrowseForNodes_POST', $_POST );
    }
}



// Custom Action Code Start
$customAction = false;
$customActionAttributeArray = array();
// Check for custom actions
if ( $http->hasPostVariable( "CustomActionButton" ) )
{
    $customActionArray = $http->postVariable( "CustomActionButton" );
    foreach ( $customActionArray as $customActionKey => $customActionValue )
    {
        $customActionString = $customActionKey;

        if ( preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray ) )
        {
            $customActionAttributeID = $matchArray[1];
            $customAction = $matchArray[2];
            $customActionAttributeArray[$customActionAttributeID] = array( 'id' => $customActionAttributeID,
                                                                           'value' => $customAction );
        }
    }
}

eZContentObjectEditHandler::initialize();

$storeActions = array( 'Preview',
                       'Translate',
                       'TranslateLanguage',
                       'VersionEdit',
                       'Apply',
                       'Publish',
                       'Store',
                       'StoreExit',
//                      'Discard',
//                      'CustomAction',
                       'EditLanguage',
                       'FromLanguage',
                       'BrowseForObjects',
                       'UploadFileRelation',
                       'NewObject',
                       'BrowseForNodes',
                       'BrowseForPrimaryNodes',
                       'RemoveAssignments',
                       'DeleteRelation',
                       'DeleteNode',
                       'SectionEdit',
                       'StateEdit',
                       'MoveNode' );
$storingAllowed = ( in_array( $Module->currentAction(), $storeActions ) ||
                    eZContentObjectEditHandler::isStoreAction() );
if ( $http->hasPostVariable( 'CustomActionButton' ) )
    $storingAllowed = true;

$hasObjectInput = true;
if ( $http->hasPostVariable( 'HasObjectInput' ) )
    $hasObjectInput =  $http->postVariable( 'HasObjectInput' );

$contentObjectDataMap = array();
foreach ( $contentObjectAttributes as $contentObjectAttribute )
{
    $contentObjectAttributeIdentifier = $contentObjectAttribute->attribute( 'contentclass_attribute_identifier' );
    $contentObjectDataMap[$contentObjectAttributeIdentifier] = $contentObjectAttribute;
}

// These variables will be modified according to validation
$inputValidated = true;
$requireFixup = false;
$validatedAttributes = array();

if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
{
    // Need to detect if post_max_size has been reached. If so, all post variables are gone...
    $postMaxSize = trim( ini_get( 'post_max_size' ) );
    $postMaxSizeBytes = (int)$postMaxSize;

    // As of PHP 5.3.2 it is possible to configure unlimited post_max_size, further checking
    if ($postMaxSizeBytes !== 0)
    {
        $postMaxSizeUnit = 'b';
        // post_max_size can have values like 8M which needs to be converted to bytes
        $last = strtolower( $postMaxSize[strlen($postMaxSize)-1] );
        if ( !is_numeric( $last ) )
            $postMaxSize = substr( $postMaxSize, 0, -1 );
        switch ( $last )
        {
            case 'g':
                $postMaxSizeBytes *= 1073741824; // = 1024 * 1024 * 1024
                $postMaxSizeUnit = 'Gb';
                break;
            case 'm':
                $postMaxSizeBytes *= 1048576; // = 1024 * 1024
                $postMaxSizeUnit = 'Mb';
                break;
            case 'k':
                $postMaxSizeBytes *= 1024;
                $postMaxSizeUnit = 'Kb';
                break;
        }
        if ( (int)$_SERVER['CONTENT_LENGTH'] > $postMaxSizeBytes &&  // This is not 100% acurrate as $_SERVER['CONTENT_LENGTH'] doesn't only count post data but also other things
            count( $_POST ) === 0 )                                 // Therefore we also check if request got no post variables.
        {
            $validation['attributes'][] = array( 'id' => '1',
                                                 'identified' => 'generalid',
                                                 'name' => ezpI18n::tr( 'kernel/content', 'Error' ),
                                                 'description' => ezpI18n::tr( 'kernel/content', 'The request sent to the server was too big to be accepted. This probably means that you uploaded a file which was too big. The maximum allowed request size is %max_size_string.', null, array( '%max_size_string' => "$postMaxSize $postMaxSizeUnit" ) ) );
            $validation['processed'] = true;
        }
    }
}

if ( $storingAllowed && $hasObjectInput)
{
    // Disable checking 'is_required' flag for some actions.
    $validationParameters = array( 'skip-isRequired' => false );
    if ( $Module->isCurrentAction( 'Store' ) ||             // 'store draft'
         $http->hasPostVariable( 'CustomActionButton' ) )   // 'custom action' like 'Find object' for 'object relation' datatype.
    {
        $validationParameters['skip-isRequired'] = true;
    }

    // Validate input
    $validationResult = $object->validateInput( $contentObjectAttributes, $attributeDataBaseName, false, $validationParameters );
    $unvalidatedAttributes = $validationResult['unvalidated-attributes'];
    $validatedAttributes = $validationResult['validated-attributes'];
    $inputValidated = $validationResult['input-validated'];

    // Fixup input
    if ( $validationResult['require-fixup'] )
        $object->fixupInput( $contentObjectAttributes, $attributeDataBaseName );

    $validation['custom_rules'] = array();
    $customValidationResult = eZContentObjectEditHandler::validateInputHandlers( $Module, $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage, $validationParameters );
    if ( $customValidationResult['warnings'] )
        $validation['custom_rules'] = $customValidationResult['warnings'];

    $inputValidated = ( $inputValidated && $customValidationResult['validated'] );

    // Check extension input handlers
    eZContentObjectEditHandler::executeInputHandlers( $Module, $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage );

    // If no redirection uri we assume it's content/edit
    if ( !isset( $currentRedirectionURI ) )
        $currentRedirectionURI = $Module->redirectionURI( 'content', 'edit', array( $ObjectID, $EditVersion, $EditLanguage ) );

    $fetchResult = $object->fetchInput( $contentObjectAttributes, $attributeDataBaseName,
                                        $customActionAttributeArray,
                                        array( 'module' => $Module,
                                               'current-redirection-uri' => $currentRedirectionURI ) );
    $attributeInputMap = $fetchResult['attribute-input-map'];
    if ( $Module->isCurrentAction( 'Discard' ) )
        $inputValidated = true;

    // If an input is invalid, prevent from redirection that might be set by a custom action
    if ( !$inputValidated && $Module->exitStatus() == eZModule::STATUS_REDIRECT )
        $Module->setExitStatus( eZModule::STATUS_OK );

    $db = eZDB::instance();
    if ( !empty( $attributeInputMap ) )
    {
        if ( $inputValidated )
        {
            if ( $Module->runHooks( 'pre_commit', array( $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage ) ) )
                return;
            $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
        }

        $version->setAttribute( 'modified', time() );

        $db->begin();
        $version->store();
//         print( "storing<br/>" );
        // Tell attributes to store themselves if necessary
        $object->storeInput( $contentObjectAttributes,
                             $attributeInputMap );
        $db->commit();
        ezpEvent::getInstance()->notify(
            'content/cache/version',
            array( $object->attribute( 'id' ), $version->attribute( 'version' ) )
        );
    }

    $validation['processed'] = true;
    $validation['attributes'] = $unvalidatedAttributes;

    $db = eZDB::instance();
    $db->begin();
    $object->setName( $class->contentObjectName( $object, $version->attribute( 'version' ), $EditLanguage ), $version->attribute( 'version' ), $EditLanguage );
    $db->commit();
  
    // While being fetched, attributes might have been modified.
    // The list needs to be refreshed so it is accurately displayed.
    $contentObjectAttributes = $version->contentObjectAttributes( $EditLanguage );
}
elseif ( $storingAllowed )
{
    if ( !isset( $currentRedirectionURI ) )
        $currentRedirectionURI = $Module->redirectionURI( 'content', 'edit', array( $ObjectID, $EditVersion, $EditLanguage ) );
    eZContentObject::recursionProtectionStart();
    foreach( $contentObjectAttributes as $contentObjectAttribute )
    {
        $object->handleCustomHTTPActions( $contentObjectAttribute,  $attributeDataBaseName,
                                          $customActionAttributeArray,
                                          array( 'module' => $Module,
                                                 'current-redirection-uri' => $currentRedirectionURI ) );
        $contentObjectAttribute->setContent( $contentObjectAttribute->attribute( 'content' ) );
    }
    eZContentObject::recursionProtectionEnd();
}

$invalidNodeAssignmentList = array();
if ( $Module->isCurrentAction( 'Publish' ) )
{
    $mainFound = false;
    $assignments = $version->attribute( 'parent_nodes' );
    $db = eZDB::instance();
    $db->begin();
    foreach ( array_keys( $assignments ) as $key )
    {
        // Check that node assignment node exists.
        if ( !$assignments[$key]->attribute( 'parent_node_obj' ) )
        {
            $validation[ 'placement' ][] = array( 'text' => ezpI18n::tr( 'kernel/content', 'A node in the node assignment list has been deleted.' ) );
            $validation[ 'processed' ] = true;
            $inputValidated = false;
            $invalidNodeAssignmentList[] = $assignments[$key]->attribute( 'parent_node' );
            $assignments[$key]->remove();
            unset( $assignments[$key] );
            eZDebugSetting::writeDebug( 'kernel-content-edit', "placement is not validated" );
        }

        if ( isset( $assignments[$key] ) &&
             $assignments[$key]->attribute( 'is_main' ) == 1 )
        {
            $mainFound = true;
            break;
        }
    }
    $db->commit();
    if ( !$mainFound and count( $assignments ) > 0 )
    {
        if( eZPreferences::value( 'admin_edit_show_locations' ) == '0' )
        {
            $validation[ 'placement' ][] = array( 'text' => ezpI18n::tr( 'kernel/content', 'No main node selected, please select one.' ) );
            $validation[ 'processed' ] = true;
            $inputValidated = false;
            eZDebugSetting::writeDebug( 'kernel-content-edit', "placement is not validated" );
        }
    }
    else
        eZDebugSetting::writeDebug( 'kernel-content-edit', "placement is validated" );

}

// After the object has been validated we can check for other actions
$Result = '';
if ( $inputValidated == true )
{
    if ( $validatedAttributes == null )
    {
        if ( $Module->runHooks( 'action_check', array( $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage, &$Result  ) ) )
            return;
    }
}
else if ( $http->hasPostVariable( 'PublishAfterConflict' ) )
{
    if ( $http->postVariable( 'PublishAfterConflict' ) == 1 )
    {
        if ( $Module->runHooks( 'action_check', array( $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $FromLanguage, &$Result  ) ) )
            return;
    }
}

if ( isset( $Params['TemplateObject'] ) )
    $tpl = $Params['TemplateObject'];

if ( !isset( $tpl ) || !( $tpl instanceof eZTemplate ) )
    $tpl = eZTemplate::factory();

$tpl->setVariable( 'validation', $validation );
$tpl->setVariable( 'validation_log', $validatedAttributes );

$tpl->setVariable( 'invalid_node_assignment_list', $invalidNodeAssignmentList );

$Module->setTitle( 'Edit ' . $class->attribute( 'name' ) . ' - ' . $object->attribute( 'name' ) );
$res = eZTemplateDesignResource::instance();

$assignments = $version->attribute( 'parent_nodes' );
$mainAssignment = false;
foreach ( $assignments as $assignment )
{
    if ( $assignment->attribute( 'is_main' ) == 1 )
    {
        $mainAssignment = $assignment;
        break;
    }
}

$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                      array( 'remote_id', $object->attribute( 'remote_id' ) ),
                      array( 'class', $class->attribute( 'id' ) ),
                      array( 'class_identifier', $class->attribute( 'identifier' ) ),
                      array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) )
                      ) );

if ( $mainAssignment )
{
    $parentNode = $mainAssignment->attribute( 'parent_node_obj' );
    if ( $parentNode )
    {
        $parentObject = $parentNode->attribute( 'object' );
        if ( $parentObject )
        {
            $parentClass = $parentObject->attribute( 'content_class' );
            if ( $parentClass )
            {
                $res->setKeys( array( array( 'parent_class', $parentClass->attribute( 'id' ) ),
                                      array( 'parent_class_identifier', $parentClass->attribute( 'identifier' ) ) ) );
            }
        }
    }
}

if ( !isset( $OmitSectionSetting ) )
    $OmitSectionSetting = false;
if ( $OmitSectionSetting !== true )
{
    $sectionID = $object->attribute( 'section_id' );
    $sectionIdentifier = '';
    $section = eZSection::fetch( $sectionID );
    if ( $section instanceof eZSection )
    {
        $sectionIdentifier = $section->attribute( 'identifier' );
    }

    $res->setKeys( array( array( 'section', $object->attribute( 'section_id' ) ),
                          array( 'section_identifier', $sectionIdentifier ) ) );
}

$object->setCurrentLanguage( $EditLanguage );

$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'edit_language', $EditLanguage );
$tpl->setVariable( 'from_language', $FromLanguage );
$tpl->setVariable( 'content_version', $version );
$tpl->setVariable( 'http', $http );
$tpl->setVariable( 'content_attributes', $contentObjectAttributes );
$tpl->setVariable( 'from_content_attributes', $fromContentObjectAttributes );
$tpl->setVariable( 'from_content_attributes_grouped_data_map', $fromContentObjectAttributes ? eZContentObject::createGroupedDataMap( $fromContentObjectAttributes ) : array() );
$tpl->setVariable( 'is_translating_content', $isTranslatingContent );
$tpl->setVariable( 'content_attributes_data_map', $contentObjectDataMap );
$tpl->setVariable( 'content_attributes_grouped_data_map', eZContentObject::createGroupedDataMap( $contentObjectAttributes ) );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'attribute_base', $attributeDataBaseName );

$objectIsDraft = $object->attribute( 'status' ) == eZContentObject::STATUS_DRAFT;

// If the object has been published we disable the location UI
$tpl->setVariable( "location_ui_enabled", $objectIsDraft );

// Give templates easy access to check if object is draft
$tpl->setVariable( "object_is_draft", $objectIsDraft );


if ( $Module->runHooks( 'pre_template', array( $class, $object, $version, $contentObjectAttributes, $EditVersion, $EditLanguage, $tpl, $FromLanguage ) ) )
    return;

$templateName = 'design:content/edit.tpl';

if ( isset( $Params['TemplateName'] ) )
    $templateName = $Params['TemplateName'];

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}
// $viewParameters contains $UserParameters only
$viewParameters = $UserParameters;
// "view_parameters" is available also in edit.tpl's templates
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( $templateName );
$Result['view_parameters'] = $viewParameters;
// $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Content' ),
//                                 'url' => false ),
//                          array( 'text' => ezpI18n::tr( 'kernel/content', 'Edit' ),
//                                 'url' => false ),
//                          array( 'text' => $object->attribute( 'name' ),
//                                 'url' => false ) );

$path = array();
$titlePath = array();

$hasPath = false;
if ( $mainAssignment )
{
    $parentNode = $mainAssignment->attribute( 'parent_node_obj' );
    if ( $parentNode )
    {
        $parents = $parentNode->attribute( 'path' );

        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' )
                             );
        }

        if ( $parentNode->attribute( 'name' ) != null )
        {
            $path[] = array( 'text' => $parentNode->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parentNode->attribute( 'node_id' ),
                             'url_alias' => $parentNode->attribute( 'url_alias' ),
                             'node_id' => $parentNode->attribute( 'node_id' ) );
        }

        $objectPathElement = array( 'text' => $object->attribute( 'name' ),
                                    'url' => false,
                                    'url_alias' => false );
        $existingNode = $object->attribute( 'main_node' );
        if ( $existingNode )
        {
            $objectPathElement['url'] = '/content/view/full/' . $existingNode->attribute( 'node_id' );
            $objectPathElement['url_alias'] = $existingNode->attribute( 'url_alias' );
            $objectPathElement['node_id'] = $existingNode->attribute( 'node_id' );
        }
        $path[] = $objectPathElement;
        $hasPath = true;
    }
}
if ( !$hasPath )
{
    $existingNode = $object->attribute( 'main_node' );
    if ( $existingNode )
    {
        $parents = $existingNode->attribute( 'path' );

        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' )
                             );
        }
        $path[] = array( 'text' => $existingNode->attribute( 'name' ),
                         'url' => '/content/view/full/' . $existingNode->attribute( 'node_id' ),
                         'url_alias' => $existingNode->attribute( 'url_alias' ),
                         'node_id' => $existingNode->attribute( 'node_id' ) );
        $hasPath = true;
    }
}
if ( !$hasPath )
{
    $path[] = array( 'text' => $object->attribute( 'name' ),
                     'url' => false );
}

$Result['path'] = $path;

$section = eZSection::fetch( $object->attribute( 'section_id' ) );
if ( $section )
{
    $Result['navigation_part'] = $section->attribute( 'navigation_part_identifier' );
    $Result['section_id'] = $section->attribute( 'id' );
}

?>
