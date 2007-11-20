<?php
//
// Definition of eZSimplifiedXMLEditOutput class
//
// Created on: <07-Apr-2006 15:37:29 ks>
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

// if ( !class_exists( 'eZXMLSchema' ) )
    //include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlschema.php' );

class eZSimplifiedXMLEditOutput
{
    public $OutputTags = array(

        'section'      => array( 'handler' => 'outputSection' ),

        'embed'        => array( 'handler' => 'outputEmbed',
                                 'attributes' => array( 'xhtml:id' => 'id',
                                                       'object_id' => false,
                                                       'node_id' => false,
                                                       'show_path' => false ),
                                 'isSingle' => true ),

        'embed-inline' => array( 'handler' => 'outputEmbed',
                                 'attributes' => array( 'xhtml:id' => 'id',
                                                       'object_id' => false,
                                                       'node_id' => false,
                                                       'show_path' => false ),
                                 'isSingle' => true ),

        'object'       => array( 'handler' => 'outputObject',
                                 'attributes' => array( 'xhtml:id' => 'id',
                                                       'image:ezurl_target' => 'target',
                                                       'image:ezurl_href' => 'href',
                                                       'image:ezurl_id' => false ),
                                 'isSingle' => true ),

        'td'           => array( 'handler' => 'outputTd',
                                 'attributes' => array( 'xhtml:width' => 'width',
                                                       'xhtml:colspan' => 'colspan',
                                                       'xhtml:rowspan' => 'rowspan' ) ),

        'th'           => array( 'handler' => 'outputTd',
                                 'attributes' => array( 'xhtml:width' => 'width',
                                                       'xhtml:colspan' => 'colspan',
                                                       'xhtml:rowspan' => 'rowspan' ) ),

        'header'       => array( 'handler' => 'outputHeader' ),

        'paragraph'    => array( 'handler' => 'outputParagraph' ),

        'line'         => array( 'handler' => 'outputLine' ),

        'link'         => array( 'handler' => 'outputLink',
                                 'attributes' => array( 'xhtml:id' => 'id',
                                                        'xhtml:title' => 'title',
                                                        'url_id' => false,
                                                        'object_id' => false,
                                                        'node_id' => false,
                                                        'show_path' => false,
                                                        'ezurl_id' => false,
                                                        'anchor_name' => false ) ),
        'anchor'       => array( 'isSingle' => true ),

        '#text'        => array( 'handler' => 'outputText' )
        );

    // Call this function to obtain edit output string

    function performOutput( $dom )
    {
        $this->XMLSchema = eZXMLSchema::instance();
        $this->NestingLevel = 0;
        $this->Output = '';
        $sectionLevel = -1;

        $this->createLinksArray( $dom );

        $this->outputTag( $dom->documentElement, $sectionLevel );

        return $this->Output;
    }

    function outputTag( $element, $sectionLevel )
    {
        $tagName = $element->nodeName;
        if ( isset( $this->OutputTags[$tagName] ) )
        {
            $currentTag = $this->OutputTags[$tagName];

            if ( isset( $currentTag['attributes'] ) )
                $attributeRules = $currentTag['attributes'];
        }
        else
        {
            $currentTag = null;
        }

        //eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', 'outputting tag ' . $element->nodeName );

        // Prepare attributes array

        $attributes = array();

        if ( $element->hasAttributes() )
        {
            $attributeNodes = $element->attributes;

            foreach ( $attributeNodes as $attrNode )
            {
                if ( $attrNode->prefix && $attrNode->prefix != 'custom' )
                {
                    $attrName = $attrNode->prefix . ':' . $attrNode->localName;
                }
                else
                {
                    $attrName = $attrNode->nodeName;
                }

                $attributes[$attrName] = $attrNode->value;
            }
        }

        // Call tag handler
        $result = null;
        if ( $currentTag && isset( $currentTag['handler'] ) )
        {
            $result = $this->callOutputHandler( 'handler', $element, $attributes, $sectionLevel );
        }

        $hasChildren = $element->hasChildNodes();
        $isInline = $this->XMLSchema->isInline( $element );
        //eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $isInline ? 'yes' : 'no', 'is inline element?' );
        $isSingle = isset( $currentTag['isSingle'] ) && $currentTag['isSingle'];
        //eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $isSingle ? 'yes' : 'no', 'is single element?' );

        // If output was not set by handler, do a normal tag output
        if ( !is_string( $result ) )
        {
            // Convert (if needed) and output attributes
            $attrString = '';
            foreach ( array_keys( $attributes ) as $name )
            {
                $value = $attributes[$name];
                if ( isset( $attributeRules ) && isset( $attributeRules[$name] ) )
                {
                    if ( !$attributeRules[$name] )
                        continue;

                    $name = $attributeRules[$name];
                }
                $attrString .= ' ' . $name . '="' . $value . '"';
            }

            $this->formatBeforeOpeningTag( $element, $isInline, $hasChildren );

            //Output opening tag
            $closing = $isSingle ? ' />' : '>';

            $this->Output .= '<' . $tagName . $attrString . $closing;

            if ( !$isSingle )
            {
                $this->formatAfterOpeningTag( $element, $isInline, $hasChildren );
            }

            $this->NestingLevel++;

            //eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->Output, 'output so far' );
        }

        if ( $element->hasChildNodes() )
        {
            // Process children
            foreach ( $element->childNodes as $child )
            {
                $this->outputTag( $child, $sectionLevel );
            }
        }

        if ( is_string( $result ) )
        {
            $this->Output .= $result;
            return;
        }
        else
        {
            $this->NestingLevel--;
            if ( !$isSingle )
            {
                $this->formatBeforeClosingTag( $element, $isInline, $hasChildren );

                $this->Output .= '</' . $tagName . '>';
            }

            $this->formatAfterClosingTag( $element, $isInline, $hasChildren );
        }

        return;
    }

