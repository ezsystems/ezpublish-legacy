<?php
//
// Definition of eZPDFXMLOutput class
//
// Created on: <27-Oct-2003 11:05:00 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
 */

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmloutputhandler.php' );
include_once( 'lib/ezpdf/classes/class.ezpdftable.php' );

class eZPDFXMLOutput extends eZXMLOutputHandler
{
    function eZPDFXMLOutput( &$xmlData, $aliasedType, &$contentObjectAttribute )
    {
        $this->eZXMLOutputHandler( $xmlData, $aliasedType );
        $this->ContentObjectAttribute = $contentObjectAttribute ;
    }

    /*!
     \reimp
    */
    function &outputText()
    {
        $retValue =& $this->pdf();
        return $retValue;
    }

    /*!
     \return the PDF rendered value of the XML data
    */
    function &pdf()
    {
        $output = "";
        $tpl =& templateInit();
        $xml = new eZXML();
        $res =& eZTemplateDesignResource::instance();
        if ( $this->ContentObjectAttribute )
        {
            $res->setKeys( array( array( 'attribute_identifier', $this->ContentObjectAttribute->attribute( 'contentclass_attribute_identifier' ) ) ) );
        }

        $dom =& $xml->domTree( $this->XMLData );
        if ( $dom )
        {
            $domNode =& $dom->elementsByName( "section" );

            $relatedObjectIDArray = array();
            $nodeIDArray = array();

            // Fetch all links and cache the url's
            $links =& $dom->elementsByName( "link" );

            if ( count( $links ) > 0 )
            {
                $linkIDArray = array();
                // Find all Link id's
                foreach ( $links as $link )
                {
                    $linkID = $link->attributeValue( 'url_id' );
                    if ( $linkID != null )
                        if ( !in_array( $linkID, $linkIDArray ) )
                            $linkIDArray[] = $linkID;

                    $objectID = $link->attributeValue( 'object_id' );
                    if ( $objectID != null )
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;

                    $nodeID = $link->attributeValue( 'node_id' );
                    if ( $nodeID != null )
                        if ( !in_array( $nodeID, $nodeIDArray ) )
                            $nodeIDArray[] = $nodeID;
                }

                if ( count( $linkIDArray ) > 0 )
                {
                    $inIDSQL = implode( ', ', $linkIDArray );

                    $db =& eZDB::instance();
                    $linkArray = $db->arrayQuery( "SELECT * FROM ezurl WHERE id IN ( $inIDSQL ) " );

                    foreach ( $linkArray as $linkRow )
                    {
                        $this->LinkArray[$linkRow['id']] = $linkRow['url'];
                    }
                }
            }

            // Fetch all embeded objects and cache by ID
            $objectArray =& $dom->elementsByName( "object" );

            if ( count( $objectArray ) > 0 )
            {
                foreach ( $objectArray as $object )
                {
                    $objectID = $object->attributeValue( 'id' );
                    if ( $objectID != null )
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;
                }
            }

            $embedTagArray =& $dom->elementsByName( "embed" );

            if ( count( $embedTagArray ) > 0 )
            {
                foreach ( $embedTagArray as $embedTag )
                {
                    $objectID = $embedTag->attributeValue( 'object_id' );
                    if ( $objectID != null )
                        if ( !in_array( $objectID, $relatedObjectIDArray ) )
                            $relatedObjectIDArray[] = $objectID;

                    $nodeID = $embedTag->attributeValue( 'node_id' );
                    if ( $nodeID !=null )
                        if ( !in_array( $nodeID, $nodeIDArray ) )
                            $nodeIDArray[] = $nodeID;
                }
            }

            if ( $relatedObjectIDArray != null )
                $this->ObjectArray =& eZContentObject::fetchIDArray( $relatedObjectIDArray );

            if ( $nodeIDArray != null )
            {
                $nodes = eZContentObjectTreeNode::fetch( $nodeIDArray );

                if ( is_array( $nodes ) )
                {
                    foreach( $nodes as $node )
                    {
                        $nodeID = $node->attribute( 'node_id' );
                        $this->NodeArray["$nodeID"] = $node;
                    }
                }
                elseif ( $nodes )
                {
                    $node =& $nodes;
                    $nodeID = $node->attribute( 'node_id' );
                    $this->NodeArray["$nodeID"] = $node;
                }
              //  else
              //  {
              //      eZDebug::writeError( "Embedded node(s) fetching failed", "XML output handler" );
              //  }
            }

            $sectionNode =& $domNode[0];
            if ( get_class( $sectionNode ) == "ezdomnode" )
            {
                $output =& $this->renderPDFSection( $tpl, $sectionNode, 0 );
            }
            $dom->cleanup();
        }
        $res->removeKey( 'attribute_identifier' );
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
     \return the PDF rendered version of the section
    */
    function &renderList( &$tpl, &$listNode, $currentSectionLevel, $listSectionLevel = null )
    {
        $output = "";
        $tagName = $listNode->name();
        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= $this->renderPDFParagraph( $tpl, $listNode, $currentSectionLevel, $listSectionLevel );
            }break;

            case 'section' :
            {
                $sectionLevel += 1;
                if ( $listSectionLevel == null )
                    $output .= $this->renderPDFSection( $tpl, $listNode, $sectionLevel );
                else
                    $output .= $this->renderPDFSection( $tpl, $listNode, $currentSectionLevel, $sectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZXMLTextType::inputSectionXML()" );
            }break;
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

        return $output;
    }

    /*!
     \private
     Will render a tag and return the rendered text.
    */
    function &renderPDFTag( &$tpl, &$tag, $currentSectionLevel, &$isBlockTag, $tdSectionLevel = null, $isChildOfLinkTag = false )
    {
        $tagText = '';
        $childTagText = '';
        $tagName = $tag->name();

        if ( !$isChildOfLinkTag && count( $this->LinkParameters ) )
            $this->LinkParameters = array();

        // Set link parameters for rendering children of link tag
        if ( $tagName=="link" )
        {
            $href='';

            if ( $tag->attributeValue( 'url_id' ) != null )
            {
                $linkID = $tag->attributeValue( 'url_id' );
                $href = $this->LinkArray[$linkID];

                include_once( 'lib/ezutils/classes/ezmail.php' );
                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;
            }
            elseif ( $tag->attributeValue( 'node_id' ) != null )
            {
                $nodeID = $tag->attributeValue( 'node_id' );
                $node =& $this->NodeArray[$nodeID];
                if ( $node != null )
                {
                    $href = $node->attribute( 'url_alias' );
                }
                //else
                //{
                //    eZDebug::writeError( "Node $nodeID doesn't exist", "XML output handler" );
                //}
            }
            elseif ( $tag->attributeValue( 'object_id' ) != null )
            {
                $objectID = $tag->attributeValue( 'object_id' );
                $object =& $this->ObjectArray["$objectID"];
                if ( $object )
                {
                    $node =& $object->attribute( 'main_node' );
                    if ( $node )
                    {
                        $href = $node->attribute( 'url_alias' );
                    }
                   // else
                   // {
                   //     eZDebug::writeError( "Object $objectID is not attached to a node", "XML output handler" );
                   // }
                }
               // else
               //  {
               //     eZDebug::writeError( "Object $objectID doesn't exist", "XML output handler" );
               //  }
            }
            elseif ( $tag->attributeValue( 'href' ) != null )
            {
                $href = $tag->attributeValue( 'href' );
                include_once( 'lib/ezutils/classes/ezmail.php' );
                if ( eZMail::validate( $href ) )
                    $href = "mailto:" . $href;
            }

            if ( $tag->attributeValue( 'anchor_name' ) != null )
            {
                $href .= '#' . $tag->attributeValue( 'anchor_name' );
            }

            if ( $href != '' )
            {
                // Making valid URI
                // include_once( 'lib/ezutils/classes/ezuri.php' );
                // eZURI::transformURI( $href );

                $this->LinkParameters['href'] = $href;

                $this->LinkParameters['class'] = $tag->attributeValue( 'class' );
                $this->LinkParameters['target'] = $tag->attributeValue( 'target' );

                $this->LinkParameters['title'] = $tag->attributeValueNS( 'title', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
                $this->LinkParameters['id'] = $tag->attributeValueNS( 'id', 'http://ez.no/namespaces/ezpublish3/xhtml/' );
            }
        }

        // render children tags
        $tagChildren = $tag->children();
        foreach ( $tagChildren as $childTag )
        {
            switch( $tagName )
            {
                case "literal" :
                {
                    $childTagText .= trim( $childTag->content() );
                }break;
                case "link" :
                {
                    // we use no template for link tag, all link parameters are used
                    // inside the templates of it's children, so we update tagText directly
                    $tagText .= $this->renderPDFTag( $tpl, $childTag, $currentSectionLevel, $isBlockTag, $tdSectionLevel, $href != '' );
                }break;
                default :
                    $childTagText .= $this->renderPDFTag( $tpl, $childTag, $currentSectionLevel, $isBlockTag, $tdSectionLevel, $isChildOfLinkTag );
            }
        }

        switch ( $tagName )
        {
            case '#text' :
            {
                $text = htmlspecialchars( $tag->content() );
                // Get rid of linebreak and spaces stored in xml file
                $text = preg_replace( "#[\n]+#", '', $text );
                $text = preg_replace( "#    #", '', $text );

                if ( $isChildOfLinkTag )
                {
                    $res =& eZTemplateDesignResource::instance();
                    $res->setKeys( array( array( 'classification', $this->LinkParameters['class'] ) ) );

                    $tpl->setVariable( 'content', $text, 'xmltagns' );

                    $tpl->setVariable( 'href', $this->LinkParameters['href'], 'xmltagns' );
                    $tpl->setVariable( 'target', $this->LinkParameters['target'], 'xmltagns' );
                    $tpl->setVariable( 'classification', $this->LinkParameters['class'], 'xmltagns' );
                    $tpl->setVariable( 'title', $this->LinkParameters['title'], 'xmltagns' );
                    $tpl->setVariable( 'id', $this->LinkParameters['id'], 'xmltagns' );

                    $uri = 'design:content/datatype/pdf/ezxmltags/link.tpl';

                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $tagText .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );

                    // Remove the design key, so it will not override other tags
                    $res->removeKey( 'classification' );
                 //   $tpl->unsetVariable( 'classification', 'xmltagns' );
                }
                else
                {
                    $tagText .= $text;
                }
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
                    $res->setKeys( array( array( 'classification', $class ),
                                          array( 'class_identifier', $object->attribute( 'class_identifier' ) ) ) );

                    $hasLink = false;
                    $linkID = $tag->attributeValueNS( 'ezurl_id', 'http://ez.no/namespaces/ezpublish3/image/' );

                    if ( $linkID != null )
                    {
                        $href = eZURL::url( $linkID );
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
                        else if ( $attribute->name() == "align" )
                            $objectParameters['align'] = $alignment;
                        else
                            $objectParameters[$attribute->name()] = $attribute->content();
                    }

                    if ( strlen( $view ) == 0 )
                        $view = 'embed';

                    if ( $object->attribute( 'can_read' ) ||
                         $object->attribute( 'can_view_embed' ) )
                    {
                        $xmlTemplate = 'object';
                    }
                    else
                    {
                        $xmlTemplate = 'object_denied';
                    }

                    $tpl->setVariable( 'classification', $class, 'xmltagns' );
                    $tpl->setVariable( 'object', $object, 'xmltagns' );
                    $tpl->setVariable( 'view', $view, 'xmltagns' );
                    $tpl->setVariable( 'object_parameters', $objectParameters, 'xmltagns' );
                    $uri = 'design:content/datatype/pdf/ezxmltags/'. $xmlTemplate .'.tpl';
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

        case 'embed' :
        {
            //$isBlockTag = true;

            $objectID = $tag->attributeValue( 'object_id' );
            if ( $objectID )
            {
                $object =& $this->ObjectArray["$objectID"];
            }

            $nodeID = $tag->attributeValue( 'node_id' );
            if ( $nodeID )
            {
                if ( isset( $this->NodeArray[$nodeID] ) )
                {
                    $node =& $this->NodeArray[$nodeID];
                    $objectID = $node->attribute( 'contentobject_id' );
                    $object =& $node->object();
                }
                else
                {
                 //   eZDebug::writeError( "Node $nodeID doesn't exist", "XML output handler" );
                    break;
                }
            }

            if ( !$object )
            {
                //eZDebug::writeError( "Can't fetch object. objectID: $objectID, nodeID: $nodeID", "XML output handler" );
                break;
            }

            // fetch attributes
            $embedAttributes =& $tag->attributes();

            // Fetch from cache
            if ( get_class( $object ) == "ezcontentobject" and
                 $object->attribute( 'status' ) == EZ_CONTENT_OBJECT_STATUS_PUBLISHED )
            {
                $view = $tag->attributeValue( 'view' );
                if ( $view == null )
                    $view = 'embed';
                $class = $tag->attributeValue( 'class' );

                $objectParameters = array();
                $objectParameters['align'] = 'right';
                foreach ( $embedAttributes as $attribute )
                {
                   $attrName = $attribute->name();
                   if ( $attrName != 'view' && $attrName != 'class' && $attrName != 'node_id' && $attrName != 'object_id' )
                       $objectParameters[$attribute->name()] = $attribute->content();
                }

                if ( $object->attribute( 'can_read' ) ||
                     $object->attribute( 'can_view_embed' ) )
                {
                    $xmlTemplate = 'embed';
                }
                else
                {
                    $xmlTemplate = 'embed_denied';
                }

                $tpl->setVariable( 'classification', $class, 'xmltagns' );
                $tpl->setVariable( 'object', $object, 'xmltagns' );
                $tpl->setVariable( 'view', $view, 'xmltagns' );
                $tpl->setVariable( 'object_parameters', $objectParameters, 'xmltagns' );
                //$tpl->setVariable( 'link_parameters', $this->LinkParameters, 'xmltagns' );

                $uri = "design:content/datatype/pdf/ezxmltags/$xmlTemplate.tpl";
                $textElements = array();
                eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, "foo", "xmltagns" );
                $tagText = implode( '', $textElements );

                // Set to true if tag breaks paragraph flow as default
                $isBlockTag = true;

                // Check if the template overrides the block flow setting
                $isBlockTagOverride = 'true';
                if ( $tpl->hasVariable( 'is_block', 'xmltagns:ContentView' ) )
                {
                    $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns:ContentView' );
                }
                else if ( $tpl->hasVariable( 'is_block', 'xmltagns' ) )
                {
                    $isBlockTagOverride = $tpl->variable( 'is_block', 'xmltagns' );
                }

                if ( $isBlockTagOverride == 'true' )
                    $isBlockTag = true;
                else
                    $isBlockTag = false;
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
                $listCount = 0;
                // find all list elements
                foreach ( $tag->children() as $listItemNode )
                {
                    $listItemContent = '';
                    $listSctionLevel = $currentSectionLevel;
                    foreach ( $listItemNode->children() as $itemChildNode )
                    {
                        $listSectionLevel = $currentSectionLevel;
                        if ( $itemChildNode->name() == "section" or $itemChildNode->name() == "paragraph" )
                        {
                            $listItemContent .= $this->renderList( $tpl, $itemChildNode, $currentSectionLevel, $listSectionLevel );
                        }
                        else
                        {
                            $listItemContent .= $this->renderPDFTag( $tpl, $itemChildNode, 0, $isBlockTag );
                        }
                    }
                    $listItemContent = $this->pdfTrim( $listItemContent );
                    $tpl->setVariable( 'list_count', ++$listCount, 'xmltagns' );
                    $tpl->setVariable( 'tag_name', $tagName, 'xmltagns' );
                    $tpl->setVariable( 'content', $listItemContent, 'xmltagns' );
                    $uri = 'design:content/datatype/pdf/ezxmltags/li.tpl';

                    $textElements = array();
                    eZTemplateIncludeFunction::handleInclude( $textElements, $uri, $tpl, 'foo', 'xmltagns' );
                    $listContent .= str_replace( $this->WhiteSpaceArray, '', implode( '', $textElements ) );
                }
                if ( $tagName == 'ol' )
                {
//                    exit();
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

                $isInlineTagList = $ini->variable( 'CustomTagSettings', 'IsInline' );
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
            } break;

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

    /*!
     \private
     Trim PDF code, and remove linebrea, linespace and tab.

     \param text

     \return trimmed text.
    */
    function pdfTrim( $text )
    {
        $text = trim( $text );
        $text = preg_replace( "/^(" . EZ_PDF_LIB_NEWLINE . ")+/i", "", $text );
        $text = preg_replace( "/(" . EZ_PDF_LIB_NEWLINE . ")+$/i", "", $text );
        return $text;
    }

    /// Contains the URL's for <link> tags hashed by ID
    var $LinkArray = array();

    /// Contains the Objects for the <object> tags hashed by ID
    var $ObjectArray = array();

    /// Whitespace array of white spaces to remove
    var $WhiteSpaceArray = array( ' ', "\r\n", "\n", "\t" );

    /// Contains the Nodes hashed by ID
    var $NodeArray = array();

    /// Array of parameters for rendering tags that are children of 'link' tag
    var $LinkParameters = array();
}

?>
