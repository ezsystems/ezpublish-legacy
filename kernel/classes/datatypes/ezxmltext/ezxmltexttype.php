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

\endcode

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "kernel/common/template.php" );
include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputtype.php' );

define( "EZ_DATATYPESTRING_XML_TEXT", "ezxmltext" );
define( 'EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_XML_TEXT_COLS_VARIABLE', '_ezxmltext_cols_' );

class eZXMLTextType extends eZDataType
{
    function eZXMLTextType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_XML_TEXT, "XML Text field" );
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD ) == null )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD, 10 );
        $classAttribute->store();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion )
    {
         $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
         if ( $contentClassAttribute->attribute( "data_int1" ) == 0 )
         {
              $contentClassAttribute->setAttribute( "data_int1", 10 );
              $contentClassAttribute->store();
         }
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $inputType =& eZXMLInputType::instance();
        $isValid = $inputType->validateInput( $http, $base, $contentObjectAttribute );

        return $isValid;
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $column = $base . EZ_DATATYPESTRING_XML_TEXT_COLS_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $column ) )
        {
            $columnValue = $http->postVariable( $column );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_XML_TEXT_COLS_FIELD,  $columnValue );
        }
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
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
                $output =& $this->renderXHTMLSection( $tpl, $sectionNode, 1 );
            }
        }
        return $output;
    }

    /*!
     Returns name of the editor which will be used..
    */
    function &xmlEditor( &$contentObjectAttribute )
    {
        $editorName =& eZXMLInputType::editorName();
        return $editorName;
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
        // TMP hack
        if ( !$contentObjectAttribute->isValid() == 1 )
        {
            $inputType =& eZXMLInputType::instance();
            $output =& $inputType->inputXML( $contentObjectAttribute );
        }
        else
        {
            eZDebug::writeDebug("called else");
            $output =& $contentObjectAttribute->originalInput();
        }
        if ( trim( $output ) == "" )
            $output = " ";
        return $output;
    }

    /*!
     \private
     \return the XHTML rendered version of the section
    */
    function &renderXHTMLSection( &$tpl, &$section, $sectionLevel )
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
                    // $level = $sectionNode->attributeValue( 'level' );
                    $level = $sectionLevel;
                    $tpl->setVariable( 'content', $sectionNode->textContent(), 'xmltagns' );
                    $tpl->setVariable( 'level', $level, 'xmltagns' );
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
                    $sectionLevel += 1;
                    $output .= $this->renderXHTMLSection( $tpl, $sectionNode, $sectionLevel );
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
                $alignment = $tag->attributeValue( 'align' );
                if ( strlen( $view ) == 0 )
                    $view = "embed";
                $object =& eZContentObject::fetch( $objectID );

                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $tpl->setVariable( 'view', $view, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";
                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                $tagText .= $text;
            }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                if ($border === null )
                    $border = 1;
                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = "";
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = "";
                        foreach ( $tableCell->children() as $tableCellChildNode )
                        {
                            $cellContent .= $this->renderXHTMLTag( $tpl, $tableCellChildNode );
                        }
                        $tpl->setVariable( 'content', $cellContent, 'xmltagns' );
                        $uri = "design:content/datatype/view/ezxmltags/td.tpl";
                        eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                        $tableData .= $text;
                    }
                    $tpl->setVariable( 'content', $tableData, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/tr.tpl";
                    eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, "foo", "xmltagns" );
                    $tableRows .= $text;
                }
                $tpl->setVariable( 'rows', $tableRows, 'xmltagns' );
                $tpl->setVariable( 'border', $border, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/table.tpl";
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
                        $listItemContent .= $this->renderXHTMLTag( $tpl, $itemChildNode );
                    }
                    $tpl->setVariable( 'content', $listItemContent, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/li.tpl";

                    eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );

                    $listContent .= $text;
                    eZDebug::writeDebug("wwwwwww");
                }

                $tpl->setVariable( 'content', $listContent, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= $text;
            }break;

            // normal content tags
            case 'emphasize' :
            case 'strong' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $text, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= $text;
            }break;

            case 'link' :
            {
                include_once( 'lib/ezutils/classes/ezmail.php' );
                $href = $tag->attributeValue( 'href' );

                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );

                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;

                $tpl->setVariable( 'href', $href, 'xmltagns' );

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
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $metaData = "";
        $doc =& $contentObjectAttribute->attribute( "data_text" );

        $xml = new eZXML();
        $dom =& $xml->domTree( $contentObjectAttribute->attribute( "data_text" ) );

        $textNodes =& $dom->elementsByName( "#text" );
        foreach ( $textNodes as $node )
        {
            $metaData .= " " . $node->content();
        }
        return $metaData;
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
