<?php
//
// Definition of eZDOMNode class
//
// Created on: <16-Nov-2001 12:11:43 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

/*! \file ezdomnode.php
  DOM node handling
*/

/*!
  \class eZDOMNode ezdomnode.php
  \ingroup eZXML
  \brief eZDOMNode encapsulates XML DOM nodes

  The following node types are supported:
  - Element node, has value \c 1
  - Attribute node, has value \c 2
  - Text node, has value \c 3
  - CDATA node, has value \c 4

  \sa eZXML eZDOMDocument
*/

/*!
 Element node, defines a node which contains attributes and children
*/
define( "EZ_XML_NODE_ELEMENT", 1 );
/*!
 Attribute node, defines a node which contains an attribute name and it's value
*/
define( "EZ_XML_NODE_ATTRIBUTE", 2 );
/*!
 Text node, defines a node which contains a text string encoded by escaping some characters.
*/
define( "EZ_XML_NODE_TEXT", 3 );
/*!
 CDATA node, defines a node which contains a text string encoding in a CDATA structure.
*/
define( "EZ_XML_NODE_CDATASECTION", 4 );

class eZDOMNode
{
    /*!
      Initializes the DOM node.
    */
    function eZDOMNode()
    {
        $this->content =& $this->value;
        $this->Content =& $this->content;
        $this->Name =& $this->tagname;
        $this->Type =& $this->type;
        $this->nodeName =& $this->Name;
    }

    /*!
     Makes a copy of the current node and returns a reference to it.
    */
    function clone()
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
      Subtree destructor. Needed to clean memory properly.
    */
    function cleanup( &$node )
    {
        if ( $node->hasChildren() )
        {
            foreach( array_keys( $node->Children ) as $key )
            {
                $child =& $node->Children[$key];
                if ( $child->hasChildren() )
                {
                    $child->cleanup( $child );
                }

            }
            $node->removeChildren();
        }
    }

    /*!
      \return The name of the node.

      For element and attributes nodes this will the name supplied when creating the node,
      for text nodes it returns \c #text and CDATA returns \c #cdata-section
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the current name to \a $name.
    */
    function setName( $name )
    {
        $this->Name = $name;
        $this->LocalName = $name;
    }

    /*!
      \return The namespace URI for the node or \c false if no URI
    */
    function namespaceURI()
    {
        return $this->NamespaceURI;
    }

    /*!
      Sets the namespace URI of the node to \a $uri.
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
      \return The prefix of the nodes name, this will be the namespace for the node.
    */
    function prefix()
    {
        return $this->Prefix;
    }

    /*!
      Sets the namespace prefix for this node to \a $value.
    */
    function setPrefix( $value )
    {
        $this->Prefix = $value;
    }

    /*!
      \return An integer value which describes the type of node.

      The type is one of:
      - 1 - Element node, that is a node which contains attributes and children.
      - 2 - Attribute node, this is a node which contains a name and a value.
      - 3 - Text node, this is a node which contains a text string
      - 4 - CDATA node, this is a node which contains a text string
    */
    function type()
    {
        return $this->Type;
    }

    /*!
      Sets the node type to \a $type.

      Use one of the following defines for the type:
      - EZ_XML_NODE_ELEMENT - Element nodes
      - EZ_XML_NODE_ATTRIBUTE - Attribute nodes
      - EZ_XML_NODE_TEXT - Text nodes
      - EZ_XML_NODE_CDATASECTION - CDATA nodes
    */
    function setType( $type )
    {
        $this->Type = $type;
    }

    /*!
      \return The content of the node or \c false if it does not contain any content.

      \note This will only make sense for text and CDATA nodes.
    */
    function &content()
    {
        return $this->Content;
    }

    /*!
      Sets the content of the node to the \a $content.

      \note This will only make sense for text and CDATA nodes.
    */
    function setContent( $content )
    {
        $this->Content = $content;
    }

    /*!
      \return An array with attribute nodes.

      \note This will only make sense for element nodes.
    */
    function &attributes()
    {
        return $this->Attributes;
    }

