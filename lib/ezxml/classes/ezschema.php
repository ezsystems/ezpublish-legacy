<?php
//
// $Id$
//
// Definition of eZSchema class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Feb-2002 09:15:42 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZSchema ezschema.php
  \ingroup eZXML
  \brief eZSchema handles schema validation on dom documents

*/

require_once( "lib/ezutils/classes/ezdebug.php" );

class eZSchema
{
    /*!
      Constructs a new schema element.
    */
    function eZSchema( )
    {
    }

    /*!
      Sets the schema from a text file or a URL.
    */
    function setSchemaFromFile( $url )
    {
        $fp = fopen( $url, "rb" );
        $doc = fread( $fp, filesize( $url ) );
        fclose( $fp );
        $this->setSchema( $doc );
    }

    /*!
      Reads the schema and builds the schema tree.
    */
    function setSchema( &$schemaDocument )
    {
        $xml = new eZXML();
        $schemaDom = $xml->domTree( $schemaDocument );

        $schemaRoot = $schemaDom->root();

        $this->RootPrefix = $schemaRoot->prefix();
        print( "default namespace prefix. " . $schemaRoot->prefix() . "<br>" );

        $registeredTypes = array();
        $usedTypes = array();

        $i = 0;
        // check that this is a schema definition
        if ( $schemaRoot->name() == "schema" )
        {
            foreach ( $schemaRoot->children() as $schemaNode )
            {
                // register types
                $element =& $this->parseElement( $schemaNode );

                if ( $element instanceof eZSchemaElement )
                {
                    if ( $i == 0 )
                    {
                        print( "validation root found:  ");
                        $this->ValidationRoot =& $element;
                    }

//                    $this->Elements[] =& $element;
                    $i++;
                }
            }
        }
    }

    /*!
      \private
      Parses the given dom tree part and returns an element object.
    */
    function &parseElement( &$schemaNode, $parentElement=false )
    {
        $ret = false;
//        if ( $schemaNode->name() == "element" )
        {
            $element = new eZSchemaElement();
            $element->setParent( $parentElement );
            $element->setName( $schemaNode->attributeValue( "name" ) );

            // set the next reference in the parent element
            if ( $parentElement instanceof eZElement )
                $parentElement->setNext( $element );

            $minOccurs = $schemaNode->attributeValue( "minOccurs" );
            $element->setMinOccurs( $minOccurs );

            $maxOccurs = $schemaNode->attributeValue( "maxOccurs" );
            $element->setMaxOccurs( $maxOccurs );

            // get element type:

            // check attributes for type def
            $type = $schemaNode->attributeValue( "type" );
            $ref = $schemaNode->attributeValue( "ref" );

            if ( $schemaNode->name() == 'element' )
            {
                // check for standard xml schema types
                if ( strpos( $type, $this->RootPrefix . ":" ) === 0 )
                {
                    $typeName = substr( $type, strlen( $this->RootPrefix . ":" ),  strlen( $type ) - strlen( $this->RootPrefix . ":" ) );
                    print( "Standard type found: " . $typeName . "<br>" );
                    $element->setDataType( $typeName );
                }
                else
                {
                    // complex type
                    $element->setDataType( $type );
                }
            }
            else if ( $schemaNode->name() == 'complexType' )
            {
                // complex type definition
                print( "complex def..<br/>" );
            }
            else if ( $ref != false )
            {
                // reference element
                print( "reference element found: $ref<br>" );
                $element->setName( $ref );
                $element->setReference( $ref );
            }
            else
            {
                print( "no attribute type def. found. Checking children...<br>" );
                // check for complex type
                foreach ( $schemaNode->children() as $typeElement )
                {
                    if ( $typeElement->name() == "complexType" )
                    {
                        print( $typeElement->name() . " found <br>" );

                        foreach ( $typeElement->children() as $listElement )
                        {
                            print( $listElement->name() . "<br>" );
                            switch ( $listElement->name() )
                            {
                                case "sequence" :
                                {
                                    foreach ( $listElement->children() as $seqElement )
                                    {
                                        $subElement =& $this->parseElement( $seqElement, $element );

                                        if ( $subElement instanceof eZSchemaElement )
                                            $element->Children[] = $subElement;

                                    }

                                }break;
                            }

                        }
                    }
                }

            }

            $ret = $element;
            print( "new element found: ". $element->name() . "<br>" );

            $this->Elements[$element->name()] =& $element;
        }

        return $ret;
    }

