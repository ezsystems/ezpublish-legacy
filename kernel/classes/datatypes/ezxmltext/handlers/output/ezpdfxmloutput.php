<?php
//
// Definition of eZPDFXMLOutput class
//
// Created on: <27-Oct-2003 11:05:00 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmloutputhandler.php' );

class eZPDFXMLOutput extends eZXMLOutputHandler
{
    function eZPDFXMLOutput( &$xmlData, $aliasedType )
    {
        $this->eZXMLOutputHandler( $xmlData, $aliasedType );
    }

    /*!
     \reimp
    */
    function &outputText()
    {
        return $this->pdf();
    }

    /*!
     \return the PDF rendered value of the XML data
    */
    function &pdf()
    {
        $tpl =& templateInit();
        $xml = new eZXML();
        $dom =& $xml->domTree( $this->XMLData );
        if ( $dom )
        {
            $node =& $dom->elementsByName( 'section' );

            // Fetch all links and cache the url's
            $links =& $dom->elementsByName( 'link' );

            if ( count( $links ) > 0 )
            {
                $linkIDArray = array();
                // Find all Link id's
                foreach ( $links as $link )
                {
                    if ( !in_array( $link->attributeValue( 'id' ), $linkIDArray ) )
                        $linkIDArray[] = $link->attributeValue( 'id' );
                }

                $inIDSQL = implode( ', ', $linkIDArray );

                $db =& eZDB::instance();

                $linkArray = $db->arrayQuery( 'SELECT * FROM ezurl WHERE id IN ( '. $inIDSQL .' ) ' );

                foreach ( $linkArray as $linkRow )
                {
                    $this->LinkArray[(string)$linkRow['id']] = $linkRow['url'];
                }
            }

            // Fetch all embeded objects and cache by ID
            $objectArray =& $dom->elementsByName( 'object' );

            if ( count( $objectArray ) > 0 )
            {
                $relatedObjectIDArray = array();
                foreach ( $objectArray as $object )
                {
                    $objectID = $object->attributeValue( 'id' );
                    $relatedObjectIDArray[] = $objectID;
                }
                $this->ObjectArray =& eZContentObject::fetchIDArray( $relatedObjectIDArray );

            }

            $sectionNode =& $node[0];
            $output = '';
            if ( get_class( $sectionNode ) == 'ezdomnode' )
            {
                $output =& $this->renderPDFSection( $tpl, $sectionNode, 0 );
            }
        }

        return $output;
    }

    function &renderPDFSection( &$tpl, &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = '';
        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmlpdf', 'level ' . $section->toString( 0 ) );

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
                // tags with parameters
                case 'header' :
                {
                    // Add the anchor tag before the header.
                    $name = $sectionNode->attributeValue( 'anchor_name' );
                    $class = $sectionNode->attributeValue( 'class' );

                    $res =& eZTemplateDesignResource::instance();
                    $res->setKeys( array( array( 'classification', $class ) ) );

                    if ( $name )
                    {
                        $tpl->setVariable( 'name', $name, 'xmltagns' );

                        $uri = 'design:content/datatype/pdf/ezxmltags/anchor.tpl';

                        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                        $output .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                    }

                    $level = $sectionLevel;
                    $tpl->setVariable( 'content', $sectionNode->textContent(), 'xmltagns' );
                    $tpl->setVariable( 'level', $level, 'xmltagns' );
                    $tpl->setVariable( 'classification', $class, 'xmltagns' );
                    $uri = 'design:content/datatype/pdf/ezxmltags/header.tpl';
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $output .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );

                    // Remove the design key, so it will not override other tags
                    $res->removeKey( 'classification' );
                }break;

                case 'paragraph' :
                {
                    $output .= $this->renderPDFParagraph( $tpl, $sectionNode, $currentSectionLevel, $tdSectionLevel );
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    eZDebugSetting::writeDebug( 'kernel-datatype-ezxmlpdf', 'level '. $sectionLevel );
                    if ( $tdSectionLevel == null )
                        $output .= $this->renderPDFSection( $tpl, $sectionNode, $sectionLevel );
                    else
                        $output .= $this->renderPDFSection( $tpl, $sectionNode, $currentSectionLevel, $sectionLevel );
                }break;