    /*!
      \return An array with attribute nodes matching the namespace URI \a $namespaceURI.

      \note This will only make sense for element nodes.
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

      \note This will only make sense for element nodes.
    */
    function hasAttributes()
    {
        return count( $this->Attributes ) > 0;
    }

    /*!
      \return The number of attributes for the node.

      \note This will only make sense for element nodes.
    */
    function attributeCount()
    {
        return count( $this->Attributes );
    }

    /*!
      \return An array with child nodes.

      \note This will only make sense for element nodes.
    */
    function &children()
    {
        return $this->Children;
    }

    /*!
      \return \c true if the node has children.

      \note This will only make sense for element nodes.
    */
    function hasChildren()
    {
        return count( $this->Children ) > 0;
    }

    /*!
      \return The number of children for the node.

      \note This will only make sense for element nodes.
    */
    function childrenCount()
    {
        return count( $this->Children );
    }

    /*!
     \return first child of current dom node.

     \note added for compatibility with DOM XML library
    */
    function first_child()
    {
        return isset( $this->Children[0] ) ? $this->Children[0] : null;
    }

    /*!
     \return node value of current dom node.

     \note added for compatibility with DOM XML library
    */
    function node_value()
    {
        return $this->Content;
    }

    /*!
      Finds the first element named \a $name and returns the children of that node.
      If no element node is found it returns \c false.

      \note This will only make sense for element nodes.
      \note If multiple elements with that name is found \c false is returned.
      \sa elementByName, children
    */
    function &elementChildrenByName( $name )
    {
        $element =& $this->elementByName( $name );
        if ( !$element )
        {
            $children = false;
            return $children;
        }
        return $element->children();
    }

    /*!
      Finds the first element named \a $name and returns the first child of that node.
      If no element node is found or there are not children it returns \c false.

      \note This will only make sense for element nodes.
      \note If multiple elements with that name is found \c false is returned.
      \sa elementByName, firstChild
    */
    function &elementFirstChildByName( $name )
    {
        $element =& $this->elementByName( $name );
        if ( !$element )
        {
            $child = false;
            return $child;
        }
        return $element->firstChild();
    }

