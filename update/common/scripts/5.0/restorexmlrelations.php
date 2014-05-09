#!/usr/bin/env php
<?php
/**
 * File containing the script to restore missing relations from XML blocks
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package update
 * @see Issue 19174
 */

require "autoload.php";

$script = eZScript::instance(
    array(
        "description" => "eZ Publish missing relations sanitizer script (#19174).",
        "use-session" => false,
        "use-modules" => false,
        "use-extensions" => true,
    )
);

$script->startup();

$options = $script->getOptions();

$script->initialize();

$cli = eZCLI::instance();

$affectedClasses = getClassList();
$languages = eZContentLanguage::fetchList();
if ( count( $languages ) < 2 )
    $script->shutdown( 0, "This upgrade script is only required for installations that have more than one language" );

$totalUpdatedRelations = 0;
foreach( $affectedClasses as $affectedClassId => $classAttributeIdentifiers )
{
    $count = eZContentObject::fetchListCount( array( 'contentclass_id' => $affectedClassId ) );

    if ( $count > 0 )
    {
        $done = 0;
        do
        {
            $objects = eZContentObject::fetchList( true, array( 'contentclass_id' => $affectedClassId ), $done, 100 );
            foreach( $objects as $object )
            {
                $updatedRelations = restoreXmlRelations( $object, $classAttributeIdentifiers );
                if ( $updatedRelations )
                    $cli->output( str_repeat( '.', $updatedRelations ), false );
                $totalUpdatedRelations += $updatedRelations;
            }
            $done += count( $objects );
        }
        while( $done < $count );
    }
}

if ( $totalUpdatedRelations )
{
    $cli->output();
    $cli->output();
    $cli->output( "Restored $totalUpdatedRelations relations" );
}

$script->shutdown();

/**
 * Returns the ids of content classes that have an xmltext attribute
 * @return array(contentclass_id=>array(contentclassattribute_id))
 */
function getClassList()
{
    $affectedClasses = array();
    $classAttributes = eZContentClassAttribute::fetchFilteredList( array( 'data_type_string' => 'ezxmltext', 'version' => eZContentClass::VERSION_STATUS_DEFINED ), false );
    foreach( $classAttributes as $classAttribute )
    {
        $contentClassId = $classAttribute['contentclass_id'];
        if ( !isset( $affectedClasses[$contentClassId] ) )
        {
            $affectedClasses[$contentClassId] = array();
        }
        $affectedClasses[$contentClassId][] = $classAttribute['identifier'];
    }
    return $affectedClasses;
}

/**
 * Parses the XML for the attributes in $classAttributeIdentifiers, and fixes the relations for $object
 * @param eZContentObject $object
 * @param array $classAttributeIdentifiers
 * @return int The number of created relations
 */
function restoreXmlRelations( eZContentObject $object, array $classAttributeIdentifiers )
{
    $currentVersion = $object->currentVersion();
    $langMask = $currentVersion->attribute( 'language_mask' );
    $languageList = eZContentLanguage::decodeLanguageMask( $langMask, true );
    $languageList = $languageList['language_list'];

    // nothing to do if the object isn't translated
    if ( count( $languageList ) < 2 )
        return 0;

    $attributeArray = $object->fetchAttributesByIdentifier(
        $classAttributeIdentifiers,
        $currentVersion->attribute( 'version' ),
        $languageList
    );

    $embedRelationsCount = $object->relatedContentObjectCount( false, 0, array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
    $linkRelationsCount = $object->relatedContentObjectCount( false, 0, array( 'AllRelations' => eZContentObject::RELATION_LINK ) );

    $embeddedObjectIdArray = $linkedObjectIdArray = array();
    foreach ( $attributeArray as $attribute )
    {
        $xmlText = eZXMLTextType::rawXMLText( $attribute );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        if ( !$dom->loadXML( $xmlText ) )
            continue;

        // linked objects
        $linkedObjectIdArray = array_merge(
            $linkedObjectIdArray,
            getRelatedObjectList( $dom->getElementsByTagName( 'link' ) )
        );

        // embedded objects
        $embeddedObjectIdArray = array_merge(
            $embeddedObjectIdArray,
            getRelatedObjectList( $dom->getElementsByTagName( 'embed' ) ),
            getRelatedObjectList( $dom->getElementsByTagName( 'embed-inline' ) )
        );
    }

    $doCommit = false;
    $restoredRelations = 0;
    if ( !empty( $embeddedObjectIdArray ) )
    {
        $object->appendInputRelationList( $embeddedObjectIdArray, eZContentObject::RELATION_EMBED );
        $restoredRelations += count( $embeddedObjectIdArray ) - $embedRelationsCount;
        $doCommit = true;
    }

    if ( !empty( $linkedObjectIdArray ) )
    {
        $object->appendInputRelationList( $linkedObjectIdArray, eZContentObject::RELATION_LINK );
        $restoredRelations += count( $linkedObjectIdArray ) - $linkRelationsCount;
        $doCommit = true;
    }

    if ( $doCommit )
        $object->commitInputRelations( $currentVersion->attribute( 'version' ) );

    return $restoredRelations;
}

/**
 * Extracts ids of embedded/linked objects in an eZXML DOMNodeList
 * @param DOMNodeList $domNodeList
 * @return array
 */
function getRelatedObjectList( DOMNodeList $domNodeList )
{
    $embeddedObjectIdArray = array();
    foreach( $domNodeList as $embed )
    {
        if ( $embed->hasAttribute( 'object_id' ) )
        {
            $embeddedObjectIdArray[] = $embed->getAttribute( 'object_id' );
        }
        elseif ( $embed->hasAttribute( 'node_id' ) )
        {
            if ( $object = eZContentObject::fetchByNodeID( $embed->getAttribute( 'node_id' ) ) )
            {
                $embeddedObjectIdArray[] = $object->attribute( 'id' );
            }
        }
    }
    return $embeddedObjectIdArray;
}

?>