    function formatBeforeOpeningTag( $element, $isInline, $hasChildren )
    {
        // Add indenting for block tags
        if ( !$isInline )
        {
            if ( $this->NestingLevel > 0 )
            {
                $this->Output .= str_repeat( '  ', $this->NestingLevel );
            }
        }
    }

    function formatAfterOpeningTag( $element, $isInline, $hasChildren )
    {
        // Add linebreak in case we have block tag as a first child
        if ( !$isInline && $hasChildren )
        {
            $firstChild = $element->firstChild;
            if ( $firstChild && $firstChild->nodeName == 'paragraph' && !$firstChild->hasAttributes() )
            {
                $firstChild = $firstChild->firstChild;
            }
            if ( $firstChild && $firstChild->nodeName == 'line' )
            {
                $firstChild = $firstChild->firstChild;
            }
            if ( $firstChild && !$this->XMLSchema->isInline( $firstChild ) )
            {
                $this->Output .= "\n";
            }
        }
    }

    function formatBeforeClosingTag( $element, $isInline, $hasChildren )
    {
        if ( !$isInline && $hasChildren )
        {
            $lastChild = $element->lastChild;
            if ( $lastChild && $lastChild->nodeName == 'paragraph' && !$lastChild->hasAttributes() )
            {
                $lastChild = $lastChild->lastChild;
            }
            if ( $lastChild && $lastChild->nodeName == 'line' )
            {
                $lastChild = $lastChild->lastChild;
            }
            if ( $lastChild && !$this->XMLSchema->isInline( $lastChild ) )
            {
                // Add line breaks and indenting for block tags
                $this->Output .= "\n";
                if ( $this->NestingLevel > 0 )
                {
                    $this->Output .= str_repeat( '  ', $this->NestingLevel );
                }
            }
        }
    }

    function formatAfterClosingTag( $element, $isInline, $hasChildren )
    {
        if ( !$isInline )
        {
            $next = $element->nextSibling;
            if ( $next )
            {
                $this->Output .= "\n";
            }
            else
            {
                $parent = $element->parentNode;
                while( $parent && $parent->nodeName == 'section' )
                {
                    $next = $parent->nextSibling;
                    if ( $next )
                    {
                        $this->Output .= "\n";
                        break;
                    }
                    $parent = $parent->parentNode;
                }
            }
        }
    }

    function callOutputHandler( $handlerName, $element, &$params, &$sectionLevel )
    {
        $result = null;
        $thisOutputTag = $this->OutputTags[$element->nodeName];
        if ( isset( $thisOutputTag[$handlerName] ) )
        {
            if ( is_callable( array( $this, $thisOutputTag[$handlerName] ) ) )
            {
                $result = call_user_func_array( array( $this, $thisOutputTag[$handlerName] ),
                                                array( $element, &$params, &$sectionLevel ) );
            }
            else
            {
                eZDebug::writeWarning( "'$handlerName' output handler for tag <$element->nodeName> doesn't exist: '" . $thisOutputTag[$handlerName] . "'.", 'eZXML converter' );
            }
        }
        return $result;
    }

    /*
        Tag handlers
    */
    function outputSection( $element, &$attributes, &$sectionLevel )
    {
        $sectionLevel++;

        $ret = '';
        return $ret;
    }

    function outputText( $element, &$attributes, &$sectionLevel )
    {
        $text = $element->textContent;

        if ( $element->parentNode->nodeName == 'literal' )
        {
            $text = str_replace("&lt;", "<", $text );
            $text = str_replace("&gt;", ">", $text );
            $text = str_replace("&apos;", "'", $text );
            $text = str_replace("&quot;", '"', $text );
            $text = str_replace("&amp;", "&", $text );
        }
        else
        {
            $text = str_replace("&gt;", ">", $text );
            $text = str_replace("&apos;", "'", $text );
            $text = str_replace("&quot;", '"', $text );

            // Sequence like '&amp;amp;' should not be converted to '&amp;' ( if not inside a literal tag )
            $text = preg_replace("#&amp;(?!lt;|gt;|amp;|&apos;|&quot;)#", "&", $text );

            $text = preg_replace( "#[\n]+#", "", $text );
        }
        return $text;
    }

