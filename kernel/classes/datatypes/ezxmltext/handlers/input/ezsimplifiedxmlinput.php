<?php
//
// Definition of eZSimplifiedXMLInput class
//
// Created on: <28-Jan-2003 13:28:39 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZSimplifiedXMLInput extends eZXMLInputHandler
{
    function eZSimplifiedXMLInput( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        // Initialize size array for image.
        $imageIni =& eZINI::instance( 'image.ini' );
        if ( $imageIni->hasVariable( 'AliasSettings', 'AliasList' ) )
        {
            $sizeArray = $imageIni->variable( 'AliasSettings', 'AliasList' );
            $sizeArray[] = 'original';
        }
        else
            $sizeArray = array( 'original' );

        $this->eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute );
        $this->SubTagArray['section'] = $this->SectionArray;
        $this->SubTagArray['paragraph'] = array_merge( $this->BlockTagArray, $this->InLineTagArray );
        $this->SubTagArray['header'] = array( );
        $this->SubTagArray['table'] = array( 'tr' );
        $this->SubTagArray['tr'] = array( 'td', 'th' );
        $this->SubTagArray['td'] = $this->SubTagArray['section'];
        $this->SubTagArray['th'] = $this->SubTagArray['section'];
        $this->SubTagArray['ol'] = array( 'li' );
        $this->SubTagArray['ul'] = array( 'li' );
        $this->SubTagArray['literal'] = array( );
        $this->SubTagArray['custom'] = $this->SectionArray;
        $this->SubTagArray['object'] = array( );
        $this->SubTagArray['li'] = $this->SubTagArray['section'];
        $this->SubTagArray['strong'] = $this->InLineTagArray;
        $this->SubTagArray['emphasize'] = $this->InLineTagArray;
        $this->SubTagArray['link'] = $this->InLineTagArray;
        $this->SubTagArray['anchor'] = $this->InLineTagArray;
        $this->SubTagArray['line'] = $this->InLineTagArray;

        $this->TagAttributeArray['table'] = array( 'class' => array( 'required' => false ),
                                                   'width' => array( 'required' => false ),
                                                   'border' => array( 'required' => false ) );

        $this->TagAttributeArray['link'] = array( 'class' => array( 'required' => false ),
                                                  'href' => array( 'required' => false ),
                                                  'id' => array( 'required' => false ),
                                                  'target' => array( 'required' => false ) );

        $this->TagAttributeArray['anchor'] = array( 'name' => array( 'required' => true ) );

        $this->TagAttributeArray['object'] = array( 'class' => array( 'required' => false ),
                                                    'id' => array( 'required' => true ),
                                                    'size' => array( 'required' => false, 'value' => $sizeArray),
                                                    'align' => array( 'required' => false ),
                                                    'view' => array( 'required' => false ),
                                                    'ezurl_href' => array( 'required' => false ),
                                                    'ezurl_id' => array( 'required' => false ),
                                                    'ezurl_target' => array( 'required' => false ) );

        $this->TagAttributeArray['custom'] = array( 'name' => array( 'required' => true ) );

        $this->TagAttributeArray['header'] = array( 'class' => array( 'required' => false ),
                                                    'level' => array( 'required' => false ),
                                                    'anchor_name' => array( 'required' => false ) );

        $this->TagAttributeArray['td'] = array( 'class' => array( 'required' => false ),
                                                'width' => array( 'required' => false ),
                                                'colspan' => array( 'required' => false ),
                                                'rowspan' => array( 'required' => false ) );

        $this->TagAttributeArray['th'] = array( 'class' => array( 'required' => false ),
                                                'width' => array( 'required' => false ),
                                                'colspan' => array( 'required' => false ),
                                                'rowspan' => array( 'required' => false ) );

        $this->TagAttributeArray['ol'] = array( 'class' => array( 'required' => false ) );

        $this->TagAttributeArray['li'] = array( 'class' => array( 'required' => false ) );

        $this->TagAttributeArray['ul'] = array( 'class' => array( 'required' => false ) );

        $this->TagAttributeArray['literal'] = array( 'class' => array( 'required' => false ) );

        $this->TagAttributeArray['strong'] = array( 'class' => array( 'required' => false ) );

        $this->TagAttributeArray['emphasize'] = array( 'class' => array( 'required' => false ) );

        $this->IsInputValid = true;
        $this->ContentObjectAttribute = $contentObjectAttribute;
    }

    /*!
     \reimp
     Validates the input and returns true if the input was valid for this datatype.
    */
    function &validateInput( &$http, $base, &$contentObjectAttribute )
    {
        $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion = $contentObjectAttribute->attribute('version');
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttributeID ) )
        {
            $data =& $http->postVariable( $base . "_data_text_" . $contentObjectAttributeID );

//             eZDebug::writeDebug($data, "input data");
            // Set original input to a global variable
            $originalInput = "originalInput_" . $contentObjectAttributeID;
            $GLOBALS[$originalInput] = $data;

            // Set input valid true to a global variable
            $isInputValid = "isInputValid_" . $contentObjectAttributeID;
            $GLOBALS[$isInputValid] = true;

            $inputData = "<section xmlns:image='http://ez.no/namespaces/ezpublish3/image/' xmlns:xhtml='http://ez.no/namespaces/ezpublish3/xhtml/' xmlns:custom='http://ez.no/namespaces/ezpublish3/custom/' >";
            $inputData .= "<paragraph>";
            $inputData .= $data;
            $inputData .= "</paragraph>";
            $inputData .= "</section>";
            $data =& $this->convertInput( $inputData );
            $message = $data[1];
            if ( $this->IsInputValid == false )
            {
                $GLOBALS[$isInputValid] = false;
                $errorMessage = null;
                foreach ( $message as $line )
                {
                    $errorMessage .= $line .";";
                }
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     $errorMessage ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
            else
            {
                // Remove all url-object links to this attribute.
                eZURLObjectLink::removeURLlinkList( $contentObjectAttributeID, $contentObjectAttributeVersion );
                $dom = $data[0];
                $objects =& $dom->elementsByName( 'object' );
                if ( $objects !== null )
                {
                    $editVersion = $contentObjectAttribute->attribute('version');
                    $editObjectID = $contentObjectAttribute->attribute('contentobject_id');
                    $editObject =& eZContentObject::fetch( $editObjectID );
                    // Fetch ID's of all embeded objects
                    $relatedObjectIDArray = array();
                    foreach ( array_keys( $objects ) as $objectKey )
                    {
                        $object =& $objects[$objectKey];
                        $objectID = $object->attributeValue( 'id' );
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;
                    }
                    // Check that all embeded objects exists in database
                    $db =& eZDB::instance();

                    $objectIDInSQL = implode( ', ', $relatedObjectIDArray );
                    $objectQuery = "SELECT id FROM ezcontentobject WHERE id IN ( $objectIDInSQL )";
                    $objectRowArray =& $db->arrayQuery( $objectQuery );

                    $existingObjectIDArray = array();
                    if ( count( $objectRowArray ) > 0 )
                    {
                        foreach ( array_keys( $objectRowArray ) as $key )
                        {
                            $existingObjectIDArray[] = $objectRowArray[$key]['id'];
                        }
                    }

                    if ( count( array_diff( $relatedObjectIDArray, $existingObjectIDArray ) ) > 0 )
                    {
                        $GLOBALS[$isInputValid] = false;
                        $objectIDString = implode( ', ', array_diff( $relatedObjectIDArray, $existingObjectIDArray ) );
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Object %1 does not exist.', false, array( $objectIDString ) ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }

                    // Fetch existing related objects
                    $relatedObjectQuery = "SELECT to_contentobject_id
                                           FROM ezcontentobject_link
                                           WHERE from_contentobject_id = $contentObjectID AND
                                                 from_contentobject_version = $editVersion";

                    $relatedObjectRowArray =& $db->arrayQuery( $relatedObjectQuery );
                    // Add existing embeded objects to object relation list if it is not already
                    $existingRelatedObjectIDArray = array();
                    foreach ( $relatedObjectRowArray as $relatedObjectRow )
                    {
                        $existingRelatedObjectIDArray[] = $relatedObjectRow['to_contentobject_id'];
                    }

                    if ( array_diff( $relatedObjectIDArray, $existingRelatedObjectIDArray ) )
                    {
                        $diffIDArray = array_diff( $relatedObjectIDArray, $existingRelatedObjectIDArray );
                        foreach ( $diffIDArray as $relatedObjectID )
                        {
                            $editObject->addContentObjectRelation( $relatedObjectID, $editVersion );
                        }
                    }

                    foreach ( array_keys( $objects ) as $objectKey )
                    {
                        $object =& $objects[$objectKey];
                        $objectID = $object->attributeValue( 'id' );
                        $currentObject =& eZContentObject::fetch( $objectID );

                        // If there are any image object with links.
                        $href = $object->attributeValueNS( 'ezurl_href', "http://ez.no/namespaces/ezpublish3/image/" );
                        $urlID = $object->attributeValueNS( 'ezurl_id', "http://ez.no/namespaces/ezpublish3/image/" );

                        if ( $href != null )
                        {
                            $linkID =& eZURL::registerURL( $href );
                            $linkObjectLink =& eZURLObjectLink::fetch( $linkID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                            if ( $linkObjectLink == null )
                            {
                                $linkObjectLink =& eZURLObjectLink::create( $linkID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                                $linkObjectLink->store();
                            }
                            $object->appendAttribute( $dom->createAttributeNodeNS( 'http://ez.no/namespaces/ezpublish3/image/', 'image:ezurl_id', $linkID ) );
                            $object->removeNamedAttribute( 'ezurl_href' );
                        }

                        if ( $urlID != null )
                        {
                            $url =& eZURL::url( $urlID );
                            if (  $url == null )
                            {
                                $GLOBALS[$isInputValid] = false;
                                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                     'Link %1 does not exist.',
                                                                                     false, array( $urlID ) ) );
                                return EZ_INPUT_VALIDATOR_STATE_INVALID;
                            }
                            else
                            {
                                $linkObjectLink =& eZURLObjectLink::fetch( $urlID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                                if ( $linkObjectLink == null )
                                {
                                    $linkObjectLink =& eZURLObjectLink::create( $urlID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                                    $linkObjectLink->store();
                                }
                            }
                        }
                    }
                }

                $links =& $dom->elementsByName( 'link' );

                if ( $links !== null )
                {
                    /* $editVersion = $contentObjectAttribute->attribute( 'version' );
                    $editObjectID = $contentObjectAttribute->attribute( 'contentobject_id' );
                    foreach ( array_keys( $links ) as $linkKey )
                    {
                        $link =& $links[$linkKey];
                        if ( $link->attributeValue( 'id' ) != null )
                        {
                            $linkID = $link->attributeValue( 'id' );
                            $url =& eZURL::url( $linkID );
                            if ( $url == null )
                            {
                                $GLOBALS[$isInputValid] = false;
                                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                     'Link %1 does not exist.',
                                                                                     false, array( $linkID ) ) );
                                return EZ_INPUT_VALIDATOR_STATE_INVALID;
                            }
                            else
                            {
                                $linkObjectLink =& eZURLObjectLink::fetch( $linkID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                                if ( $linkObjectLink == null )
                                {
                                    $linkObjectLink =& eZURLObjectLink::create( $linkID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                                    $linkObjectLink->store();
                                }
                            }
                        }
                    }*/


                    $urlArray = array();
                    // Optimized for speed, find all url's then fetch from DB
                    foreach ( array_keys( $links ) as $linkKey )
                    {
                        $link =& $links[$linkKey];
                        // Cache all URL's not converted to relations
                        if ( $link->attributeValue( 'href' ) != null )
                        {
                            $url = $link->attributeValue( 'href' );

                            if ( !in_array( $url, $urlArray ) )
                                $urlArray[] = $url;
                        }

                        if ( $link->attributeValue( 'id' ) != null )
                        {
                            $linkID = $link->attributeValue( 'id' );
                            $url =& eZURL::url( $linkID );
                            if ( $url == null )
                            {
                                $GLOBALS[$isInputValid] = false;
                                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                     'Link %1 does not exist.',
                                                                                     false, array( $linkID ) ) );
                                return EZ_INPUT_VALIDATOR_STATE_INVALID;
                            }
                            else
                            {
                                if ( !in_array( $url, $urlArray ) )
                                    $urlArray[] = $url;
                            }
                        }
                    }

                    // Fetch the ID's of all existing URL's and register unexisting
                    $linkIDArray =& eZURL::registerURLArray( $urlArray );

                    // Register all unique URL's for this object attribute
                    foreach ( array_keys( $linkIDArray ) as $url )
                    {
                        $linkID = $linkIDArray[$url];
                        $linkObjectLink =& eZURLObjectLink::create( $linkID, $contentObjectAttributeID, $contentObjectAttributeVersion );
                        $linkObjectLink->store();
                    }

                    // Update XML to only store id, not href
                    foreach ( array_keys( $links ) as $linkKey )
                    {
                        $link =& $links[$linkKey];
                        $url = $link->attributeValue( 'href' );
                        $linkID = $linkIDArray[$url];

                        if ( $link->attributeValue( 'id' ) == null )
                            $link->appendAttribute( $dom->createAttributeNode( 'id', $linkID ) );
                        $link->removeNamedAttribute( 'href' );
                    }
                }

                $domString =& eZXMLTextType::domString( $dom );

//                 eZDebug::writeDebug( $domString, "unprocessed xml" );
                $domString = preg_replace( "#<paragraph> </paragraph>#", "<paragraph>&nbsp;</paragraph>", $domString );
                $domString = str_replace ( "<paragraph />" , "", $domString );
                $domString = str_replace ( "<line />" , "", $domString );
                $domString = str_replace ( "<paragraph></paragraph>" , "", $domString );
                $domString = preg_replace( "#<paragraph>&nbsp;</paragraph>#", "<paragraph />", $domString );
                $domString = preg_replace( "#<paragraph></paragraph>#", "", $domString );

                $domString = preg_replace( "#[\n]+#", "", $domString );
                $domString = preg_replace( "#&lt;/line&gt;#", "\n", $domString );
                $domString = preg_replace( "#&lt;paragraph&gt;#", "\n\n", $domString );
//                 eZDebug::writeDebug( $domString, "domstring" );

                $xml = new eZXML();
                $tmpDom =& $xml->domTree( $domString, array( 'CharsetConversion' => false ) );
                $domString = eZXMLTextType::domString( $tmpDom );

//                eZDebug::writeDebug($domString, "stored xml");
                $contentObjectAttribute->setAttribute( "data_text", $domString );
                $contentObjectAttribute->setValidationLog( $message );

                $paragraphs = $tmpDom->elementsByName( 'paragraph' );
                $headers = $tmpDom->elementsByName( 'header' );

                $classAttribute =& $contentObjectAttribute->contentClassAttribute();
                if ( $classAttribute->attribute( "is_required" ) == true )
                {
                    if ( count( $paragraphs ) == 0  && count( $headers ) == 0  )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Content required' ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }
                    else
                        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
      Change input tags to standard tag name
    */
    function &standizeTag( $tagName )
    {
        $convertedTagName = $tagName;
        if ( in_array( $tagName, $this->TagAliasArray['strong'] ) )
            $convertedTagName = "strong";
        if ( in_array( $tagName, $this->TagAliasArray['emphasize'] ) )
        {
            $convertedTagName = "emphasize";
        }
        if ( in_array( $tagName, $this->TagAliasArray['link'] ) )
        {
            $convertedTagName = "link";
        }
        if ( in_array( $tagName, $this->TagAliasArray['header'] ) )
        {
            $convertedTagName = "header";
        }
        return $convertedTagName;
    }

    /*!
     */
    function &handleStartTag( $standardTagName, $tagName, $lastInsertedNodeTag, &$currentNode, &$domDocument, &$TagStack, &$message, $attrPart, &$isInsideTd )
    {
        unset( $subNode );
        $subNode = new eZDOMNode();

        // Check if tag is valid
        $currentTag = $standardTagName;
        $parentNodeTag = $lastInsertedNodeTag;
        // Deal with block tags.
        if ( in_array( $currentTag, $this->BlockTagArray ) )
        {
            if ( $parentNodeTag == "section" )
            {
                // Add paragraph tag
                $subNode->Name = "paragraph";
                $subNode->LocalName = "paragraph";
                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                $domDocument->registerElement( $subNode );
                $currentNode->appendChild( $subNode );
                $childTag = $this->SubTagArray['paragraph'];
                $TagStack[] = array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                $currentNode =& $subNode;
                $parentNodeTag = "paragraph";
                unset( $subNode );
                $subNode = new eZDOMNode();
            }
            elseif ( $lastInsertedNodeTag == "line" )
            {
                // Pop up line tag
                $lastNodeArray = array_pop( $TagStack );
                $lastNode =& $lastNodeArray["ParentNodeObject"];
                unset( $currentNode );
                $currentNode =& $lastNode;

                $parentNodeArray = array_pop( $TagStack );
                if ( $parentNodeArray !== null )
                {
                    $parentNodeTag = $parentNodeArray["TagName"];
                    $parentNode =& $parentNodeArray["ParentNodeObject"];
                    $parentChildTag = $parentNodeArray["ChildTag"];
                    $TagStack[] = array( "TagName" => $parentNodeTag, "ParentNodeObject" => &$parentNode, "ChildTag" => $parentChildTag );
                }
                if ( $parentNodeTag == "section" )
				{
					// Add paragraph tag
					$subNode->Name = "paragraph";
					$subNode->LocalName = "paragraph";
					$subNode->Type = EZ_NODE_TYPE_ELEMENT;
					$domDocument->registerElement( $subNode );
					$currentNode->appendChild( $subNode );
					$childTag = $this->SubTagArray['paragraph'];
					$TagStack[] = array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
					$currentNode =& $subNode;
					$parentNodeTag = "paragraph";
					unset( $subNode );
					$subNode = new eZDOMNode();
                }
            }
        }

        // If last inserted tag is paragraph, pop up it.
        if ( $currentTag == "paragraph" and $lastInsertedNodeTag == "paragraph" )
        {
             //pop up paragraph tag
             $lastNodeArray = array_pop( $TagStack );
             $lastNode =& $lastNodeArray["ParentNodeObject"];
             unset( $currentNode );
             $currentNode =& $lastNode;

             $parentNodeArray = array_pop( $TagStack );
             if ( $parentNodeArray !== null )
             {
                 $parentNodeTag = $parentNodeArray["TagName"];
                 $parentNode =& $parentNodeArray["ParentNodeObject"];
                 $parentChildTag = $parentNodeArray["ChildTag"];
                 $TagStack[] = array( "TagName" => $parentNodeTag, "ParentNodeObject" => &$parentNode, "ChildTag" => $parentChildTag );
             }
        }

        // Pop up line tag if a paragraph tag find
        if ( $currentTag == "paragraph" and $lastInsertedNodeTag == "line" )
        {
            // Pop up line tag
            $lastNodeArray = array_pop( $TagStack );
            $lastNode =& $lastNodeArray["ParentNodeObject"];
            unset( $currentNode );
            $currentNode =& $lastNode;

            $parentNodeArray = array_pop( $TagStack );
            if ( $parentNodeArray !== null )
            {
                $parentNodeTag = $parentNodeArray["TagName"];
                $parentNode =& $parentNodeArray["ParentNodeObject"];
                $parentChildTag = $parentNodeArray["ChildTag"];
                $TagStack[] = array( "TagName" => $parentNodeTag, "ParentNodeObject" => &$parentNode, "ChildTag" => $parentChildTag );
            }

            // If last inserted tag is paragraph, pop up it.
            if ( $parentNodeTag == "paragraph" )
            {
                $lastNodeArray = array_pop( $TagStack );
                $lastNode =& $lastNodeArray["ParentNodeObject"];
                unset( $currentNode );
                $currentNode =& $lastNode;

                $parentNodeArray = array_pop( $TagStack );
                if ( $parentNodeArray !== null )
                {
                    $parentNodeTag = $parentNodeArray["TagName"];
                    $parentNode =& $parentNodeArray["ParentNodeObject"];
                    $parentChildTag = $parentNodeArray["ChildTag"];
                    $TagStack[] = array( "TagName" => $parentNodeTag, "ParentNodeObject" => &$parentNode, "ChildTag" => $parentChildTag );
                }
            }
        }

        if ( in_array( $currentTag, $this->SubTagArray[$parentNodeTag] ) and $currentTag != $parentNodeTag )
        {
            if ( $currentTag == "paragraph" and $parentNodeTag == "li" )
            {
                $isValidTag = true;
                $deletedNodeArray = array();
                while( $isValidTag )
                {
                    $lastChild =& $currentNode->lastChild();
                    if ( $lastChild->Name == "#text" or in_array( $lastChild->Name, $this->LineTagArray ) )
                    {
                        $deletedNodeArray[] = $lastChild;
                        $currentNode->removeLastChild();
                    }
                    else
                        $isValidTag = false;
                }
                if ( $deletedNodeArray != null )
                {
                    unset( $insertedNode );
                    $insertedNode = new eZDOMNode();
                    $insertedNode->Name = "line";
                    $insertedNode->LocalName = "line";
                    $insertedNode->Type = EZ_NODE_TYPE_ELEMENT;
                    $domDocument->registerElement( $insertedNode );
                    $currentNode->appendChild( $insertedNode );
                    $childTag = $this->LineTagArray;

                    for ( $i = count( $deletedNodeArray ); $i > 0; $i--)
                    {
                        $domDocument->registerElement( $deletedNodeArray[$i-1] );
                        $insertedNode->appendChild( $deletedNodeArray[$i-1] );
                    }
                }
                $currentTag = "line";
            }
            $subNode->Name = $currentTag;
            $subNode->LocalName = $currentTag;
            $subNode->Type = EZ_NODE_TYPE_ELEMENT;

            $allowedAttr = array();
            $existAttrNameArray = array();
            unset( $attr );

            if ( $currentTag == "td" or $currentTag == "th" )
            {
                $attrPart = str_replace( " width", "xhtml:width", $attrPart );
                $attrPart = str_replace( " colspan", "xhtml:colspan", $attrPart );
                $attrPart = str_replace( " rowspan", "xhtml:rowspan", $attrPart );
            }

            if ( $currentTag == "object" )
            {
                $attrPart = str_replace( " href", "image:ezurl_href", $attrPart );
                $attrPart = str_replace( " ezurl_id", "image:ezurl_id", $attrPart );
                $attrPart = str_replace( " target", "image:ezurl_target", $attrPart );
            }

            $attr =& $this->parseAttributes( $attrPart );
            // Tag is valid Check attributes
            // parse attruibet

            if ( $attr !== null )
            {
                foreach ( $attr as $attrbute )
                {
                    $attrName = $attrbute->Name;
                    $existAttrNameArray[] = $attrName;

                    if ( isset( $this->TagAttributeArray[$currentTag][$attrName] ) or $currentTag == "object" or $currentTag == "custom" )
                    {
                        if ( $currentTag == "object" )
                        {
                            if ( $attrName != "id" and $attrName != "class" and $attrName != "align" and $attrName != "size"
                                 and $attrName != "view" and $attrName != "ezurl_href"
                                 and $attrName != "ezurl_id" and $attrName != "ezurl_target" )
                                $attrbute->setPrefix( "custom" );
                        }

                        if ( $currentTag == "custom" )
                        {
                            if ( $attrName != "name" )
                                $attrbute->setPrefix( "custom" );
                        }
                        $allowedAttr[] = $attrbute;
                    }
                    else
                    {
                        // attr is not allowed
                        $message[] = "Attribute '" .  $attrName . "' in tag " . $currentTag . " is not supported (removed)";
                    }
                }
            }
            // Check if there should be any more attributes
            if ( isset( $this->TagAttributeArray[$currentTag] ) )
            {
                foreach ( $this->TagAttributeArray[$currentTag] as $key => $attribute )
                {
                    if ( $attribute['required'] == true )
                    {
                        // Check if tag is already found
                        if ( in_array( $key, $existAttrNameArray ) )
                        {
                            //do nothing
                        }
                        else
                        {
                            //Set input invalid
                            $this->IsInputValid = false;
                            $message[] = "Attribute '" . $key . "' in tag " . $currentTag . " not found (need fix)";
                        }
                    }

                    if ( isset( $attribute['value'] ) )
                    {
                        foreach ( $attr as $attrbuteObject )
                        {
                            $attrName = $attrbuteObject->Name;
                            $attrContent = $attrbuteObject->Content;
                            if ( $attrName == $key )
                            {
                                if ( in_array( $attrContent, $attribute['value'] ) )
                                {
                                    //do nothing
                                }
                                else
                                {
                                    $validValue = "";
                                    foreach ( $attribute['value'] as $value )
                                    {
                                        $validValue .= "'" . $value . "' ";
                                    }
                                    //Set input invalid
                                    $this->IsInputValid = false;
                                    $message[] = "Attribute '" . $key . "' in tag " . $currentTag . " has invalid value ( choose " . $validValue . " )";
                                }
                            }
                        }
                    }
                }
            }

            if ( $allowedAttr != null )
            {
                $subNode->Attributes = $allowedAttr;
            }

            if ( $currentTag == "link" )
            {
                if ( $allowedAttr == null )
                {
                    //Set input invalid
                    $this->IsInputValid = false;
                    $message[] = "Tag 'link' must have attribute 'href' or valid 'id' (need fix)";
                }
                else
                {
                    foreach ( $allowedAttr as $allowedAttrNode )
                    {
                        if ( $allowedAttrNode->name() == 'href' )
                        {
                            $content = $allowedAttrNode->content();
                            if ( strlen( trim( $content ) ) == 0 )
                            {
                                $this->IsInputValid = false;
                                $message[] = "Attribute 'href' in tag 'link' cannot be empty";
                            }
                        }
                    }
                }
            }

            $domDocument->registerElement( $subNode );
            $currentNode->appendChild( $subNode );

            $childTag = $this->SubTagArray[$currentTag];

            if ( $tagName[strlen($tagName) - 1]  != "/" )
            {
                $TagStack[] = array( "TagName" => $currentTag, "ParentNodeObject" =>& $currentNode, "ChildTag" => $childTag );
                unset( $currentNode );
                $currentNode =& $subNode;
            }

            // Add paragraph tag for td, th and custom
            if ( $currentTag == "td" or $currentTag == "th" or $currentTag == "custom" or $currentTag == "li" )
            {
                // Set variable isInsideTd to true
                $isInsideTd = true;

                unset( $subNode );
                $subNode = new eZDOMNode();

                $subNode->Name = "paragraph";
                $subNode->LocalName = "paragraph";
                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                $domDocument->registerElement( $subNode );
                $currentNode->appendChild( $subNode );
                $childTag = $this->SubTagArray['paragraph'];
                $TagStack[] = array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                $currentNode =& $subNode;
                $parentNodeTag = "paragraph";
            }
        }
        else
        {
            if ( $currentTag != "paragraph" and $currentTag != "line"  )
            {
                // Tag does not exist in the array
                $message[] = "Tag '" . $currentTag . "' is not allowed to be the child of '" . $parentNodeTag ."' (removed)";
            }
        }
        return $currentNode;
    }

    /*!
     \reimp
    */
    function &convertInput( &$text )
    {
        $message = array();
        // fix newlines
        // Convet windows newlines
        $text =& preg_replace( "#\r\n#", "\n", $text );
        // Convet mac newlines
        $text =& preg_replace( "#\r#", "\n", $text );

        $text =& preg_replace( "#\n[\n]+#", "\n\n", $text );

        $text =& preg_replace( "#\n[\n]+#", "<paragraph>", $text );
        $text =& preg_replace( "#\n#", "</line>", $text );

        // Convert headers
        $text =& preg_replace( "#<header>#", "<header level='1'>", $text );

        $data = $text;

//         eZDebug::writeDebug($data, "input");
        $domDocument = new eZDOMDocument();
        $currentNode =& $domDocument;
        $TagStack = array();
        $pos = 0;
        $endTagPos = 0;
        $lastInsertedNodeTag = null;
        $lastInsertedChildTag = null;
        $lastInsertedNode = null;
        $sectionLevel = 0;
        $overrideContent = false;
        $isInsideTd = false;
        $tdSectionLevel = 0;
        $isInlineCustomTag = false;
        while ( $pos < strlen( $data ) )
        {
            //$isUnsupportedTag = false;
            $char = $data[$pos];
            if ( $char == "<" )
            {
                $parentTag =null;
                $lastInsertedNodeArray = array_pop( $TagStack );
                if ( $lastInsertedNodeArray !== null )
                {
                    $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                    $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                    $parentTag = $lastInsertedNode["TagName"];
                    $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                    $TagStack[] = array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag );
                }
                // find tag name
                $endTagPos = strpos( $data, ">", $pos );

                // tag name with attributes
                $tagName = substr( $data, $pos + 1, $endTagPos - ( $pos + 1 ) );


                // get tag name without attributes
                $firstSpaceEnd = strpos( $tagName, " " );
                $firstNewlineEnd = strpos( $tagName, "\n" );
                $tagNameEnd = 0;

                if ( $firstNewlineEnd != false )
                {
                    if ( $firstSpaceEnd != false )
                    {
                        $tagNameEnd = min( $firstSpaceEnd, $firstNewlineEnd );
                    }
                    else
                    {
                        $tagNameEnd = $firstNewlineEnd;
                    }
                }
                else
                {
                    if ( $firstSpaceEnd != false )
                    {
                        $tagNameEnd = $firstSpaceEnd;
                    }
                    else
                    {
                        $tagNameEnd = 0;
                    }
                }
                if ( $tagNameEnd > 0 )
                {
                    $justName = substr( $tagName, 0, $tagNameEnd );
                }
                else
                    $justName = $tagName;
                // remove trailing / from the name if exists
                if ( $justName[strlen($justName) - 1]  == "/" )
                {
                    $justName = substr( $justName, 0, strlen( $justName ) - 1 );
                }
                $justName = strtolower( $justName );

                // check if it's an endtag </tagname> $attributeString
                if ( $justName[0] == "/" )
                {
                    $lastInsertedNodeArray = array_pop( $TagStack );
                    $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                    $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                    $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                    $tagName = substr( $justName, 1, strlen( $justName ) );

                    // Convert to standard tag name.
                    $convertedTag = $this->standizeTag( $tagName );

                    // If last inserted node is line and either a paragraph or a li tag found, should pop up line node.
                    if ( $lastInsertedNodeTag == "line" and ( $convertedTag == "paragraph" or
                                                              $convertedTag == "li" or
                                                              $convertedTag == "td" or
                                                              $convertedTag == "th" or
                                                              $convertedTag == "custom" ) )
                    {
                        unset( $currentNode );
                        $currentNode =& $lastInsertedNode;
                        $lastInsertedNodeArray = array_pop( $TagStack );
                        $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                        $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                        $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                    }

                    $withNewLine = false;
                    if ( $convertedTag == 'line' and ( $lastInsertedNodeTag == "paragraph" or $lastInsertedNodeTag == "li" ) )
                    {
                        $isValidTag = true;
                        $deletedNodeArray = array();
                        while( $isValidTag )
                        {
                            $lastChild =& $currentNode->lastChild();
                            if ( $lastChild->Name == "#text" or in_array( $lastChild->Name, $this->LineTagArray ) )
                            {
                                $deletedNodeArray[] = $lastChild;
                                $currentNode->removeLastChild();
                            }
                            else
                                $isValidTag = false;
                        }
                        if ( $deletedNodeArray != null )
                        {
                            unset( $insertedNode );
                            $insertedNode = new eZDOMNode();
                            $insertedNode->Name = "line";
                            $insertedNode->LocalName = "line";
                            $insertedNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $insertedNode );
                            $currentNode->appendChild( $insertedNode );
                            $childTag = $this->LineTagArray;

                            for ( $i = count( $deletedNodeArray ); $i > 0; $i-- )
                            {
                                $domDocument->registerElement( $deletedNodeArray[$i-1] );
                                $insertedNode->appendChild( $deletedNodeArray[$i-1] );
                            }
                            $withNewLine = true;
                        }
                    }
                    if ( $convertedTag == 'td' or $convertedTag == 'th' or $convertedTag == 'custom' or $convertedTag == 'li' )
                    {
                        $isInsideTd = false;
                        // Reset table section level
                        $tdSectionLevel = 0;
                        while ( $lastInsertedNodeTag == "section" or $lastInsertedNodeTag == "paragraph" )
                        {
                            unset( $currentNode );
                            $currentNode =& $lastInsertedNode ;

                            $lastInsertedNodeArray = array_pop( $TagStack );
                            $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                            $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                            $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];

                            if ( $convertedTag == 'custom' and $lastInsertedNodeTag != 'custom' and $lastInsertedNodeTag != 'section' )
                            {
                                $TagStack[] = array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag );
                                break;
                            }
                        }
                    }

                    if ( $lastInsertedNodeTag != $convertedTag )
                    {
                        $TagStack[] = array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag );

                        if ( $convertedTag == "line" and  $withNewLine )
                        {
                            //Add line tag
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Name = "line";
                            $subNode->LocalName = "line";
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );
                            $childTag = $this->LineTagArray;
                            $TagStack[] = array( "TagName" => "line", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                            $lastInsertedNodeTag = "line";
                            $lastInsertedChildTag = $childTag;
                        }
                        if ( in_array( $lastInsertedNodeTag, $this->SupportedInputTagArray ) and in_array( $convertedTag, $this->SupportedInputTagArray ) )
                        {
                            if ( $this->IsInputValid == true )
                                $message[] = "Unmatched tag " . $lastInsertedNodeTag;
                            //Set input invalid
                            $this->IsInputValid = false;
                        }
                    }
                    else
                    {
                        unset( $currentNode );
                        $currentNode =& $lastInsertedNode ;

                        if ( $convertedTag == "line" )
                        {
                            //Add line tag
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Name = "line";
                            $subNode->LocalName = "line";
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );
                            $childTag = $this->LineTagArray;
                            $TagStack[] = array( "TagName" => "line", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                            $lastInsertedNodeTag = "line";
                            $lastInsertedChildTag = $childTag;
//                             eZDebug::writeDebug( "line tag added");
                        }

                        // If end tag header found, add paragraph node.
                        if ( $convertedTag == "header" or $convertedTag == "paragraph" )
                        {
                            // Add paragraph tag
                            // create the new XML element node
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Name = "paragraph";
                            $subNode->LocalName = "paragraph";
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );
                            $childTag = array_merge( $this->BlockTagArray, $this->InLineTagArray );
                            $TagStack[] = array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                            $lastInsertedNodeTag = "paragraph";
                            $lastInsertedChildTag = $childTag;
                        }

                        if ( in_array( $tagName, $this->BlockTagArray ) and !$isInlineCustomTag )
                        {
                            $lastInsertedNodeArray = array_pop( $TagStack );
                            $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                            $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                            $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                            unset( $currentNode );
                            $currentNode =& $lastInsertedNode;

                            // Add paragraph tag
                            // create the new XML element node
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Name = "paragraph";
                            $subNode->LocalName = "paragraph";
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );
                            $childTag = array_merge( $this->BlockTagArray, $this->InLineTagArray );
                            $TagStack[] = array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                            $lastInsertedNodeTag = "paragraph";
                            $lastInsertedChildTag = $childTag;
                        }

                        // Reset attribute isInlineCustomTag to false.
                        $isInlineCustomTag = false;
                    }
                }
                else
                {
                    // Convert to standard tag name.
                    $justName = $this->standizeTag( $justName );

                    if ( $tagNameEnd > 0 )
                    {
                        $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                    }
                    else
                    {
                        $attributePart = null;
                    }

                    if ( $justName == "section" )
                    {
                        $covertedName = 'section';
                        unset( $subNode );
                        $subNode = new eZDOMNode();

                        if ( $tagNameEnd > 0 )
                        {
                            $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                            // attributes
                            unset( $attr );
                            $attr =& $this->parseAttributes( $attributePart );
                            $subNode->Attributes = $attr;
                        }
                        $subNode->Name = $covertedName;
                        $subNode->LocalName = $covertedName;
                        $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                        $domDocument->registerElement( $subNode );
                        $currentNode->appendChild( $subNode );

                        $childTag = $this->SectionArray;
                        if ( $tagName[strlen($tagName) - 1]  != "/" )
                        {
                            $TagStack[] = array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                        }
                    }
                    elseif ( !in_array( $justName, $this->SupportedTagArray ) )
                    {
//                        $isUnsupportedTag = true;
                        $message[] = "Unsupported tag " . $justName .  "(removed)";
                    }
                    elseif ( $justName == "header" )
                    {
                        while( $lastInsertedNodeTag == "paragraph" or $lastInsertedNodeTag == "line" )
                        {
                            $lastNodeArray = array_pop( $TagStack );
                            $lastTag = $lastNodeArray["TagName"];
                            $lastNode =& $lastNodeArray["ParentNodeObject"];
                            $lastChildTag = $lastNodeArray["ChildTag"];
                            unset( $currentNode );
                            $currentNode =& $lastNode;

                            $lastInsertedNodeArray = array_pop( $TagStack );
                            if ( $lastInsertedNodeArray !== null )
                            {
                                $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                                $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                                $parentTag = $lastInsertedNode["TagName"];
                                $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                                $TagStack[] = array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag );
                            }
                        }

                        if ( in_array( $justName, $lastInsertedChildTag ) )
                        {
                            $headerLevel = 1;
                            $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                            // attributes
                            $allowedAttr = array();
                            unset( $attr );
                            $attr =& $this->parseAttributes( $attributePart );
                            foreach( $attr as $attrbute )
                            {
                                $attrName = $attrbute->Name;
                                if ( $attrName == 'level' )
                                {
                                    $headerLevel = $attrbute->Content;
                                }
                                elseif ( $attrName == 'anchor_name' )
                                {
                                    $anchorName = $attrbute->Content;
                                    $allowedAttr[] = $attrbute;
                                }
                                elseif ( $attrName == 'class' )
                                {
                                    $allowedAttr[] = $attrbute;
                                }
                                else
                                {
                                    $message[] = "Attribute '" .  $attrName . "' in tag " . $justName . " is not supported (removed)";
                                }
                            }

                            /* if ( $lastInsertedNodeTag == "td" or $lastInsertedNodeTag == "th" )
                            {
                                    $sectionLevel = $sectionLevel;
                            }*/
                            if ( $isInsideTd )
                            {
                                $sectionLevel = $sectionLevel;
                                //$tdSectionLevel
                                switch ( $headerLevel )
                                {
                                    case "1" :
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 1, $TagStack, $currentNode );
                                    }break;
                                    case "2":
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 2, $TagStack, $currentNode );
                                    }break;
                                    case "3":
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 3, $TagStack, $currentNode );
                                    }break;
                                    case "4":
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 4, $TagStack, $currentNode );
                                    }break;
                                    case "5":
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 5, $TagStack, $currentNode );
                                    }break;
                                    case "6":
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 6, $TagStack, $currentNode );
                                    }break;
                                    default:
                                    {
                                        $currentNode =& $this->sectionLevel( $tdSectionLevel, 1, $TagStack, $currentNode );
                                    }break;
                                }
                            }
                            else
                            {
                                switch ( $headerLevel )
                                {
                                    case "1" :
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 1, $TagStack, $currentNode );
                                    }break;
                                    case "2":
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 2, $TagStack, $currentNode );
                                    }break;
                                    case "3":
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 3, $TagStack, $currentNode );
                                    }break;
                                    case "4":
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 4, $TagStack, $currentNode );
                                    }break;
                                    case "5":
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 5, $TagStack, $currentNode );
                                    }break;
                                    case "6":
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 6, $TagStack, $currentNode );
                                    }break;
                                    default:
                                    {
                                        $currentNode =& $this->sectionLevel( $sectionLevel, 1, $TagStack, $currentNode );
                                    }break;
                                }
                            }

                            // Add section tag
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Name = "section";
                            $subNode->LocalName = "section";
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );
                            $childTag = $this->SectionArray;
                            $TagStack[] = array( "TagName" => "section", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                            $currentNode =& $subNode;
                            $covertedName = 'header';
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Attributes = $allowedAttr;
                            $subNode->Name = $covertedName;
                            $subNode->LocalName = $covertedName;
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );

                            $childTag = array( );
                            if ( $tagName[strlen($tagName) - 1]  != "/" )
                            {
                                $TagStack[] = array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                                unset( $currentNode );
                                $currentNode =& $subNode;
                            }
                        }
                        else
                        {
                            $message[] = "Tag '" . $justName . "' is not allowed to be the child of '" . $lastInsertedNodeTag ."' (removed)";
                        }
                    }
                    elseif ( $justName == "custom" )
                    {
                        $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                        // attributes
                        unset( $attr );
                        $attr =& $this->parseAttributes( $attributePart );

                        $supportedAttr = array();
                        foreach( $attr as $attrbute )
                        {
                            $attrName = $attrbute->Name;
                            if ( $attrName == 'name' )
                            {
                                $customTagName = $attrbute->Content;
                                $supportedAttr[] = $attrbute;
                                include_once( "lib/ezutils/classes/ezini.php" );
                                $ini =& eZINI::instance( 'content.ini' );
                                $availableCustomTags =& $ini->variable( 'CustomTagSettings', 'AvailableCustomTags' );

                                if ( !in_array( $customTagName, $availableCustomTags ) )
                                {
                                    $availableTagList = "";
                                    foreach ( array_keys ( $availableCustomTags ) as $key )
                                    {
                                        $availableTagList .= " '" . $availableCustomTags[$key] . "'";
                                    }
                                    $message[] = "Custom tag '" . $customTagName . "' is not available, use one of the following:" . $availableTagList;
                                    $this->IsInputValid = false;
                                }

                                $isInlineTagList =& $ini->variable( 'CustomTagSettings', 'IsInline' );
                                foreach ( array_keys ( $isInlineTagList ) as $key )
                                {
                                    $isInlineTagValue =& $isInlineTagList[$key];
                                    if ( $isInlineTagValue )
                                    {
                                        if ( $customTagName == $key )
                                            $isInlineCustomTag = true;
                                    }
                                }
                            }
                            else
                            {
                                $attrbute->setPrefix( "custom" );
                                $supportedAttr[] = $attrbute;
                            }
                        }

                        if ( $isInlineCustomTag )
                        {
                            $covertedName = 'custom';
                            unset( $subNode );

                            $subNode = new eZDOMNode();
                            $subNode->Attributes = $supportedAttr;
                            $subNode->Name = $covertedName;
                            $subNode->LocalName = $covertedName;
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );

                            $childTag = $this->InLineTagArray;
                            if ( $tagName[strlen($tagName) - 1]  != "/" )
                            {
                                $TagStack[] = array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag );
                                unset( $currentNode );
                                $currentNode =& $subNode;
                            }
                        }
                        else
                        {
                            $currentNode =& $this->handleStartTag( $justName, $tagName, $lastInsertedNodeTag, $currentNode, $domDocument, $TagStack, $message, $attributePart, $isInsideTd );
                        }
                    }
                    else
                    {
                        $currentNode =& $this->handleStartTag( $justName, $tagName, $lastInsertedNodeTag, $currentNode, $domDocument, $TagStack, $message, $attributePart, $isInsideTd );
                    }
                    if ( $justName == "literal" )
                    {
                        // Find the end tag and create override contents
                        $preEndTagPos = strpos( strtolower( $data ), "</literal>", $pos );
                        $overrideContent = substr( $data, $pos + 5, $preEndTagPos - ( $pos + 5 ) );
                        $pos = $preEndTagPos - 1;
                    }
                }
            }

            $pos = strpos( $data, "<", $pos + 1 );

            if ( $pos == false )
            {
                // end of document
                $pos = strlen( $data );
            }
            /*elseif ( $isUnsupportedTag )
            {
                //do nothing
            }*/
            else
            {
                // content tag
                $tagContent = substr( $data, $endTagPos + 1, $pos - ( $endTagPos + 1 ) );
                if ( trim( $tagContent ) != "" )
                {
                    // $domDocument->registerElement( $subNode );
                    unset( $subNode );
                    $subNode = new eZDOMNode();
                    $subNode->Name = "#text";
                    $subNode->Type = EZ_NODE_TYPE_TEXT;

                    // convert special chars
                    if ( $justName != 'literal' )
                    {
                        $tagContent =& str_replace("&gt;", ">", $tagContent );
                        $tagContent =& str_replace("&lt;", "<", $tagContent );
                        $tagContent =& str_replace("&apos;", "'", $tagContent );
                        $tagContent =& str_replace("&quot;", '"', $tagContent );
                        $tagContent =& str_replace("&amp;", "&", $tagContent );
                        $tagContent =& str_replace("&nbsp;", " ", $tagContent );
                    }

                    $subNode->Content = $tagContent;
                    $domDocument->registerElement( $subNode );
                    $currentNode->appendChild( $subNode );
                }
            }
        }
        $output = array( $domDocument, $message );
        return $output;
    }

    // Get section level and reset cuttent node according to input header.
    function &sectionLevel( &$sectionLevel, $headerLevel, &$TagStack, &$currentNode )
    {
         if ( $sectionLevel < $headerLevel )
         {
             $sectionLevel += 1;
         }
         elseif ( $sectionLevel == $headerLevel )
         {
             $lastNodeArray = array_pop( $TagStack );
             // $lastTag = $lastNodeArray["TagName"];
             $lastNode =& $lastNodeArray["ParentNodeObject"];
             // $lastChildTag = $lastNodeArray["ChildTag"];
             unset( $currentNode );
             $currentNode =& $lastNode;
             $sectionLevel = $headerLevel;
         }
         else
         {
             for ( $i=1;$i<=( $sectionLevel - $headerLevel + 1 );$i++ )
             {
                 $lastNodeArray = array_pop( $TagStack );
                 $lastTag = $lastNodeArray["TagName"];
                 $lastNode =& $lastNodeArray["ParentNodeObject"];
                 $lastChildTag = $lastNodeArray["ChildTag"];
                 unset( $currentNode );
                 $currentNode =& $lastNode;
             }
             $sectionLevel = $headerLevel;
         }
         return $currentNode;
    }

    /*!
      \private
      Parses the attributes. Returns false if no attributes in the supplied string is found.
    */
    function &parseAttributes( $attributeString )
    {
        if ( $attributeString != null )
        {
           $attrbutes = false;
           // Register attributes which with double or single quotes
           $attrbutesNodeWithQuote = $this->registerAttributes( $attributeString );

           // Remove registed attributes
           $attributeString = preg_replace( "/([0-9A-Za-z]+)\s*\=\"(.*?)\"/e", "", $attributeString );
           $attributeString = preg_replace( "/([0-9A-Za-z]+)\s*\='(.*?)'/e", "", $attributeString );

           // Change attributes which without quotes with double quote.
           $attributeString = preg_replace( "/([a-zA-Z0-9:-_#\-]+)\s*\=([a-zA-Z0-9:-_#\-]+)/e", "'\\1'.'=\"'.'\\2'.'\"'", $attributeString );
           $attrbutesNodeWithoutQuote = $this->registerAttributes( $attributeString );

           if ( $attrbutesNodeWithQuote[0] !== null )
           {
               foreach ( $attrbutesNodeWithQuote as $attrbutesNode )
               {
                   $attrbutes[] = $attrbutesNode;
               }
           }
           if ( $attrbutesNodeWithoutQuote[0] != null )
           {
               foreach ( $attrbutesNodeWithoutQuote as $attrbutesNode )
               {
                   $attrbutes[] = $attrbutesNode;
               }
           }
           return $attrbutes;
        }
        else
            return null;
    }

    /*!
      \private
    */
    function &registerAttributes( $partialAttributeString )
    {
        $ret = false;
        $partialAttributeString = trim( $partialAttributeString );
        preg_match_all( "/([a-zA-Z0-9:-_]+\s*\=s*(\"|').*?(\\2))/i", $partialAttributeString, $attributeArray );
        foreach ( $attributeArray[0] as $attributePart )
        {
            if ( trim( $attributePart ) != "" && trim( $attributePart ) != "/" )
            {
                $attributeNamespaceURI = false;
                $attributePrefix = false;
                $attributeTmpArray = preg_split ("#\s*(=\s*(\"|'))#", $attributePart );

                $attributeName = $attributeTmpArray[0];

                // strip out namespace; nameSpace:Name
                $colonPos = strpos( $attributeName, ":" );

                if ( $colonPos > 0 )
                {
                    $attributePrefix = substr( $attributeName, 0, $colonPos );
                    $attributeName = substr( $attributeName, $colonPos + 1, strlen( $attributeName ) );
                }
                else
                {
                    $attributePrefix = false;
                }

                $attributeValue = $attributeTmpArray[1];

                // remove " from value part
                $attributeValue = substr( $attributeValue, 0, strlen( $attributeValue ) - 1);

                // check for namespace definition
                if ( $attributePrefix == "xmlns" )
                {
                    $attributeNamespaceURI = $attributeValue;
                    $this->NamespaceArray[$attributeName] = $attributeValue;

//                    $this->DOMDocument->registerNamespaceAlias( $attributeName, $attributeValue );
                }

                $attributeContentPrefix = false;
                $contentColonPos = strpos( $attributeValue, ":" );
                if (  $contentColonPos > 0 )
                {
                    $attributeContentPrefix = substr( $attributeValue, 0, $contentColonPos );
                    $attributeContentPrefixName = substr( $attributeValue, $contentColonPos + 1, strlen( $attributeValue ) );
                }
                else
                {
                    $attributeContentPrefix = false;
                }

                // check for namespace definition
                if ( $attributeContentPrefix == "javascript" )
                {
                    return $ret;
                }

                // check for default namespace definition
                if ( $attributeName == "xmlns" )
                {
                    $attributeNamespaceURI = $attributeValue;

                    // change the default namespace
                    $this->NamespaceStack[] = $attributeNamespaceURI;
                }

                unset( $attrNode );
                $attrNode = new eZDOMNode();
                $attrNode->Name = $attributeName;

                if ( $attributePrefix != false && $attributePrefix != "xmlns" )
                {
                    $attrNode->Prefix = $attributePrefix;
                    $attrNode->LocalName = $attributeName;

                    // find prefix
                    if ( isSet( $this->NamespaceArray["$attributePrefix"] ) )
                    {
                        $attrNode->NamespaceURI = $this->NamespaceArray["$attributePrefix"];
                    }
                    else
                    {
                        eZDebug::writeError( "Namespace: $attributePrefix not found", "eZ xml" );
                    }
                }
                else if ( $attributePrefix == "xmlns" )
                {
                    $attrNode->LocalName = $attributeName;
                    $attrNode->NamespaceURI = $attributeNamespaceURI;
                    $attrNode->Prefix = $attributePrefix;
                }
                else
                {
                    // check for default namespace definition
                    if ( $attributeName == "xmlns" )
                    {
                        $attrNode->LocalName = $attributeName;
                        $attrNode->NamespaceURI = $attributeNamespaceURI;
                    }
                    else
                    {
                        $attrNode->NamespaceURI = false;
                        $attrNode->LocalName = false;
                    }
                    $attrNode->Prefix = false;
                }

                $attrNode->Type = EZ_NODE_TYPE_ATTRIBUTE;
                $attrNode->Content = $attributeValue;

                $ret[] =& $attrNode;

            }
        }
        return $ret;
    }

    /*!
     \reimp
     Returns the input XML representation of the datatype.
    */
    function &inputXML()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

        $originalInput = "originalInput_" . $contentObjectAttributeID;
        $isInputValid = "isInputValid_" . $contentObjectAttributeID;

        if ( isset( $GLOBALS[$isInputValid] ) and $GLOBALS[$isInputValid] == false )
        {
            $output = $GLOBALS[$originalInput];
        }
        else
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $this->XMLData, array( 'CharsetConversion' => false ) );
            $links = array();
            $node = array();

            if ( $dom )
            {
                $node =& $dom->elementsByName( "section" );

                // Fetch all links and cache the url's
                $links =& $dom->elementsByName( "link" );
            }
            if ( count( $links ) > 0 )
            {
                $linkIDArray = array();
                // Find all Link id's
                foreach ( $links as $link )
                {
                    $linkIDValue = $link->attributeValue( 'id' );
                    if ( !$linkIDValue )
                        continue;
                    if ( !in_array( $linkIDValue, $linkIDArray ) )
                         $linkIDArray[] = $link->attributeValue( 'id' );
                }

                $inIDSQL = implode( ', ', $linkIDArray );

                $db =& eZDB::instance();

                $linkArray = $db->arrayQuery( "SELECT * FROM ezurl WHERE id IN ( $inIDSQL ) " );

                foreach ( $linkArray as $linkRow )
                {
                    $this->LinkArray[$linkRow['id']] = $linkRow['url'];
                }
            }

            $output = "";
            if ( count( $node ) > 0 )
            {
                $children =& $node[0]->children();
                if ( count( $children ) > 0 )
                {
                    $output .= $this->inputSectionXML( $node[0], 0 );
                }
            }
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given table cell
    */
    function &inputTdXML( &$tdNode, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        if ( get_class( $tdNode ) == "ezdomnode" )
            $tagName = $tdNode->name();
        else
            $tagName = "";
        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= trim( $this->inputParagraphXML( $tdNode, $currentSectionLevel, $tdSectionLevel ) ) . "\n\n";
            }break;
            case 'section' :
            {
                $tdSectionLevel += 1;
                $output .= $this->inputSectionXML( $tdNode, $currentSectionLevel, $tdSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputTdXML()" );
            }break;
        }
        return $output;
    }


    /*!
     \private
     \return the user input format for the given table cell
    */
    function &inputListXML( &$listNode, $currentSectionLevel, $listSectionLevel = null )
    {
        $output = "";
        if ( get_class( $listNode ) == "ezdomnode" )
            $tagName = $listNode->name();
        else
            $tagName = "";
        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= trim( $this->inputParagraphXML( $listNode, $currentSectionLevel, $listSectionLevel ) ) . "\n\n";
            }break;
            case 'section' :
            {
                $listSectionLevel += 1;
                $output .= $this->inputSectionXML( $tdNode, $currentSectionLevel, $listSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputListXML()" );
            }break;
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given section
    */
    function &inputSectionXML( &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        foreach ( $section->children() as $sectionNode )
        {
            if ( $tdSectionLevel == null )
            {
                $sectionLevel = $currentSectionLevel;
            }
            else
            {
                $sectionLevel = $tdSectionLevel;
                $currentSectionLevel = $currentSectionLevel;
            }
            $tagName = $sectionNode->name();

            switch ( $tagName )
            {
                case 'header' :
                {
                    $level = $sectionLevel;
                    $anchorName = $sectionNode->attributeValue( 'anchor_name' );
                    $className = $sectionNode->attributeValue( 'class' );
                    $headerAttr = "level='$level'";
                    if ( $anchorName != null )
                        $headerAttr .= " anchor_name='$anchorName'";
                    if ( $className != null )
                        $headerAttr .= " class='$className'";
                    $output .= "<$tagName $headerAttr>" . $sectionNode->textContent(). "</$tagName>\n";
                }break;

                case 'paragraph' :
                {
                    $output .= trim( $this->inputParagraphXML( $sectionNode, $sectionLevel, $tdSectionLevel ) ) . "\n\n";
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    if ( $tdSectionLevel == null )
                        $output .= $this->inputSectionXML( $sectionNode, $sectionLevel );
                    else
                        $output .= $this->inputSectionXML( $sectionNode, $currentSectionLevel, $sectionLevel );
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputSectionXML()" );
                }break;
            }
        }
        return $output;
    }

    /*!
     \return the input xml of the given paragraph
    */
    function &inputParagraphXML( &$paragraph, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $output .= $this->inputTagXML( $paragraphNode, $currentSectionLevel, $tdSectionLevel );
        }
        return $output;
    }

    /*!
     \return the input xml for the given tag
    */
    function &inputTagXML( &$tag, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = "";
        $tagName = $tag->name();
        $childTagText = "";
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            $childTagText .= $this->inputTagXML( $childTag, $currentSectionLevel, $tdSectionLevel );
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $output .= $tag->content();
                // Get rid of linebreak and spaces stored in xml file
                $output = preg_replace( "#[\n]+#", "", $output );
                $output = preg_replace( "#    #", "", $output );
            }break;

            case 'object' :
            {
                $view = $tag->attributeValue( 'view' );
                $size = $tag->attributeValue( 'size' );
                $alignment = $tag->attributeValue( 'align' );
                $className = $tag->attributeValue( 'class' );
                $hasLink = false;
                $linkID = $tag->attributeValueNS( 'ezurl_id', "http://ez.no/namespaces/ezpublish3/image/" );

                if ( $linkID != null )
                {
                    $href =& eZURL::url( $linkID );
                    $target = $tag->attributeValueNS( 'ezurl_target', "http://ez.no/namespaces/ezpublish3/image/" );
                    $hasLink = true;
                }

                if ( strlen( $view ) == 0 )
                    $view = "embed";

                $objectID = $tag->attributeValue( 'id' );

                $objectAttr = "id=\"$objectID\"";

                if ( $className != null )
                     $objectAttr .= " class=\"$className\"";
                if ( $size != null )
                    $objectAttr .= " size=\"$size\"";
                if ( $alignment != null )
                    $objectAttr .= " align=\"$alignment\"";
                if ( $hasLink )
                {
                    $objectAttr .= " href=\"$href\"";
                    if ( $target != null )
                        $objectAttr .= " target=\"$target\"";
                }

                if ( $view != "embed" )
                {
                    $objectAttr .= " view=\"$view\"";
                }

                $customAttributes =& $tag->attributesNS( "http://ez.no/namespaces/ezpublish3/custom/" );
                foreach ( $customAttributes as $attribute )
                {
                    $objectAttr .= " $attribute->Name=\"$attribute->Content\"";
                }
                $output .= "<object $objectAttr />";
            }break;

            case 'ul' :
            case 'ol' :
            {
                $listContent = "";
                $className = $tag->attributeValue( 'class' );
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = "";
                    foreach ( $listItemNode->children() as $itemChildNode )
                    {
                        $listSectionLevel = $currentSectionLevel;
                        if ( $itemChildNode->name() == "section" or $itemChildNode->name() == "paragraph" )
                            $listItemContent .= $this->inputListXML( $itemChildNode, $currentSectionLevel, $listSectionLevel );
                        else
                            $listItemContent .= $this->inputTagXML( $itemChildNode, $currentSectionLevel, $tdSectionLevel );
                    }
                    $liClassName = $listItemNode->attributeValue( 'class' );
                    if ( $liClassName != null )
                        $listContent .= "  <li class='$liClassName'>" . trim($listItemContent) . "</li>\n";
                    else
                        $listContent .= "  <li>" . trim($listItemContent). "</li>\n";
                }
                if ( $className != null )
                    $output .= "<$tagName class='$className'>\n$listContent</$tagName>" .  "\n";
                else
                    $output .= "<$tagName>\n$listContent</$tagName>" .  "\n";
            }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                $width = $tag->attributeValue( 'width' );
                $tableClass = $tag->attributeValue( 'class' );
                if ( $width == null )
                    $width = "100%";
                if ( $border == null )
                    $border = 1;
                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = "";
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = "";
                        $tdSectionLevel = $currentSectionLevel;
                        $className = $tableCell->attributeValue( 'class' );
                        $cellWidth = $tableCell->attributeValueNS( 'width', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $colspan = $tableCell->attributeValueNS( 'colspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );
                        $rowspan = $tableCell->attributeValueNS( 'rowspan', "http://ez.no/namespaces/ezpublish3/xhtml/" );

                        foreach ( $tableCell->children() as $tableCellChildNode )
                        {
                            $cellContent .= $this->inputTdXML( $tableCellChildNode, $currentSectionLevel, $tdSectionLevel );
                        }

                        $cellAttribute = "";
                        if ( $className != null )
                            $cellAttribute .= " class='$className'";
                        if ( $cellWidth != null )
                            $cellAttribute .= " width='$cellWidth'";
                        if ( $colspan != null )
                            $cellAttribute .= " colspan='$colspan'";
                        if ( $rowspan != null )
                            $cellAttribute .= " rowspan='$rowspan'";

                        if ( $tableCell->name() == "th" )
                        {
                            $tableData .= "  <th$cellAttribute>" . trim( $cellContent ) . "</th>\n";
                        }
                        else
                        {
                            $tableData .= "  <td$cellAttribute>" . trim( $cellContent ) . "</td>\n";
                        }
                    }
                    $tableRows .= "<tr>\n$tableData</tr>\n";
                }
                if ( $tableClass != null )
                    $output .= "<table class='$tableClass' border='$border' width='$width'>\n$tableRows</table>\n";
                else
                    $output .= "<table border='$border' width='$width'>\n$tableRows</table>\n";
            }break;

            case 'literal' :
            {
                $className = $tag->attributeValue( 'class' );
				$literalText = '';
                foreach ( $tagChildren as $childTag )
                {
                    $literalText .= $childTag->content();
                }
                if ( $className != null )
                    $output .= "<$tagName class='$className'>" . $literalText . "</$tagName>";
                else
                    $output .= "<$tagName>" . $literalText . "</$tagName>";
            }break;

            // normal content tags
            case 'emphasize' :
            case 'em' :
            case 'i' :
            {
                $className = $tag->attributeValue( 'class' );
                if ( $className != null )
                    $output .= "<emphasize class='$className'>" . $childTagText . "</emphasize>";
                else
                    $output .= "<emphasize>" . $childTagText . "</emphasize>";
            }break;

            // normal content tags
            case 'b' :
            case 'bold' :
            case 'strong' :
            {
                $className = $tag->attributeValue( 'class' );
                if ( $className != null )
                    $output .= "<strong class='$className'>" . $childTagText . "</strong>";
                else
                    $output .= "<strong>" . $childTagText . "</strong>";
            }break;

            // Custom tags
            case 'custom' :
            {
                $name = $tag->attributeValue( 'name' );
                $isInline = false;
                include_once( "lib/ezutils/classes/ezini.php" );
                $ini =& eZINI::instance( 'content.ini' );

                $isInlineTagList =& $ini->variable( 'CustomTagSettings', 'IsInline' );
                foreach ( array_keys ( $isInlineTagList ) as $key )
                {
                    $isInlineTagValue =& $isInlineTagList[$key];
                    if ( $isInlineTagValue )
                    {
                        if ( $name == $key )
                            $isInline = true;
                    }
                }

                $customAttributes =& $tag->attributesNS( "http://ez.no/namespaces/ezpublish3/custom/" );
                $customAttr = "";
                foreach ( $customAttributes as $attribute )
                {
                    $customAttr .= " $attribute->Name=\"$attribute->Content\"";
                }

                if ( $isInline )
                    $output .= "<$tagName name='$name'" . $customAttr . ">" . $childTagText . "</custom>";
                else
                {
                    $customTagContent = "";
                    foreach ( $tag->children() as $tagChild )
                    {
                        $customTagContent .= $this->inputTdXML( $tagChild, $currentSectionLevel, $tdSectionLevel );
                    }
                    $output .= "<$tagName name='$name'" . $customAttr . ">\n" .   trim( $customTagContent ) . "\n</$tagName>";
                }
            }break;

            case 'link' :
            {
                $linkID = $tag->attributeValue( 'id' );
                $target = $tag->attributeValue( 'target' );
                $className = $tag->attributeValue( 'class' );
                if ( $linkID != null )
                {
                    // Fetch URL from cached array
                    $href = $this->LinkArray[$linkID];
                }
                else
                {
                    $href = $tag->attributeValue( 'href' );
                }
                $attributes = array();
                if ( $className != '' )
                    $attributes[] = "class='$className'";
                $attributes[] = "href='$href'";
                if ( $target != '' )
                    $attributes[] = "target='$target'";
                $attributeText = '';
                if ( count( $attributes ) > 0 )
                    $attributeText = ' ' . implode( ' ', $attributes );
                $output .= "<$tagName$attributeText>" . $childTagText . "</$tagName>";
            }break;

            case 'anchor' :
            {
                $name = $tag->attributeValue( 'name' );
                $output .= "<$tagName name='$name' />";
            }break;

            case 'line' :
            {
                $output .= $childTagText . "\n";
            }break;

            case 'tr' :
            case 'td' :
            case 'th' :
            case 'li' :
            case 'paragraph' :
            {
            }break;
            default :
            {
                eZDebug::writeError( "Unsupported tag: $tagName", "eZXMLTextType::inputParagraphXML()" );
            }break;
        }
        return $output;
    }

    var $SectionArray = array( 'paragraph', 'section', 'header' );

    var $BlockTagArray = array( 'table', 'ul', 'ol', 'literal', 'custom', 'object' );

    var $InLineTagArray = array( 'emphasize', 'strong', 'link', 'anchor', 'line' );

    var $LineTagArray = array( 'emphasize', 'strong', 'link', 'anchor', 'li' );

    var $TagAliasArray = array( 'strong' => array( 'b', 'bold', 'strong' ), 'emphasize' => array( 'em', 'i', 'emphasize' ), 'link' => array( 'link', 'a' ) , 'header' => array( 'header', 'h' ) );

    /// Contains all supported tag for xml parse
    var $SupportedTagArray = array( 'paragraph', 'section', 'header', 'table', 'ul', 'ol', 'literal', 'custom', 'object', 'emphasize', 'strong', 'link', 'anchor', 'tr', 'td', 'th', 'li', 'line' );

    /// Contains all supported input tag
    var $SupportedInputTagArray = array( 'header', 'table', 'ul', 'ol', 'literal', 'custom', 'object', 'emphasize', 'strong', 'link', 'anchor', 'tr', 'td', 'th', 'li' );

    var $ContentObjectAttribute;

    var $IsInputValid;

    /// Contains all links hashed by ID
    var $LinkArray = array();
}
?>
