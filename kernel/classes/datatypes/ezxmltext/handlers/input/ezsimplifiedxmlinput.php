<?php
//
// Definition of eZSimplifiedXMLInput class
//
// Created on: <28-Jan-2003 13:28:39 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//


include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

class eZSimplifiedXMLInput extends eZXMLInputHandler
{
    function eZSimplifiedXMLInput( &$xmlData, $aliasedType )
    {
        $this->eZXMLInputHandler( $xmlData, $aliasedType );
        $this->subTagArray['section'] = $this->sectionArray;
        $this->subTagArray['paragraph'] = array_merge( $this->blockTagArray, $this->inlineTagArray );
        $this->subTagArray['header'] = array( );
        $this->subTagArray['table'] = array( 'tr' );
        $this->subTagArray['tr'] = array( 'td', 'th' );
        $this->subTagArray['td'] = $this->subTagArray['section'];
        $this->subTagArray['th'] = $this->subTagArray['section'];
        $this->subTagArray['ol'] = array( 'li' );
        $this->subTagArray['ul'] = array( 'li' );
        $this->subTagArray['literal'] = array( );
        $this->subTagArray['custom'] = $this->sectionArray;
        $this->subTagArray['object'] = array( );
        $this->subTagArray['li'] = $this->inlineTagArray;
        $this->subTagArray['strong'] = $this->inlineTagArray;
        $this->subTagArray['emphasize'] = $this->inlineTagArray;
        $this->subTagArray['link'] = $this->inlineTagArray;
        $this->subTagArray['anchor'] = $this->inlineTagArray;
        $this->subTagArray['line'] = $this->inlineTagArray;
        $this->tagAttributeArray['table'] = array( 'width' => array( 'required' => false ),
                                             'border' => array( 'required' => false ) );

        $this->tagAttributeArray['link'] = array( 'href' => array( 'required' => false ),
                                            'id' => array( 'required' => false ) );

        $this->tagAttributeArray['anchor'] = array( 'name' => array( 'required' => true ) );

        $this->tagAttributeArray['object'] = array( 'id' => array( 'required' => true ),
                                              'size' => array( 'required' => false ),
                                              'align' => array( 'required' => false ) );

        $this->tagAttributeArray['custom'] = array( 'name' => array( 'required' => true ) );

        $this->isInputValid = true;
        $this->originalInput = "";
    }