    function outputTd( $element, &$attributes, &$sectionLevel )
    {
        $ret = null;

        // We have to reset section level in the table cell
        $sectionLevel = 0;
        return $ret;
    }

    function outputHeader( $element, &$attributes, &$sectionLevel )
    {
        $ret = null;
        $attributes['level'] = $sectionLevel;
        return $ret;
    }

    function outputParagraph( $element, &$attributes, &$sectionLevel )
    {
        $ret = null;
        if ( count( $attributes ) == 0 )
        {
            $next = $element->nextSibling;
            if ( $next )
            {
                $ret = "\n\n";
            }
            else
            {
                $ret = "";
                $parent = $element->parentNode;
                while( $parent && $parent->nodeName == 'section' )
                {
                    $next = $parent->nextSibling;
                    if ( $next )
                    {
                        $ret = "\n\n";
                        break;
                    }
                    $parent = $parent->parentNode;
                }
            }
        }
        return $ret;
    }

    function outputLine( $element, &$attributes, &$sectionLevel )
    {
        $ret = '';
        $next = $element->nextSibling;
        if ( is_object( $next ) )
        {
            $ret = "\n";
        }
        return $ret;
    }

    function outputEmbed( $element, &$attributes, &$sectionLevel )
    {
        $ret = null;
        $href = '';
        $objectID = isset( $attributes['object_id'] ) ? $attributes['object_id'] : null;
        $nodeID = isset( $attributes['node_id'] ) ? $attributes['node_id'] : null;
        $showPath = isset( $attributes['show_path'] ) ? $attributes['show_path'] : null;
        if ( $objectID )
        {
            $href = 'ezobject://' .$objectID;
        }
        elseif ( $nodeID )
        {
            $href = eZSimplifiedXMLEditOutput::eznodeHref( $nodeID, $showPath );
        }
        $attributes['href'] = $href;
        return $ret;
    }

    function outputObject( $element, &$attributes, &$sectionLevel )
    {
        $ret = null;
        if ( isset( $attributes['image:ezurl_id'] ) )
        {
            $linkID = $attributes['image:ezurl_id'];
            if ( $linkID != null )
            {
                $href = eZURL::url( $linkID );
                $attributes['href'] = $href;
            }
        }
        return $ret;
    }

    function outputLink( $element, &$attributes, &$sectionLevel )
    {
        $ret = null;
        $href = '';
        $linkID = isset( $attributes['url_id'] ) ? $attributes['url_id'] : null;
        $objectID = isset( $attributes['object_id'] ) ? $attributes['object_id'] : null;
        $nodeID = isset( $attributes['node_id'] ) ? $attributes['node_id'] : null;
        $anchorName = isset( $attributes['anchor_name'] ) ? $attributes['anchor_name'] : null;
        $showPath = isset( $attributes['show_path'] ) ? $attributes['show_path'] : null;

        if ( $objectID )
        {
            $href = 'ezobject://' .$objectID;
        }
        elseif ( $nodeID )
        {
            $href = eZSimplifiedXMLEditOutput::eznodeHref( $nodeID, $showPath );
        }
        elseif ( $linkID )
        {
            // Fetch URL from cached array
            $href = $this->LinkArray[$linkID];
        }
        else
        {
            $href = isset( $attributes['href'] ) ? $attributes['href'] : '';
        }

        if ( $anchorName != null )
        {
            $href .= '#' . $anchorName;
        }

        $attributes['href'] = $href;
        return $ret;
    }

    /*!
      Helper function to generate the href attribute value for the eznode:// protocol
     */
    static function eznodeHref( $nodeID, $showPath )
    {
        if ( $showPath == 'true' )
        {
            //include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
            $href = $node ? 'eznode://' . $node['path_identification_string'] : 'eznode://' . $nodeID;
        }
        else
        {
            $href = 'eznode://' . $nodeID;
        }

        return $href;
    }

    /*!
      Helper function to prepare links array
    */
    function createLinksArray( $dom )
    {
        $links = array();
        $node = array();

        if ( $dom )
        {
            // Fetch all links and cache the url's
            $links = $dom->getElementsByTagName( "link" );
        }

        $linkIDArray = array();
        // Find all Link id's
        foreach ( $links as $link )
        {
            $linkIDValue = $link->getAttribute( 'url_id' );
            if ( !$linkIDValue )
            {
                continue;
            }
            if ( !in_array( $linkIDValue, $linkIDArray ) )
            {
                 $linkIDArray[] = $linkIDValue;
             }
        }

        if ( count( $linkIDArray ) > 0 )
        {
            $inIDSQL = implode( ', ', $linkIDArray );
            $db = eZDB::instance();
            $linkArray = $db->arrayQuery( "SELECT * FROM ezurl WHERE id IN ( $inIDSQL ) " );
            foreach ( $linkArray as $linkRow )
            {
                $this->LinkArray[$linkRow['id']] = $linkRow['url'];
            }
        }
    }

    public $XMLSchema;
    public $Output = '';
    public $NestingLevel = 0;

    // Contains all links hashed by ID
    public $LinkArray = array();
}
?>
