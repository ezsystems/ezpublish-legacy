<?php
//
// Definition of eZXMLTextType class
//
// Created on: <06-May-2002 20:02:55 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
                $output =& $this->renderXHTMLSection( $tpl, $sectionNode, 0 );
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
        /*    if ( !$contentObjectAttribute->isValid() == 1 )
        {
            $inputType =& eZXMLInputType::instance();
            $output =& $inputType->inputXML( $contentObjectAttribute );
        }
        else
        {
            eZDebug::writeDebug("called else");
            $output =& $contentObjectAttribute->originalInput();
        }*/
        $inputType =& eZXMLInputType::instance();
        $output =& $inputType->inputXML( $contentObjectAttribute );
        if ( trim( $output ) == "" )
            $output = " ";
        return $output;
    }

    /*!
     \private
     \return the XHTML rendered version of the section
    */
    function &renderXHTMLSection( &$tpl, &$section, $currentSectionLevel )
    {
        $output = "";
         eZDebug::writeDebug("level ". $section->toString( 0 ) );
        foreach ( $section->children() as $sectionNode )
        {
            $sectionLevel = $currentSectionLevel;
            $tagName = $sectionNode->name();
            switch ( $tagName )
            {
                // tags with parameters
                case 'header' :
                {
                    $level = $sectionLevel;
                    $tpl->setVariable( 'content', $sectionNode->textContent(), 'xmltagns' );
                    $tpl->setVariable( 'level', $level, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/header.tpl";
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $output .= implode( '', $textElements );
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderXHTMLParagraph( $tpl, $sectionNode );
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    eZDebug::writeDebug("level ". $sectionLevel );
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
     \return the XHTML rendered version of the section
    */
    function &renderXHTMLTdTag( &$tpl, &$td, $currentSectionLevel )
    {
        $output = "";
        foreach ( $td->children() as $tdNode )
        {
            $sectionLevel = $currentSectionLevel;
            $tagName = $tdNode->name();
            switch ( $tagName )
            {
                // tags with parameters
                case 'header' :
                {
                    $level = $sectionLevel;
                    $tpl->setVariable( 'content', $tdNode->textContent(), 'xmltagns' );
                    $tpl->setVariable( 'level', $level, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/header.tpl";
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $output .= implode( '', $textElements );
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderXHTMLTdParagraph( $tpl, $tdNode, $sectionLevel  );
                }break;

                case 'section' :
                {
                    $output .= $this->renderXHTMLTdTag( $tpl, $tdNode, $sectionLevel );
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::renderXHTMLTdTag()" );
                }break;
            }
        }
        return $output;
    }

     /*!
     \private
     \return XHTML rendered version of the paragrph
    */
    function &renderXHTMLTdParagraph( &$tpl, $paragraph, $currentSectionLevel )
    {
        $output = "";
        foreach ( $paragraph->children() as $paragraphNode )
        {

            $sectionLevel = $currentSectionLevel;
            $tagName = $paragraphNode->name();
            switch ( $tagName )
            {
                // tags with parameters
                case 'header' :
                {
                    $level = $sectionLevel;
                    $tpl->setVariable( 'content', $paragraphNode->textContent(), 'xmltagns' );
                    $tpl->setVariable( 'level', $level, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/header.tpl";
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $output .= implode( '', $textElements );
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderXHTMLTdParagraph( $tpl, $paragraphNode, $sectionLevel  );
                }break;

                case 'section' :
                {
                    $output .= $this->renderXHTMLTdTag( $tpl, $paragraphNode, $sectionLevel );
                }break;

                default :
                {
                    $output .= $this->renderXHTMLTag( $tpl, $paragraphNode, $currentSectionLevel  );
                }break;
            }
        }

        $tpl->setVariable( 'content', $output, 'xmltagns' );
        $uri = "design:content/datatype/view/ezxmltags/paragraph.tpl";
        $textElements = array();
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
        $output = implode( '', $textElements );
        return $output;
    }

    /*!
     \private
     \return XHTML rendered version of the paragrph
    */
    function &renderXHTMLParagraph( &$tpl, $paragraph, $currentSectionLevel )
    {
        $output = "";
        $sectionLevel = $currentSectionLevel;
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $output .= $this->renderXHTMLTag( $tpl, $paragraphNode, $sectionLevel );
        }

        $tpl->setVariable( 'content', $output, 'xmltagns' );
        $uri = "design:content/datatype/view/ezxmltags/paragraph.tpl";
        $textElements = array();
        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
        $output = implode( '', $textElements );
        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderXHTMLTag( &$tpl, &$tag, $currentSectionLevel )
    {
        $tagText = "";
        $childTagText = "";
        $tagName = $tag->name();
        // render children tags
        $tagChildren = $tag->children();
        $sectionLevel = $currentSectionLevel + 1;
        foreach ( $tagChildren as $childTag )
        {
            $childTagText .= $this->renderXHTMLTag( $tpl, $childTag, $currentSectionLevel );
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $tagText .= $tag->content();
            }break;

            case 'object' :
            {
                $objectID = $tag->attributeValue( 'id' );
                $view = $tag->attributeValue( 'view' );
                $alignment = $tag->attributeValue( 'align' );
                //$size = $tag->attributeValue( 'size' );
                $src = $tag->attributeValue( 'src' );
                $parameters = array();
                $item = array( "alignment" => $alignment ,
                               "src" => $src );
                $parameters[] = $item;
                if ( strlen( $view ) == 0 )
                    $view = "embed";
                $object =& eZContentObject::fetch( $objectID );

                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $tpl->setVariable( 'view', $view, 'xmltagns' );
                $tpl->setVariable( 'parameters', $parameters, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText = implode( '', $textElements );
            }break;

            case 'table' :
            {
                $tableRows = "";
                $border = $tag->attributeValue( 'border' );
                if ( $border === null )
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
                            $cellContent .= $this->renderXHTMLTdParagraph( $tpl, $tableCellChildNode, $sectionLevel );
                        }
                        $tpl->setVariable( 'content', $cellContent, 'xmltagns' );
                        if ( $tableCell->Name == "th" )
                            $uri = "design:content/datatype/view/ezxmltags/th.tpl";
                        else
                            $uri = "design:content/datatype/view/ezxmltags/td.tpl";
                        $textElements = array();
                        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                        $tableData .= implode( '', $textElements );
                    }
                    $tpl->setVariable( 'content', $tableData, 'xmltagns' );
                    $uri = "design:content/datatype/view/ezxmltags/tr.tpl";
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                    $tableRows .= implode( '', $textElements );
                }

                $tpl->setVariable( 'rows', $tableRows, 'xmltagns' );
                $tpl->setVariable( 'border', $border, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/table.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText .= implode( '', $textElements );
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

                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $listContent .= implode( '', $textElements );
                }

                $tpl->setVariable( 'content', $listContent, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            // Literal text which allows xml specific caracters < >
            case 'literal' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                eZDebug::writeDebug("ooo" .$childTagText);
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            // normal content tags
            case 'emphasize' :
            case 'strong' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            case 'custom' :
            {
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );

                // Get the name of the custom tag.
                $name = $tag->attributeValue( 'name' );

                $uri = "design:content/datatype/view/ezxmltags/$name.tpl";

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            case 'link' :
            {
                include_once( 'lib/ezutils/classes/ezmail.php' );
                $linkID = $tag->attributeValue( 'id' );
                $target = $tag->attributeValue( 'target' );
                if ( $linkID != null )
                    $href =& eZURL::url( $linkID );
                else
                    $href = $tag->attributeValue( 'href' );

                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );

                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;

                $tpl->setVariable( 'href', $href, 'xmltagns' );
                $tpl->setVariable( 'target', $target, 'xmltagns' );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
            }break;

            case 'anchor' :
            {
                $name = $tag->attributeValue( 'name' );

                $tpl->setVariable( 'name', $name, 'xmltagns' );

                $uri = "design:content/datatype/view/ezxmltags/$tagName.tpl";

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= implode( '', $textElements );
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

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( $objectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );

        $node =& eZDataType::contentObjectAttributeDOMNode( $objectAttribute );
//         $node = new eZDOMNode();
//         $node->setName( 'attribute' );
//         $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
//         $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', 'ezxmltext' ) );
        include_once( 'lib/ezxml/classes/ezxml.php' );
        $xml = new eZXML();
        $dom =& $xml->domTree( $objectAttribute->attribute( "data_text" ) );

//         $node->appendChild( eZDOMDocument::createTextNode( $objectAttribute->attribute( 'data_text' ) ) );
        $contentNode = new eZDOMNode();
        $contentNode->setPrefix( 'ezobject' );
        $contentNode->setName( 'content' );
        $contentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $contentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'type', 'ezxmltext' ) );

        $contentNode->appendChild( $dom->root() );
        $node->appendChild( $contentNode );

        return $node;
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, &$contentObjectAttribute )
    {
        switch ( $action )
        {
            case "enable_editor" :
            {
                $http =& eZHTTPTool::instance();
                $http->removeSessionVariable( 'DisableEditorExtension' );
            }break;
            case "disable_editor" :
            {
                $http =& eZHTTPTool::instance();
                $http->setSessionVariable( 'DisableEditorExtension', true );
            }break;
            default :
            {
                eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZOptionType" );
            }break;
        }
    }
}

eZDataType::register( EZ_DATATYPESTRING_XML_TEXT, "ezXMLTextType" );

?>
