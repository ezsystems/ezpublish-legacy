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

/*!

<?xml version="1.0" encoding="utf-8" ?>
<section>
<header>This is a level one header</header>
<paragraph>
This is a <emphasize>block</emphasize> of text.
</paragraph>
  <section>
  <header>This is a level two header</header>
  <paragraph>
  This is the second paragraph.<emphasize>emphasized/bold text</emphasize>
  </paragraph>
  <header>This is a level two header</header>
  <paragraph>
  This is the second paragraph.<emphasize>emphasized/bold text</emphasize>
  </paragraph>
  <paragraph>
  This is the second paragraph.<emphasize>emphasized/bold text</emphasize>
  </paragraph>
  <paragraph>
  <ul>
     <li>List item 1</li>
     <li>List item 2</li>
  </ul>
  </paragraph>
  <header>This is a level two header</header>
  </section>
</section>


$sectionArray = array( 'paragraph', 'section', 'header' );

$blockTagArray = array( 'table', 'ul', 'ol', 'literal', 'custom', 'object' );
$inlineTagArray = array( 'emphasize', 'strong', 'link', 'anchor', 'foo' );

$tagAliasArray = array( 'stro' => arrau( 'b', 'bold' ) );

$tableTagArray = array( 'tr' );
$trTagArray = array( 'td', 'th' );

$tagAttributeArra['table'] = array( 'width' => array( 'required' => false ) );
$tagAttributeArra['link'] = array( 'href' => array( 'required' => true ) );

$paragraph = array_merge( $blockTagArray, $inlineTagArray );


//
function handleTag()
{
}

Section


*/

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php' );

