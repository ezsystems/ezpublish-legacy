<?php
//
// Definition of eZAuthor class
//
// Created on: <19-Aug-2002 10:52:01 bf>
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
  \class eZAuthor ezauthor.php
  \ingroup eZKernel
  \brief eZAuthor handles author lists

  \code

  include_once( "kernel/classes/datatypes/ezauthor/ezauthor.php" );

  $author = new eZAuthor( "Colour" );
  $author->addValue( "Red" );
  $author->addValue( "Green" );

  // Serialize the class to an XML document
  $xmlString =& $author->xmlString();

  \endcode
*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZAuthor
{
    /*!
    */
    function eZAuthor( )
    {
        $Authors = array();
        $this->AuthorCount = 0;
    }

    /*!
     Sets the name of the author
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the author set.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     Adds an author
    */
    function addAuthor( $name, $email )
    {
        $this->Authors[] = array( "id" => $this->AuthorCount,
                                  "name" => $name,
                                  "email" => $email,
                             "is_default" => false );

        $this->AuthorCount += 1;
    }

    function removeAuthors( $array_remove )
    {
        $authors =& $this->Authors;
        $shiftvalue = 0;
        foreach ( $array_remove as $id )
        {
            array_splice( $authors, $id - $shiftvalue, 1 );
            $shiftvalue++;
//            eZDebug::writeNotice( $authors, "authors " . $shiftvalue );
        }

        $this->AuthorCount -= $shiftvalue;
    }

    function hasAttribute( $name )
    {
        if ( ( $name == "author_list" ) || ( $name == "name" ) )
            return true;
        else
            return false;
    }

   function &attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "author_list" :
            {
                return $this->Authors;
            }break;
        }
    }

    /*!
     Will decode an xml string and initialize the eZ author object
    */
    function decodeXML( $xmlString )
    {
        $xml = new eZXML();
        $dom =& $xml->domTree( $xmlString );

        if ( $dom )
        {
            $authorArray =& $dom->elementsByName( "author" );
            foreach ( $authorArray as $author )
            {
                $this->addAuthor( $author->attributeValue( "name" ), $author->attributeValue( "email" ) );
            }
        }
        else
        {
        }
    }

    /*!
     Will return the XML string for this author set.
    */
    function &xmlString( )
    {
        $doc = new eZDOMDocument( "Author" );

        $root =& $doc->createElementNode( "ezauthor" );
        $doc->setRoot( $root );

        $authors =& $doc->createElementNode( "authors" );

        $root->appendChild( $authors );
        $id=0;
        foreach ( $this->Authors as $author )
        {
            $authorNode =& $doc->createElementNode( "author" );
            $authorNode->appendAttribute( $doc->createAttributeNode( "id", $id++ ) );
            $authorNode->appendAttribute( $doc->createAttributeNode( "name", $author["name"] ) );
            $authorNode->appendAttribute( $doc->createAttributeNode( "email", $author["email"] ) );

            $authors->appendChild( $authorNode );
        }

        $xml =& $doc->toString();

        return $xml;
    }

    /// Contains the Authors
    var $Authors;

    /// Contains the author counter value
    var $AuthorCount;
}

?>
