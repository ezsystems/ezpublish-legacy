<?php
//
// Created on: <24-Sep-2003 16:09:21 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file rssimport.php
*/

//include_once( 'kernel/classes/ezrssimport.php' );
//include_once( 'kernel/classes/ezcontentclass.php' );
//include_once( 'kernel/classes/ezcontentobject.php' );
//include_once( 'kernel/classes/ezpersistentobject.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/ezcontentobjectversion.php' );
//include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
//include_once( "lib/ezdb/classes/ezdb.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );

//For ezUser, we would make this the ezUser class id but otherwise just pick and choose.

//fetch this class
$rssImportArray = eZRSSImport::fetchActiveList();

// Loop through all configured and active rss imports. If something goes wrong while processing them, continue to next import
foreach ( $rssImportArray as $rssImport )
{
    // Get RSSImport object
    $rssSource = $rssImport->attribute( 'url' );
    $addCount = 0;

    if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Starting.' );
    }

    $xmlData = eZHTTPTool::getDataByURL( $rssSource, false, 'eZ Publish RSS Import' );
    if ( $xmlData === false )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Failed to open RSS feed file: '.$rssSource );
        }
        continue;
    }

    // Create DomDocument from http data
    $domDocument = new DOMDocument( '1.0', 'utf-8' );
    $success = $domDocument->loadXML( $xmlData );

    if ( !$success )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Invalid RSS document.' );
        }
        continue;
    }

    $root = $domDocument->documentElement;

    switch( $root->getAttribute( 'version' ) )
    {
        default:
        case '1.0':
        {
            $version = '1.0';
        } break;

        case '0.91':
        case '0.92':
        case '2.0':
        {
            $version = $root->getAttribute( 'version' );
        } break;
    }

    $importDescription = $rssImport->importDescription();
    if ( $version != $importDescription['rss_version'] )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Invalid RSS version missmatch. Please reconfigure import.' );
        }
        continue;
    }

    switch( $root->getAttribute( 'version' ) )
    {
        default:
        case '1.0':
        {
            rssImport1( $root, $rssImport, $cli );
        } break;

        case '0.91':
        case '0.92':
        case '2.0':
        {
            rssImport2( $root, $rssImport, $cli );
        } break;
    }

}

//include_once( 'kernel/classes/ezstaticcache.php' );
eZStaticCache::executeActions();

/*!
  Parse RSS 1.0 feed

  \param DOM root node
  \param RSS Import item
  \param cli
*/
function rssImport1( $root, $rssImport, $cli )
{
    global $isQuiet;

    $addCount = 0;

    // Get all items in rss feed
    $itemArray = $root->getElementsByTagName( 'item' );
    $channel = $root->getElementsByTagName( 'channel' )->item( 0 );

    // Loop through all items in RSS feed
    foreach ( $itemArray as $item )
    {
        $addCount += importRSSItem( $item, $rssImport, $cli, $channel );
    }

    if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': End. '.$addCount.' objects added' );
    }

}

/*!
  Parse RSS 2.0 feed

  \param DOM root node
  \param RSS Import item
  \param cli
*/
function rssImport2( $root, $rssImport, $cli )
{
    global $isQuiet;

    $addCount = 0;

    // Get all items in rss feed
    $channel = $root->getElementsByTagName( 'channel' )->item( 0 );

    // Loop through all items in RSS feed
    foreach ( $channel->getElementsByTagName( 'item' ) as $item )
    {
        $addCount += importRSSItem( $item, $rssImport, $cli, $channel );
    }

    if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': End. '.$addCount.' objects added' );
    }

}

