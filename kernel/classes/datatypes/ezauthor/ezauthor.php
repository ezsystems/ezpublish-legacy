<?php
//
// Definition of eZAuthor class
//
// Created on: <19-Aug-2002 10:52:01 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
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
  \class eZAuthor ezauthor.php
  \ingroup eZDatatype
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
    function addAuthor( $id, $name, $email )
    {
        if ( $id == -1 )
            $id = $this->Authors[$this->AuthorCount - 1]['id'] + 1;

        $this->Authors[] = array( "id" => $id,
                                  "name" => $name,
                                  "email" => $email,
                             "is_default" => false );

        $this->AuthorCount ++;
    }

    function removeAuthors( $array_remove )
    {
        $authors =& $this->Authors;

        if ( count( $array_remove ) > 0 )
            foreach ( $array_remove as $id )
            {
                foreach ( $authors as $authorKey => $author )
                {
                    if ( $author['id'] == $id )
                    {
                        array_splice( $authors, $authorKey, 1 );
                        $this->AuthorCount --;
                    }
                }
            }
    }

    function attributes()
    {
        return array( 'author_list', 'name', 'is_empty' );
    }

    function hasAttribute( $name )
    {
        if ( ( $name == "author_list" ) || ( $name == "name" ) || ( $name == "is_empty" ) )
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
            case "is_empty" :
            {
                return count( $this->Authors ) == 0 ;
            }break;
            case "author_list" :
            {
                return $this->Authors;
            }break;
        }
    }

    /*!
     \return a string which contains all the interesting meta data.

     The result of this method can passed to the search engine or other
     parts which work on meta data.

     The string will contain all the authors with their name and email.

     Example:
     \code
     'John Doe john@doe.com'
     \endcode
    */
    function metaData()
    {
        $data = '';
        foreach ( $this->Authors as $author )
        {
            $data .= $author['name'] . ' ' . $author['email'] . "\n";
        }
        return $data;
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
            $authorArray =& $dom->elementsByName( 'author' );
            if ( is_array( $authorArray ) )
            {
                foreach ( $authorArray as $author )
                {
                    $this->addAuthor( $author->attributeValue( "id" ), $author->attributeValue( "name" ), $author->attributeValue( "email" ) );
                }
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
        if ( is_array( $this->Authors ) )
        {
            foreach ( $this->Authors as $author )
            {
                $authorNode =& $doc->createElementNode( "author" );
                $authorNode->appendAttribute( $doc->createAttributeNode( "id", $id++ ) );
                $authorNode->appendAttribute( $doc->createAttributeNode( "name", $author["name"] ) );
                $authorNode->appendAttribute( $doc->createAttributeNode( "email", $author["email"] ) );

                $authors->appendChild( $authorNode );
            }
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
