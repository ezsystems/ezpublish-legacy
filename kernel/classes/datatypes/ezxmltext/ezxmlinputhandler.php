<?php
//
// Definition of eZXMLInputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
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

/*! \file ezxmlinputhandler.php
*/

/*!
  \class eZXMLInputHandler
  \brief The class eZXMLInputHandler does

*/

include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "lib/ezxml/classes/ezdomnode.php" );
include_once( "lib/ezxml/classes/ezdomdocument.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );


class eZXMLInputHandler
{
    /*!
     Constructor
    */
    function eZXMLInputHandler()
    {
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function &validateInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );

            $data =& $this->convertInput( $data );
            $message = $data[1];
            if ( $message != "" )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                     $message,
                                                                     'ezXMLTextType' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
            else
            {
                $dom = $data[0];
                $contentObjectAttribute->setAttribute( "data_text", $dom->toString() );
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
                        $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                             'Object '. $objectID .' does not exist.',
                                                                             'ezXMLTextType' ) );
                        return EZ_INPUT_VALIDATOR_STATE_INVALID;
                    }else
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
                foreach ( $links as $link )
                {
                    if ( $link->attributeValue( 'id' ) != null )
                    {
                        $linkID = $link->attributeValue( 'id' );
                        $url =& eZURL::url( $linkID );
                        if (  $url == null )
                        {
                            $contentObjectAttribute->setValidationError( ezi18n( 'content/datatypes',
                                                                                 'Link '. $linkID .' does not exist.',
                                                                                 'ezXMLTextType' ) );
                            return EZ_INPUT_VALIDATOR_STATE_INVALID;
                        }
                    }
                    if ( $link->attributeValue( 'href' ) != null )
                    {
                        $url = $link->attributeValue( 'href' );
                        $linkID =& eZURL::registerURL( $url );
                    }
                }
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }


    /*!
     \private
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

        // Convert headers
        $text =& preg_replace( "#<header>#", "<header level='1'>", $text );
        $sectionData = "<section>";
        $sectionArray =& preg_split( "#(<header.*?>.*?</header>)#", $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
        $sectionLevel = 0;
        $unMatchedSection = 0;
        foreach ( $sectionArray as $sectionPart )
        {
            // check for header
            if ( preg_match( "#(<header\s+level=[\"|'](.*?)[\"|']\s*>.*?</header>)#", $sectionPart, $matchArray ) )
            {
                // get level for header
                $newSectionLevel = $matchArray[2];

                if ( $newSectionLevel > $sectionLevel )
                {
                    $sectionData .= "<section>\n";
                    $unMatchedSection += 1;
                }

                if ( $newSectionLevel < $sectionLevel )
                {
                    for ( $i=1;$i<=($unMatchedSection - $newSectionLevel + 1);$i++ )
                    {
                        $sectionData .= "\n</section>\n";
                    }
                    $sectionData .= "\n<section>\n";
                    $unMatchedSection = $newSectionLevel;
                }

                $sectionLevel = $newSectionLevel;
                $sectionData .= $sectionPart;
                // Remove header level.
                $sectionData = preg_replace( "# level=.*?>#", ">" , $sectionData );
            }
            else
            {
                $paragraphArray = explode( "\n\n", $sectionPart );

                foreach ( $paragraphArray as $paragraph )
                {
                    if ( trim( $paragraph ) != "" )
                    {
                        $sectionData .= "<paragraph>" . trim( $paragraph ) . "</paragraph>\n";
                    }
                }
            }
        }
        /* if ( $sectionLevel > 1 )
        {
            $sectionData .= str_repeat( "\n</section>\n", $sectionLevel - 1 );
        }*/

        while ( $unMatchedSection > 0 )
        {
            $sectionData .= "</section>";
            $unMatchedSection -= 1;
        }
        $sectionData .= "</section>";

        $data =& $sectionData;

        $domDocument = new eZDOMDocument();
        $currentNode =& $domDocument;
        $TagStack = array();
        $pos = 0;
        $endTagPos = 0;
        $unMatchedParagraph = false;
        $headerSubtag = false;
        $lastInsertedNodeTag = null;
        while ( $pos < strlen( $data ) )
        {
            $char = $data[$pos];
            if ( $char == "<" )
            {
                 $lastInsertedNodeArray = array_pop( $TagStack );
                 if ( $lastInsertedNodeArray !== null )
                 {
                     $lastInsertedNodeTag = $lastInsertedNodeArray["TagName"];
                     $lastInsertedNode =& $lastInsertedNodeArray["ParentNodeObject"];
                     array_push( $TagStack,
                             array( "TagName" => $lastInsertedNodeTag, "ParentNodeObject" => &$lastInsertedNode ) );
                 }
                // find tag name
                $endTagPos = strpos( $data, ">", $pos );

                // tag name with attributes
                $tagName = substr( $data, $pos + 1, $endTagPos - ( $pos + 1 ) );
                // check if it's an endtag </tagname>
                if ( $tagName[0] == "/" )
                {
                    $lastNodeArray = array_pop( $TagStack );
                    $lastTag = $lastNodeArray["TagName"];
                    $lastNode =& $lastNodeArray["ParentNodeObject"];

                    $tagName = substr( $tagName, 1, strlen( $tagName ) );

                    if ( $lastTag != $tagName )
                    {
                        if ( $tagName == "paragraph" )
                        {
                            array_push( $TagStack,
                                        array( "TagName" => $lastTag, "ParentNodeObject" => &$lastNode ) );
                            $unMatchedParagraph = true;
                        }
                        elseif ( ( $lastTag  == "header" ) and $headerSubtag )
                        {
                            array_push( $TagStack,
                                        array( "TagName" => $lastTag, "ParentNodeObject" => &$lastNode ) );
                        }
                        else
                        {
                            $message =  "Unmatched tag " . $tagName;
                            $output = array( null, $message );
                            return $output;
                        }
                    }
                    else
                    {
                        unset( $currentNode );
                        $currentNode =& $lastNode;
                        eZDebug::writeDebug( $tagName. " is valid" );
                    }
                }
                elseif ( ( $tagName == "paragraph" ) and ( $unMatchedParagraph ) )
                {
                    // Do nothing
                    $unMatchedParagraph = false;
                }
                elseif ( $lastInsertedNodeTag == "header" )
                {
                    // Do nothing
                    $headerSubtag = true;
                }
                else
                {
                    $firstSpaceEnd = strpos( $tagName, " " );
                    $firstNewlineEnd = strpos( $tagName, "\n" );

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

                    // create the new XML element node
                    unset( $subNode );
                    $subNode = new eZDOMNode();

                    // find attributes
                    if ( $tagNameEnd > 0 )
                    {
                        $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );

                        // attributes
                        unset( $attr );
                        $attr =& eZXML::parseAttributes( $attributePart );

                        if ( $attr != false )
                            $subNode->Attributes =& $attr;
                    }

                    $subNode->Name = $justName;
                    $subNode->LocalName = $justName;
                    $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                    $domDocument->registerElement( &$subNode );
                    $currentNode->appendChild( $subNode );

                    if ( $tagName[strlen($tagName) - 1]  != "/" )
                    {
                        array_push( $TagStack,
                                    array( "TagName" => $justName, "ParentNodeObject" => &$currentNode ) );
                        unset( $currentNode );
                        $currentNode =& $subNode;
                        $lastInsertedTag = $justName;
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
                    $domDocument->registerElement( &$subNode );
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

                    $subNode->Content = $tagContent;

                    $domDocument->registerElement( &$subNode );

                    $currentNode->appendChild( $subNode );
                }
            }
        }
        $output = array( $domDocument, $message );
        return $output;
    }

    /*!
     Returns the input XML representation of the datatype.
    */
    function &inputXML( &$contentObjectAttribute )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );

