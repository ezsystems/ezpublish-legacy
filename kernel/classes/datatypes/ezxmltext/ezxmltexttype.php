<?php
//
// Definition of eZXMLTextType class
//
// Created on: <06-May-2002 20:02:55 bf>
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

//!! eZKernel
//! The class eZXMLTextType does
/*!

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "kernel/common/template.php" );

define( "EZ_DATATYPESTRING_XML_TEXT", "ezxmltext" );

class eZXMLTextType extends eZDataType
{
    function eZXMLTextType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_XML_TEXT, "XML Text field" );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data =& $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );

            $data =& $this->convertInput( $data );

            $contentObjectAttribute->setAttribute( "data_text", $data );
        }
    }

    /*!
     \private
    */
    function &convertInput( &$text )
    {
        // get paragraphs
        $data =& preg_replace( "#\n[\n]+#", "\n\n", $text );
        $paragraphArray = explode( "\n\n", $data );

        $paragraphData = '';
        foreach ( $paragraphArray as $paragraph )
        {
            $paragraphData .= '<paragraph>' . trim( $paragraph ) . '</paragraph>';
        }

        // convert to XML
        $data  = '<?xml version="1.0" encoding="utf-8" ?><section>' . $paragraphData . '</section>';

        return $data;
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( &$attribute )
    {
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $tpl =& templateInit();

        $xml = new eZXML();
        $dom = $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );
        if ( $dom )
        {
            $node = $dom->elementsByName( "section" );

            $output = "";
            $children =& $node[0]->children();
            foreach ( $children as $child )
            {
                if ( $child->name() == "paragraph" )
                {
                    $childContent = '';
                    $formattingTags =& $child->children();
                    foreach ( $formattingTags as $tag )
                    {
                        // normal text tag
                        if ( $tag->name() == "#text" )
                        {
                            $tpl->setVariable( "content", $tag->content(), "xmltagns" );
                            $uri = "design:content/datatype/view/ezxmltags/text.tpl";
                            eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                            $childContent .= $text;
                        }

                        $childContent .= $this->renderTag( $tpl, $tag );
                    }
                    $tpl->setVariable( "content", &$childContent, "xmltagns" );
                    $uri = "design:content/datatype/view/ezxmltags/paragraph.tpl";
                    eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                    $output .= $text;
                }
            }
        }
        return $output;
    }

    /*!
     Returns the XML representation of the datatype.
    */
    function &xml( &$contentObjectAttribute )
    {
        $xml =& $contentObjectAttribute->attribute( "data_text" );
        return $xml;
    }

    /*!
     Returns the input XML representation of the datatype.
    */
    function &inputXML( &$contentObjectAttribute )
    {
        $xml = new eZXML();
        $dom = $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );

        if ( $dom )
            $node = $dom->elementsByName( "section" );

        if ( count( $node ) > 0 )
        {
            $output = "";
            $children =& $node[0]->children();
            foreach ( $children as $child )
            {
                if ( $child->name() == "paragraph" )
                {
                    $formattingTags =& $child->children();
                    foreach ( $formattingTags as $tag )
                    {
                        if ( $tag->name() == "#text" )
                        {
                            $output .= $tag->content();
                        }

                        $output .= $this->inputTagXML( $tag );
                    }
                    $output .= "\n\n";
                }
            }
        }
        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderTag( &$tpl, &$tag )
    {
        $tagText = "";
        $childTagText = "";
        $tagName = $tag->name();
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            $childTagText .= $this->renderTag( $tpl, $childTag );
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $tagText .= $tag->content();
            }break;

            // one liner tags
            case 'br' :
            {

            }break;

            case 'object' :
            {
                $objectID = $tag->attributeValue( 'id' );
                $object =& eZContentObject::fetch( $objectID );
                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";
                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                $tagText .= $text;
            }break;

            // normal content tags
            case 'empahsize' :
            case 'strong' :
            case 'bold' :
            case 'italic' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= $text;
            }break;

            // tags with parameters
            case 'header' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";
                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= $text;
            }break;

            default :
            {
                // unsupported tag
            }break;
        }
        return $tagText;
    }

    /*!
     \private
     \return the user input format for this tag
    */
    function &inputTagXML( &$tag )
    {
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            $childTagText .= $this->inputTagXML( $childTag );
        }

        $output = "";
        $tagName = $tag->name();
        switch ( $tagName )
        {
            // one liner tags
            case 'br' :
            {
                $output .= "<br/>";
            }break;

            case 'object' :
            {
                $objectID = $tag->attributeValue( 'id' );
                $output .= "<$tagName id='$objectID'/>" . $tag->textContent();
            }break;

            // normal content tags
            case 'empahsize' :
            case 'strong' :
            case 'bold' :
            case 'italic' :
            case 'header' :
            {
                $output .= "<$tagName>" . $childTagText . $tag->textContent() . "</$tagName>";
            }break;

            default :
            {
                // unsupported tag
            }break;
        }
        return $output;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Returns the text.
    */
    function title( &$data_instance )
    {
        return $data_instance->attribute( "data_text" );
    }
}

eZDataType::register( EZ_DATATYPESTRING_XML_TEXT, "ezXMLTextType" );

?>