    /*!
      \deprecated Use firstElementByName() instead.
      \returns The first element that is named \a $name.
               If multiple elements with that name is found \c false is returned.

      \note This will only make sense for element nodes.
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
                {
                    $retValue = false;
                    return $retValue;
                }
                $element =& $child;
            }
        }
        return $element;
    }

    /*
    \returns The first element that is named \a $name.
               If multiple elements with that name is found \c false is returned.

      \note This will only make sense for element nodes.
    */
    function &firstElementByName( $name )
    {
        $element = false;
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $name && !$child->prefix() )
            {
                $element =& $child;
                break;
            }
        }
        return $element;
    }

    /*!
     Alias for libxml compatibility
    */
    function get_elements_by_tagname( $name )
    {
        $elements = array();
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $name )
                $elements[] =& $child;
        }

        return $elements;
    }

    /*!
      Finds the first element named \a $name and returns the text content of that node.
      If no element node is found or no text content exists it returns \c false.

      \note This will only make sense for element nodes.
      \note If multiple elements with that name is found \c false is returned.
      \sa elementByName, textContent
    */
    function elementTextContentByName( $name )
    {
        $element = $this->elementByName( $name );
        if ( !$element )
        {
            return false;
        }

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

        unset( $child );
        $child = false;
        return $child;
    }

    /*!
      \deprecated Use getElementsByTagName/getElementsByTagNameNS instead.
      \return An array with elements that matches the name \a $name.

      \note This will only make sense for element nodes.
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
      \return An array with text contents taken from all child nodes which matches the name \a $name.

      \note This will only make sense for element nodes.
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
                $elements[] = $child->textContent();
            }
        }
        return $elements;
    }

    /*!
      \deprecated This function is deprecated.
                  Use getAttribute instead.

      \return The value of the attribute named \a $attributeName.
      If no value is found \c false is returned.

      \note This will only make sense for element nodes.
    */
    function attributeValue( $attributeName )
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
      Alias for libxml compatibility
    */
    function get_attribute( $attributeName )
    {
        return $this->attributeValue( $attributeName );
    }

    /*!
      Finds the first element named \a $name and returns the value of the attribute named \a $attributeName.
      If no element node is found or no attribute with the given name exists it returns \c false.

      \note This will only make sense for element nodes.
      \note If multiple elements with that name is found \c false is returned.
      \sa elementByName, attributeValue
    */
    function elementAttributeValueByName( $name, $attributeName )
    {
        $element = $this->elementByName( $name );
        if ( !$element )
            return false;
        else
            return $element->attributeValue( $attributeName );
    }

    /*!
      Goes trough all attributes of the node and matches the attribute names
      with the parameter \a $attributeDefinitions.

      \param $attributeDefinitions An associative array which maps from matching attribute name to lookup name.
      \param $defaultValue If other value than \c null it will be set as value for all lookup names that didn't match

      The matching attribute name in the will be matched against the attributes of the node.
      When a match is found the attribute value will be fetched and placed in the returned
      associative array using lookup name as key.

      A code example will explain this, the variable \a $songNode contains the following xml
      \code
      <song name="Shine On You Crazy Diamond" track="1" />
      \endcode

      The PHP code is.
      \code
      $def = array( 'name' => 'song_name',
                    'track' => 'track_number' );
      $values = $songNode->attributeValues( $def );
      \endcode

      \a $values will now contain.
      \code
      array( 'song_name' => 'Shine On You Crazy Diamond',
             'track_number => '1' )
      \endcode

      This method and appendAttributes() work together, the values inserted with appendAttributes()
      can be extracted with this method.

      \note This will only make sense for element nodes.
      \sa elementAttributeValueByName, appendAttributes
    */
    function attributeValues( $attributeDefinitions = false, $defaultValue = null )
    {
        $hash = array();
        foreach ( $this->Attributes as $attribute )
        {
            if ( $attributeDefinitions === false )
            {
                $hash[$attribute->name()] = $attribute->content();
                continue;
            }

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
      \deprecated This function is deprecated.
                  Use getAttributeNS instead.

      \return The value of the attribute named \a $attributeName and having namespace \a $namespaceURI.
      If no value is found \c false is returned.

      \note This will only make sense for element nodes.
    */
    function attributeValueNS( $attributeName, $namespaceURI )
    {
        $returnValue = false;
        if ( count( $this->Attributes  ) > 0 )
        {
            foreach ( $this->Attributes as $attribute )
            {
                if ( $attribute->name() == $attributeName &&
                     $attribute->namespaceURI() == $namespaceURI )
                {

                    $returnValue = $attribute->content();
                }
            }
        }

        return $returnValue;
    }

    /*!
      Appends the node \a $node as a child of the current node.

      \return The node that was just inserted or \c false if it failed to insert a node.

      \note This will only make sense for element nodes.
    */
    function appendChild( &$node )
    {
        if ( get_class( $node ) == "ezdomnode" )
        {
            if ( $this->parentNode !== false )
                $node->parentNode =& $this;

            $this->Children[] =& $node;
            return $node;
        }
        return false;
    }

    /*!
     Alias for libXML compatibility
    */
    function append_child( &$node )
    {
        return $this->appendChild( $node );
    }

    /*!
      Appends the attribute node \a $node as an attribute of the current node.

      \return The attribute node that was just inserted or \c false if it failed to insert an attribute.

      \note This will only make sense for element nodes.
    */
    function appendAttribute( &$node )
    {
        if ( get_class( $node ) == "ezdomnode" )
        {
            $this->Attributes[] =& $node;
            return $node;
        }
        return false;
    }

    function set_attribute( $name, $value )
    {
        $this->removeNamedAttribute( $name );
        return $this->appendAttribute( eZDOMDocument::createAttributeNode( $name, $value ) );
    }

    /*!
      Appends multiple attributes and attribute values.

      \param $attributeValues An associative array containing the attribute values to insert,
                              it maps from lookup name to attribute value.
      \param $attributeDefinitions An associative array defining how lookup names maps to attribute names,
                                   the array key is the attribute name and the array value the lookup name.
      \param $includeEmptyValues If \c true it will set attribute values even though they don't exist in \a $attributeValues

      \code
      $definition = array( 'name' => 'song_name',
                           'track' => 'track_name' );
      $values = array( 'song_name' => 'Shine On You Crazy Diamond',
                       'track_number' => '1' );
      $node->appendAttributes( $values, $definition );
      \endcode

      The node will then look like.
      \code
      <song name="Shine On You Crazy Diamond" track="1" />
      \endcode

      This method and attributeValues() work together, the returned result of attributeValues()
      can be inserted with this method.

      \note This will only make sense for element nodes.
      \sa attributeValues
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
                $this->Attributes[] = eZDOMDocument::createAttributeNode( $attributeXMLName, $value );
            }
        }
    }

    /*!
      Removes the attribute node named \a $name.
      \return The removed attribute node or \c false if no such node exists.

      \note This will only make sense for element nodes.
    */
    function removeNamedAttribute( $name )
    {
        $removed = false;
        foreach( array_keys( $this->Attributes ) as $key )
        {
            if ( $this->Attributes[$key]->name() == $name )
            {
                unset( $this->Attributes[$key] );
                $removed = true;
            }
        }
        return $removed;
    }

    /*!
     Alias for libxml compatibility
    */
    function remove_attribute( $name )
    {
        return $this->removeNamedAttribute( $name );
    }

    /*!
      Removes all attribute from the node.

      \note This will only make sense for element nodes.
    */
    function removeAttributes()
    {
        $this->Attributes = array();
    }

    /*!
      Removes all child nodes that matches the name \a $name.
      \return \c true if it removed any nodes, otherwise \c false.

      \note This will only make sense for element nodes.
    */
    function removeNamedChildren( $name )
    {
        $removed = false;
        foreach( array_keys( $this->Children ) as $key )
        {
            if ( $this->Children[$key]->name() == $name )
            {
                if ( $this->parentNode !== false )
                {
                    unset( $this->Children[$key]->parentNode );
                    $this->Children[$key]->parentNode = null;
                }
                unset( $this->Children[$key] );
                $removed = true;
            }
        }
        return $removed;
    }

    /*!
      Removes all child nodes from the current node.

      \note This will only make sense for element nodes.
    */
    function removeChildren()
    {
        if ( $this->parentNode !== false )
        {
            foreach( array_keys( $this->Children ) as $key )
            {
               unset( $this->Children[$key]->parentNode );
               $this->Children[$key]->parentNode = null;
            }
        }

        $this->Children = array();
    }

    /*!
      Removes the last child node of the current node.

      \note This will only make sense for element nodes.
    */
    function removeLastChild()
    {
        end( $this->Children );
        $key = key( $this->Children );
        if ( $this->parentNode !== false )
        {
            unset( $this->Children[$key]->parentNode );
            $this->Children[$key]->parentNode = null;
        }

        unset( $this->Children[$key] );
    }

    /*!
      Removes child by the given child object.

      \note W3C DOM function
    */
    function removeChild( &$childToRemove )
    {
        if ( $childToRemove->parentNode !== false )
        {
            unset( $childToRemove->parentNode );
            $childToRemove->parentNode = null;
        }
        $childToRemove->flag = true;

        foreach ( array_keys( $this->Children ) as $key )
        {
            if ( $this->Children[$key]->flag === true )
            {
                unset( $this->Children[$key] );
                break;
            }
        }
        $childToRemove->flag = false;
    }

    /*!
      \return The content() of the first child node or \c false if there are no children.

      \note This will only make sense for element nodes.
      \sa elementTextContentByName
    */
    function textContent()
    {
        return $this->collectTextContent( $this );
    }

    function collectTextContent( &$element )
    {
        $ret = '';
        if ( $element->Type == EZ_XML_NODE_TEXT or
             $element->Type == EZ_XML_NODE_CDATASECTION )
        {
            $ret = $element->content();
        }
        else
        {
            if ( count( $element->Children ) > 0 )
            {
                foreach( array_keys( $element->Children ) as $key )
                {
                    $child =& $element->Children[$key];
                    $ret .= $this->collectTextContent( $child );
                }
            }
        }
        return $ret;
    }

    /*!
      \return A string that represents the current node.
      The string will be created according to the node type which are:
      - Element node, places the name in <>, expands all attributes and calls toString() on all children.
      - Text node, returns the content() by escaping the characters & < > ' and ".
      - CDATA node, returns the text wrapped in <![CDATA[ and ]]

      \param $level The current tab level, starts at 0 and is increased by 1 for each recursion
      \param $charset Which charset the text will be encoded in, currently not used

      Example strings.
      \code
      '<song name="Shine On You Crazy Diamond" track="1" />'
      'This &amp; that &quot;wrapped&quot; in &lt;div&gt; tags'
      '<![CDATA[This & that "wrapped" in <div> tags'
      \endcode

      \note This will only make sense for element nodes.
    */
    function toString( $level, $charset = false, $convertSpecialChars = true )
    {
        $spacer = str_repeat( " ", $level*2 );
        $ret = "";
        switch ( $this->Name )
        {
            case "#text" :
            {
                $tagContent = $this->Content;
                // convert special chars
                if ( $convertSpecialChars )
                {
                    $tagContent = str_replace( "&", "&amp;", $tagContent );
                    $tagContent = str_replace( ">", "&gt;", $tagContent );
                    $tagContent = str_replace( "<", "&lt;", $tagContent );
                    $tagContent = str_replace( "'", "&apos;", $tagContent );
                    $tagContent = str_replace( '"', "&quot;", $tagContent );
                }

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
                        $attrContent = str_replace( "&", "&amp;", $attrContent );
                        $attrContent = str_replace( ">", "&gt;", $attrContent );
                        $attrContent = str_replace( "<", "&lt;", $attrContent );
                        $attrContent = str_replace( "'", "&apos;", $attrContent );
                        $attrContent = str_replace( '"', "&quot;", $attrContent );

                        $attrStr .=  $attrPrefix . $attr->name() . "=\"" . $attrContent . "\"";
                        ++$i;
                    }
                }

                if ( $isOneLiner )
                    $oneLinerEnd = " /";
                else
                    $oneLinerEnd = "";

                $ret = '';

                if ( $this->Name =='link' )  //don't insert enything before <link> tag
                {
                    $ret .= "<" . $prefix . $this->Name . $attrStr . $oneLinerEnd . ">";
                }
                else //make alignment
                {
                    if ( $level > 0 )
                        $ret .= "\n";
                    $ret .= "$spacer<" . $prefix . $this->Name . $attrStr . $oneLinerEnd . ">";
                }

                $lastChildType = false;
                if ( count( $this->Children ) > 0 )
                {
                    foreach ( $this->Children as $child )
                    {
                        $ret .= $child->toString( $level + 1, $charset, $convertSpecialChars );
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

    /*!
     Alias for libxml compatibility
    */
    function dump_mem( $format, $charset = false )
    {
        return $this->toString( 0, $charset);
    }


    /*
        W3C DOM compatibility functions
    */
    // \note W3C DOM function

    function setAttribute( $name, $value )
    {
        foreach ( $this->Attributes as $attribute )
        {
            if ( $attribute->name() == $name )
            {
                $attribute->setContent( $value );
                return $attribute;
            }
        }

        $attr = eZDOMDocument::createAttribute( $name );
        $attr->setContent( $value );
        return $this->appendAttribute( $attr );
    }

    // \note W3C DOM function

    function setAttributeNS( $namespaceURI, $qualifiedName, $value )
    {
        foreach ( $this->Attributes as $attribute )
        {
            if ( !$attribute->Prefix )
                continue;

            $fullName = $attribute->Prefix . ':' . $attribute->LocalName;
            if ( $fullName == $qualifiedName )
            {
                $attribute->setContent( $value );
                return $attribute;
            }
        }
        $attr = eZDOMDocument::createAttributeNS( $namespaceURI, $qualifiedName );
        $attr->setContent( $value );
        return $this->appendAttribute( $attr );
    }

    // \note W3C DOM function
    function getAttribute( $attributeName )
    {
        $returnValue = '';
        foreach ( $this->Attributes as $attribute )
        {
            if ( $attribute->name() == $attributeName && !$attribute->Prefix )
                $returnValue = $attribute->Content;
        }

        return $returnValue;
    }

    // \note W3C DOM function
    function getAttributeNS( $namespaceURI, $localName )
    {
        $returnValue = '';
        foreach ( $this->Attributes as $attribute )
        {
            if ( $attribute->LocalName == $localName &&
                 $attribute->NamespaceURI == $namespaceURI )
                $returnValue = $attribute->Content;
        }

        return $returnValue;
    }

    // \note W3C DOM function
    function removeAttribute( $name )
    {
        $removed = false;
        foreach( array_keys( $this->Attributes ) as $key )
        {
            if ( $this->Attributes[$key]->name() == $name && !$this->Attributes[$key]->Prefix )
            {
                unset( $this->Attributes[$key] );
                $removed = true;
            }
        }
        return $removed;
    }

    // \note W3C DOM function
    function removeAttributeNS( $namespaceURI, $localName )
    {
        $removed = false;
        foreach( array_keys( $this->Attributes ) as $key )
        {
            if ( $this->Attributes[$key]->LocalName == $localName &&
                 $this->Attributes[$key]->NamespaceURI == $namespaceURI )
            {
                unset( $this->Attributes[$key] );
                $removed = true;
            }
        }
        return $removed;
    }

    /*
      \note W3C DOM function
    */
    function hasChildNodes()
    {
        return count( $this->Children ) > 0;
    }

    /*!
      \return The first child of the node or \c false if there are no children.

      \note This will only make sense for element nodes.
      \note W3C DOM function
    */

    function &firstChild()
    {
        if ( count( $this->Children ) == 0 )
        {
            $child = false;
            return $child;
        }
        reset( $this->Children );
        $key = key( $this->Children );
        $child =& $this->Children[$key];

        return $child;
    }

    /*!
     \return The last child node or \c false if there are no children.

      \note This will only make sense for element nodes.
    */

    function &lastChild()
    {
        if ( count( $this->Children ) == 0 )
        {
            $child = false;
            return $child;
        }
        end( $this->Children );
        $key = key( $this->Children );
        $child =& $this->Children[$key];

        return $child;
    }

    /*!
      Replaces child by the new one given.

      \note W3C DOM function
    */
    function replaceChild( &$newChild, &$oldChild )
    {
        if ( $this->parentNode !== false )
        {
            unset( $oldChild->parentNode );
            $oldChild->parentNode = null;
        }
        $oldChild->flag = true;

        $newChildren = array();

        foreach( array_keys( $this->Children ) as $key )
        {
            if ( $this->Children[$key]->flag === true )
            {
                if ( $this->parentNode !== false )
                    $newChild->parentNode =& $this;

                $newChildren[$key] =& $newChild;
            }
            else
            {
                $newChildren[$key] =& $this->Children[$key];
            }
        }
        $this->Children =& $newChildren;
        $oldChild->flag = false;

        return $oldChild;
    }

    /*!
      Replaces child by the new one given.

      \note W3C DOM function
    */
    function insertBefore( &$newNode, &$refNode )
    {
        $refNode->flag = true;

        $newChildren = array();

        foreach ( array_keys( $this->Children ) as $key )
        {
            if ( $this->Children[$key]->flag === true )
            {
                $newChildren[] =& $newNode;
                if ( $this->parentNode !== false )
                {
                    $newNode->parentNode =& $this;
                }
            }
            $newChildren[] =& $this->Children[$key];
        }
        $this->Children =& $newChildren;
        $refNode->flag = false;
        return $newNode;
    }

    /*!
      \note emulation of W3C DOM property
    */

    function &nextSibling()
    {
        $ret = null;
        if ( !$this->parentNode )
            return $ret;

        $parent =& $this->parentNode;
        $this->flag = true;

        $next = false;
        $children =& $parent->Children;

        foreach( array_keys( $children ) as $child_key )
        {
            if ( $next )
            {
                $ret =& $children[$child_key];
                break;
            }
            elseif ( $children[$child_key]->flag === true )
            {
                $next = true;
            }
        }
        $this->flag = false;

        return $ret;
    }

   /*!
      \note emulation of W3C DOM property
    */

    function &previousSibling()
    {
        $ret = null;
        if ( !$this->parentNode )
            return $ret;

        $parent =& $this->parentNode;
        $this->flag = true;

        $prev = false;
        $children =& $parent->Children;
        foreach( array_keys( $children ) as $child_key )
        {
            if ( $prev !== false && $children[$child_key]->flag === true )
            {
                $ret =& $children[$prev];
                break;
            }
            $prev = $child_key;
        }
        $this->flag = false;
        return $ret;
    }

    /*!
      \note W3C DOM function
    */

    function getElementsByTagName( $name )
    {
        $elements = array();
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $name && !$child->prefix() )
                $elements[] =& $child;
        }

        return $elements;
    }

    /*!
      \note W3C DOM function
    */
    function getElementsByTagNameNS( $namespaceURI, $localName )
    {
        $elements = array();
        foreach ( array_keys( $this->Children ) as $key )
        {
            $child =& $this->Children[$key];
            if ( $child->name() == $localName && $child->namespaceURI() == $namespaceURI )
                $elements[] =& $child;
        }

        return $elements;
    }

    /*!
      Outputs DOM subtree to the debug output in the easy readable form.

      \param node  subtree root node
    */

    function writeDebug( &$node, $text, $showAttributes = false, $showParent = false )
    {
        if ( !$node )
            $node =& $this;

        if ( $node )
        {
            if ( get_class( $node ) == 'ezdomnode' )
            {
                $d = eZDOMNode::debugNode( $node, $showAttributes, $showParent );
                eZDebug::writeDebug( $d, $text );
            }
            else
                eZDebug::writeDebug( $node, $text );
        }
        else
        {
            eZDebug::writeDebug( array( $node ), $text );
        }
    }

    function debugNode( &$node, $showAttributes, $showParent )
    {
        $d = array();
        $d['name'] = $node->nodeName;
        if ( $node->nodeName == '#text' )
            $d['text'] = $node->content;
        else if ( $node->Type == 2 )
            $d['value'] = $node->value;

        if ( $showParent )
           $d['parent'] = $node->parentNode->nodeName;

        if ( count( $node->Children ) )
        {
            $d['children'] = array();
            foreach( array_keys($node->Children) as $child_key )
            {
                $d['children'][] = eZDOMNode::debugNode( $node->Children[$child_key], $showAttributes, $showParent );
            }
        }

        if ( $showAttributes && count( $node->Attributes ) )
        {
            $d['attributes'] = array();
            foreach( array_keys($node->Attributes) as $attr_key )
            {
                $d['attributes'][] = eZDOMNode::debugNode( $node->Attributes[$attr_key], $showAttributes, $showParent );
            }
        }
        return $d;
    }

    /*!
      Outputs XML from DOM as a string.

      \param node  subtree root node
    */
    function writeDebugStr( &$node, $text )
    {
        if ( is_object( $node ) )
            eZDebug::writeDebug( $node->toString( 0 ), $text );
        else
            eZDebug::writeDebug( $node, $text );
    }

    /// \privatesection

    /// Name of the node
    var $Name = false;

    /// tagname, added for DOM XML compatibility.
    var $tagname = null;

    /// DOM W3C compatibility
    var $nodeName = null;

    /// Type of the DOM node. ElementNode=1, AttributeNode=2, TextNode=3, CDATASectionNode=4
    var $type;
    var $Type = EZ_XML_NODE_ELEMENT;

    /// Content of the node
    var $content = "";
    var $Content = "";
    var $value = '';

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

    /// Reference to the parent node.
    ///  Available only if Document has been created with parameter $setParentNode = true
    ///  or parsed with eZXML::domTree function with $params["SetParentNode"] = true
    var $parentNode = false;

    // temporary flag to mark node
    var $flag = false;
}

?>
