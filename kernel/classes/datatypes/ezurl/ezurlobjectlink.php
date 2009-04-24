<?php
//
// Definition of eZURLObjectLink class
//
// Created on: <04-Jul-2003 13:14:41 wy>
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

/*! \file ezurlobjectlink.php
*/

/*!
  \class eZURLObjectLink ezurlobjectlink.php
  \ingroup eZDatatype
  \brief The class eZURLObjectLink does

*/

//include_once( 'kernel/classes/ezpersistentobject.php' );

class eZURLObjectLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZURLObjectLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'url_id' => array( 'name' => 'URLID',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true,
                                                            'foreign_class' => 'eZURL',
                                                            'foreign_attribute' => 'id',
                                                            'multiplicity' => '1..*' ),
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         'contentobject_attribute_version' => array( 'name' => 'ContentObjectAttributeVersion',
                                                                                     'datatype' => 'integer',
                                                                                     'default' => 0,
                                                                                     'short_name' => 'contentobject_attr_version',
                                                                                     'required' => true ) ),
                      'keys' => array( 'url_id', 'contentobject_attribute_id', 'contentobject_attribute_version' ),
                      'sort' => array( 'url_id' => 'asc' ),
                      'class_name' => 'eZURLObjectLink',
                      'name' => 'ezurl_object_link' );
    }

    /*!
     \static
    */
    static function create( $urlID, $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        $row = array(
            'url_id' => $urlID,
            'contentobject_attribute_id' => $contentObjectAttributeID,
            'contentobject_attribute_version' => $contentObjectAttributeVersion );
        return new eZURLObjectLink( $row );
    }

    /*!
     \static
     \return the url object for id \a $id.
    */
    static function fetch( $urlID, $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLObjectLink::definition(),
                                                null,
                                                array( 'url_id' => $urlID,
                                                       'contentobject_attribute_id' => $contentObjectAttributeID,
                                                       'contentobject_attribute_version' => $contentObjectAttributeVersion ),
                                                $asObject );
    }

    /*!
     \static
     \return \c true if the URL \a $urlID has any object links
    */
    static function hasObjectLinkList( $urlID )
    {
        return ( eZURLObjectLink::fetchObjectVersionCount( $urlID ) > 0 );
    }

    /*!
     \static
     \return all object versions which has the link.
    */
    static function fetchObjectVersionList( $urlID, $parameters = false )
    {
        $objectVersionList = array();
        $urlObjectLinkList = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                                   null,
                                                                   array( 'url_id' => $urlID ),
                                                                   null,
                                                                   $parameters,
                                                                   true );
        $storedVersionList = array();
        foreach ( $urlObjectLinkList as $urlObjectLink )
        {
            $objectAttributeID = $urlObjectLink->attribute( 'contentobject_attribute_id' );
            $objectAttributeVersion = $urlObjectLink->attribute( 'contentobject_attribute_version' );
            $objectAttribute = eZContentObjectAttribute::fetch( $objectAttributeID, $objectAttributeVersion );
            if ( $objectAttribute ) // Object and version has been deleted
            {
                $objectID = $objectAttribute->attribute( 'contentobject_id' );
                $objectVersion = $objectAttribute->attribute( 'version' );
                $object = eZContentObject::fetch( $objectID );
                if ( $object )
                {
                    $versionObject = $object->version( $objectVersion );
                    if ( $versionObject and !in_array( $versionObject->attribute( 'id' ), $storedVersionList ) )
                    {
                        $objectVersionList[] = $versionObject;
                        $storedVersionList[] = $versionObject->attribute( 'id' );
                    }
                }
            }
        }
        return $objectVersionList;
    }

    /*!
     Get url object count
     \param urld id
    */
     static function fetchObjectVersionCount( $urlID )
     {
         $result = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                        array(),
                                                        array( 'url_id' => $urlID ),
                                                        false,
                                                        null,
                                                        false,
                                                        false,
                                                        array( array( 'operation' => 'count( * )',
                                                                      'name' => 'count' ) ) );
         return $result[0]['count'];
     }

    /*!
     \static
     Removes all links for the object attribute \a $contentObjectAttributeID and version \a $contentObjectVersion.
     If \a $contentObjectVersion is \c false then all versions are removed as well.
    */
    static function removeURLlinkList( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        $conditions = array( 'contentobject_attribute_id' => $contentObjectAttributeID );
        if ( $contentObjectAttributeVersion !== false )
            $conditions['contentobject_attribute_version'] = $contentObjectAttributeVersion;
        eZPersistentObject::removeObject( eZURLObjectLink::definition(),
                                          $conditions );
    }


    /*!
     \static
     \return all links for the contenobject attribute ID \a $contenObjectAttributeID and version \a $contenObjectVersion.
     If \a $contentObjectVersion is \c false then all links for all versions are returned.
    */
    static function fetchLinkList( $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        $linkList = array();
        $conditions = array( 'contentobject_attribute_id' => $contentObjectAttributeID );
        if ( $contentObjectAttributeVersion !== false )
            $conditions['contentobject_attribute_version'] = $contentObjectAttributeVersion;
        $urlObjectLinkList = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                                   null,
                                                                   $conditions,
                                                                   null,
                                                                   null,
                                                                   $asObject );
        foreach ( $urlObjectLinkList as $urlObjectLink )
        {
            if ( $asObject )
            {
                $linkID = $urlObjectLink->attribute( 'url_id' );
                $linkList[] = eZURL::fetch( $linkID );
            }
            else
            {
                $linkID = $urlObjectLink['url_id'];
                $linkList[] = $linkID;
            }
        }
        return $linkList;
    }

    /*!
     \static
     Clear view cache for every object which contains URL with given link ID \a $urlID.
    */
    static function clearCacheForObjectLink( $urlID )
    {
        //include_once( "kernel/classes/ezcontentcachemanager.php" );
        $urlObjectLinkList = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                                    null,
                                                                    array( 'url_id' => $urlID ),
                                                                    null,
                                                                    null,
                                                                    true );
        foreach ( $urlObjectLinkList as $urlObjectLink )
        {
            $objectAttributeID = $urlObjectLink->attribute( 'contentobject_attribute_id' );
            $objectAttributeVersion = $urlObjectLink->attribute( 'contentobject_attribute_version' );
            $objectAttribute = eZContentObjectAttribute::fetch( $objectAttributeID, $objectAttributeVersion );
            if ( $objectAttribute )
            {
                $objectID = $objectAttribute->attribute( 'contentobject_id' );
                $objectVersion = $objectAttribute->attribute( 'version' );
                eZContentCacheManager::clearContentCacheIfNeeded( $objectID, $objectVersion );
            }
        }
    }


    /// \privatesection
    public $URLID;
    public $ContentObjectAttributeID;
    public $ContentObjectAttributeVersion;
}
?>
