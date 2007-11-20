<?php
//
// Definition of eZXHTMLXMLOutput class
//
// Created on: <18-Aug-2006 15:05:00 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'kernel/classes/datatypes/ezxmltext/ezxmloutputhandler.php' );
////include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );

class eZXHTMLXMLOutput extends eZXMLOutputHandler
{

    var $OutputTags = array(

    'section'      => array( 'quickRender' => true,
                             'initHandler' => 'initHandlerSection',
                             'renderHandler' => 'renderChildrenOnly' ),

    'embed'        => array( 'initHandler' => 'initHandlerEmbed',
                             'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification',
                                                           'xhtml:id' => 'id',
                                                           'object_id' => false,
                                                           'node_id' => false,
                                                           'show_path' => false ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'embed-inline' => array( 'initHandler' => 'initHandlerEmbed',
                             'renderHandler' => 'renderInline',
                             'attrNamesTemplate' => array( 'class' => 'classification',
                                                           'xhtml:id' => 'id',
                                                           'object_id' => false,
                                                           'node_id' => false,
                                                           'show_path' => false ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'table'        => array( 'initHandler' => 'initHandlerTable',
                             'renderHandler' => 'renderAll',
                             'contentVarName' => 'rows',
                             'attrNamesTemplate' => array( 'class' => 'classification',
                                                           'width' => 'width' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'tr'           => array( //'quickRender' => array( 'tr', "\n" ),
                             'initHandler' => 'initHandlerTr',
                             'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'td'           => array( 'initHandler' => 'initHandlerTd',
                             'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'xhtml:width' => 'width',
                                                           'xhtml:colspan' => 'colspan',
                                                           'xhtml:rowspan' => 'rowspan',
                                                           'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'th'           => array( 'initHandler' => 'initHandlerTd',
                             'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'xhtml:width' => 'width',
                                                           'xhtml:colspan' => 'colspan',
                                                           'xhtml:rowspan' => 'rowspan',
                                                           'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'ol'           => array( 'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'ul'           => array( 'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'li'           => array( 'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'header'       => array( 'initHandler' => 'initHandlerHeader',
                             'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'paragraph'    => array( //'quickRender' => array( 'p', "\n" ),
                             'renderHandler' => 'renderParagraph',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'line'         => array( //'quickRender' => array( '', "<br/>" ),
                             'renderHandler' => 'renderLine' ),

    'literal'      => array( 'renderHandler' => 'renderAll',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'strong'       => array( 'renderHandler' => 'renderInline',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'emphasize'    => array( 'renderHandler' => 'renderInline',
                             'attrNamesTemplate' => array( 'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'link'         => array( 'initHandler' => 'initHandlerLink',
                             'renderHandler' => 'renderInline',
                             'attrNamesTemplate' => array( 'xhtml:id' => 'id',
                                                           'xhtml:title' => 'title',
                                                           'url_id' => false,
                                                           'object_id' => false,
                                                           'node_id' => false,
                                                           'show_path' => false,
                                                           'ezurl_id' => false,
                                                           'anchor_name' => false,
                                                           'class' => 'classification' ),
                             'attrDesignKeys' => array( 'class' => 'classification' ) ),

    'anchor'       => array( 'renderHandler' => 'renderInline' ),

    'custom'       => array( 'initHandler' => 'initHandlerCustom',
                             'renderHandler' => 'renderCustom',
                             'attrNamesTemplate' => array( 'name' => false ) ),

    '#text'        => array( 'quickRender' => true,
                             'renderHandler' => 'renderText' )
    );

    function eZXHTMLXMLOutput( &$xmlData, $aliasedType, $contentObjectAttribute = null )
    {
        $this->eZXMLOutputHandler( $xmlData, $aliasedType, $contentObjectAttribute );

        $ini = eZINI::instance('ezxml.ini');
        if ( $ini->variable( 'ezxhtml', 'RenderParagraphInTableCells' ) == 'disabled' )
            $this->RenderParagraphInTableCells = false;
    }

    function initHandlerSection( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        $ret = array();
        if( !isset( $parentParams['section_level'] ) )
            $parentParams['section_level'] = 0;
        else
            $parentParams['section_level']++;

        // init header counter for current level and resert it for the next level
        $level = $parentParams['section_level'];
        if ( $level != 0 )
        {
            if ( !isset( $this->HeaderCount[$level] ) )
                $this->HeaderCount[$level] = 0;

            $this->HeaderCount[$level + 1] = 0;
        }

        return $ret;
    }

    function initHandlerHeader( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        $level = $parentParams['section_level'];
        $this->HeaderCount[$level]++;

        // headers auto-numbering
        $i = 1;
        $headerAutoName = '';
        while ( $i <= $level )
        {
            if ( $i > 1 )
                $headerAutoName .= "_";

            $headerAutoName .= $this->HeaderCount[$i];
            $i++;
        }
        $levelNumber = str_replace( "_", ".", $headerAutoName );

        if ( $this->ObjectAttributeID )
            $headerAutoName = $this->ObjectAttributeID . '_' . $headerAutoName;

        $ret = array( 'tpl_vars' => array( 'level' => $level,
                                           'header_number' => $levelNumber,
                                           'toc_anchor_name' => $headerAutoName ) );

        return $ret;
    }

    function initHandlerLink( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        $ret = array();

        // Set link parameters for rendering children of link tag
        $href='';
        if ( $element->getAttribute( 'url_id' ) != null )
        {
            $linkID = $element->getAttribute( 'url_id' );
            $href = $this->LinkArray[$linkID];
        }
        elseif ( $element->getAttribute( 'node_id' ) != null )
        {
            $nodeID = $element->getAttribute( 'node_id' );
            $node = $this->NodeArray[$nodeID];

            if ( $node != null )
            {
                $view = $element->getAttribute( 'view' );
                if ( $view )
                    $href = 'content/view/' . $view . '/' . $nodeID;
                else
                    $href = $node->attribute( 'url_alias' );
            }
            else
            {
                eZDebug::writeWarning( "Node #$nodeID doesn't exist", "XML output handler: link" );
            }
        }
        elseif ( $element->getAttribute( 'object_id' ) != null )
        {
            $objectID = $element->getAttribute( 'object_id' );
            if ( isset( $this->ObjectArray["$objectID"] ) )
            {
                $object = $this->ObjectArray["$objectID"];
                $node = $object->attribute( 'main_node' );
                if ( $node )
                {
                    $nodeID = $node->attribute( 'node_id' );

                    $view = $element->getAttribute( 'view' );
                    if ( $view )
                        $href = 'content/view/' . $view . '/' . $nodeID;
                    else
                        $href = $node->attribute( 'url_alias' );
                }
                else
                {
                    eZDebug::writeWarning( "Object #$objectID doesn't have assigned nodes", "XML output handler: link" );
                }
            }
            else
            {
                eZDebug::writeWarning( "Object #$objectID doesn't exist", "XML output handler: link" );
            }
        }
        elseif ( $element->getAttribute( 'href' ) != null )
        {
            $href = $element->getAttribute( 'href' );
        }

        if ( $element->getAttribute( 'anchor_name' ) != null )
        {
            $href .= '#' . $element->getAttribute( 'anchor_name' );
        }

        if ( $href !== false )
        {
            $attributes['href'] = $href;
            $parentParams['link_parameters'] = $attributes;
        }

        return $ret;
    }

    function initHandlerEmbed( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        // default return value in case of errors
        $ret = array( 'no_render' => true );

        $tplSuffix = '';
        $objectID = $element->getAttribute( 'object_id' );
        if ( $objectID &&
             !empty( $this->ObjectArray["$objectID"] ) )
        {
            $object = $this->ObjectArray["$objectID"];
        }
        else
        {
            $nodeID = $element->getAttribute( 'node_id' );
            if ( $nodeID )
            {
                if ( isset( $this->NodeArray[$nodeID] ) )
                {
                    $node = $this->NodeArray[$nodeID];
                    $objectID = $node->attribute( 'contentobject_id' );
                    $object = $node->object();
                    $tplSuffix = '_node';
                }
                else
                {
                    eZDebug::writeWarning( "Node #$nodeID doesn't exist", "XML output handler: embed" );
                    return $ret;
                }
            }
        }

        if ( !isset( $object ) || !$object || !( $object instanceof eZContentObject ) )
        {
            eZDebug::writeWarning( "Can't fetch object #$objectID", "XML output handler: embed" );
            return $ret;
        }
        if ( $object->attribute( 'status' ) != eZContentObject::STATUS_PUBLISHED )
        {
            eZDebug::writeWarning( "Object #$objectID is not published", "XML output handler: embed" );
            return $ret;
        }

        if ( $object->attribute( 'can_read' ) ||
             $object->attribute( 'can_view_embed' ) )
        {
            $templateName = $element->nodeName . $tplSuffix;
        }
        else
        {
            $templateName = $element->nodeName . '_denied';
        }

        $objectParameters = array();
        $excludeAttrs = array( 'view', 'class', 'node_id', 'object_id' );

        foreach ( array_keys( $attributes ) as $attrName )
        {
           $value = $attributes[$attrName];
           if ( !in_array( $attrName, $excludeAttrs ) )
           {
               if ( strpos( $attrName, ':' ) !== false )
                   $attrName = substr( $attrName, strpos( $attrName, ':' ) + 1 );

               $objectParameters[$attrName] = $value;
               unset( $attributes[$attrName] );
           }
        }

        if ( isset( $parentParams['link_parameters'] ) )
            $linkParameters = $parentParams['link_parameters'];
        else
            $linkParameters = array();

        $ret = array( 'template_name' => $templateName,
                      'tpl_vars' => array( 'object' => $object,
                                           'link_parameters' => $linkParameters,
                                           'object_parameters' => $objectParameters ),
                      'design_keys' => array( 'class_identifier', $object->attribute( 'class_identifier' ) ) );

        if ( $tplSuffix == '_node')
            $ret['tpl_vars']['node'] = $node;

        return $ret;
    }

    function initHandlerTable( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        // Numbers of rows and cols are lower by 1 for back-compatibility.
        $rows = $element->childNodes;
        $rowCount = $rows->length;
        $rowCount--;
        $lastRow = $element->lastChild;
        $cols = $lastRow->childNodes;
        $colCount = $cols->length;
        if ( $colCount )
            $colCount--;

        $ret = array( 'tpl_vars' => array( 'col_count' => $colCount,
                                           'row_count' => $rowCount ) );
        return $ret;
    }

    function initHandlerTr( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        $ret = array();
        if( !isset( $siblingParams['table_row_count'] ) )
            $siblingParams['table_row_count'] = 0;
        else
            $siblingParams['table_row_count']++;

        $parentParams['table_row_count'] = $siblingParams['table_row_count'];

        // Number of cols is lower by 1 for back-compatibility.
        $cols = $element->childNodes;
        $colCount = $cols->length;
        if ( $colCount )
            $colCount--;

        $ret = array( 'tpl_vars' => array( 'row_count' => $parentParams['table_row_count'],
                                           'col_count' => $colCount ) );
        return $ret;
    }

    function initHandlerTd( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        if( !isset( $siblingParams['table_col_count'] ) )
            $siblingParams['table_col_count'] = 0;
        else
            $siblingParams['table_col_count']++;

        $ret = array( 'tpl_vars' => array( 'col_count' => &$siblingParams['table_col_count'],
                                           'row_count' => &$parentParams['table_row_count'] ) );
        return $ret;
    }

    function initHandlerCustom( $element, &$attributes, &$siblingParams, &$parentParams )
{
        $ret = array( 'template_name' => $attributes['name'] );
        return $ret;
    }

    // Render handlers

    function renderParagraph( $element, $childrenOutput, $vars )
    {
        // don't render if inside 'li' or inside 'td' (by option)
        $parent = $element->parentNode;

        if ( ( $parent->nodeName == 'li' && $parent->childNodes->length == 1 ) ||
             ( $parent->nodeName == 'td' && $parent->childNodes->length == 1 && !$this->RenderParagraphInTableCells ) )
        {
            return $childrenOutput;
        }

        // break paragraph by block tags
        $tagText = '';
        $lastTagInline = null;
        $inlineContent = '';
        foreach( $childrenOutput as $key=>$childOutput )
        {
            if ( $childOutput[0] === true )
                $inlineContent .= $childOutput[1];

            if ( ( $childOutput[0] === false && $lastTagInline === true ) ||
                 ( $childOutput[0] === true && !array_key_exists( $key + 1, $childrenOutput ) ) )
            {
                $tagText .= $this->renderTag( $element, $inlineContent, $vars );
                $inlineContent = '';
            }

            if ( $childOutput[0] === false )
                $tagText .= $childOutput[1];

            $lastTagInline = $childOutput[0];
        }
        return array( false, $tagText );
    }

    function renderInline( $element, $childrenOutput, $vars )
    {
        $renderedArray = array();
        $lastTagInline = null;
        $inlineContent = '';

        foreach( $childrenOutput as $key=>$childOutput )
        {
            if ( $childOutput[0] === true )
                $inlineContent .= $childOutput[1];

            // Render only inline parts, block parts just passed to parent
            if ( ( $childOutput[0] === false && $lastTagInline === true ) ||
                 ( $childOutput[0] === true && !array_key_exists( $key + 1, $childrenOutput ) ) )
            {
                $tagText = $this->renderTag( $element, $inlineContent, $vars );
                $renderedArray[] = array( true, $tagText );
                $inlineContent = '';
            }

            if ( $childOutput[0] === false )
                $renderedArray[] = array( false, $childOutput[1] );

            $lastTagInline = $childOutput[0];

        }
        return $renderedArray;
    }

    function renderLine( $element, $childrenOutput, $vars )
    {
        $renderedArray = array();
        $lastTagInline = null;
        $inlineContent = '';

        foreach( $childrenOutput as $key=>$childOutput )
        {
            if ( $childOutput[0] === true )
                $inlineContent .= $childOutput[1];

            // Render line tag only if the last part of childrenOutput is inline and the next tag
            // within the same paragraph is 'line' too.

            if ( $childOutput[0] === false && $lastTagInline === true )
            {
                $renderedArray[] = array( true, $inlineContent );
                $inlineContent = '';
            }
            elseif ( $childOutput[0] === true && !array_key_exists( $key + 1, $childrenOutput ) )
            {
                $next = $element->nextSibling;
                if ( $next && $next->nodeName == 'line' )
                {
                    $tagText = $this->renderTag( $element, $inlineContent, $vars );
                    $renderedArray[] = array( true, $tagText );
                }
                else
                    $renderedArray[] = array( true, $inlineContent );
            }

            if ( $childOutput[0] === false )
                $renderedArray[] = array( false, $childOutput[1] );

            $lastTagInline = $childOutput[0];
        }
        return $renderedArray;
    }

    function renderCustom( $element, $childrenOutput, $vars )
    {
        if ( $this->XMLSchema->isInline( $element ) )
        {
            $ret = $this->renderInline( $element, $childrenOutput, $vars );
        }
        else
        {
            $ret = $this->renderAll( $element, $childrenOutput, $vars );
        }
        return $ret;
    }

    function renderChildrenOnly( $element, $childrenOutput, $vars )
    {
        $tagText = '';
        foreach( $childrenOutput as $childOutput )
        {
            $tagText .= $childOutput[1];
        }

        return array( false, $tagText );
    }

    function renderText( $element, $childrenOutput, $vars )
    {
        if ( $element->parentNode->nodeName != 'literal' )
        {
            $text = htmlspecialchars( $element->textContent );
            // Get rid of linebreak and spaces stored in xml file
            $text = preg_replace( "#[\n]+#", "", $text );

            if ( $this->AllowMultipleSpaces )
                $text = preg_replace( "#  #", " &nbsp;", $text );
            else
                $text = preg_replace( "# +#", " ", $text );

            if ( $this->AllowNumericEntities )
                $text = preg_replace( '/&amp;#([0-9]+);/', '&#\1;', $text );
        }
        else
        {
            $text = $element->textContent;
        }

        return array( true, $text );
    }


    /// Array of parameters for rendering tags that are children of 'link' tag
    public $LinkParameters = array();

    public $HeaderCount = array();

    public $RenderParagraphInTableCells = true;
}

?>
