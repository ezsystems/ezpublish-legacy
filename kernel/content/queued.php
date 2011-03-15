<?php
/**
 * File containing the content/queued view.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 * @subpackage content
 */

$tpl = eZTemplate::factory();
$module = $Params['Module'];
$http = eZHTTPTool::instance();

$pContentObjectId = $Params['ContentObjectID'];
$pVersion = $Params['version'];

$tpl->setVariable( 'contentObjectId', $pContentObjectId );
$tpl->setVariable( 'version', $pVersion );

$virtualNodeID = 0;
$contentObject = eZContentObject::fetch( $pContentObjectId );
$contentObjectVersion = $contentObject->version( $pVersion );
$nodeAssignments = $contentObjectVersion->attribute( 'node_assignments' );
$contentClass = eZContentClass::fetch( $contentObject->attribute( 'contentclass_id' ) );
$section = eZSection::fetch( $contentObject->attribute( 'section_id' ) );
$navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

$res = eZTemplateDesignResource::instance();
$designKeys = array( array( 'object', $contentObject->attribute( 'id' ) ), // Object ID
                     array( 'node', $virtualNodeID ), // Fake node id
                     array( 'remote_id', $contentObject->attribute( 'remote_id' ) ),
                     array( 'class', $contentClass->attribute( 'id' ) ), // Class ID
                     array( 'class_identifier', $contentClass->attribute( 'identifier' ) ), // Class identifier
                     array( 'class_group', $contentObject->attribute( 'match_ingroup_id_list' ) ),
                     array( 'state', $contentObject->attribute( 'state_id_array' ) ),
                     array( 'state_identifier', $contentObject->attribute( 'state_identifier_array' ) ),
                     array( 'section', $contentObject->attribute( 'section_id' ) ),
                     array( 'section_identifier', $section->attribute( 'identifier' ) )
              );
$res->setKeys( $designKeys );

if ( $http->hasSessionVariable( 'RedirectURIAfterPublish' ) )
    $tpl->setVariable( 'redirect_uri', $http->sessionVariable( 'RedirectURIAfterPublish' ) );

$tpl->setVariable( 'content_object', $contentObject );
$tpl->setVariable( 'content_object_version', $contentObjectVersion );
$tpl->setVariable( 'content_class', $contentClass );

$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Content' ),
                                 'url' => false ),
                          array( 'text' => ezpI18n::tr( 'kernel/content', 'Publishing queue' ),
                                 'url' => false ),
                          array( 'text' => $contentObject->attribute( 'name' ),
                                 'url' => false ) );

$Result['content'] = $tpl->fetch( 'design:content/queued.tpl' );
$Result['navigation_part'] = $navigationPartIdentifier;
return $Result;
?>