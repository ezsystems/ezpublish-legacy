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

            // $data =& $this->convertInput( $data );
            $data =& eZXMLTextType::convertInput( $data );
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
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
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
                    }
                }
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
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
                $view = $tag->attributeValue( 'view' );
                $src = $tag->attributeValue( 'src' );
                $alignment = $tag->attributeValue( 'align' );
                if ( strlen( $view ) == 0 )
                    $view = "embed";
                if ( strlen( $alignment ) == 0 )
                    $alignment = "center";

                $objectID = $tag->attributeValue( 'id' );
                // $output .= "<$tagName id='$objectID' view='$view'/>" . $tag->textContent();
                $output .= "<object id=\"$objectID\" src=\"$src\" align=\"$alignment\" />";
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
            case 'strong' :
            {
                $output .= "<$tagName>" . $childTagText . "</$tagName>";
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
                if ( $linkID != null )
                    $href =& eZURL::url( $linkID );
                else
                {
                    $href = $tag->attributeValue( 'href' );
                    $target = $tag->attributeValue( 'target' );
                    if ( strlen( $target ) == 0 )
                        $target = "self_";
                }
                $output .= "<$tagName href='$href' target='$target'>" . $childTagText . "</$tagName>";
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
