<?php
//
// Definition of eZDOMDocument class
//
// Created on: <16-Nov-2001 12:18:23 bf>
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
  \class eZDOMDocument ezdomdocument.php
  \ingroup eZXML
  \brief eZDOMDocument handles DOM nodes in DOM documents

  \code
  $doc = new eZDOMDocument();
  $doc->setName( "FishCatalogue" );

  $root =& $doc->createElementNode( "FishCatalogue" );
  $doc->setRoot( $root );

  $freshWater =& $doc->createElementNode( "FreshWater" );
  $root->appendChild( $freshWater );

  $saltWater =& $doc->createElementNode( "SaltWater" );
  $root->appendChild( $saltWater );

  $guppy =& $doc->createElementNode( "Guppy" );
  $guppy->appendChild( $doc->createTextNode( "Guppy is a small livebreeder." ) );

  $freshWater->appendChild( $guppy );

  $cod =& $doc->createElementNode( "Cod" );
  $saltWater->appendChild( $cod );

  $cod->appendChild( $doc->createCDATANode( "A big dull fish <-> !!" ) );

  print( $doc->toString() );

  // will print the following
    <?xml version="1.0"?>
    <FishCatalogue>
      <FreshWater>
        <Guppy>
    Guppy is a small livebreeder.    </Guppy>
      </FreshWater>
      <SaltWater>
        <Cod>
    <![CDATA[A big dull fish <-> !!]]>    </Cod>
      </SaltWater>
    </FishCatalogue>

  \endcode
*/

include_once( "lib/ezxml/classes/ezdomnode.php" );

class eZDOMDocument
{
    /*!
      Creates a new DOM document object. You can provide a name for the document.
    */
    function eZDOMDocument( $name="")
    {
        $this->Name = $name;
    }

    /*!
      Sets the document name
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the document root if it exists.
      False is returned if the root does not exist.
    */
    function &root()
    {
        return $this->Root;
    }

    /*!
      Sets the document root.
    */
    function setRoot( &$node )
    {
        if ( get_class( $node ) == "ezdomnode" )
        {
            $this->Root =& $node;
        }
    }

    /*!
      Sets the document root.
    */
    function appendChild( &$node )
    {
        if ( get_class( $node ) == "ezdomnode" )
        {
            $this->Root =& $node;
        }
    }

    /*!
      Creates and returns a text node.
    */
    function &createTextNode( $text )
    {
        $node = new eZDOMNode();
        $node->setName( "#text" );
        $node->setContent( $text );
        $node->setType( 3 );

        return $node;
    }

    /*!
      Creates and returns a cdata node.
    */
    function &createCDATANode( $text )
    {
        $node = new eZDOMNode();
        $node->setName( "#cdata-section" );
        $node->setContent( $text );
        $node->setType( 4 );

        return $node;
    }

    /*!
      Creates and returns a element node
    */
    function &createElementNode( $name, $attributes = array() )
    {
        $node = new eZDOMNode();
        $node->setName( $name );
        $node->setType( 1 );
        foreach ( $attributes as $attributeKey => $attributeValue )
        {
            $node->appendAttribute( eZDomDocument::createAttributeNode( $attributeKey, $attributeValue ) );
        }

        return $node;
    }

    /*!
      Creates and returns a element node with a text node as child.
      \sa createTextNode, createElementNode
    */
    function &createElementTextNode( $name, $text, $attributes = array() )
    {
        $node =& eZDOMDocument::createElementNode( $name, $attributes );
        $textNode =& eZDOMDocument::createTextNode( $text );
        $node->appendChild( $textNode );

        return $node;
    }

    /*!
      Creates and returns a element node with a CDATA node as child.
      \sa createCDATANode, createElementNode
    */
    function &createElementCDATANode( $name, $text, $attributes = array() )
    {
        $node =& eZDOMDocument::createElementNode( $name, $attributes );
        $cdataNode =& eZDOMDocument::createCDATANode( $text );
        $node->appendChild( $cdataNode );

        return $node;
    }

