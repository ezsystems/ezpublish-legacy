<?php
//
// Definition of eZDOMNode class
//
// Created on: <16-Nov-2001 12:11:43 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezdomnode.php
  DOM node handling
*/

/*!
  \class eZDOMNode ezdomnode.php
  \ingroup eZXML
  \brief eZDOMNode handles DOM nodes in DOM documents

  Type of the DOM node can be: ElementNode=1, AttributeNode=2, TextNode=3, CDATASectionNode=4
  \sa eZXML eZDOMDocument
*/

define( "EZ_XML_NODE_ELEMENT", 1 );
define( "EZ_XML_NODE_ATTRIBUTE", 2 );
define( "EZ_XML_NODE_TEXT", 3 );
define( "EZ_XML_NODE_CDATASECTION", 4 );

class eZDOMNode
{
    /*!
      Creates a new DOM node.
    */
    function eZDOMNode( )
    {
    }

    /*!
     Makes a copy of the current node and returns a reference to it.
    */
    function &clone()
    {
        $tmp = new eZDOMNode();
        $tmp->Name = $this->Name;
        $tmp->Type = $this->Type;
        $tmp->Content = $this->Content;
        $tmp->Children = $this->Children;
        $tmp->Attributes = $this->Attributes;
        $tmp->NamespaceURI = $this->NamespaceURI;
        $tmp->LocalName = $this->LocalName;
        $tmp->Prefix = $this->Prefix;
        return $tmp;
    }

    /*!
      Returns the node name.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the nodes name.
    */
    function setName( $name )
    {
        $this->Name = $name;
        $this->LocalName = $name;
    }

    /*!
      Returns the nodes namespace URI.
    */
    function namespaceURI()
    {
        return $this->NamespaceURI;
    }

    /*!
      Sets the namespace URI
    */
    function setNamespaceURI( $uri )
    {
        $this->NamespaceURI = $uri;
    }

    /*!
      Returns the local name of the node if the node uses namespaces. If not false is returned.
    */
    function localName()
    {
        return $this->LocalName;
    }

    /*!
      Returns returns the namespace prefix
    */
    function prefix()
    {
        return $this->Prefix;
    }

    /*!
      Sets the namespace prefix for this element.
    */
    function setPrefix( $value )
    {
        $this->Prefix = $value;
    }

    /*!
      Returns the node type.
      Type of the DOM node can be: ElementNode=1, AttributeNode=2, TextNode=3, CDATASectionNode=4
    */
    function type()
    {
        return $this->Type;
    }

    /*!
      Sets the node type.
      Type of the DOM node can be: ElementNode=1, AttributeNode=2, TextNode=3, CDATASectionNode=4
    */
    function setType( $type )
    {
        $this->Type = $type;
    }

    /*!
      Returns the node content.
    */
    function &content()
    {
        return $this->Content;
    }

    /*!
      Sets the node content.
    */
    function setContent( $content )
    {
        $this->Content = $content;
    }

    /*!
      Returns the node attributes.
    */
    function &attributes()
    {
        return $this->Attributes;
    }

    /*!
    Returns attributes for the given namespace.
    */
    function &attributesNS( $namespaceURI )
    {
        $ret = array();
        if ( count( $this->Attributes  ) > 0 )
        {
            foreach ( $this->Attributes as $attribute )
            {
                if ( $attribute->namespaceURI() == $namespaceURI )
                {

                    $ret[] = $attribute;
                }
            }
        }
        return $ret;
    }

    /*!
      \return \c true if the node has any attributes.
    */
    function &hasAttributes()
    {
        return count( $this->Attributes ) > 0;
    }

    /*!
      \return the number of attributes for the node.
    */
    function &attributeCount()
    {
        return count( $this->Attributes );
    }

    /*!
      Returns the node children.
    */
    function &children()
    {
        return $this->Children;
    }

    /*!
      \return \c true if the node has children.
    */
    function &hasChildren()
    {
        return count( $this->Children ) > 0;
    }

