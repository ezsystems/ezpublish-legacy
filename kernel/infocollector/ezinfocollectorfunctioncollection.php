<?php
//
// Definition of eZInfocollectorFunctionCollection class
//
// Created on: <03-Oct-2006 13:05:24 sp>
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

/*! \file ezinfocollectorfunctioncollection.php
*/

/*!
  \class eZInfocollectorFunctionCollection ezinfocollectorfunctioncollection.php
  \brief The class eZInfocollectorFunctionCollection does

*/

class eZInfocollectorFunctionCollection
{
    /*!
     Constructor
    */
    function eZInfocollectorFunctionCollection()
    {
    }

    static function fetchCollectedInfoCount( $objectAttributeID, $objectID, $value, $creatorID = false, $userIdentifier = false )
    {
        //include_once( 'kernel/classes/ezinformationcollection.php' );
        if ( $objectAttributeID )
            $count = eZInformationCollection::fetchCountForAttribute( $objectAttributeID, $value );
        else
            $count = eZInformationCollection::fetchCollectionsCount( $objectID, $creatorID, $userIdentifier );
        return array( 'result' => $count );
    }

    static function fetchCollectedInfoCountList( $objectAttributeID )
    {
        //include_once( 'kernel/classes/ezinformationcollection.php' );
        $count = eZInformationCollection::fetchCountList( $objectAttributeID );
        return array( 'result' => $count );
    }

    static function fetchCollectedInfoCollection( $collectionID, $contentObjectID )
    {
        //include_once( 'kernel/classes/ezinformationcollection.php' );
        $collection = false;
        if ( $collectionID )
            $collection = eZInformationCollection::fetch( $collectionID );
        else if ( $contentObjectID )
        {
            $userIdentifier = eZInformationCollection::currentUserIdentifier();
            $collection = eZInformationCollection::fetchByUserIdentifier( $userIdentifier, $contentObjectID );
        }
        return array( 'result' => $collection );
    }

    static function fetchCollectionsList( $objectID = false, $creatorID = false, $userIdentifier = false, $limit = false, $offset = false, $sortBy = false )
    {
        //include_once( 'kernel/classes/ezinformationcollection.php' );

        $collection = eZInformationCollection::fetchCollectionsList( $objectID,
                                                                     $creatorID,
                                                                     $userIdentifier,
                                                                     array( 'limit' => $limit, 'offset' => $offset ),
                                                                     $sortBy
                                                                   );
        return array( 'result' => $collection );
     }


}

?>
