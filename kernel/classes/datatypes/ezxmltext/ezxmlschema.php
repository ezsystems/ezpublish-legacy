<?php
//
// Definition of eZXMLSchema class
//
// Created on: <27-Mar-2006 15:28:39 ks>
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


include_once( "lib/ezxml/classes/ezxml.php" );

class eZXMLSchema
{
    var $Schema = array(
        'section'   => array( 'blockChildrenAllowed' => array( 'header', 'paragraph', 'section' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => false,
                              'attributes' => array( 'xmlns:image', 'xmlns:xhtml', 'xmlns:custom' ) ),
    
        'embed'     => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => true,
                              'attributes' => array( 'object_id', 'node_id', 'show_path', 'size',
                                                     'align', 'view', 'xhtml:id', 'class', 'target' ),
                              'attributesDefaults' => array( 'align' => 'right', 'view' => 'embed' ) ),

        'embed-inline' => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => true,
                              'attributes' => array( 'object_id', 'node_id', 'show_path', 'size',
                                                     'align', 'view', 'xhtml:id', 'class', 'target' ),
                              'attributesDefaults' => array( 'align' => 'right', 'view' => 'embed-inline' ) ),
    
        'object'    => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => true,
                              'attributes' => array( 'class', 'id', 'size', 'align',
                                                     'view', 'image:ezurl_id', 'image:ezurl_target' ),
                              'attributesDefaults' => array( 'align' => 'right', 'view' => 'embed' ) ),
    
        'table'     => array( 'blockChildrenAllowed' => array( 'tr' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => array( 'class', 'width', 'border' ) ),
    
        'tr'        => array( 'blockChildrenAllowed' => array( 'td', 'th' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => false,
                              'attributes' => false ),
    
        'td'        => array( 'blockChildrenAllowed' => array( 'header', 'paragraph', 'section', 'table' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => false,
                              'attributes' => array( 'class', 'xhtml:width', 'xhtml:colspan', 'xhtml:rowspan' ) ),
    
        'th'        => array( 'blockChildrenAllowed' => array( 'header', 'paragraph', 'section', 'table' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => false,
                              'attributes' => array( 'class', 'xhtml:width', 'xhtml:colspan', 'xhtml:rowspan' ) ),
    
        'ol'        => array( 'blockChildrenAllowed' => array( 'li' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => array( 'class' ) ),
    
        'ul'        => array( 'blockChildrenAllowed' => array( 'li' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => array( 'class' ) ),
    
        'li'        => array( 'blockChildrenAllowed' => array( 'paragraph' ),
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => array( 'class' ) ),
    
        'header'    => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => array( 'class', 'anchor_name' ) ),
    
        'paragraph' => array( 'blockChildrenAllowed' => array( 'line', 'link', 'embed', 'object', 'table', 'ol', 'ul', 'custom' ),
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => array( 'class' ) ),
    
        'line'      => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => true,
                              'isInline' => false,
                              'attributes' => false ),
    
        'literal'   => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => array( '#text' ),
                              'childrenRequired' => true,
                              'isInline' => true,
                              'attributes' => array( 'class' ) ),
    
        'strong'    => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => true,
                              'isInline' => true,
                              'attributes' => array( 'class' ) ),
    
        'emphasize' => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => true,
                              'isInline' => true,
                              'attributes' => array( 'class' ) ),
    
        'link'      => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => true,
                              'isInline' => true,
                              'attributes' => array( 'class', 'xhtml:id', 'target', 'xhtml:title',
                                                     'object_id', 'node_id', 'show_path', 'anchor_name',
                                                     'url_id', 'id' ),
                              'attributesDefaults' => array( 'target' => '_self' ) ),
    
        'anchor'    => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => true,
                              'attributes' => array( 'name' ) ),
    
        'custom'    => array( 'blockChildrenAllowed' => true,
                              'inlineChildrenAllowed' => true,
                              'childrenRequired' => false,
                              'isInline' => null,
                              'attributes' => array( 'name' ) ),
    
        '#text'     => array( 'blockChildrenAllowed' => false,
                              'inlineChildrenAllowed' => false,
                              'childrenRequired' => false,
                              'isInline' => true,
                              'attributes' => false )
    );

    function eZXMLSchema()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( 'content.ini' );
                
        // Get inline custom tags list
        $this->isInlineTagList = $ini->variable( 'CustomTagSettings', 'IsInline' );
        
        include_once( 'lib/version.php' );
        $eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;
        if ( $eZPublishVersion >= 3.8 )
        {
            // Fix for empty paragraphs setting
            $allowEmptyParagraph = $ini->variable( 'paragraph', 'AllowEmpty' );
            $this->Schema['paragraph']['childrenRequired'] = $allowEmptyParagraph == 'true' ? false : true;
        }
        else
        {
            // Fix for headers content
            $this->Schema['header']['inlineChildrenAllowed'] = array( '#text' );
        }
    }

    function &instance()
    {
        $impl =& $GLOBALS["eZXMLSchemaGlobalInstance"];
        
        $class = get_class( $impl );
        if ( $class != "ezxmlschema" )
        {
            unset( $impl );
            $impl = new eZXMLSchema();

            // Set global instance
            $GLOBALS["eZXMLSchemaGlobalInstance"] =& $impl;
        }

        return $impl;
    }

    // Determines if the tag is inline
    function isInline( $element )
    {
        if ( is_string( $element ) )
            $elementName = $element;
        else
            $elementName = $element->nodeName;

        $isInline = $this->Schema[$elementName]['isInline'];

        // Special workaround for custom tags.
        if ( $isInline === null )
        {
            if ( !is_string( $element ) )
            {
                $isInline = false;
                $name = $element->getAttribute( 'name' );

                if ( isset( $this->isInlineTagList[$name] ) )
                {
                    if ( $this->isInlineTagList[$name] == 'true' )
                        $isInline = true;
                }
            }
        }
        return $isInline;
    }

    /*! 
       Checks if one element is allowed to be a child of another
       
       \param $parent   parent element: eZDOMNode or string
       \param $child    child element: eZDOMNode or string
       
       \return true  if elements match schema
       \return false if elements don't match schema
       \return null  in case of errors
    */

    function check( $parent, $child )
    {
        if ( is_string( $parent ) )
            $parentName = $parent;
        else
            $parentName = $parent->nodeName;

        if ( is_string( $child ) )
            $childName = $child;
        else
            $childName = $child->nodeName;

        if ( isset( $this->Schema[$childName] ) )
        {
            $isInline = $this->isInline( $child );
            
            if ( $isInline === true )
            {
                $allowed = $this->Schema[$parentName]['inlineChildrenAllowed'];
            }
            elseif ( $isInline === false )
            {
                // Special workaround for custom tags.
                if ( $parentName == 'custom' && !is_string( $parent ) &&
                     $parent->getAttribute( 'inline' ) != 'true' )
                {
                    $allowed = true;
                }
                else
                    $allowed = $this->Schema[$parentName]['blockChildrenAllowed'];
            }
            else
                return true;
            
            if ( is_array( $allowed ) )
                $allowed = in_array( $childName, $allowed );

            if ( !$allowed )
                return false;
        }
        else
        {
            //eZDebug::writeError( "No schema set for <" . $childName . "> tag.", 'eZXMLSchema' );
            return null;
        }
        return true;
    }

    function childrenRequired( $element )
    {
        //if ( !isset( $this->Schema[$element->nodeName] ) )
        //    return false;

        return $this->Schema[$element->nodeName]['childrenRequired'];
    }

    function hasAttributes( $element )
    {
        //if ( !isset( $this->Schema[$element->nodeName] ) )
        //    return false;

        return ( $this->Schema[$element->nodeName]['attributes'] != false );
    }

    function attributes( $element )
    {
        return $this->Schema[$element->nodeName]['attributes'];
    }

    function attrDefaultValue( $element, $attrName )
    {
        if ( isset( $this->Schema[$element->nodeName]['attributesDefaults'][$attrName] ) )
            return $this->Schema[$element->nodeName]['attributesDefaults'][$attrName];
    }

    function exists( $element )
    {
        $name = is_string( $element ) ? $element : $element->nodeName;
        return isset( $this->Schema[$name] );
    }

    // for custom tags
    var $isInlineTagList = array();
}
?>