    /*!
      \return the number of children for the node.
    */
    function &childrenCount()
    {
        return count( $this->Children );
    }

    /*!
      Returns the first node children or \c null if no children.
    */
    function &firstChild()
    {
        if ( count( $this->Children ) == 0 )
            return null;
        return $this->Children[0];
    }

    /*!
     \returns the children of first element that is named \a $name.
     \note If multiple elements with that name is found \c false is returned.
     \sa elementByName, children
    */
    function &elementChildrenByName( $name )
    {
        $element =& $this->elementByName( $name );
        if ( !$element )
            return false;
        return $element->children();
    }

    /*!
     \returns the first child of first element that is named \a $name.
     \note If multiple elements with that name is found \c false is returned.
     \sa elementByName, firstChild
    */
    function &elementFirstChildByName( $name )
    {
        $element =& $this->elementByName( $name );
        if ( !$element )
            return false;
        return $element->firstChild();
    }

    /*!
     \returns the first element that is named \a $name.
              If multiple elements with that name is found \c false is returned.
     \sa elementsByName
    */
    function &elementByName( $name )
    {
        $element = false;
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $name )
            {
                if ( $element )
                    return false;
                $element =& $child;
            }
        }
        return $element;
    }

    /*!
     \returns the text content of first element that is named \a $name.
     \note If multiple elements with that name is found \c false is returned.
     \sa elementByName, textContent
    */
    function &elementTextContentByName( $name )
    {
        $element =& $this->elementByName( $name );
        if ( !$element )
            return false;
        return $element->textContent();
    }

    /*!
     \param attribute name
     \param attribute value

     \return element by attribute value
    */
    function &elementByAttributeValue( $attr, $value )
    {
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->attributeValue( $attr ) == $value )
            {
                return $child;
            }
        }
        return false;
    }

    /*!
     \return an array with elements that is named \a $name.
     \sa elementByName
    */
    function &elementsByName( $name )
    {
        $elements = array();
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $name )
            {
                $elements[] =& $child;
            }
        }
        return $elements;
    }

    /*!
     \return an array with text contents taken from all child elements with the name \a $name.
     \sa elementsByName, textContent
    */
    function &elementsTextContentByName( $name )
    {
        $elements = array();
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $name )
            {
                $elements[] =& $child->textContent();
            }
        }
        return $elements;
    }

    /*!
      Returns the attribute value for the given attribute.
      If no value is found false is returned.
    */
    function &attributeValue( $attributeName )
    {
        $returnValue = false;
        foreach ( $this->Attributes as $attribute )
        {
            if ( $attribute->name() == $attributeName )
                $returnValue = $attribute->content();
        }

        return $returnValue;
    }

    /*!
     Finds the first element that is named \a $name then extracts the value
     of the attribute named \a $attributeName on that element.
     \return the attribute value if found.
     \note If multiple elements with that name is found \c false is returned.
     \sa elementByName, attributeValue
    */
    function &elementAttributeValueByName( $name, $attributeName )
    {
        $element =& $this->elementByName( $name );
        if ( !$element )
            return false;
        return $element->attributeValue( $attributeName );
    }

    /*!
      Returns the attribute value for the given attribute.
      If no value is found false is returned.
      \sa elementAttributeValueByName
    */
    function &attributeValues( $attributeDefinitions, $defaultValue = null )
    {
        $hash = array();
        foreach ( $this->Attributes as $attribute )
        {
            foreach ( $attributeDefinitions as $attributeName => $keyName )
            {
                if ( $attribute->name() == $attributeName )
                {
                    $hash[$keyName] = $attribute->content();
                    break;
                }
            }
        }
        if ( $defaultValue !== null )
        {
            foreach ( $attributeDefinitions as $attributeName => $keyName )
            {
                if ( !isset( $hash[$keyName] ) )
                    $hash[$keyName] = $defaultValue;
            }
        }

        return $hash;
    }

    /*!
      Returns the attribute value for the given attribute name and namespace.
      If no value is found false is returned.
    */
    function &attributeValueNS( $attributeName, $namespaceURI )
    {
        $returnValue = false;
        if ( count( $this->Attributes  ) > 0 )
        {
            foreach ( $this->Attributes as $attribute )
            {
                if ( ( $attribute->name() == $attributeName )
                     &&
                     ( $attribute->namespaceURI() == $namespaceURI )
                     )
                {

                    $returnValue = $attribute->content();
                }
            }
        }

        return $returnValue;
    }

    /*!
      Appends a child node to the current node.
      \return the node that was just inserted or \c false if it failed to insert a node.
    */
    function &appendChild( &$node )
    {
        if ( get_class( $node ) == "ezdomnode" )
        {
            $this->Children[] =& $node;
            return $node;
        }
        return false;
    }

    /*!
      Appends an attribute node.
      \return the attribute that was just inserted or \c false if it failed to insert an attribute.
    */
    function &appendAttribute( &$node )
    {
        if ( get_class( $node ) == "ezdomnode" )
        {
            $this->Attributes[] =& $node;
            return $node;
        }
        return false;
    }

    /*!
      Appends an attribute node.
    */
    function appendAttributes( $attributeValues,
                               $attributeDefinitions,
                               $includeEmptyValues = false )
    {
        foreach ( $attributeDefinitions as $attributeXMLName => $attributeKey )
        {
            if ( $includeEmptyValues or
                 ( isset( $attributeValues[$attributeKey] ) and
                   $attributeValues[$attributeKey] !== false ) )
            {
                $value = false;
                if ( isset( $attributeValues[$attributeKey] ) and
                     $attributeValues[$attributeKey] !== false )
                    $value = $attributeValues[$attributeKey];
                $this->Attributes[] =& eZDOMDocument::createAttributeNode( $attributeXMLName, $value );
            }
        }
    }

    /*!
      Removes the attribute node named \a $name.
    */
    function removeNamedAttribute( $name )
    {
        $removed = false;
        $attributeArray = array();
        for ( $i = 0; $i < count( $this->Attributes ); ++$i )
        {
            $attribute =& $this->Attributes[$i];
            if ( $attribute->name() != $name )
                $attributeArray[] =& $attribute;
            else
                $removed = true;
        }
        unset( $this->Attributes );
        $this->Attributes =& $attributeArray;
        return $removed;
    }

    /*!
      Removes all attribute from the node.
    */
    function removeAttributes()
    {
        $this->Attributes = array();
    }

    /*!
      Removes the child(s) node named \a $name.
    */
    function removeNamedChildren( $name )
    {
        $removed = false;
        $childArray = array();
        for ( $i = 0; $i < count( $this->Children ); ++$i )
        {
            $child =& $this->Children[$i];
            if ( $child->name() != $name )
                $childArray[] =& $child;
            else
                $removed = true;
        }
        unset( $this->Childs );
        $this->Children =& $childArray;
        return $removed;
    }

    /*!
      Removes all children from the node.
    */
    function removeChildren()
    {
        $this->Children = array();
    }

    /*!
      Removes the last appended child node
    */
    function removeLastChild( )
    {
        end( $this->Children );
        $key = key( $this->Children );
        unset( $this->Children[$key] );
    }

    /*!
     \return the last appended child
    */
    function &lastChild()
    {
        return end( $this->Children );
    }

    /*!
     Returns the contents of the node if it has one child which is a #text node.
     \c false is returned if unsuccessful.
     \sa elementTextContentByName
    */
    function &textContent( )
    {
        $children =& $this->children();

        if ( count( $children ) == 1 )
        {
            return $children[0]->content();
        }
        else
            return false;
    }

    /*!
      Returns a XML string of the DOM Node and subnodes
    */
    function &toString( $level, $charset = false )
    {
        $spacer = str_repeat( " ", $level*2 );
        $ret = "";
        switch ( $this->Name )
        {
            case "#text" :
            {
                $tagContent = $this->Content;

                $tagContent =& str_replace( "&", "&amp;", $tagContent );
                $tagContent =& str_replace( ">", "&gt;", $tagContent );
                $tagContent =& str_replace( "<", "&lt;", $tagContent );
                $tagContent =& str_replace( "'", "&apos;", $tagContent );
                $tagContent =& str_replace( '"', "&quot;", $tagContent );

                $ret = $tagContent;
            }break;

            case "#cdata-section" :
            {
                $ret = "<![CDATA[";
                $ret .= $this->Content;
                $ret .= "]]>";
            }break;

            default :
            {
                $isOneLiner = false;
                // check if it's a oneliner
                if ( count( $this->Children ) == 0 and ( $this->Content == "" ) )
                    $isOneLiner = true;

                $attrStr = "";

                // check for namespace definition
                if ( $this->namespaceURI() != "" )
                {
                    $attrPrefix = "";
                    if ( $this->Prefix != "" )
                        $attrPrefix = ":" . $this->prefix();
                    $attrStr = " xmlns" . $attrPrefix . "=\"" . $this->namespaceURI() . "\"";
                }

                $prefix = "";
                if ( $this->Prefix != false )
                    $prefix = $this->Prefix. ":";

                // generate attributes string
                if ( count( $this->Attributes ) > 0 )
                {
                    $i = 0;
                    foreach ( $this->Attributes as $attr )
                    {
                        $attrPrefix = "";
                        if ( $attr->prefix() != false )
                            $attrPrefix = $attr->prefix(). ":";

                        if ( $i > 0 )
                            $attrStr .= "\n" . $spacer . str_repeat( " ", strlen( $prefix . $this->Name ) + 1 + 1  );
                        else
                            $attrStr .= ' ';

                        $attrContent = $attr->content();
                        $attrContent =& str_replace( "&", "&amp;", $attrContent );
                        $attrContent =& str_replace( ">", "&gt;", $attrContent );
                        $attrContent =& str_replace( "<", "&lt;", $attrContent );
                        $attrContent =& str_replace( "'", "&apos;", $attrContent );
                        $attrContent =& str_replace( '"', "&quot;", $attrContent );

                        $attrStr .=  $attrPrefix . $attr->name() . "=\"" . $attrContent . "\"";
                        ++$i;
                    }
                }

                if ( $isOneLiner )
                    $oneLinerEnd = " /";
                else
                    $oneLinerEnd = "";

                $ret = '';
                if ( $level > 0 )
                    $ret .= "\n";
                $ret .= "$spacer<" . $prefix . $this->Name . $attrStr . $oneLinerEnd . ">";

                $lastChildType = false;
                if ( count( $this->Children ) > 0 )
                {
                    foreach ( $this->Children as $child )
                    {
                        $ret .= $child->toString( $level + 1 );
                        $lastChildType = $child->type();
                    }
                }

                if ( !$isOneLiner )
                {
                    if ( $lastChildType == 1 )
                        $ret .= "\n$spacer";
                    $ret .= "</" . $prefix . $this->Name . ">";
                }
//                    $ret .= "$spacer</" . $prefix . $this->Name . ">\n";

            }break;
        }
        return $ret;
    }

    /// \privatesection
    /// Name of the node
    var $Name = false;

    /// Type of the DOM node. ElementNode=1, AttributeNode=2, TextNode=3, CDATASectionNode=4
    var $Type = EZ_XML_NODE_ELEMENT;

    /// Content of the node
    var $Content = "";

    /// Subnodes
    var $Children = array();

    /// Attributes
    var $Attributes = array();

    /// Contains the namespace URI. E.g. xmlns="http://ez.no/article/", http://ez.no/article/ would be the namespace URI
    var $NamespaceURI = false;

    /// The local part of a name. E.g: book:title, title is the local part
    var $LocalName = false;

    /// contains the namespace prefix. E.g: book:title, book is the prefix
    var $Prefix = false;
}

?>
