<?php
//
// Definition of eZInfocollectorFunctionCollection class
//
// Created on: <03-Oct-2006 13:05:24 sp>
//

// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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

    function fetchCollectedInfoCount( $objectAttributeID, $objectID, $value, $creatorID = false, $userIdentifier = false )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        if ( $objectAttributeID )
            $count = eZInformationCollection::fetchCountForAttribute( $objectAttributeID, $value );
        else
            $count = eZInformationCollection::fetchCollectionsCount( $objectID, $creatorID, $userIdentifier );
        return array( 'result' => $count );
    }

    function fetchCollectedInfoCountList( $objectAttributeID )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        $count = eZInformationCollection::fetchCountList( $objectAttributeID );
        return array( 'result' => $count );
    }

    function fetchCollectedInfoCollection( $collectionID, $contentObjectID )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        $collection = false;
        if ( $collectionID )
            $collection = eZInformationCollection::fetch( $collectionID );
        else if ( $contentObjectID )
        {
            $userIdentifier = eZInformationCollection::currentUserIdentifier();
            $collection = eZInformationCollection::fetchByUserIdentifier( $userIdentifier, $contentObjectID );
        }
        return array( 'result' => &$collection );
    }

    function fetchCollectionsList( $objectID = false, $creatorID = false, $userIdentifier = false, $limit = false, $offset = false, $sortBy = false )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );

        $collection = eZInformationCollection::fetchCollectionsList( $objectID,
                                                                     $creatorID,
                                                                     $userIdentifier,
                                                                     array( 'limit' => $limit, 'offset' => $offset ),
                                                                     $sortBy
                                                                   );
        return array( 'result' => &$collection );
     }


}

?>