class eZSimplifiedXMLInput extends eZXMLInputHandler
{
    function eZSimplifiedXMLInput( &$xmlData, $aliasedType )
    {
        $this->eZXMLInputHandler( $xmlData, $aliasedType );
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

            $inputData = "<section>";
            $inputData .= "<p>";
            $inputData .= $data;
            $inputData .= "</p>";
            $inputData .= "</section>";

            $data =& $this->convertInput( $inputData );
            $message = $data[1];
            if ( $message == "Valid" )
            {
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
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                             'Object %1 does not exist.',
                                                                             'ezXMLTextType',
                                                                             array( $objectID ) ) );
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
                            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                                 'Link %1 does not exist.',
                                                                                 'ezXMLTextType',
                                                                                 array( $linkID ) ) );
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

        $text =& preg_replace( "#\n[\n]+#", "<p>", $text );

        // Convert headers
        $text =& preg_replace( "#<header>#", "<header level='1'>", $text );

        $data = $text;

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
                // Get the original last inserted tag name.
                switch ( $lastInsertedNodeTag )
                {
                    case 'paragraph' :
                    {
                        $originalTagName = "p";
                    }break;
                    default:
                    {
                        $originalTagName = $lastInsertedNodeTag;
                    }break;
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

                    $isPTag = false;
                    switch ( $tagName )
                    {

                        // FIX: Need to use alias array
                        case 'i' :
                        case 'em' :
                        {
                            $convertedTag = "emphasize";
                        }break;
                        case 'b' :
                        case 'bold' :
                        case 'strong' :
                        {
                            $convertedTag = "strong";
                        }break;
                        case 'p' :
                        {
                            $convertedTag = "paragraph";
                            $isPTag = true;
                        }break;
                        case 'table' :
                        {
                            $convertedTag = "table";
                        }break;
                        case 'custom' :
                        {
                            unset( $currentNode );
                            $currentNode =& $lastInsertedNode ;
                            $lastInsertedNodeArray = array_pop( $TagStack );
                            $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                            $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                            $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                            $convertedTag = "custom";
                        }break;
                        case 'header' :
                        {
                            $convertedTag = "header";
                            $isPTag = true;
                        }break;
                        case 'ul' :
                        {
                            unset( $currentNode );
                            $currentNode =& $lastInsertedNode ;
                            $lastInsertedNodeArray = array_pop( $TagStack );
                            $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                            $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                            $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                            $convertedTag = "paragraph";
                        }break;
                        case 'ol' :
                        {
                            unset( $currentNode );
                            $currentNode =& $lastInsertedNode ;
                            $lastInsertedNodeArray = array_pop( $TagStack );
                            $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                            $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                            $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                            $convertedTag = "paragraph";
                        }break;
                        case 'td' :
                        {
                            while ( $lastInsertedNodeTag == "section" or $lastInsertedNodeTag == "paragraph" )
                            {
                                unset( $currentNode );
                                $currentNode =& $lastInsertedNode ;

                                $lastInsertedNodeArray = array_pop( $TagStack );
                                $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                                $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                                $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                            }
                            $convertedTag = "td";
                        }break;
                        case 'th' :
                        {
                            while ( $lastInsertedNodeTag == "section" or $lastInsertedNodeTag == "paragraph" )
                            {
                                unset( $currentNode );
                                $currentNode =& $lastInsertedNode ;

                                $lastInsertedNodeArray = array_pop( $TagStack );
                                $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                                $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                                $lastInsertedChildTag = $lastInsertedNodeArray["ChildTag"];
                            }
                            $convertedTag = "th";
                        }break;
                        default:
                        {
                            $convertedTag = $tagName;
                        }break;

                    }
                    if ( $lastInsertedNodeTag != $convertedTag )
                    {
                        array_push( $TagStack,
                                    array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode, "ChildTag" => $lastInsertedChildTag ) );
                    }
                    else
                    {
                        unset( $currentNode );
                        $currentNode =& $lastInsertedNode ;

                        if ( $isPTag )
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
                            $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "literal", "custom" );
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
                    $covertedName = null;

                    // create the new XML element node
                    unset( $subNode );
                    $subNode = new eZDOMNode();

                    switch ( $justName )
                    {
                        case 'p' :
                        {
                            if ( $lastInsertedNodeTag == "paragraph" )
                            {
                                $lastNodeArray = array_pop( $TagStack );
                                $lastInsertedNodeTag = $lastNodeArray["TagName"];
                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                $lastInsertedChildTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom", "p", "section" );
                                unset( $currentNode );
                                $currentNode =& $lastNode;
                            }

                            // Fix the general syntax error that paragraph starts with <b> tag.
                            if ( $lastInsertedNodeTag == "strong" )
                            {
                                $lastNodeArray = array_pop( $TagStack );
                                $lastTag = $lastNodeArray["TagName"];
                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                $lastInsertedChildTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom", "p", "section" );
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
                                if ( $lastInsertedNodeTag == "paragraph" )
                                {
                                    $lastNodeArray = array_pop( $TagStack );
                                    $lastTag = $lastNodeArray["TagName"];
                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                    $lastInsertedChildTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom", "p", "section" );
                                    unset( $currentNode );
                                    $currentNode =& $lastNode;
                                }
                            }

                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'paragraph';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'strong' :
                        case 'b' :
                        case 'bold' :
                        {
                            if ( $lastInsertedNodeTag == 'section' or $lastInsertedNodeTag == 'td' or $lastInsertedNodeTag == 'th' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'strong';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array(  "i", "em", "emphasize", "header", "link", "anchor" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'i' :
                        case 'em' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'emphasize';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "strong", "b", "bold", "header", "link", "anchor" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                             array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            elseif ( $lastInsertedNodeTag == 'literal' )
                            {
                                //no error message;
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'table' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                if ( $tagNameEnd > 0 )
                                {
                                    $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                                    // attributes
                                    unset( $attr );
                                    $attr =& $this->parseAttributes( $attributePart );
                                    $covertedName = 'table';
                                    $subNode->Attributes = $attr;
                                    $subNode->Name = $covertedName;
                                    $subNode->LocalName = $covertedName;
                                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                    $domDocument->registerElement( $subNode );
                                    $currentNode->appendChild( $subNode );
                                    $childTag = array( "tr" );
                                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                                    {
                                        array_push( $TagStack,
                                                    array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                        unset( $currentNode );
                                        $currentNode =& $subNode;
                                        $lastInsertedTag = $covertedName;
                                    }
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'tr' :
                        {
                            if ( $lastInsertedNodeTag == 'tr' )
                            {
                                $lastNodeArray = array_pop( $TagStack );
                                $lastTag = $lastNodeArray["TagName"];
                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                $lastInsertedChildTag = array( "tr" );
                                unset( $currentNode );
                                $currentNode =& $lastNode;
                                $message .= "<li>Tag '" . $justName . "' does not have an end tag '" . $originalTagName ."' (fixed).</li>";
                            }

                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'tr';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "td", "th" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'td' :
                        {
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                /* if ( $tagNameEnd > 0 )
                                {
                                    $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );

                                    // attributes
                                    unset( $attr );
                                    $attr =& $this->parseAttributes( $attributePart );

                                    $convertedAttr = array();
                                    foreach( $attr as $attrbute )
                                    {
                                        $attrName = $attrbute->Name;
                                        $attrName = strtolower( $attrName );
                                        if ( $attrName == 'colspan' or $attrName == 'rowspan' )
                                        {
                                            $convertedAttr[] = $attrbute;
                                        }
                                    }
                                    if ( $convertedAttr != false )
                                        $subNode->Attributes = $convertedAttr;
                                }*/
                                $covertedName = 'td';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "p", "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom"  );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedNodeTag = $covertedName;
                                }

                                // Add paragraph tag
                                // create the new XML element node
                                unset( $subNode );
                                $subNode = new eZDOMNode();
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                unset( $currentNode );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;

                        case 'th' :
                        {
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'th';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "p", "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom"  );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedNodeTag = $covertedName;
                                }

                                // Add paragraph tag
                                // create the new XML element node
                                unset( $subNode );
                                $subNode = new eZDOMNode();
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                unset( $currentNode );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;

                        case 'custom' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }

                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                if ( $tagNameEnd > 0 )
                                {
                                    $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                                    // attributes
                                    unset( $attr );
                                    $attr =& $this->parseAttributes( $attributePart );
                                    $covertedName = 'custom';
                                    $subNode->Attributes = $attr;
                                    $subNode->Name = $covertedName;
                                    $subNode->LocalName = $covertedName;
                                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                    $domDocument->registerElement( $subNode );
                                    $currentNode->appendChild( $subNode );
                                    $childTag = array( "p", "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );

                                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                                    {
                                        array_push( $TagStack,
                                                    array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                        unset( $currentNode );
                                        $currentNode =& $subNode;
                                        $lastInsertedTag = $covertedName;
                                    }

                                    // Add paragraph tag

                                    // create the new XML element node
                                    unset( $subNode );
                                    $subNode = new eZDOMNode();
                                    $subNode->Name = "paragraph";
                                    $subNode->LocalName = "paragraph";
                                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                    $domDocument->registerElement( $subNode );
                                    $currentNode->appendChild( $subNode );
                                    $childTag = array( "table", "strong", "b", "bold", "i", "em", "h1", "h2", "h3", "h4", "h5", "h6", "img", "ol", "ul", "a", "literal", "custom" );
                                    array_push( $TagStack,
                                                array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = "paragraph";
                                    $lastInsertedChildTag = $childTag;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'literal' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }

                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'literal';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "p", "header" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedNodeTag = $covertedName;
                                }
                                // Find the end tag and create override contents
                                $preEndTagPos = strpos( $data, "</literal>", $pos );
                                $overrideContent = substr( $data, $pos + 5, $preEndTagPos - ( $pos + 5 ) );
                                $pos = $preEndTagPos - 1;
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'header' :
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
                                            if ( $sectionLevel < 1 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 1 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 1;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 1 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 1;
                                            }
                                        }break;
                                        case "2":
                                        {
                                            if ( $sectionLevel < 2 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 2 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 2;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 2 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 2;
                                            }
                                        }break;
                                        case "3":
                                        {
                                            if ( $sectionLevel < 3 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 3 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 3;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 3 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 3;
                                            }
                                        }break;
                                        case "4":
                                        {
                                            if ( $sectionLevel < 4 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 4 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 4;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 4 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 4;
                                            }
                                        }break;
                                        case "5":
                                        {
                                            if ( $sectionLevel < 5 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 5 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 5;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 5 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 5;
                                            }
                                        }break;
                                        case "6":
                                        {
                                            if ( $sectionLevel < 6 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 6 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 6;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 6 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 6;
                                            }
                                        }break;
                                        default:
                                        {
                                            if ( $sectionLevel < 1 )
                                            {
                                                $sectionLevel += 1;
                                            }
                                            elseif ( $sectionLevel == 1 )
                                            {
                                                $lastNodeArray = array_pop( $TagStack );
                                                $lastTag = $lastNodeArray["TagName"];
                                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                $lastChildTag = $lastNodeArray["ChildTag"];
                                                unset( $currentNode );
                                                $currentNode =& $lastNode;
                                                $sectionLevel = 1;
                                            }
                                            else
                                            {
                                                for ( $i=1;$i<=( $sectionLevel - 1 + 1 );$i++ )
                                                {
                                                    $lastNodeArray = array_pop( $TagStack );
                                                    $lastTag = $lastNodeArray["TagName"];
                                                    $lastNode =& $lastNodeArray["ParentNodeObject"];
                                                    $lastChildTag = $lastNodeArray["ChildTag"];
                                                    unset( $currentNode );
                                                    $currentNode =& $lastNode;
                                                }
                                                $sectionLevel = 1;
                                            }
                                        }break;
                                    }
                                }

                                // Add section tag
                                $subNode->Name = "section";
                                $subNode->LocalName = "section";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "literal", "custom", "p", "section" );
                                array_push( $TagStack,
                                            array( "TagName" => "section", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "section";

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
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;

                        case 'li' :
                        {
                            if ( $lastInsertedNodeTag == 'li' )
                            {
                                $lastNodeArray = array_pop( $TagStack );
                                $lastTag = $lastNodeArray["TagName"];
                                $lastNode =& $lastNodeArray["ParentNodeObject"];
                                $lastInsertedChildTag = array( "li" );
                                unset( $currentNode );
                                $currentNode =& $lastNode;
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'li';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "strong", "b", "bold", "i", "em", "emphasize", "object", "link", "anchor", "literal", "custom", "table" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                        }break;
                        case 'ol' :
                        {
                            if ( $lastInsertedNodeTag == 'section' or $lastInsertedNodeTag == 'td' or $lastInsertedNodeTag == 'th' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'ol';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "li" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'ul' :
                        {
                            if ( $lastInsertedNodeTag == 'section' or $lastInsertedNodeTag == 'td' or $lastInsertedNodeTag == 'th' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                $covertedName = 'ul';
                                $subNode->Name = $covertedName;
                                $subNode->LocalName = $covertedName;
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );

                                $childTag = array( "li" );
                                if ( $tagName[strlen($tagName) - 1]  != "/" )
                                {
                                    array_push( $TagStack,
                                                array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                    unset( $currentNode );
                                    $currentNode =& $subNode;
                                    $lastInsertedTag = $covertedName;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'link' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );

                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                if ( $tagNameEnd > 0 )
                                {
                                    $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                                    // attributes
                                    unset( $attr );
                                    $attr =& $this->parseAttributes( $attributePart );

                                    $covertedName = 'link';
                                    $subNode->Attributes = $attr;
                                    $subNode->Name = $covertedName;
                                    $subNode->LocalName = $covertedName;
                                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                    $domDocument->registerElement( $subNode );
                                    $currentNode->appendChild( $subNode );

                                    $childTag = array( "object", "i", "em", "emphasize", "b", "bold", "strong" );
                                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                                    {
                                        array_push( $TagStack,
                                                    array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                        unset( $currentNode );
                                        $currentNode =& $subNode;
                                        $lastInsertedTag = $covertedName;
                                    }
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'anchor' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );

                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) )
                            {
                                if ( $tagNameEnd > 0 )
                                {
                                    $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                                    // attributes
                                    unset( $attr );
                                    $attr =& $this->parseAttributes( $attributePart );

                                    $covertedName = 'anchor';
                                    $subNode->Attributes = $attr;
                                    $subNode->Name = $covertedName;
                                    $subNode->LocalName = $covertedName;
                                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                    $domDocument->registerElement( $subNode );
                                    $currentNode->appendChild( $subNode );

                                    $childTag = array( "object", "i", "em", "emphasize", "b", "bold", "strong" );
                                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                                    {
                                        array_push( $TagStack,
                                                    array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                        unset( $currentNode );
                                        $currentNode =& $subNode;
                                        $lastInsertedTag = $covertedName;
                                    }
                                    $lastNodeArray = array_pop( $TagStack );
                                    $lastTag = $lastNodeArray["TagName"];
                                    $lastNode =& $lastNodeArray["ParentNodeObject"];

                                    unset( $currentNode );
                                    $currentNode =& $lastNode;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'object' :
                        {
                            if ( $lastInsertedNodeTag == 'section' )
                            {
                                // Add paragraph tag
                                $subNode->Name = "paragraph";
                                $subNode->LocalName = "paragraph";
                                $subNode->Type = EZ_NODE_TYPE_ELEMENT;
                                $domDocument->registerElement( $subNode );
                                $currentNode->appendChild( $subNode );
                                $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom" );
                                array_push( $TagStack,
                                            array( "TagName" => "paragraph", "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                $currentNode =& $subNode;
                                $lastInsertedTag = "paragraph";
                                $lastInsertedChildTag = $childTag;

                                unset( $subNode );
                                $subNode = new eZDOMNode();
                            }
                            if ( in_array( $justName, $lastInsertedChildTag ) or $lastInsertedNodeTag == 'header' )
                            {
                                $convertedAttr = array();
                                if ( $tagNameEnd > 0 )
                                {
                                    $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );

                                    // attributes
                                    unset( $attr );
                                    $attr =& $this->parseAttributes( $attributePart );
                                    foreach( $attr as $attrbute )
                                    {
                                        $attrName = $attrbute->Name;
                                        if ( $attrName == 'id' )
                                        {
                                            $convertedAttr[] = $attrbute;
                                        }
                                        if ( $attrName == 'size' )
                                        {
                                            $convertedAttr[] = $attrbute;
                                        }
                                        if ( $attrName == 'align' )
                                        {
                                            $convertedAttr[] = $attrbute;
                                        }
                                    }
                                }
                                if ( in_array( $justName, $lastInsertedChildTag ) )
                                {
                                    if ( $convertedAttr != false )
                                        $subNode->Attributes = $convertedAttr;
                                    $covertedName = 'object';
                                    $subNode->Name = $covertedName;
                                    $subNode->LocalName = $covertedName;
                                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                                    $domDocument->registerElement( $subNode );
                                    $currentNode->appendChild( $subNode );

                                    $childTag = array( "" );
                                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                                    {
                                        array_push( $TagStack,
                                                    array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                        unset( $currentNode );
                                        $currentNode =& $subNode;
                                        $lastInsertedTag = $covertedName;
                                    }

                                    $lastNodeArray = array_pop( $TagStack );
                                    $lastTag = $lastNodeArray["TagName"];
                                    $lastNode =& $lastNodeArray["ParentNodeObject"];

                                    unset( $currentNode );
                                    $currentNode =& $lastNode;
                                }
                            }
                            else
                            {
                                $message .= "<li>Tag '" . $justName . "' is not allowed to be the child of '" . $originalTagName ."' (removed).</li>";
                            }
                        }break;
                        case 'section' :
                        {
                            $covertedName = 'section';
                            $subNode->Name = $covertedName;
                            $subNode->LocalName = $covertedName;
                            $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                            $domDocument->registerElement( $subNode );
                            $currentNode->appendChild( $subNode );

                            $childTag = array( "table", "strong", "b", "bold", "i", "em", "emphasize", "header", "object", "ol", "ul", "link", "anchor", "literal", "custom", "p", "section" );
                            if ( $tagName[strlen($tagName) - 1]  != "/" )
                            {
                                array_push( $TagStack,
                                            array( "TagName" => $covertedName, "ParentNodeObject" => &$currentNode, "ChildTag" => $childTag ) );
                                unset( $currentNode );
                                $currentNode =& $subNode;
                                $lastInsertedTag = $covertedName;
                            }
                        }break;
                        case 'tbody' :
                        {
                            // remove the tag without log message (probably it will be supported later).
                        }break;
                        default :
                        {
                            $message .= "<li>Tag '" . $justName . "' is not supported (removed).</li>";
                        }break;
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
                if (  trim( $tagContent ) != "" )
                {
                    $domDocument->registerElement( $subNode );
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

    /*!
      \private
      Parses the attributes. Returns false if no attributes in the supplied string is found.
    */
    function &parseAttributes( $attributeString )
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
                    /* $level = $sectionNode->attributeValue( 'level' );
                    if ( is_numeric( $level ) )
                        $level = $sectionNode->attributeValue( 'level' );
                    else
                        $level = 1;*/

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
//                     if ( strlen( $target ) == 0 )
//                         $target = "self_";
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
}

?>
