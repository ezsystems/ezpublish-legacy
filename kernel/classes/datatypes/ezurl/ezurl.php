<?php
//
// Definition of eZURL class
//
// Created on: <08-Oct-2002 19:44:48 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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
  \class eZURL ezurl.php
  \ingroup eZDatatype
  \brief A class which handles central storage of urls

  URLs can be stored using eZURL. When registering URL's
  to eZURL you will get a URL ID which is used to identify
  URLs.

*/

//include_once( 'kernel/classes/ezpersistentobject.php' );

class eZURL extends eZPersistentObject
{
    /*!
    */
    function eZURL( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
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

    static function create( $url )
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
    static function removeByID( $urlID )
    {
        eZPersistentObject::removeObject( eZURL::definition(),
                                          array( 'id' => $urlID ) );
    }

    /*!
     \static
     Registers a URL to the URL database. The URL id is
     returned if successful. False is returned if not.
    */
    static function registerURL( $url )
    {
        $urlID = false;
        $db = eZDB::instance();

        // check if URL already exists
        $checkURLQuery = "SELECT id FROM ezurl WHERE url='" . $db->escapeString( $url ) . "'";
        $urlArray = $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 0 )
        {
            // store URL
            $url = eZURL::create( $url );
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
    static function registerURLArray( $urlArray )
    {
        $db = eZDB::instance();

        foreach( $urlArray as $key => $url )
        {
            $urlArrayTmp[$key] = $db->escapeString( $url );
        }
        // Fetch the already existing URL's
        $inURLSQL = implode( '\', \'', $urlArrayTmp );
        $checkURLQuery = "SELECT id, url FROM ezurl WHERE url IN ( '$inURLSQL' )";
        $urlRowArray = $db->arrayQuery( $checkURLQuery );

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
                $url = eZURL::create( $url );
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
    static function setIsValid( $id, $isValid )
    {
        $dateTime = time();
        $isValid = (int) $isValid;
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
    static function setLastChecked( $id, $dateTime = false )
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
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURL::definition(),
                                                null, array( 'id' => $id ),
                                                $asObject );
    }

    /*!
     \return the number of registered URLs.
    */
    static function fetchListCount( $parameters = array() )
    {
        return eZURL::handleList( $parameters, true );
    }

    /*!
     \return all registered URLs.
    */
    static function fetchList( $parameters = array() )
    {
        return eZURL::handleList( $parameters, false );
    }

    /*!
     \return all registered URLs.
    */
    static function handleList( $parameters = array(), $asCount = false )
    {
        $parameters = array_merge( array( 'as_object' => true,
                                          'is_valid' => null,
                                          'offset' => false,
                                          'limit' => false,
                                          'only_published' => false ),
                                   $parameters );
        $asObject = $parameters['as_object'];
        $isValid = $parameters['is_valid'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];
        $onlyPublished = $parameters['only_published'];
        $limitArray = null;
        if ( !$asCount and $offset !== false and $limit !== false )
            $limitArray = array( 'offset' => $offset,
                                 'length' => $limit );
        $conditions = array();
        if( $isValid === false ) $isValid = 0;
        if ( $isValid !== null )
        {
            $conditions['is_valid'] = $isValid;
        }
        if ( count( $conditions ) == 0 )
            $conditions = null;

        if ( $onlyPublished )  // Only fetch published urls
        {
            $conditionQuery = "";
            if ( $isValid !== null )
            {
                $isValid = (int) $isValid;
                $conditionQuery = " AND ezurl.is_valid=$isValid ";
            }
            //include_once( "lib/ezdb/classes/ezdb.php" );
            $db = eZDB::instance();
            //include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
            $cObjAttrVersionColumn = eZPersistentObject::getShortAttributeName( $db, eZURLObjectLink::definition(), 'contentobject_attribute_version' );

            if ( $asCount )
            {
                $urls = $db->arrayQuery( "SELECT count( DISTINCT ezurl.id ) AS count
                                            FROM
                                                 ezurl,
                                                 ezurl_object_link,
                                                 ezcontentobject_attribute,
                                                 ezcontentobject_version
                                            WHERE
                                                 ezurl.id                                     = ezurl_object_link.url_id
                                             AND ezurl_object_link.contentobject_attribute_id = ezcontentobject_attribute.id
                                             AND ezurl_object_link.$cObjAttrVersionColumn     = ezcontentobject_attribute.version
                                             AND ezcontentobject_attribute.contentobject_id   = ezcontentobject_version.contentobject_id
                                             AND ezcontentobject_attribute.version            = ezcontentobject_version.version
                                             AND ezcontentobject_version.status               = 1
                                                 $conditionQuery" );
                return $urls[0]['count'];
            }
            else
            {
                $query = "SELECT DISTINCT ezurl.*
                            FROM
                                  ezurl,
                                  ezurl_object_link,
                                  ezcontentobject_attribute,
                                  ezcontentobject_version
                            WHERE
                                  ezurl.id                                     = ezurl_object_link.url_id
                              AND ezurl_object_link.contentobject_attribute_id = ezcontentobject_attribute.id
                              AND ezurl_object_link.$cObjAttrVersionColumn     = ezcontentobject_attribute.version
                              AND ezcontentobject_attribute.contentobject_id   = ezcontentobject_version.contentobject_id
                              AND ezcontentobject_attribute.version            = ezcontentobject_version.version
                              AND ezcontentobject_version.status               = 1
                             $conditionQuery";

                if ( !$offset && !$limit )
                {
                    $urlArray = $db->arrayQuery( $query );
                }
                else
                {
                    $urlArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                                 'limit'  => $limit ) );
                }
                if ( $asObject )
                {
                    $urls = array();
                    foreach ( $urlArray as $url )
                    {
                        $urls[] = new eZURL( $url );
                    }
                    return $urls;
                }
                else
                    $urls = $urlArray;
                return $urls;
            }
        }
        else
        {
            if ( $asCount )
            {
                $urls = eZPersistentObject::fetchObjectList( eZURL::definition(),
                                                             array(),
                                                             $conditions,
                                                             false,
                                                             null,
                                                             false,
                                                             false,
                                                             array( array( 'operation' => 'count( id )',
                                                                           'name' => 'count' ) ) );
                return $urls[0]['count'];
            }
            else
            {
                return eZPersistentObject::fetchObjectList( eZURL::definition(),
                                                            null, $conditions, null, $limitArray,
                                                            $asObject );
            }
        }
    }

    /*!
     \static
     Returns the URL with the given ID. False is returned if the ID
     does not exits.
    */
    static function url( $id, $onlyValid = false )
    {
        $url = false;

        if ( !is_numeric( $id ) )
        {
            return $url;
        }

        $id = (int) $id;
        $db = eZDB::instance();
        $checkURLQuery = "SELECT url, is_valid FROM ezurl WHERE id='$id'";
        $urlArray = $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            if ( $onlyValid and
                 !$urlArray[0]['is_valid'] )
            {
                 $url = "/url/view/" . $id;
                 return $url;
            }
            $url = $urlArray[0]['url'];
        }
        return $url;
    }

    /*!
     \static
     Returns the URL with the given ID. False is returned if the ID
     does not exits.
    */
    static function urlByMD5( $urlMD5 )
    {
        $db = eZDB::instance();

        $url = false;
        $urlMD5 = $db->escapeString( $urlMD5 );
        $checkURLQuery = "SELECT url FROM ezurl WHERE original_url_md5='$urlMD5'";
        $urlArray = $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            $url = $urlArray[0]['url'];
        }
        return $url;
    }

    /*!
     \static
     Returns the URL with the given URL. Returns false if the URL does not exists.
    */
    static function urlByURL( $urlText )
    {
        $db = eZDB::instance();

        $url = false;
        $checkURLQuery = "SELECT * FROM ezurl WHERE url='" . $db->escapeString( $urlText ) . "'";
        $urlArray = $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            $url = new eZURL( $urlArray[0] );
        }
        return $url;
    }
}

?>