//        print( htmlspecialchars( $contentObjectAttribute->attribute( "data_text" ) ) );
        if ( $dom )
            $node =& $dom->elementsByName( "section" );

        eZDebug::writeDebug( $contentObjectAttribute->attribute( "data_text" ), "eZXMLTextType::inputXML" );

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
                    $output .= trim( $this->inputParagraphXML( $sectionNode ) ) . "\n\n";
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
    function &inputParagraphXML( &$paragraph )
    {
        $output = "";
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $output .= $this->inputTagXML( $paragraphNode );
        }
        return $output;
    }

    /*!
     \return the input xml for the given tag
    */
    function &inputTagXML( &$tag )
    {
        $output = "";
        $tagName = $tag->name();

        switch ( $tagName )
        {
            case '#text' :
            {
                $output .= $tag->content();
            }break;

            case 'object' :
            {
                $view = $tag->attributeValue( 'view' );
                if ( strlen( $view ) == 0 )
                    $view = "embed";

                $objectID = $tag->attributeValue( 'id' );
                $output .= "<$tagName id='$objectID' view='$view'/>" . $tag->textContent();
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
                            $cellContent .= $this->inputTagXML( $tableCellChildNode );
                        }
                        $tableData .= "  <td>\n" . trim( $cellContent ) . "\n  </td>\n";
                    }
                    $tableRows .= "<tr>\n $tableData</tr>\n";
                }
                $output .= "<table border='$border' width='$width'>\n$tableRows</table>\n";
            }break;

            // normal content tags
            case 'emphasize' :
            case 'strong' :
            {
                $output .= "<$tagName>" . $childTagText . $tag->textContent() . "</$tagName>";
            }break;

            // Custom tags
            case 'custom' :
            {
                $name = $tag->attributeValue( 'name' );

                $output .= "<$tagName name='$name'>" . $childTagText . $tag->textContent() . "</$tagName>";
            }break;

            case 'link' :
            {
                $linkID = $tag->attributeValue( 'id' );
                if ( $linkID != null )
                    $href =& eZURL::url( $linkID );
                else
                    $href = $tag->attributeValue( 'href' );
                $output .= "<$tagName href='$href'>" . $childTagText . $tag->textContent() . "</$tagName>";
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
