<?php
//
// Definition of eZURL class
//
// Created on: <08-Oct-2002 19:44:48 bf>
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
  \class eZURL ezurl.php
  \ingroup eZKernel
  \brief A class which handles central storage of urls

  URLs can be stored using eZURL. When registering URL's
  to eZURL you will get a URL ID which is used to identify
  URLs.

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZURL extends eZPersistentObject
{
    /*!
    */
    function eZURL( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'url' => array( 'name' => 'URL',
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => true ),
                                         'original_url_md5' => array( 'name' => 'OriginalURLMD5',
                                                                      'datatype' => 'string',
                                                                      'default' => '',
                                                                      'required' => true ),
                                         'is_valid' => array( 'name' => 'IsValid',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'last_checked' => array( 'name' => 'LastChecked',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZURL',
                      'name' => 'ezurl' );
    }

    function &create( $url )
    {
        $dateTime = time();
        $row = array(
            'id' => null,
            'url' => $url,
            'original_url_md5' => md5( $url ),
            'is_valid' => true,
            'last_checked' => 0,
            'created' => $dateTime,
            'modified' => $dateTime );
        return new eZURL( $row );
    }

    /*!
     \static
     Removes the URL with ID \a $urlID.
    */
    function removeByID( $urlID )
    {
        eZPersistentObject::removeObject( eZURL::definition(),
                                          array( 'id' => $urlID ) );
    }

    /*!
     \static
     Registers a URL to the URL database. The URL id is
     returned if successful. False is returned if not.
    */
    function registerURL( $url )
    {
        $urlID = false;
        $db =& eZDB::instance();

        // check if URL already exists
        $checkURLQuery = "SELECT id FROM ezurl WHERE url='$url'";
        $urlArray =& $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 0 )
        {
            // store URL
            $url =& eZURL::create( $url );
            $url->store();
            $urlID = $url->attribute( 'id' );
        }
        else
        {
            $urlID = $urlArray[0]['id'];
        }
        return $urlID;
    }

    /*!
     \static
     Registers an array of URLs to the URL database. A hash of array( url -> id )
     is returned.
    */
    function registerURLArray( $urlArray )
    {
        $db =& eZDB::instance();

        // Fetch the already existing URL's
        $inURLSQL = implode( '\', \'', $urlArray );
        $checkURLQuery = "SELECT id, url FROM ezurl WHERE url IN ( '$inURLSQL' )";
        $urlRowArray =& $db->arrayQuery( $checkURLQuery );

        $registeredURLArray = array();
        foreach ( $urlRowArray as $urlRow )
        {
            $registeredURLArray[$urlRow['url']] = $urlRow['id'];
        }

        // Check for URL's which are not registered, and register them
        foreach ( $urlArray as $url )
        {
            if ( !isset( $registeredURLArray[$url] ) )
            {
                $url =& eZURL::create( $url );
                $url->store();
                $urlID = $url->attribute( 'id' );
                $urlText = $url->attribute('url' );
                $registeredURLArray[$urlText] = $urlID;
            }
        }

        return $registeredURLArray;
    }

    /*!
     \static
     Updates the is_valid field of urls passed in \a $id.
     \param $id Can either be an array with ids or just one id value.
    */
    function setIsValid( $id, $isValid )
    {
        $dateTime = time();
        eZPersistentObject::updateObjectList( array( 'definition' => eZURL::definition(),
                                                     'update_fields' => array( 'is_valid' => $isValid,
                                                                               'modified' => $dateTime ),
                                                     'conditions' => array( 'id' => $id ) ) );
    }

    /*!
     Sets the modification date to \a $dateTime or the current
     date if it's \c false.
    */
    function setModified( $dateTime = false )
    {
        if ( $dateTime === false )
        {
            $dateTime = time();
        }
        $this->Modified = $dateTime;
    }

    /*!
     Sets the last checked date to \a $dateTime or the current
     date if it's \c false.
    */
    function setLastChecked( $id, $dateTime = false )
    {
        if ( $dateTime === false )
        {
            $dateTime = time();
        }
        eZPersistentObject::updateObjectList( array( 'definition' => eZURL::definition(),
                                                     'update_fields' => array( 'last_checked' => $dateTime ),
                                                     'conditions' => array( 'id' => $id ) ) );
    }

    /*!
     \return the url object for id \a $id.
    */
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURL::definition(),
                                                null, array( 'id' => $id ),
                                                $asObject );
    }

    /*!
     \return the number of registered URLs.
    */
    function &fetchListCount( $parameters = array() )
    {
        return eZURL::handleList( $parameters, true );
    }

    /*!
     \return all registered URLs.
    */
    function &fetchList( $parameters = array() )
    {
        return eZURL::handleList( $parameters, false );
    }

    /*!
     \return all registered URLs.
    */
    function &handleList( $parameters = array(), $asCount = false )
    {
        $parameters = array_merge( array( 'as_object' => true,
                                          'is_valid' => null,
                                          'offset' => false,
                                          'limit' => false ),
                                   $parameters );
        $asObject = $parameters['as_object'];
        $isValid = $parameters['is_valid'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];
        $limitArray = null;
        if ( !$asCount and $offset !== false and $limit !== false )
            $limitArray = array( 'offset' => $offset,
                                 'length' => $limit );
        $conditions = array();
        if ( $isValid !== null )
        {
            $conditions['is_valid'] = $isValid;
        }
        if ( count( $conditions ) == 0 )
            $conditions = null;
        if ( $asCount )
        {
            $rows = eZPersistentObject::fetchObjectList( eZURL::definition(),
                                                         array(), $conditions, null, null,
                                                         false, null,
                                                         array( array( 'operation' => 'count( id )',
                                                                       'name' => 'count' ) ) );
            return $rows[0]['count'];
        }
        else
            return eZPersistentObject::fetchObjectList( eZURL::definition(),
                                                        null, $conditions, null, $limitArray,
                                                        $asObject );
    }

    /*!
     \static
     Returns the URL with the given ID. False is returned if the ID
     does not exits.
    */
    function &url( $id, $onlyValid = false )
    {
        $db =& eZDB::instance();

        $url = false;
        $checkURLQuery = "SELECT url, is_valid FROM ezurl WHERE id='$id'";
        $urlArray =& $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            if ( $onlyValid and
                 !$urlArray[0]['is_valid'] )
            {
                 $url = "/url/view/" . $id;
                 return $url;
            }
            $url =& $urlArray[0]['url'];
        }
        return $url;
    }

    /*!
     \static
     Returns the URL with the given ID. False is returned if the ID
     does not exits.
    */
    function &urlByMD5( $urlMD5 )
    {
        $db =& eZDB::instance();

        $url = false;
        $checkURLQuery = "SELECT url FROM ezurl WHERE original_url_md5='$urlMD5'";
        $urlArray =& $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            $url =& $urlArray[0]['url'];
        }
        return $url;
    }

    /*!
     \static
     Returns the URL with the given URL. Returns false if the URL does not exists.
    */
    function &urlByURL( $urlText )
    {
        $db =& eZDB::instance();

        $url = false;
        $checkURLQuery = "SELECT * FROM ezurl WHERE url='$urlText'";
        $urlArray =& $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            $urlRow =& $urlArray[0];
            $url = new eZURL( $urlRow );
        }
        return $url;
    }
}

?>
