<?php
//
// Definition of eZURLAlias class
//
// Created on: <01-Aug-2003 16:44:56 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file ezurlalias.php
*/

/*!
  \class eZURLAlias ezurlalias.php
  \brief Handles URL aliases in eZ publish

  URL aliases are different names for existing URLs in eZ publish.
  Using URL aliases allows for having better looking urls on the webpage
  as well as having fixed URLs pointing to various locations.

  This class handles storing, fetching, moving and subtree updates on eZ publish URL aliases.

  Creating a new alias is done by using the create() function and passing the correct parameters.
  Fetching an url can either be done with it's ID using fetch() or by it's URL string by using fetchBySourceURL.
  To fetch the original URL you must use the translate() function.
*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZURLAlias extends eZPersistentObject
{
    /*!
     Initializes a new URL alias.
    */
    function eZURLAlias( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "source_url" => array( 'name' => "SourceURL",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "source_md5" => array( 'name' => "SourceMD5",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "destination_url" => array( 'name' => "DestinationURL",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "is_internal" => array( 'name' => "IsInternal",
                                                                 'datatype' => 'integer',
                                                                 'default' => '0',
                                                                 'required' => true ),
                                         "forward_to_id" => array( 'name' => "ForwardToID",
                                                                   'datatype' => 'integer',
                                                                   'default' => '0',
                                                                   'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZURLAlias",
                      "name" => "ezurlalias" );
    }

    /*!
     \static
     Creates a new URL alias with the new URL \a $sourceURL and the original URL \a $destinationURL
     \param $isInternal decides if the url is internal or not (user created).
     \return the URL alias object
    */
    function &create( $sourceURL, $destinationURL, $isInternal = true )
    {
        $row = array( 'source_url' => $sourceURL,
                      'destination_url' => $destinationURL,
                      'is_internal' => $isInternal );
        $alias = new eZURLAlias( $row );
        return $alias;
    }

    /*!
     Generates the md5 for the alias and stores the values.
    */
    function store()
    {
        $this->SourceMD5 = md5( $this->SourceURL );
        eZPersistentObject::store();
    }

    /*!
     Removes this url alias as well as all other aliases that relate to it,
     for instance forwarding aliases.
     \note If you want to remove just this alias you must use remove()
    */
    function cleanup()
    {
        $id = $this->attribute( 'id' );
        $db =& eZDB::instance();
        $sql = "DELETE FROM ezurlalias WHERE forward_to_id = '" . $db->escapeString( $id ) . "' AND is_internal = '1'";
        $db->query( $sql );
        $this->remove();
    }

    /*!
     \static
     Makes sure all aliases which are children of the alias \a $oldPathString is updated
     to have the correct \a $newPathString.
    */
    function updateChildAliases( $newPathString, $oldPathString )
    {
        $oldPathStringLength = strlen( $oldPathString );
        $db =& eZDB::instance();
        $newPathStringText = $db->escapeString( $newPathString );
        $oldPathStringText = $db->escapeString( $oldPathString );
        $subStringQueryPart = $db->subString( 'source_url', $oldPathStringLength + 1 );
        $newPathStringQueryPart = $db->concatString( array( "'$newPathStringText'", $subStringQueryPart ) );
        $sql = "UPDATE ezurlalias
SET
    source_url = $newPathStringQueryPart
WHERE
    is_internal = 1 AND
    source_url LIKE '$oldPathStringText/%'";

        $db->query( $sql );

        $md5QueryPart = $db->md5( 'source_url' );
        $sql = "UPDATE ezurlalias
SET
    source_md5 = $md5QueryPart
WHERE
    source_url like '$oldPathStringText%'";

        $db->query( $sql );
    }

    /*!
     Updates all forwards urls that originally points to \a $oldForwardID
     to point to correct url \a $newForardID.
    */
    function updateForwardID( $newForwardID, $oldForwardID )
    {
        $db =& eZDB::instance();
        $oldForwardIDText = $db->escapeString( $oldForwardID );
        $newForwardIDText = $db->escapeString( $newForwardID );
        $sql = "UPDATE ezurlalias
SET
    forward_to_id = '$newForwardIDText'
WHERE
    forward_to_id = '$oldForwardIDText'";

        $db->query( $sql );
    }

    /*!
     \static
      Fetches the URL alias by ID.
    */
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLAlias::definition(),
                                                null,
                                                array( "id" => $id
                                                      ),
                                                $asObject );
    }

    /*!
     \static
      Fetches non-internal URL alias by offset and limit
    */
    function &fetchByOffset( $offset, $limit, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZURLAlias::definition(),
                                                    null,
                                                    array( "is_internal" => 0 ),
                                                    null,
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

    /*!
     \static
      Counts the non-internal URL alias
    */
    function &totalCount( )
    {
        $db =& eZDB::instance();
        $query = "SELECT count(id) AS count FROM ezurlalias WHERE is_internal = 0";
        $res = $db->arrayQuery( $query );
        return $res[0]['count'];
    }

    /*!
     \static
     Fetches an URL alias by souce URL, if no URL is found false is returned.
    */
    function &fetchBySourceURL( $source )
    {
        return eZPersistentObject::fetchObject( eZURLAlias::definition(),
                                                null, array( 'source_url' => $source ), true );
    }




    /*!
     \static
     Converts the path \a $urlElement into a new alias url which only conists of characters
     in the range a-z, numbers and _.
     All other characters are converted to _.
     \return the converted element
    */
    function convertToAlias( $urlElement )
    {

        $urlElement = strtolower( $urlElement );
        $urlElement = preg_replace( array( "#[^a-z0-9_ ]#" ,
                                           "/ /",
                                           "/__+/" ),
                                    array( "",
                                           "_",
                                           "_" ),
                                      $urlElement );
        return $urlElement;
    }




    /*!
     \static
     Converts the path \a $pathURL into a new alias path with limited characters.
     For more information on the conversion see convertToAlias().
     \note each element in the path (separated by / (slash) ) is converted separately.
     \return the converted path
    */
    function convertPathToAlias( $pathURL )
    {
        $result = array();

        $elements = explode( '/', $pathURL );

        foreach ( $elements as $element )
        {
            $element = eZURLAlias::convertToAlias( $element );
            $result[] = $element;
        }

        return implode( '/', $result );
    }




    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The eZURLAlias object of the new url is returned if the translation was found, but the resource has moved.
    */
    function translate( &$uri )
    {
        if ( get_class( $uri ) == "ezuri" )
        {
            $uriString = $uri->elements();
        }
        else
        {
            $uriString = $uri;
        }

        $db =& eZDB::instance();
        $query = "SELECT destination_url, forward_to_id
                  FROM ezurlalias
                  WHERE source_md5 = '" . md5( $uriString ) . "' ORDER BY forward_to_id ASC";

        $return = false;
        $urlAliasArray = $db->arrayQuery( $query );
        if ( count( $urlAliasArray ) > 0 )
        {
            $uriString = $urlAliasArray[0]['destination_url'];

            if ( $urlAliasArray[0]['forward_to_id'] != 0 )
            {
                $uriString = 'error/301';

                $return = eZURLAlias::fetch( $urlAliasArray[0]['forward_to_id'] );
            }
            else
            {
                $return = true;
            }
        }

        if ( get_class( $uri ) == "ezuri" )
        {
            $uri->setURIString( $uriString );
        }
        else
        {
            $uri = $uriString;
        }
        return $return;
    }
}

?>
