<?php
/**
 * File containing the eZInfocollectorFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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

    static public function fetchCollectedInfoCount( $objectAttributeID, $objectID, $value, $creatorID = false, $userIdentifier = false )
    {
        if ( $objectAttributeID )
            $count = eZInformationCollection::fetchCountForAttribute( $objectAttributeID, $value );
        else
            $count = eZInformationCollection::fetchCollectionsCount( $objectID, $creatorID, $userIdentifier );
        return array( 'result' => $count );
    }

    static public function fetchCollectedInfoCountList( $objectAttributeID )
    {
        $count = eZInformationCollection::fetchCountList( $objectAttributeID );
        return array( 'result' => $count );
    }

    static public function fetchCollectedInfoCollection( $collectionID, $contentObjectID )
    {
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

    static public function fetchCollectionsList( $objectID = false, $creatorID = false, $userIdentifier = false, $limit = false, $offset = false, $sortBy = false )
    {
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
