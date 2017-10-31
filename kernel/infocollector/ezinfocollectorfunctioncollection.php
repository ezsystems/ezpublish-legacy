<?php
/**
 * File containing the eZInfocollectorFunctionCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZInfocollectorFunctionCollection ezinfocollectorfunctioncollection.php
  \brief The class eZInfocollectorFunctionCollection does

*/

class eZInfocollectorFunctionCollection
{
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