                default :
                {
                    eZDebug::writeError( 'Unsupported tag at this level: $tagName', 'eZXMLTextType::inputSectionXML()' );
                }break;
            }
        }
        return $output;
    }

    /*!
     \private
     \return PDF rendered version of the paragrph
    */
    function &renderPDFParagraph( &$tpl, $paragraph, $currentSectionLevel, $tdSectionLevel = null )
    {
        $insideParagraph = true;
        $paragraphCount = 0;
        $paragraphContentArray = array();

        $sectionLevel = $currentSectionLevel;
        foreach ( $paragraph->children() as $paragraphNode )
        {
            $isBlockTag = false;
            $content =& $this->renderPDFTag( $tpl, $paragraphNode, $sectionLevel, $isBlockTag, $tdSectionLevel );
            if ( $isBlockTag === true )
            {
                $paragraphCount++;
            }

            if ( !isset( $paragraphContentArray[$paragraphCount]['Content'] ) )
                $paragraphContentArray[$paragraphCount] = array( 'Content' => $content, 'IsBlock' => $isBlockTag );
            else
                $paragraphContentArray[$paragraphCount] = array( 'Content' => $paragraphContentArray[$paragraphCount]['Content'] . $content, 'IsBlock' => $isBlockTag );
            if ( $isBlockTag === true )
            {
                $paragraphCount++;
            }
        }
        $output = '';
        foreach ( $paragraphContentArray as $paragraphContent )
        {
            if ( !$paragraphContent['IsBlock'] )
            {
                $tpl->setVariable( 'content', $paragraphContent['Content'], 'xmltagns' );
                $uri = 'design:content/datatype/pdf/ezxmltags/paragraph.tpl';
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $output .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
            }
            else
            {
                $output .= $paragraphContent['Content'];
            }

        }
//        if ( $paragraph->children() == null )
//            $output = "\n\n";
        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderPDFTag( &$tpl, &$tag, $currentSectionLevel, &$isBlockTag, $tdSectionLevel = null )
    {
        $tagText = '';
        $childTagText = '';
        $tagName = $tag->name();
        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            if ( $tag->name() == 'literal' )
                $childTagText .= trim( $childTag->content() );
            else
                $childTagText .= $this->renderPDFTag( $tpl, $childTag, $currentSectionLevel, $isBlockTag, $tdSectionLevel );
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $tagText .= htmlspecialchars( $tag->content() );
                // Get rid of linebreak and spaces stored in xml file
                $tagText = preg_replace( "#[\n]+#", '', $tagText );
                $tagText = preg_replace( "#    #", '', $tagText );
            }break;

            case 'object' :
            {
                $isBlockTag = true;
                $objectID = $tag->attributeValue( 'id' );
                // fetch attributes
                $objectAttributes =& $tag->attributes();
                $object =& $this->ObjectArray[(string)$objectID];
                // Fetch from cache
                if ( get_class( $object ) == 'ezcontentobject' )
                {
                    $view = $tag->attributeValue( 'view' );
                    $alignment = $tag->attributeValue( 'align' );
                    $size = $tag->attributeValue( 'size' );
                    $src = '';
                    $classID = $object->attribute( 'contentclass_id' );
                    $class = $tag->attributeValue( 'class' );

                    $res =& eZTemplateDesignResource::instance();
                    $res->setKeys( array( array( 'classification', $class ) ) );

                    $hasLink = false;
                    $linkID = $tag->attributeValueNS( 'ezurl_id', 'http://ez.no/namespaces/ezpublish3/image/' );

                    if ( $linkID != null )
                    {
                        $href =& eZURL::url( $linkID );
                        $target = $tag->attributeValueNS( 'ezurl_target', 'http://ez.no/namespaces/ezpublish3/image/' );
                        if ( $target == null )
                            $target = '_self';
                        $hasLink = true;
                    }

                    $objectParameters = array();
                    foreach ( $objectAttributes as $attribute )
                    {
                        if ( $attribute->name() == 'ezurl_id' )
                            $objectParameters['href'] = $href;
                        else if ( $attribute->name() == 'ezurl_target' )
                            $objectParameters['target'] = $target;
                        else
                            $objectParameters[$attribute->name()] = $attribute->content();
                    }

                    if ( strlen( $view ) == 0 )
                        $view = 'embed';
                    $tpl->setVariable( 'classification', $class, 'xmltagns' );
                    $tpl->setVariable( 'object', $object, 'xmltagns' );
                    $tpl->setVariable( 'view', $view, 'xmltagns' );
                    $tpl->setVariable( 'object_parameters', $objectParameters, 'xmltagns' );
                    $uri = 'design:content/datatype/pdf/ezxmltags/'. $tagName .'.tpl';
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $tagText = str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );

                    // Set to true if tag breaks paragraph flow as default
                    $isBlockTag = true;

                    // Check if the template overrides the block flow setting
                    if ( $tpl->hasVariable( 'is_block', 'xmltagns:ContentView' ) )
                    {
                        $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns' );

                        if ( $isBlockTagOverride == 'true' )
                            $isBlockTag = true;
                        else
                            $isBlockTag = false;
                    }

                    // Remove the design key, so it will not override other tags
                    $res->removeKey( 'classification' );
                }
            }break;

            case 'table' :
            {
                $tableRows = '';
                $border = $tag->attributeValue( 'border' );
                if ( $border === null )
                    $border = 1;

                $width = $tag->attributeValue( 'width' );
                if ( $width === null )
                    $width = '100%';

                $rowCount = 0;
                // find all table rows
                foreach ( $tag->children() as $tableRow )
                {
                    $tableData = '';
                    foreach ( $tableRow->children() as $tableCell )
                    {
                        $cellContent = '';
                        $tdSctionLevel = $currentSectionLevel;
                        $cellContent .= $this->renderPDFSection( $tpl, $tableCell, $currentSectionLevel, $tdSctionLevel );

                        $tpl->setVariable( 'content', $cellContent, 'xmltagns' );
                        $cellWidth = $tableCell->attributeValueNS( 'width', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
                        $colspan = $tableCell->attributeValueNS( 'colspan', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
                        $rowspan = $tableCell->attributeValueNS( 'rowspan', 'http://ez.no/namespaces/ezpublish3/xhtml/' );

                        $class = $tableCell->attributeValue( 'class' );

                        $res =& eZTemplateDesignResource::instance();
                        $res->setKeys( array( array( 'classification', $class ) ) );

                        if ( $tableCell->Name == 'th' )
                            $uri = 'design:content/datatype/pdf/ezxmltags/th.tpl';
                        else
                            $uri = 'design:content/datatype/pdf/ezxmltags/td.tpl';
                        $textElements = array();
                        $tpl->setVariable( 'classification', $class, 'xmltagns' );
                        $tpl->setVariable( 'colspan', $colspan, 'xmltagns' );
                        $tpl->setVariable( 'rowspan', $rowspan, 'xmltagns' );
                        $tpl->setVariable( 'width', $cellWidth, 'xmltagns' );
                        eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                        $tableData .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );

                        // Remove the design key, so it will not override other tags
                        $res->removeKey( 'classification' );
                    }
                    $tpl->setVariable( 'content', $tableData, 'xmltagns' );
                    $tpl->setVariable( 'row_count', $rowCount, 'xmltagns' );
                    $uri = 'design:content/datatype/pdf/ezxmltags/tr.tpl';
                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $tableRows .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                    $rowCount++;
                }
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'rows', $tableRows, 'xmltagns' );
                $tpl->setVariable( 'border', $border, 'xmltagns' );
                $tpl->setVariable( 'width', $width, 'xmltagns' );
                $uri = 'design:content/datatype/pdf/ezxmltags/table.tpl';
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                $isBlockTag = true;

                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
            }break;

            case 'ul' :
            case 'ol' :
            {
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $isBlockTag = true;

                $listContent = '';
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = '';
                    foreach ( $listItemNode->children() as $itemChildNode )
                    {
                        $listItemContent .= $this->renderPDFTag( $tpl, $itemChildNode, $currentSectionLevel, $isBlockTag );
                    }
                    $tpl->setVariable( 'content', $listItemContent, 'xmltagns' );
                    $uri = 'design:content/datatype/pdf/ezxmltags/li.tpl';

                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $listContent .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                }

                $className = $tag->attributeValue( 'class' );
                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'content', $listContent, 'xmltagns' );
                $uri = 'design:content/datatype/pdf/ezxmltags/'. $tagName .'.tpl';

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
            }break;

            // Literal text which allows xml specific caracters < >
            case 'literal' :
            {
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $uri = 'design:content/datatype/pdf/ezxmltags/'. $tagName .'.tpl';

                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
            }break;

            // normal content tags
            case 'emphasize' :
            case 'strong' :
            case 'line' :
            {
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                $tpl->setVariable( 'classification', $class, 'xmltagns' );

                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );
                $uri = 'design:content/datatype/pdf/ezxmltags/'. $tagName .'.tpl';

                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                $tagText = trim( $tagText );

                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
            }break;

            // custom tags which could added for special custom needs.
            case 'custom' :
            {
                // Get the name of the custom tag.
                $name = $tag->attributeValue( 'name' );
                $isInline = false;
                include_once( 'lib/ezutils/classes/ezini.php' );
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

                if ( $isInline )
                {
                    $childContent = $childTagText;
                }
                else
                {
                    $childContent = $this->renderPDFSection( $tpl, $tag, $currentSectionLevel, $tdSectionLevel );
                    $isBlockTag = true;
                }

                $customAttributes =& $tag->attributesNS( 'http://ez.no/namespaces/ezpublish3/custom/' );
                foreach ( $customAttributes as $attribute )
                {
                    $tpl->setVariable( $attribute->Name, $attribute->Content, 'xmltagns' );
                }

                $tpl->setVariable( 'content',  $childContent, 'xmltagns' );
                $uri = 'design:content/datatype/pdf/ezxmltags/'. $name .'.tpl';
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
            }
            case 'link' :
            {
                $class = $tag->attributeValue( 'class' );

                $res =& eZTemplateDesignResource::instance();
                $res->setKeys( array( array( 'classification', $class ) ) );

                include_once( 'lib/ezutils/classes/ezmail.php' );
                $linkID = $tag->attributeValue( 'id' );
                $target = $tag->attributeValue( 'target' );
                if ( $target == '_self' )
                    $target = false;
                if ( $linkID != null )
                {
                    $href = $this->LinkArray[(string)$linkID];
                }
                else
                    $href = $tag->attributeValue( 'href' );
                $tpl->setVariable( 'content', $childTagText, 'xmltagns' );

                if ( eZMail::validate( $href ) )
                    $href = 'mailto:' . $href;

                $tpl->setVariable( 'href', $href, 'xmltagns' );
                $tpl->setVariable( 'target', $target, 'xmltagns' );
                $tpl->setVariable( 'classification', $class, 'xmltagns' );

                $uri = 'design:content/datatype/pdf/ezxmltags/'. $tagName .'.tpl';

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );

                // Remove the design key, so it will not override other tags
                $res->removeKey( 'classification' );
            }break;

            case 'anchor' :
            {
                $name = $tag->attributeValue( 'name' );

                $tpl->setVariable( 'name', $name, 'xmltagns' );

                $uri = 'design:content/datatype/pdf/ezxmltags/'. $tagName .'.tpl';

                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
            }break;

            default :
            {
                // unsupported tag
            }break;
        }
        return $tagText;
    }

    /// Contains the URL's for <link> tags hashed by ID
    var $LinkArray = array();

    /// Contains the Objects for the <object> tags hashed by ID
    var $ObjectArray = array();

    /// Whitespace array of white spaces to remove
    var $WhiteSpaceArray = array( ' ', "\r\n", "\n", "\t" );
}

?>