/*!
 Import specifiec rss item into content tree

 \param RSS item xml element
 \param $rssImport Object
 \param cli
 \param channel

 \return 1 if object added, 0 if not
*/
function importRSSItem( $item, $rssImport, $cli, $channel )
{
    global $isQuiet;
    $rssImportID = $rssImport->attribute( 'id' );
    $rssOwnerID = $rssImport->attribute( 'object_owner_id' ); // Get owner user id
    $parentContentObjectTreeNode = eZContentObjectTreeNode::fetch( $rssImport->attribute( 'destination_node_id' ) ); // Get parent treenode object

    if ( $parentContentObjectTreeNode == null )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Destination tree node seems to be unavailable' );
        }
        return 0;
    }

    $parentContentObject = $parentContentObjectTreeNode->attribute( 'object' ); // Get parent content object
    $titleElement = $item->getElementsByTagName( 'title' )->item( 0 );
    $title = is_object( $titleElement ) ? $titleElement->textContent : '';

    // Test for link or guid as unique identifier
    $link = $item->getElementsByTagName( 'link' )->item( 0 );
    $guid = $item->getElementsByTagName( 'guid' )->item( 0 );
    if ( $link->textContent )
    {
        $md5Sum = md5( $link->textContent );
    }
    elseif ( $guid->textContent )
    {
        $md5Sum = md5( $guid->textContent );
    }
    else
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Item has no unique identifier. RSS guid or link missing.' );
        }
        return 0;
    }

    // Try to fetch RSSImport object with md5 sum matching link.
    $existingObject = eZPersistentObject::fetchObject( eZContentObject::definition(), null,
                                                       array( 'remote_id' => 'RSSImport_'.$rssImportID.'_'.$md5Sum ) );

    // if object exists, continue to next import item
    if ( $existingObject != null )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Object ( ' . $existingObject->attribute( 'id' ) . ' ) with URL: '.$linkURL.' already exists' );
        }
        unset( $existingObject ); // delete object to preserve memory
        return 0;
    }

    // Fetch class, and create ezcontentobject from it.
    $contentClass = eZContentClass::fetch( $rssImport->attribute( 'class_id' )  );

    // Instantiate the object with user $rssOwnerID and use section id from parent. And store it.
    $contentObject = $contentClass->instantiate( $rssOwnerID, $parentContentObject->attribute( 'section_id' ) );

    $db = eZDB::instance();
    $db->begin();
    $contentObject->store();
    $contentObjectID = $contentObject->attribute( 'id' );

    // Create node assignment
    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObjectID,
                                                       'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                       'is_main' => 1,
                                                       'parent_node' => $parentContentObjectTreeNode->attribute( 'node_id' ) ) );
    $nodeAssignment->store();

    $version = $contentObject->version( 1 );
    $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
    $version->store();

    // Get object attributes, and set their values and store them.
    $dataMap = $contentObject->dataMap();
    $importDescription = $rssImport->importDescription();

    // Set content object attribute values.
    $classAttributeList = $contentClass->fetchAttributes();
    foreach( $classAttributeList as $classAttribute )
    {
        $classAttributeID = $classAttribute->attribute( 'id' );
        if ( isset( $importDescription['class_attributes'][$classAttributeID] ) )
        {
            if ( $importDescription['class_attributes'][$classAttributeID] == '-1' )
            {
                continue;
            }

            $importDescriptionArray = explode( ' - ', $importDescription['class_attributes'][$classAttributeID] );
            if ( count( $importDescriptionArray ) < 1 )
            {
                $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Invalid import definition. Please redit.' );
                break;
            }

            $elementType = $importDescriptionArray[0];
            array_shift( $importDescriptionArray );
            switch( $elementType )
            {
                case 'item':
                {
                    setObjectAttributeValue( $dataMap[$classAttribute->attribute( 'identifier' )],
                                             recursiveFindRSSElementValue( $importDescriptionArray,
                                                                           $item ) );
                } break;

                case 'channel':
                {
                    setObjectAttributeValue( $dataMap[$classAttribute->attribute( 'identifier' )],
                                             recursiveFindRSSElementValue( $importDescriptionArray,
                                                                           $channel ) );
                } break;
            }
        }
    }

    $contentObject->setAttribute( 'remote_id', 'RSSImport_'.$rssImportID.'_'. $md5Sum );
    $contentObject->store();
    $db->commit();

    // Publish new object. The user id is sent to make sure any workflow 
    // requiring the user id has access to it.
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                                 'version' => 1,
                                                                                 'user_id' => $rssOwnerID ) );

    if ( !isset( $operationResult['status'] ) || $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE )
    {
        if ( isset( $operationResult['result'] ) && isset( $operationResult['result']['content'] ) )
            $failReason = $operationResult['result']['content'];
        else
            $failReason = "unknown error";
        $cli->error( "Publishing failed: $failReason" );
        unset( $failReason );
    }

    $db->begin();
    unset( $contentObject );
    unset( $version );
    $contentObject = eZContentObject::fetch( $contentObjectID );
    $version = $contentObject->attribute( 'current' );
    // Set object Attributes like modified and published timestamps
    $objectAttributeDescription = $importDescription['object_attributes'];
    foreach( $objectAttributeDescription as $identifier => $objectAttributeDefinition )
    {
        if ( $objectAttributeDefinition == '-1' )
        {
            continue;
        }

        $importDescriptionArray = explode( ' - ', $objectAttributeDefinition );

        $elementType = $importDescriptionArray[0];
        array_shift( $importDescriptionArray );
        switch( $elementType )
        {
            default:
            case 'item':
            {
                $domNode = $item;
            } break;

            case 'channel':
            {
                $domNode = $channel;
            } break;
        }

        switch( $identifier )
        {
            case 'modified':
            {
                $dateTime = recursiveFindRSSElementValue( $importDescriptionArray,
                                                          $domNode );
                if ( !$dateTime )
                {
                    break;
                }
                $contentObject->setAttribute( $identifier, strtotime( $dateTime ) );
                $version->setAttribute( $identifier, strtotime( $dateTime ) );
            } break;

            case 'published':
            {
                $dateTime = recursiveFindRSSElementValue( $importDescriptionArray,
                                                          $domNode );
                if ( !$dateTime )
                {
                    break;
                }
                $contentObject->setAttribute( $identifier, strtotime( $dateTime ) );
                $version->setAttribute( 'created', strtotime( $dateTime ) );
            } break;
        }
    }
    $version->store();
    $contentObject->store();
    $db->commit();

    if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Object created; ' . $title );
    }

    return 1;
}

