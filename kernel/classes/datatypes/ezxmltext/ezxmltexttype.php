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
//! The class eZXMLTextType haneles XML formatted datatypes
/*!
The formatted datatypes store the data in XML. A typical example of this is shown below:
\code
<?xml version="1.0" encoding="utf-8" ?>
<section>
<header>This is a level one header</header>
<paragraph>
This is a <emphasize>block</emphasize> of text.
</paragraph>
  <section>
  <header>This is a level two header</header>
  <paragraph>
  This is the second paragraph.
  </paragraph>
  </section>
</section>

\endcode

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

            // \todo add better validation of XML

            $xml = new eZXML();
            $dom =& $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );

            if ( $dom )
            {
                $contentObjectAttribute->setAttribute( "data_text", $data );
            }

            eZDebug::writeNotice( $data, "XML text" );
        }
    }

    /*!
     \private
    */
    function &convertInput( &$text )
    {
        // get paragraphs
        $data =& preg_replace( "#\n[\n]+#", "\n\n", $text );


        // split on headers
        $sectionData = "<section>";
        $sectionArray =& preg_split( "#(<header.*?>.*?</header>)#", $data, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
        $sectionLevel = 1;
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
                }

                if ( $newSectionLevel < $sectionLevel )
                {
                    $sectionData .= "\n</section>\n";
                }

                $sectionLevel = $newSectionLevel;
                $sectionData .= $sectionPart;
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
        if ( $sectionLevel > 1 )
        {
            $sectionData .= str_repeat( "\n</section>\n", $sectionLevel - 1 );
        }
        $sectionData .= "</section>";

        print( nl2br( htmlspecialchars( $sectionData ) ). "<br>" );

        $data  = '<?xml version="1.0" encoding="utf-8" ?>' . $sectionData;

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
        $dom =& $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );
        if ( $dom )
        {
            $node =& $dom->elementsByName( "section" );

            $sectionNode =& $node[0];
            $output = "";
            if ( get_class( $sectionNode ) == "ezdomnode" )
            {
                $output =& $this->renderXHTMLSection( $tpl, $sectionNode );
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
            $output .= $this->inputSectionXML( $node[0] );
        }
        return $output;
    }

    /*!
     \private
     \return the XHTML rendered version of the section
    */
    function &renderXHTMLSection( &$tpl, &$section )
    {
        $output = "";
        foreach ( $section->children() as $sectionNode )
        {
            $tagName = $sectionNode->name();
            switch ( $tagName )
            {
                // tags with parameters
                case 'header' :
                {
                    $level = $sectionNode->attributeValue( 'level' );

                    $tpl->setVariable( 'content', $sectionNode->textContent(), 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/header.tpl";
                    eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
                    $output .= $text;
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderXHTMLParagraph( $tpl, $sectionNode );
                }break;

                case 'section' :
                {
                    $output .= $this->renderXHTMLSection( $tpl, $sectionNode );
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
     \private
     \return XHTML rendered version of the paragrph
    */
    function &renderXHTMLParagraph( &$tpl, $paragraph )
    {
        $output = "";
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $output .= $this->renderXHTMLTag( $tpl, $paragraphNode );
        }

        $tpl->setVariable( 'content', $output, 'xmltagns' );
        $uri = "design:content/datatype/view/ezxmltags/paragraph.tpl";
        eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
        $output =& $text;
        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderXHTMLTag( &$tpl, &$tag )
    {
        $tagText = "";
        $childTagText = "";
        $tagName = $tag->name();
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            $childTagText .= $this->renderXHTMLTag( $tpl, $childTag );
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
                $view = $tag->attributeValue( 'view' );
                if ( strlen( $view ) == 0 )
                    $view = "embed";

                $object =& eZContentObject::fetch( $objectID );
                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $tpl->setVariable( 'view', $view, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";
                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                $tagText .= $text;
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
                        $listItemContent =& $this->inputTagXML( $itemChildNode );
                    }
                    $tpl->setVariable( 'content', $listItemContent, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/li.tpl";

                    eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );

                    $listContent .= $text;
                }

                $tpl->setVariable( 'content', $listContent, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= $text;

//                $output .= "<$tagName>\n$listContent</$tagName>";
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

            default :
            {
                // unsupported tag
            }break;
        }
        return $tagText;
    }

    /*!
     \private
     \return the user input format for the given section
    */
    function &inputSectionXML( &$section )
    {
        $output = "";
        foreach ( $section->children() as $sectionNode )
        {
            $tagName = $sectionNode->name();
            switch ( $tagName )
            {
                case 'header' :
                {
                    $level = $sectionNode->attributeValue( 'level' );
                    if ( is_numeric( $level ) )
                        $level = $sectionNode->attributeValue( 'level' );
                    else
                        $level = 1;
                    $output .= "<$tagName level='$level'>" . $sectionNode->textContent(). "</$tagName>\n";
                }break;

                case 'paragraph' :
                {
                    $output .= trim( $this->inputParagraphXML( $sectionNode ) ) . "\n\n";
                }break;

                case 'section' :
                {
                    $output .= $this->inputSectionXML( $sectionNode );
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
                        $listItemContent =& $this->inputTagXML( $itemChildNode );
                    }
                    $listContent .= "  <li>$listItemContent</li>\n";
                }
                $output .= "<$tagName>\n$listContent</$tagName>";
            }break;

            // normal content tags
            case 'empahsize' :
            case 'strong' :
            case 'bold' :
            case 'italic' :
            {
                $output .= "<$tagName>" . $childTagText . $tag->textContent() . "</$tagName>";
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag: $tagName", "eZXMLTextType::inputParagraphXML()" );
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
