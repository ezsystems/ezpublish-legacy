<?php
//
// Definition of Runcronworflows class
//
// Created on: <24-Sep-2003 16:09:21 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file rssimport.php
*/

include_once( 'kernel/classes/ezrssimport.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentobjectversion.php' );
include_once( 'lib/ezutils/classes/ezoperationhandler.php' );

//For ezUser, we would make this the ezUser class id but otherwise just pick and choose.

//fetch this class
$rssImportArray = eZRSSImport::fetchActiveList();

// Loop through all configured and active rss imports. If something goes wrong while processing them, continue to next import
foreach ( array_keys( $rssImportArray ) as $rssImportKey )
{
    // Get RSSImport object
    $rssImport =& $rssImportArray[$rssImportKey];
    $rssSource =& $rssImport->attribute( 'url' );
    $addCount = 0;

    if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Starting.' );
    }

    // Open and read RSSImport url
    $fid = fopen( $rssSource, 'r' );
    if ( $fid === false )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Failed to open RSS feed file: '.$rssSource );
        }
        continue;
    }

    $xmlData = "";
    do {
        $data = fread($fid, 8192);
        if (strlen($data) == 0) {
            break;
        }
        $xmlData .= $data;
    } while(true);

    fclose( $fid );

    // Create DomDocumnt from http data
    $xmlObject = new eZXML();
    $domDocument =& $xmlObject->domTree( $xmlData );

    if ( $domDocument == null or $domDocument === false )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Invalid RSS document.' );
        }
        continue;
    }

    $root =& $domDocument->root();

    if ( $root == null )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Invalid RSS document.' );
        }
        continue;
    }

    switch( $root->attributeValue( 'version' ) )
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

/*!
  Parse RSS 1.0 feed

  \param DOM root node
  \param RSS Import item
  \param cli
*/
function rssImport1( &$root, &$rssImport, &$cli )
{
    $addCount = 0;

    // Get all items in rss feed
    $itemArray =& $root->elementsByName( 'item' );

    // Loop through all items in RSS feed
    foreach ( array_keys ( $itemArray ) as $itemKey )
    {
        $item =& $itemArray[$itemKey];

        $addCount += importRSSItem( $item, $rssImport, $cli );
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
function rssImport2( &$root, &$rssImport, &$cli )
{
    $addCount = 0;

    // Get all items in rss feed
    $channel =& $root->elementByName( 'channel' );

    // Loop through all items in RSS feed
    foreach ( $channel->elementsByName( 'item' ) as $item )
    {
        $addCount += importRSSItem( $item, $rssImport, $cli );
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

 \return 1 if object added, 0 if not
*/
function importRSSItem( &$item, &$rssImport, &$cli )
{
    $rssImportID =& $rssImport->attribute( 'id' );
    $rssOwnerID =& $rssImport->attribute( 'object_owner_id' ); // Get owner user id
    $parentContentObjectTreeNode =& eZContentObjectTreeNode::fetch( $rssImport->attribute( 'destination_node_id' ) ); // Get parent treenode object

    if ( $parentContentObjectTreeNode == null )
    {
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Destination tree node seems to be unavailable' );
        }
        return 0;
    }

    $parentContentObject =& $parentContentObjectTreeNode->attribute( 'object' ); // Get parent content object

    $title =& $item->elementByName( 'title' );
    $link =& $item->elementByName( 'link' );
    $description =& $item->elementByName( 'description' );

    $md5Sum = md5( $link->textContent() );

    // Try to fetch RSSImport object with md5 sum matching link.
    $existingObject =& eZPersistentObject::fetchObject( eZContentObject::definition(), null,
                                                        array( 'remote_id' => 'RSSImport_'.$rssImportID.'_'.$md5Sum ) );

    // if object exists, continue to next import item
    if ( $existingObject != null )
    {
        unset( $existingObject ); // delete object to preserve memory
        if ( !$isQuiet )
        {
            $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Object with URL: '.$link->textContent().' already exists' );
        }
        return 0;
    }

    // Fetch class, and create ezcontentobject from it.
    $contentClass =& eZContentClass::fetch( $rssImport->attribute( 'class_id' )  );

    // Instantiate the object with user $rssOwnerID and use section id from parent. And store it.
    $contentObject =& $contentClass->instantiate( $rssOwnerID, $parentContentObject->attribute( 'section_id' ) );
    $contentObject->store();

    // Create node assignment
    $nodeAssignment =& eZNodeAssignment::create( array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                                                        'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                        'is_main' => 1,
                                                        'parent_node' => $parentContentObjectTreeNode->attribute( 'node_id' ) ) );
    $nodeAssignment->store();

    $version =& $contentObject->version( 1 );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
    $version->store();

    // Get object attributes, and set their values and store them.
    $dataMap =& $contentObject->dataMap();

    $attributeTitle =& $dataMap[$rssImport->attribute( 'class_title' )];
    if ( $attributeTitle != null && $title != null )
    {
        if ( get_class( $attributeTitle->attribute( 'content' ) ) == 'ezxmltext' )
        {
            setEZXMLAttribute( $attributeTitle, $title->textContent() );
        }
        else
        {
            $attributeTitle->setAttribute( 'data_text', $title->textContent() );
        }
        $attributeTitle->store();
    }
    else if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Could not find title map for : '.$rssImport->attribute( 'class_title' ) );
    }

    $attributeLink =& $dataMap[$rssImport->attribute( 'class_url' )];
    if ( $attributeLink != null && $link != null )
    {
        if ( get_class( $attributeLink->attribute( 'content' ) ) == 'ezxmltext' )
        {
            setEZXMLAttribute( $attributeLink, $link->textContent() );
        }
        else
        {
            $attributeLink->setContent( $link->textContent() );
        }
        $attributeLink->store();
    }
    else if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Could not find link map for : '.$rssImport->attribute( 'class_link' ) );
    }

    $attributeDescription =& $dataMap[$rssImport->attribute( 'class_description' )];
    if ( $attributeDescription != null && $description != null )
    {
        if ( get_class( $attributeDescription->attribute( 'content' ) ) == 'ezxmltext' )
        {
            setEZXMLAttribute( $attributeDescription, $description->textContent() );
        }
        else
        {
            $attributeDescription->setAttribute( 'data_text', $description->textContent() );
        }
        $attributeDescription->store();
    }
    else if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Could not find description map for : '.$rssImport->attribute( 'class_descriptione' ) );
    }

    $contentObject->setAttribute( 'remote_id', 'RSSImport_'.$rssImportID.'_'.md5( $link->textContent() ) );
    $contentObject->store();

    //publish new object
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                                 'version' => 1 ) );

    if ( !$isQuiet )
    {
        $cli->output( 'RSSImport '.$rssImport->attribute( 'name' ).': Object created; '.$title->textContent() );
    }

    return 1;
}

function setEZXMLAttribute( &$attribute, &$attributeValue, $link = false )
{
    include_once( "kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinput.php" );
    $inputData = "<?xml version=\"1.0\"?>";
    $inputData .= "<section>";
    $inputData .= "<paragraph>";
    $inputData .= $attributeValue;
    $inputData .= "</paragraph>";
    $inputData .= "</section>";

    $dumpdata = "";
    $simplifiedXMLInput = new eZSimplifiedXMLInput( $dumpdata, null, null );
    $inputData = $simplifiedXMLInput->convertInput( $inputData );
    $description = $inputData[0]->toString();
    $attribute->setAttribute( 'data_text', $description );
    $attribute->store();
}

?>
