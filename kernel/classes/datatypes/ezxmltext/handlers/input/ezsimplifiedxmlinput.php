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

            // $data =& $this->convertInput( $data );
            $data =& $this->convertInput( $data );
            $message = $data[1];
            if ( $message != "" )
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
//                 foreach ( $links as $link )
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
                $contentObjectAttribute->setAttribute( "data_text", $dom->toString() );
//                 print( "<pre>" );
//                 print( htmlspecialchars( $contentObjectAttribute->attribute( "data_text" ) ) . "<br/>" );
//                 print_r( $dom );
//                 print( "</pre>" );
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

        $text =& preg_replace( "#<bold>#", "<strong>", $text );
        $text =& preg_replace( "#</bold>#", "</strong>", $text );

        $text =& preg_replace( "#<em>#", "<emphasize>", $text );
        $text =& preg_replace( "#</em>#", "</emphasize>", $text );

        $text =& preg_replace( "#<td><paragraph>#", "<tdtag><paragraph>", $text );
        $text =& preg_replace( "#</paragraph></td>#", "</paragraph></tdtag>", $text );
        $text =& preg_replace( "#<td>#", "<tdtag><paragraph>", $text );
        $text =& preg_replace( "#</td>#", "</paragraph></tdtag>", $text );

        $text =& preg_replace( "#<tdtag>#", "<td>", $text );
        $text =& preg_replace( "#</tdtag>#", "</td>", $text );

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
            if ( preg_match( "#(<header.*?level=[\"|'](.*?)[\"|'].*?>.*?</header>)#", $sectionPart, $matchArray ) )
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

                $overrideContent = false;
                if ( $tagName == 'literal' )
                {
                    // If pre tag, find the end tag and create override contents
                    $preEndTagPos = strpos( $data, "</literal>", $pos );
                    $overrideContent = substr( $data, $pos + 5, $preEndTagPos - ( $pos + 5 ) );
                    $pos = $preEndTagPos -1;
                }

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
                        /* elseif ( $tagName == "paragraph" and $lastTag == "td" )
                        {
                            array_push( $TagStack,
                                        array( "TagName" => $lastTag, "ParentNodeObject" => &$lastNode ) );
                        }
                        elseif ( $lastInsertedNodeTag == "paragraph"  and  $tagName == "td" )
                        {
                            unset( $currentNode );
                            $currentNode =& $lastNode;
                            $lastNodeArray = array_pop( $TagStack );
                            $lastTag = $lastNodeArray["TagName"];
                            $lastNode =& $lastNodeArray["ParentNodeObject"];
                            unset( $currentNode );
                            $currentNode =& $lastNode;
                        }*/
                        elseif ( ( $lastTag  == "header" ) and $headerSubtag )
                        {
                            array_push( $TagStack,
                                        array( "TagName" => $lastTag, "ParentNodeObject" => &$lastNode ) );
                        }
                        else
                        {
                            $message =  "Unmatched tag - If the unmatched tag is not " . $lastTag . ", ";
                            $message .=  "then it must be " . $tagName;
                            $output = array( null, $message );
                            return $output;
                        }
                    }
                    else
                    {
                        unset( $currentNode );
                        $currentNode =& $lastNode;
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

                    $domDocument->registerElement( $subNode );
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
    function &inputTdXML( &$tdNode )
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
                $output .= "<paragraph>" . trim( $this->inputParagraphXML( $tdNode ) ) . "</paragraph>";
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
                            $cellContent .= $this->inputTdXML( $tableCellChildNode );
                        }
                        $tableData .= "  <td>" . trim( $cellContent ) . "</td>";
                    }
                    $tableRows .= "<tr>\n $tableData</tr>\n";
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

                $output .= "<$tagName name='$name' ff>" . $childTagText . "</$tagName>";
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