    /*!
      Validates the eZDOMDocument with the schema.
    */
    function validate( $dom )
    {
        $root =& $dom->root();

        $schemaRoot =& $this->ValidationRoot;
        print( "Validating document: " . $root->name() . "<br>" );

        if ( $schemaRoot->name() == $root->name() )
        {
            $this->validateNode( $root, $schemaRoot );
        }
        else
        {
            print( "Error: document root mismatch." );
        }
    }

    /*!
      \private
      Validates a DOM node.
    */
    function validateNode( &$domNode, &$schemaElement )
    {
        print( "Validating: " . $domNode->name() . "<br>" );

        print( "Comparing: " . $domNode->name() . " against " .
               $schemaElement->name() . " <br>" );

        if ( $domNode->name() == $schemaElement->name() )
        {
            // find the next schema element
            $refElement = false;
            if ( $schemaElement->type() == "reference" )
            {
                if ( isset( $this->Elements[$element->name()] ) )
                {
                    $refElement =& $this->Elements[$element->name()];
                }
                else
                    print( "reference not found<br>" );
            }

            foreach ( $domNode->children() as $subNode )
            {
                $this->validateNode( $subNode, $schemaElement );
                // print( $subNode->name() );
            }
        }
        else
        {
            print( "Error validating document" . $domNode->name() );
        }

    }

    /*!
      Debug function to print the document tree.
     */
    function printTree( $dom )
    {
        $level = 1;
        print( "<br>Document structure for: ". $this->ValidationRoot->name() . "<br>" );
        $children = $this->ValidationRoot->children();
        $childrenKeys = array_keys( $children );
        foreach ( $childrenKeys as $key )
        {
            $this->printElement( $children[$key], $dom->root(), $level );
        }
    }

    /*!
      \private
      Debug function. Prints the element information.
    */
    function printElement( $element, &$dom, $level )
    {
        $spacer = str_repeat( "&nbsp;", $level*4 );

        // fetch reference, if any
        $refElement = false;
        if ( $element->type() == "reference" )
        {
            if ( isset( $this->Elements[$element->name()] ) )
            {
                $refElement =& $this->Elements[$element->name()];
            }
            else
                print( "reference not found<br>" );
        }

        if ( $refElement )
            $tmpElement =& $refElement;
        else
            $tmpElement =& $element;

        print( "$spacer Looking for element: " .
               $tmpElement->name() .
               " " . $tmpElement->dataType() .
               " " . $element->minOccurs() .
               "->" . $element->maxOccurs() .
               "<br>" );

        $currentElementName = $tmpElement->name();

        $counter = 0;
        foreach ( $dom->children() as $domNode )
        {
            if ( $domNode->name() == $currentElementName )
            {
                // validate children

                foreach ( $tmpElement->children() as $child )
                {
                    $this->printElement( $child, $domNode, $level + 1 );
                }

                $counter++;
            }
            else
            {
//                print( "Unknown tag: " . $domNode->name() . " expecting $currentElementName <br> " );
            }
        }

        if ( $counter >= $element->minOccurs() )
        {
            if ( ( $counter <= $element->maxOccurs() ) or
                 ( $element->maxOccurs() == "unbounded" )
                 )
            {
                print( "Found $counter elements of $currentElementName , ok ! <br>" );
            }
            else
            {
                print( "<b>Error</b>: Element $currentElementName count NOT within range, too many elements: $counter  <br>" );
            }

        }
        else
            print( "<b>Error</b>: Element $currentElementName count NOT within range , to few items $counter <br>" );
    }

    /*!
     Debug function to print the schema tree.
    */
    function printSchemaTree()
    {
        print( "<br/><b>Schema Tree</b><br/>" );
        $this->printSchemaNode( $this->ValidationRoot, 0 );
        print( "<br/>" );
    }

    /*!

    */
    function printSchemaNode( $node, $level )
    {
        $spacer = str_repeat( "&nbsp;", $level*4 );
        print( $spacer . $node->name() . " " . $node->type() . "<br>" );

        foreach ( $node->children() as $childNode )
        {
            $this->printSchemaNode( $childNode, $level + 1 );
        }
    }

    /// Contains the validation root
    public $ValidationRoot = false;

    /// Contains the schema elements, elements are indexed by their name.
    public $Elements = array();

    /// Description or the schema
    public $Annotation = "";

    /// Contains the schema root namespace prefix
    public $RootPrefix;

}

?>
