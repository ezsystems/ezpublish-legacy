<?php
/**
 * File containing the eZXHTMLXMLOutput class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZXHTMLXMLOutput extends eZXMLOutputHandler
{

    public $OutputTags = array(

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
                             'leavingHandler' => 'leavingHandlerTable',
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

        // init header counter for current level and for the next level if needed
        $level = $parentParams['section_level'];
        if ( $level != 0 )
        {
            if ( !isset( $this->HeaderCount[$level] ) )
                $this->HeaderCount[$level] = 0;

            if ( !isset( $this->HeaderCount[$level + 1] ) )
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
            if ( isset( $this->LinkArray[$linkID] ) )
                $href = $this->LinkArray[$linkID];
        }
        elseif ( $element->getAttribute( 'node_id' ) != null )
        {
            $nodeID = $element->getAttribute( 'node_id' );
            $node = isset( $this->NodeArray[$nodeID] ) ? $this->NodeArray[$nodeID] : null;

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

        if ( eZINI::instance()->variable( 'SiteAccessSettings', 'ShowHiddenNodes' ) !== 'true' )
        {
            if ( isset( $node ) )
            {
                // embed with a node ID
                if ( $node->attribute( 'is_invisible' ) )
                {
                    eZDebug::writeNotice( "Node #{$nodeID} is invisible", "XML output handler: embed" );
                    return $ret;
                }
            }
            else
            {
                // embed with an object id
                // checking if at least a location is visible
                $oneVisible = false;
                foreach( $object->attribute( 'assigned_nodes' ) as $assignedNode )
                {
                    if ( !$assignedNode->attribute( 'is_invisible' ) )
                    {
                        $oneVisible = true;
                        break;
                    }
                }
                if ( !$oneVisible )
                {
                    eZDebug::writeNotice( "None of the object #{$objectID}'s location(s) is visible", "XML output handler: embed" );
                    return $ret;
                }
            }
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

        foreach ( $attributes as $attrName => $value )
        {
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
                      'design_keys' => array( 'class_identifier' => $object->attribute( 'class_identifier' ) ) );

        if ( $tplSuffix == '_node')
            $ret['tpl_vars']['node'] = $node;

        return $ret;
    }

    function initHandlerTable( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        // Backing up the section_level, headings' level should be restarted inside tables.
        // @see http://issues.ez.no/11536
        $this->SectionLevelStack[] = $parentParams['section_level'];
        $parentParams['section_level'] = 0;

        // Numbers of rows and cols are lower by 1 for back-compatibility.
        $rowCount = self::childTagCount( $element ) -1;
        $lastRow = $element->lastChild;

        while ( $lastRow && !( $lastRow instanceof DOMElement && $lastRow->nodeName == 'tr' ) )
        {
           $lastRow = $lastRow->previousSibling;
        }

        $colCount = self::childTagCount( $lastRow );

        if ( $colCount )
            $colCount--;

        $ret = array( 'tpl_vars' => array( 'col_count' => $colCount,
                                           'row_count' => $rowCount ) );
        return $ret;
    }

    function leavingHandlerTable( $element, &$attributes, &$siblingParams, &$parentParams )
    {
        // Restoring the section_level as it was before entering the table.
        // @see http://issues.ez.no/11536
        $parentParams['section_level'] = array_pop($this->SectionLevelStack);
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
        $colCount = self::childTagCount( $element );
        if ( $colCount )
            $colCount--;

        $ret = array( 'tpl_vars' => array( 'row_count' => $parentParams['table_row_count'],
                                           'col_count' => $colCount ) );

        // Allow overrides based on table class
        $parent = $element->parentNode;
        if ( $parent instanceof DOMElement && $parent->hasAttribute('class') )
            $ret['design_keys'] = array( 'table_classification' => $parent->getAttribute('class') );

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

        // Allow overrides based on table class
        $parent = $element->parentNode->parentNode;
        if ( $parent instanceof DOMElement && $parent->hasAttribute('class') )
            $ret['design_keys'] = array( 'table_classification' => $parent->getAttribute('class') );

        if ( !$this->RenderParagraphInTableCells
                && self::childTagCount( $element ) == 1 )
        {
            // paragraph will not be rendered so its align attribute needs to
            // be taken into account at the td/th level
            // Looking for the paragraph with align attribute
            foreach( $element->childNodes as $c )
            {
                if ( $c instanceof DOMElement )
                {
                    if ( $c->hasAttribute( 'align' ) )
                    {
                        $attributes['align'] = $c->getAttribute( 'align' );
                    }
                    break ;
                }
            }
        }
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
        // don't render if inside 'li' or inside 'td'/'th' (by option)
        $parent = $element->parentNode;


        if ( ( $parent->nodeName == 'li' && self::childTagCount( $parent ) == 1 ) ||
             ( in_array( $parent->nodeName, array( 'td', 'th' ) ) && !$this->RenderParagraphInTableCells && self::childTagCount( $parent ) == 1 ) )

        {
            return $childrenOutput;
        }

        // Break paragraph by block tags (like table, ol, ul, header and paragraphs)
        $tagText = '';
        $lastTagInline = null;
        $inlineContent = '';
        foreach( $childrenOutput as $key => $childOutput )
        {
            if ( $childOutput[0] === true )// is inline
            {
                if( $childOutput[1] === ' ' )
                {
                    if ( isset( $childrenOutput[$key+1] ) && $childrenOutput[$key+1][0] === false )
                        continue;
                    else if ( isset( $childrenOutput[ $key - 1 ] ) && $childrenOutput[ $key - 1 ][0] === false )
                        continue;
                }

                $inlineContent .= $childOutput[1];
            }

            // Only render paragraph if current tag is block and previous was an inline tag
            // OR  if current one is inline and it's the last item in the child list
            if ( ( $childOutput[0] === false && $lastTagInline === true ) ||
                 ( $childOutput[0] === true && !isset( $childrenOutput[ $key + 1 ]  ) ) )
            {
                $tagText .= $this->renderTag( $element, $inlineContent, $vars );
                $inlineContent = '';
            }

            if ( $childOutput[0] === false )// is block
                $tagText .= $childOutput[1];

            $lastTagInline = $childOutput[0];
        }
        return array( false, $tagText );
    }

    /* Count child elemnts, ignoring whitespace and text
     *
     * @param DOMElement $parent
     * @return int
     */
    protected static function childTagCount( DOMElement $parent )
    {
        $count = 0;
        foreach( $parent->childNodes as $child )
        {
            if ( $child instanceof DOMElement ) $count++;
        }
        return $count;
    }

    function renderInline( $element, $childrenOutput, $vars )
    {
        $renderedArray = array();
        $lastTagInline = null;
        $inlineContent = '';

        foreach( $childrenOutput as $key=>$childOutput )
        {
            if ( $childOutput[0] === true )// is inline
                $inlineContent .= $childOutput[1];

            // Only render tag if current tag is block and previous was an inline tag
            // OR  if current one is inline and it's the last item in the child list
            if ( ( $childOutput[0] === false && $lastTagInline === true ) ||
                 ( $childOutput[0] === true && !isset( $childrenOutput[ $key + 1 ] ) ) )
            {
                $tagText = $this->renderTag( $element, $inlineContent, $vars );
                $renderedArray[] = array( true, $tagText );
                $inlineContent = '';
            }

            if ( $childOutput[0] === false )// is block
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
            if ( $childOutput[0] === true )// is inline
                $inlineContent .= $childOutput[1];

            // Render line tag only if the last part of childrenOutput is inline and the next tag
            // within the same paragraph is 'line' too.

            if ( $childOutput[0] === false && $lastTagInline === true )
            {
                $renderedArray[] = array( true, $inlineContent );
                $inlineContent = '';
            }
            elseif ( $childOutput[0] === true && !isset( $childrenOutput[ $key + 1 ] ) )
            {
                $next = $element->nextSibling;
                // Make sure we get next element that is an element (ignoring whitespace)
                while ( $next && !$next instanceof DOMElement )
                {
                   $next = $next->nextSibling;
                }

                if ( $next && $next->nodeName == 'line' )
                {
                    $tagText = $this->renderTag( $element, $inlineContent, $vars );
                    $renderedArray[] = array( true, $tagText );
                }
                else
                    $renderedArray[] = array( true, $inlineContent );
            }

            if ( $childOutput[0] === false )// is block
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
            if ( trim( $element->textContent ) === ''
                && ( ( $element->previousSibling && $element->previousSibling->nodeName === 'line' )
                    || ( $element->nextSibling && $element->nextSibling->nodeName === 'line' ) ) )
            {
                // spaces before or after a line element are irrelevant
                return array( true, '' );
            }
            $text = htmlspecialchars( $element->textContent );
            $text = str_replace( array( '&amp;nbsp;', "\xC2\xA0" ), '&nbsp;', $text);
            // Get rid of linebreak and spaces stored in xml file
            $text = str_replace( "\n", '', $text );

            if ( $this->AllowMultipleSpaces )
                $text = str_replace( '  ', ' &nbsp;', $text );
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

    /**
     * Stack of section levels saved when entering tables.
     * @var array
     */
    protected $SectionLevelStack = array();

    public $RenderParagraphInTableCells = true;
}

?>