    /*!
     \reimp
     Validates the input and returns true if the input was valid for this datatype.
    */
    function &validateInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );

            eZDebug::writeDebug($data, "input data");

            $this->originalInput = $data;
            $http =& eZHTTPTool::instance();
            $http->setSessionVariable( 'inputValue', $this->originalInput );
            $http->setSessionVariable( 'isInputValid', "true" );

            $inputData = "<section>";
            $inputData .= "<paragraph>";
            $inputData .= $data;
            $inputData .= "</paragraph>";
            $inputData .= "</section>";

            $data =& $this->convertInput( $inputData );
            $message = $data[1];
            if ( $this->isInputValid == false )
            {
                // Set session variable to store input
                $http->setSessionVariable( 'isInputValid', "false" );
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     $message,
                                                                     'ezXMLTextType' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
            else
            {
                $dom = $data[0];
                $objects =& $dom->elementsByName( 'object' );
                foreach ( $objects as $object )
                {
                    $objectID = $object->attributeValue( 'id' );
                    $currentObject =& eZContentObject::fetch( $objectID );
                    $editVersion = $contentObjectAttribute->attribute('version');
                    $editObjectID = $contentObjectAttribute->attribute('contentobject_id');
                    $editObject =& eZContentObject::fetch( $editObjectID );
                    if (  $currentObject == null )
                    {
                        // Set session variable to store input
                        $http =& eZHTTPTool::instance();
                        $http->setSessionVariable( 'inputValue', $this->originalInput );
                        $http->setSessionVariable( 'isInputValid', "false" );
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Object '. $objectID .' does not exist.',
                                                                             'ezXMLTextType' ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }
                    else
                    {
                        $relatedObjects =& $editObject->relatedContentObjectArray( $editVersion );
                        $relatedObjectIDArray = array();
                        foreach (  $relatedObjects as  $relatedObject )
                        {
                            $relatedObjectID =  $relatedObject->attribute( 'id' );
                            $relatedObjectIDArray[] =  $relatedObjectID;
                        }
                        if ( !in_array(  $objectID, $relatedObjectIDArray ) )
                        {
                            $editObject->addContentObjectRelation( $objectID, $editVersion );
                        }
                    }
                }
                $links =& $dom->elementsByName( 'link' );
                foreach ( array_keys( $links ) as $linkKey )
                {

                    $link =& $links[$linkKey];
                    if ( $link->attributeValue( 'id' ) != null )
                    {
                        $linkID = $link->attributeValue( 'id' );
                        $url =& eZURL::url( $linkID );
                        if (  $url == null )
                        {
                             // Set session variable to store input
                            $http =& eZHTTPTool::instance();
                            $http->setSessionVariable( 'inputValue', $this->originalInput );
                            $http->setSessionVariable( 'isInputValid', "false" );
                            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                 'Link '. $linkID .' does not exist.',
                                                                                 'ezXMLTextType' ) );
                            return EZ_INPUT_VALIDATOR_STATE_INVALID;
                        }
                    }
                    if ( $link->attributeValue( 'href' ) != null )
                    {
                        $url = $link->attributeValue( 'href' );
                        $linkID =& eZURL::registerURL( $url );
                        $link->appendAttribute( $dom->createAttributeNode( 'id', $linkID ) );
                        $link->removeNamedAttribute( 'href' );
                    }
                }

                $domString = $dom->toString();
                $domString = preg_replace( "#<paragraph> </paragraph>#", "<paragraph>&nbsp;</paragraph>", $domString );
                $domString = str_replace ( "<paragraph />" , "", $domString );
                $domString = str_replace ( "<paragraph></paragraph>" , "", $domString );
                $domString = preg_replace( "#>[\W]+<#", "><", $domString );
                $domString = preg_replace( "#<paragraph>&nbsp;</paragraph>#", "<paragraph />", $domString );

                $xml = new eZXML();
                $tmpDom =& $xml->domTree( $domString, array( 'CharsetConversion' => false ) );
                $domString = $tmpDom->toString();

                eZDebug::writeDebug($domString, "stored xml");
                $contentObjectAttribute->setAttribute( "data_text", $domString );
                $contentObjectAttribute->setValidationLog( $message );
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
      Change input tags to standard tag name
    */
    function &standizeTag( $tagName )
    {
        $convertedTagName = $tagName;
        if ( in_array( $tagName, $this->tagAliasArray['strong'] ) )
            $convertedTagName = "strong";
        if ( in_array( $tagName, $this->tagAliasArray['emphasize'] ) )
        {
            $convertedTagName = "emphasize";
        }
        return $convertedTagName;
    }

    /*!
      Function deal with all block tags, pop up paragraph node.
    */
    function &handleEndTag( $tagName, $lastInsertedNodeTag, &$currentNode, &$lastInsertedNode, &$TagStack )
    {
        $parentNodeTag = $lastInsertedNodeTag;
        if ( in_array( $tagName, $this->blockTagArray ) )
        {
            unset( $currentNode );
            $currentNode =& $lastInsertedNode;
            $lastInsertedNodeArray = array_pop( $TagStack );
            $parentNodeTag = $lastInsertedNodeArray["TagName"];
        }
        if ( $tagName == 'td' or $tagName == 'th' )
        {
            while ( $parentNodeTag == "section" or $parentNodeTag == "paragraph" )
            {
                unset( $currentNode );
                $currentNode =& $lastInsertedNode ;

                $parentNodeArray = array_pop( $TagStack );
                $parentNodeTag = $parentNodeArray["TagName"];
                $lastInsertedNode =& $parentNodeArray["ParentNodeObject"];
            }
        }
        return $parentNodeTag;
    }

    /*!
     */
    function &handleStartTag( $standardTagName, $tagName, $lastInsertedNodeTag, &$currentNode, &$domDocument, &$TagStack, &$message, $attrPart )
    {
        unset( $subNode );
        $subNode = new eZDOMNode();

        // Check if tag is valid
        $currentTag = $standardTagName;
        $parentNodeTag = $lastInsertedNodeTag;
        // Deal with block tags.
        if ( in_array( $currentTag, $this->blockTagArray ) )
        {
            if ( $parentNodeTag == "section" )
            {
                // Add paragraph tag
                $subNode->Name = "paragraph";
                $subNode->LocalName = "paragraph";
                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                $domDocument->registerElement( $subNode );
                $currentNode->appendChild( $subNode );
                $childTag = $this->subTagArray['paragraph'];
                array_push( $TagStack,
                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                $currentNode =& $subNode;
                $parentNodeTag = "paragraph";
                unset( $subNode );
                $subNode = new eZDOMNode();
            }
            elseif ( $lastInsertedNodeTag == "paragraph" )
            {
                // pop up paragraph tag
                $lastNodeArray = array_pop( $TagStack );
                $parentNodeTag = $lastNodeArray["TagName"];
                $lastNode =& $lastNodeArray["ParentNodeObject"];
                unset( $currentNode );
                $currentNode =& $lastNode;

                // Add paragraph tag
                $subNode->Name = "paragraph";
                $subNode->LocalName = "paragraph";
                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                $domDocument->registerElement( $subNode );
                $currentNode->appendChild( $subNode );
                $childTag = $this->subTagArray['paragraph'];
                array_push( $TagStack,
                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                $currentNode =& $subNode;
                $parentNodeTag = "paragraph";
                unset( $subNode );
                $subNode = new eZDOMNode();
            }
        }
        // Deal with paragraph tag.
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
                 array_push( $TagStack,
                             array( "TagName" => $parentNodeTag, "ParentNodeObject" => &$parentNode, "ChildTag" => $parentChildTag ) );
             }
        }

        if ( in_array( $currentTag, $this->subTagArray[$parentNodeTag] ) and $currentTag != $parentNodeTag )
        {
            $subNode->Name = $currentTag;
            $subNode->LocalName = $currentTag;
            $subNode->Type = EZ_NODE_TYPE_ELEMENT;

            $allowedAttr = array();
            $existAttrNameArray = array();
            unset( $attr );
            $attr =& $this->parseAttributes( $attrPart );
            // Tag is valid Check attributes
            // parse attruibet
            foreach ( $attr as $attrbute )
            {
                $attrName = $attrbute->Name;
                $existAttrNameArray[] = $attrName;
                if ( isset( $this->tagAttributeArray[$currentTag][$attrName] ) )
                {
                    $allowedAttr[] = $attrbute;
                }
                else
                {
                    // attr is not allowed
                }
            }
            // Check if there should be any more attributes
            foreach ( $this->tagAttributeArray[$currentTag] as $key => $attribute )
            {
                if ( $attribute['required'] == true )
                {
                    // Chekc if tag is already found
                    if ( in_array( $key, $existAttrNameArray ) )
                    {
                        //do nothing
                    }
                    else
                    {
                        //Set input invalid
                        $this->isInputValid = false;
                        $message .= "<li>Attribute '" . $key . "' in tag " . $currentTag . " not found (need fix)</li>";
                    }
                }
            }

            if ( $allowedAttr != null )
            {
                $subNode->Attributes = $allowedAttr;
            }

            if ( $allowedAttr == null and $currentTag == "link" )
            {
                //Set input invalid
                $this->isInputValid = false;
                $message .= "<li>Tag 'link' must have attribute 'href' or valid 'id' (need fix)</li>";
            }

            $domDocument->registerElement( $subNode );
            $currentNode->appendChild( $subNode );

            $childTag = $this->subTagArray[$currentTag];

            if ( $tagName[strlen($tagName) - 1]  != "/" )
            {
                array_push( $TagStack,
                            array( "TagName" => $currentTag, "ParentNodeObject" =>& $currentNode, "ChildTag" => $childTag ) );
                unset( $currentNode );
                $currentNode =& $subNode;
            }
        }
        else
        {
            if ( $currentTag != "paragraph" )
            // Tag does not exist in the array
            $message .= "<li>Tag '" . $currentTag . "' is not allowed to be the child of '" . $parentNodeTag ."' (removed).</li>";
        }
        return $currentNode;
    }

    /*!
     \reimp
    */
    function &convertInput( &$text )
    {
        $message = null;
        // fix newlines
        // Convet windows newlines
        $text =& preg_replace( "#\r\n#", "\n", $text );
        // Convet mac newlines
        $text =& preg_replace( "#\r#", "\n", $text );

        $text =& preg_replace( "#\n[\n]+#", "\n\n", $text );

        /* $text =& preg_replace( "#<bold>#", "<strong>", $text );
        $text =& preg_replace( "#</bold>#", "</strong>", $text );

        $text =& preg_replace( "#<em>#", "<emphasize>", $text );
        $text =& preg_replace( "#</em>#", "</emphasize>", $text );*/

        $text =& preg_replace( "#\n[\n]+#", "<paragraph>", $text );

        // Convert headers
        $text =& preg_replace( "#<header>#", "<header level='1'>", $text );

        $data = $text;

        eZDebug::writeDebug($data, "input");
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
        while ( $pos < strlen( $data ) )
        {
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
                    array_push( $TagStack,
                                array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag ) );
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

                    // Call function to handle special end tags.

                    $lastInsertedNodeTag = $this->handleEndTag( $convertedTag, $lastInsertedNodeTag, &$currentNode, &$lastInsertedNode, &$TagStack );

                    if ( $lastInsertedNodeTag != $convertedTag )
                    {
                        array_push( $TagStack,
                                    array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag ) );
                        if ( in_array( $convertedTag, $this->supportedInputTagArray ) )
                        {
                            //Set input invalid
                            // $this->isInputValid = false;
                            $message .= "<li>Unmatched tag " . $convertedTag . "(removed)</li>";
                        }
                        if ( in_array( $lastInsertedNodeTag, $this->supportedInputTagArray ) and in_array( $convertedTag, $this->supportedTagArray ) )
                        {
                            if ( $this->isInputValid == true )
                                $message .= "<li>Unmatched tag " . $lastInsertedNodeTag . "</li>";
                            //Set input invalid
                            $this->isInputValid = false;
                        }
                    }
                    else
                    {
                        unset( $currentNode );
                        $currentNode =& $lastInsertedNode ;

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
                            $childTag = array_merge( $this->blockTagArray, $this->inlineTagArray );
                            array_push( $TagStack,
                                        array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                            $lastInsertedNodeTag = "paragraph";
                            $lastInsertedChildTag = $childTag;
                        }
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
                        $subNode->Name = $covertedName;
                        $subNode->LocalName = $covertedName;
                        $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                        $domDocument->registerElement( $subNode );
                        $currentNode->appendChild( $subNode );

                        $childTag = $this->sectionArray;
                        if ( $tagName[strlen($tagName) - 1]  != "/" )
                        {
                            array_push( $TagStack,
                                        array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                            unset( $currentNode );
                            $currentNode =& $subNode;
                        }
                    }
                    elseif ( !in_array( $justName, $this->supportedTagArray ) )
                    {
                        $message .= "<li>Unsupported tag " . $justName .  "(removed)</li>";
                    }
                    elseif ( $justName == "header" )
                    {
                        if ( $lastInsertedNodeTag == "paragraph" )
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
                                 array_push( $TagStack,
                                             array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag ) );
                             }
                         }

                        if ( in_array( $justName, $lastInsertedChildTag ) )
                        {
                            $headerLevel = 1;
                            $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                            // attributes
                            unset( $attr );
                            $attr =& $this->parseAttributes( $attributePart );
                            foreach( $attr as $attrbute )
                            {
                                $attrName = $attrbute->Name;
                                if ( $attrName == 'level' )
                                {
                                    $headerLevel = $attrbute->Content;
                                }
                            }

                            if ( $lastInsertedNodeTag == "td" or $lastInsertedNodeTag == "th" )
                            {
                                    $sectionLevel = $sectionLevel;
                            }
                            else
                            {
                                switch ( $headerLevel )
                                {
                                    case "1" :
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 1, &$TagStack, &$currentNode );
                                    }break;
                                    case "2":
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 2, &$TagStack, &$currentNode );
                                    }break;
                                    case "3":
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 3, &$TagStack, &$currentNode );
                                    }break;
                                    case "4":
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 4, &$TagStack, &$currentNode );
                                    }break;
                                    case "5":
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 5, &$TagStack, &$currentNode );
                                    }break;
                                    case "6":
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 6, &$TagStack, &$currentNode );
                                    }break;
                                    default:
                                    {
                                        $sectionLevel = $this->sectionLevel( $sectionLevel, 1, &$TagStack, &$currentNode );
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
                            $childTag = $this->sectionArray;
                            array_push( $TagStack,
                                        array( "TagName" => "section", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                            $currentNode =& $subNode;
                            $covertedName = 'header';
                            unset( $subNode );
                            $subNode = new eZDOMNode();
                            $subNode->Name = $covertedName;
                            $subNode->LocalName = $covertedName;
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );

                            $childTag = array( );
                            if ( $tagName[strlen($tagName) - 1]  != "/" )
                            {
                                array_push( $TagStack,
                                            array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                unset( $currentNode );
                                $currentNode =& $subNode;
                            }
                        }
                        else
                        {
                            $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $lastInsertedNodeTag ."' (removed).</li>";
                        }
                    }
                    else
                    {
                        $currentNode =& $this->handleStartTag( $justName, $tagName, $lastInsertedNodeTag, $currentNode, $domDocument, $TagStack, $message, $attributePart );
                    }
                    if ( $justName == "literal" )
                    {
                        // Find the end tag and create override contents
                        $preEndTagPos = strpos( $data, "</literal>", $pos );
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
                    $tagContent =& str_replace("&gt;", ">", $tagContent );
                    $tagContent =& str_replace("&lt;", "<", $tagContent );
                    $tagContent =& str_replace("&apos;", "'", $tagContent );
                    $tagContent =& str_replace("&quot;", '"', $tagContent );
                    $tagContent =& str_replace("&amp;", "&", $tagContent );
                    $tagContent =& str_replace("&nbsp;", " ", $tagContent );

                    $subNode->Content = $tagContent;
                    $domDocument->registerElement( $subNode );
                    $currentNode->appendChild( $subNode );
                }
            }
        }
        $output = array( $domDocument, $message );
        return $output;
    }

    // Add an paragrph node to current node
    function addParagraphNode( &$domDocument, &$TagStack, &$currentNode )
    {
        // create the new XML element node
        //unset( $subNode );
        $subNode = new eZDOMNode();
        $subNode->Name = "paragraph";
        $subNode->LocalName = "paragraph";
        $subNode->Type = EZ_NODE_TYPE_ELEMENT;
        $domDocument->registerElement( $subNode );
        $currentNode->appendChild( $subNode );
        $childTag = $childTagForParagraph;
        array_push( $TagStack,
                    array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
        $currentNode =& $subNode;
        $lastInsertedTag = "paragraph";
        $lastInsertedChildTag = $childTag;
    }

    // Get section level and reset cuttent node according to input header.
    function &sectionLevel( $sectionLevel, $headerLevel, &$TagStack, &$currentNode )
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
         return $sectionLevel;
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

           foreach ( $attrbutesNodeWithQuote as $attrbutesNode )
           {
               $attrbutes[] = $attrbutesNode;
           }
           foreach ( $attrbutesNodeWithoutQuote as $attrbutesNode )
           {
               $attrbutes[] = $attrbutesNode;
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

                    $this->DOMDocument->registerNamespaceAlias( $attributeName, $attributeValue );
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
                    array_push( $this->NamespaceStack, $attributeNamespaceURI );
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
    function &inputXML( )
    {
        $http =& eZHTTPTool::instance();
        $isInputValid = $http->sessionVariable( 'isInputValid' );
        if ( $isInputValid == "false" )
        {
            $output = $http->sessionVariable( 'inputValue' );
        }
        else
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $this->XMLData );

            if ( $dom )
                $node =& $dom->elementsByName( "section" );

            $output = "";
            if ( count( $node ) > 0 )
            {
                $children =& $node[0]->children();
                if ( count( $children ) > 0 )
                    $output .= $this->inputSectionXML( $node[0], 0 );
            }
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given section
    */
    function &inputTdXML( &$tdNode, $currentSectionLevel )
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
                $output .= trim( $this->inputParagraphXML( $tdNode, $currentSectionLevel ) ) . "\n\n";
            }break;
            case 'section' :
            {
                $sectionLevel = $currentSectionLevel + 1;
                $output .= $this->inputSectionXML( $tdNode, $sectionLevel );
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
     \return the user input format for the given section
    */
    function &inputSectionXML( &$section,  $currentSectionLevel )
    {
        $output = "";
        foreach ( $section->children() as $sectionNode )
        {
            $sectionLevel = $currentSectionLevel;
            $tagName = $sectionNode->name();

            switch ( $tagName )
            {
                case 'header' :
                {
                    $level = $sectionLevel;
                    $output .= "<$tagName level='$level'>" . $sectionNode->textContent(). "</$tagName>\n";
                }break;

                case 'paragraph' :
                {
                    $output .= trim( $this->inputParagraphXML( $sectionNode, $currentSectionLevel ) ) . "\n\n";
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    $output .= $this->inputSectionXML( $sectionNode, $sectionLevel );
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
    function &inputParagraphXML( &$paragraph, $currentSectionLevel )
    {
        $output = "";
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $output .= $this->inputTagXML( $paragraphNode, $currentSectionLevel );
        }
        return $output;
    }

    /*!
     \return the input xml for the given tag
    */
    function &inputTagXML( &$tag, $currentSectionLevel )
    {
        $output = "";
        $tagName = $tag->name();
        $childTagText = "";
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            $childTagText .= $this->inputTagXML( $childTag );
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $output .= $tag->content();
            }break;

            case 'object' :
            {
                $size = "";
                $view = $tag->attributeValue( 'view' );
                $size = $tag->attributeValue( 'size' );
                $alignment = $tag->attributeValue( 'align' );
                if ( strlen( $view ) == 0 )
                    $view = "embed";
                if ( strlen( $alignment ) == 0 )
                    $alignment = "center";

                $objectID = $tag->attributeValue( 'id' );
                if ( $size != "" )
                    $output .= "<object id=\"$objectID\" size=\"$size\" align=\"$alignment\" />";
                else
                    $output .= "<object id=\"$objectID\" align=\"$alignment\" />";
            }break;

            case 'ul' :
            case 'ol' :
            {
                $listContent = "";
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = "";
                    foreach ( $listItemNode->children() as $itemChildNode )
                    {
                        $listItemContent .= $this->inputTagXML( $itemChildNode );
                    }
                    $listContent .= "  <li>$listItemContent</li>\n";
                }
                $output .= "<$tagName>\n$listContent</$tagName>";
            }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                $width = $tag->attributeValue( 'width' );
                // $borderColor = $tag->attributeValue( 'bordercolor' );
                if ( $border == null )
                    $border = 1;
                if ( $width == null )
                    $width = "100%";
                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = "";
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = "";

                        foreach ( $tableCell->children() as $tableCellChildNode )
                        {
                            $cellContent .= $this->inputTdXML( $tableCellChildNode, $currentSectionLevel );
                        }
                        if ( $tableCell->name() == "th" )
                            $tableData .= "<th>" . trim( $cellContent ) . "</th>";
                        else
                            $tableData .= "  <td>" . trim( $cellContent ) . "</td>";
                    }
                    $tableRows .= "<tr>\n$tableData</tr>";
                }
                $output .= "<table border='$border' width='$width'>\n$tableRows</table>\n";
            }break;

            case 'literal' :
            {
                $output .= "<$tagName>" . $childTagText . "</$tagName>";
            }break;

            // normal content tags
            case 'emphasize' :
            case 'em' :
            case 'i' :
            {
                $output .= "<emphasize>" . $childTagText . "</emphasize>";
            }break;

            // normal content tags
            case 'b' :
            case 'bold' :
            case 'strong' :
            {
                $output .= "<strong>" . $childTagText . "</strong>";
            }break;

            // Custom tags
            case 'custom' :
            {
                $name = $tag->attributeValue( 'name' );
                $customTagContent = "";
                foreach ( $tag->children() as $tagChild )
                {
                    $customTagContent .= $this->inputParagraphXML( $tagChild );
                }
                $output .= "<$tagName name='$name'>" .  $customTagContent . "</$tagName>";
            }break;

            case 'link' :
            {
                $linkID = $tag->attributeValue( 'id' );
                $target = false;
                if ( $linkID != null )
                    $href =& eZURL::url( $linkID );
                else
                {
                    $href = $tag->attributeValue( 'href' );
                    $target = $tag->attributeValue( 'target' );
                }
                $attributes = array();
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
                $output .= "<line>" . $childTagText . "</line>";
            }break;

            case 'tr' :
            case 'td' :
            case 'th' :
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

    var $sectionArray = array( 'paragraph', 'section', 'header' );

    var $blockTagArray = array( 'table', 'ul', 'ol', 'literal', 'custom', 'object' );

    var $inlineTagArray = array( 'emphasize', 'strong', 'link', 'anchor', 'line' );

    var $tagAliasArray = array( 'strong' => array( 'b', 'bold', 'strong' ), 'emphasize' => array( 'em', 'i', 'emphasize' ) );

    // Contains all supported tag for xml parse
    var $supportedTagArray = array( 'paragraph', 'section', 'header', 'table', 'ul', 'ol', 'literal', 'custom', 'object', 'emphasize', 'strong', 'link', 'anchor', 'tr', 'td', 'th', 'li', 'line' );

    // Contains all supported input tag
    var $supportedInputTagArray = array( 'header', 'table', 'ul', 'ol', 'literal', 'custom', 'object', 'emphasize', 'strong', 'link', 'anchor', 'tr', 'td', 'th', 'li' , 'line' );
}

?>
