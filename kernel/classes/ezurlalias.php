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

  Handles storing, fetching, moving of eZ publish URL aliases
*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZURLAlias extends eZPersistentObject
{
    function eZURLAlias( $row )
    {
        $this->eZPersistentObject( $row );
    }

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

    function store()
    {
        $this->SourceMD5 = md5( $this->SourceURL );
        eZPersistentObject::store();
    }

    /*!
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
     Transforms the URI if there exists an alias for it.
     false is returned if successful
     The eZURLAlias object of the new url is returned if the translation was found, but the resource has moved
    */
    function translate( &$uri )
    {
        $db =& eZDB::instance();
        if ( get_class( $uri ) == "ezuri" )
        {
            $query = "SELECT destination_url, forward_to_id
                  FROM ezurlalias
                  WHERE source_md5 = '" . md5( $uri->elements() ) . "'";
        }
        else
        {
            $query = "SELECT destination_url, forward_to_id
                  FROM ezurlalias
                  WHERE source_md5 = '$uri'";
        }

        $return = false;
        $urlAliasArray = $db->arrayQuery( $query );
        if ( count( $urlAliasArray ) > 0 )
        {
            $uri->setURIString( $urlAliasArray[0]['destination_url'] );

            if ( $urlAliasArray[0]['forward_to_id'] != 0 )
            {
                $uri->setURIString( "error/301" );

                return eZURLAlias::fetch( $urlAliasArray[0]['forward_to_id'] );
            }
            else
            {
                return 1;
            }
        }

        return $return;
    }
}

?>