    /*!
      Creates and returns a element node with a namespace URI
    */
    function &createElementNodeNS( $uri, $name )
    {
        $node = new eZDOMNode();
        $node->setNamespaceURI( $uri );
        $node->setName( $name );
        $node->setType( 1 );

        return $node;
    }

    /*!
      Creates and returns an attribute node
    */
    function &createAttributeNode( $name, $content, $prefix = false )
    {
        $node = new eZDOMNode();
        $node->setName( $name );
        if ( $prefix )
            $node->setPrefix( $prefix );
        $node->setContent( $content );
        $node->setType( 2 );

        return $node;
    }

    /*!
      Creates an namespace definition attribute
    */
    function &createAttributeNamespaceDefNode( $prefix, $uri )
    {
        $node = new eZDOMNode();
        $node->setName( $prefix );
        $node->setPrefix( "xmlns" );
        $node->setContent( $uri );
        $node->setType( 2 );

        return $node;
    }

    /*!
      Creates and returns an attribute node with a namespace URI
    */
    function &createAttributeNodeNS( $uri, $name, $content )
    {
        $node = new eZDOMNode();
        $node->setName( $name );
//        $node->setPrefix( $prefix );
        $node->setNamespaceURI( $uri );
        $node->setContent( $content );
        $node->setType( 2 );

        return $node;
    }

    /*!
      Returns a XML string of the DOM document
    */
    function &toString( $charset = true, $charsetConversion = true )
    {
        $charsetText = '';
        if ( $charset === true )
            $charset = 'UTF-8';
        if ( $charset !== false )
            $charsetText = " encoding=\"$charset\"";
        $text = "<?xml version=\"1.0\"$charsetText?>\n";

        if ( get_class( $this->Root ) == "ezdomnode" )
        {
            $text .= $this->Root->toString( 0, $charset );
        }

        if ( $charsetConversion )
        {
            include_once( 'lib/ezi18n/classes/eztextcodec.php' );
            $codec =& eZTextCodec::instance( false, $charset, false );
            if ( $codec )
            {
                $text =& $codec->convertString( $text );
            }
        }

        return $text;
    }

    /*!
      Registers the elements
    */
    function registerElement( &$node )
    {
        $this->NamedNodes[$node->name()][] =& $node;

        if ( $node->namespaceURI() != "" )
        {
            $this->NamedNodesNS[$node->name()][$node->namespaceURI()][] =& $node;
        }
    }

    /*!
      Returns all the elements with the given name by reference.
    */
    function &elementsByName( $name )
    {
        return $this->NamedNodes[$name];
    }

    /*!
      Returns all the elements with the given name and namespace URI by reference.
    */
    function &elementsByNameNS( $name, $namespaceURI )
    {
        return $this->NamedNodesNS[$name][$namespaceURI];
    }

    /*!
     Regsiter a new namespace alias. Provide the alias/prefix and the
     namespace identifier.
    */
    function registerNamespaceAlias( $alias, $namespace )
    {
        $this->Namespaces[$alias] =& $namespace;
    }

    /*!
     Returns the namespace which corresponds to the given alias.
     Returns false if the namespace is not known.
    */
    function &namespaceByAlias( $alias )
    {
        if ( isset( $this->Namespaces[$alias] ) )
            return $this->Namespaces[$alias];
        else
            return false;
    }

    /// Document name
    var $Name;

    /// XML version
    var $Version;

    /// Contains an array of reference to the named nodes
    var $NamedNodes = array();

    /// Contains an array of references to the named nodes with namespace
    var $NamedNodesNS = array();

    /// Contains an array of reference to the named nodes with namespace
    var $NamedNodes = array();

    /// Contains an array of the registered namespaces and their aliases
    var $Namespaces = array();

    var $Standalone;
    var $Type;

    /// Reference to the first child of the DOM document
    var $Root;
}

?>