function recursiveFindRSSElementValue( $importDescriptionArray, $xmlDomNode )
{
    if ( !is_array( $importDescriptionArray ) )
    {
        return false;
    }

    $valueType = $importDescriptionArray[0];
    array_shift( $importDescriptionArray );
    switch( $valueType )
    {
        case 'elements':
        {
            if ( count( $importDescriptionArray ) == 1 )
            {
                $element = $xmlDomNode->getElementsByTagName( $importDescriptionArray[0] )->item( 0 );

                $resultText = is_object( $element ) ? $element->textContent : false;
                return $resultText;
            }
            else
            {
                $elementName = $importDescriptionArray[0];
                array_shift( $importDescriptionArray );
                return recursiveFindRSSElementValue( $importDescriptionArray, $xmlDomNode->getElementsByTagName( $elementName )->item( 0 ) );
            }
        }

        case 'attributes':
        {
            return $xmlDomNode->getAttribute( $importDescriptionArray[0] );
        } break;
    }
}

function setObjectAttributeValue( $objectAttribute, $value )
{
    if ( $value === false )
    {
        return;
    }

    $dataType = $objectAttribute->attribute( 'data_type_string' );
    switch( $dataType )
    {
        case 'ezxmltext':
        {
            setEZXMLAttribute( $objectAttribute, $value );
        } break;

        case 'ezurl':
        {
            $objectAttribute->setContent( $value );
        } break;

        case 'ezkeyword':
        {
            //include_once( 'kernel/classes/datatypes/ezkeyword/ezkeyword.php' );
            $keyword = new eZKeyword();
            $keyword->initializeKeyword( $value );
            $objectAttribute->setContent( $keyword );
        } break;

        case 'ezdatetime':
        case 'ezdate':
        {
            $timestamp = strtotime( $value );
            if ( $timestamp )
                $objectAttribute->setAttribute( 'data_int', $timestamp );
        } break;

        default:
        {
            $objectAttribute->setAttribute( 'data_text', $value );
        } break;
    }

    $objectAttribute->store();
}

function setEZXMLAttribute( $attribute, $attributeValue, $link = false )
{
    //include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinputparser.php' );
    $contentObjectID = $attribute->attribute( "contentobject_id" );
    $parser = new eZSimplifiedXMLInputParser( $contentObjectID, false, 0, false );

    $attributeValue = str_replace( "\r", '', $attributeValue );
    $attributeValue = str_replace( "\n", '', $attributeValue );
    $attributeValue = str_replace( "\t", ' ', $attributeValue );

    $document = $parser->process( $attributeValue );
    if ( !is_object( $document ) )
    {
        $cli = eZCLI::instance();
        $cli->output( 'Error in xml parsing' );
        return;
    }
    $domString = eZXMLTextType::domString( $document );

    $attribute->setAttribute( 'data_text', $domString );
    $attribute->store();
}

?>
