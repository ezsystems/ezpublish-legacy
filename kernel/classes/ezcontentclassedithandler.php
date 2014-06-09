<?php
/**
 * File containing the eZContentClassEditHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Handler for content class editing.
 */
class eZContentClassEditHandler
{

    /**
     * Store the modification made to an eZContentClass.
     *
     * @param eZContentClass Content class to be stored.
     * @param array[eZContentClassAttribute] Attributes of the new content class.
     * @param array Unordered view parameters
     */
    public function store( eZContentClass $class, array $attributes, array &$unorderedParameters )
    {
        $oldClassAttributes = $class->fetchAttributes( $class->attribute( 'id' ), true, eZContentClass::VERSION_STATUS_DEFINED );
        // Delete object attributes which have been removed.
        foreach ( $oldClassAttributes as $oldClassAttribute )
        {
            /** @var eZContentClassAttribute $oldClassAttribute */
            $attributeExists = false;
            $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
            foreach ( $class->fetchAttributes( ) as $newClassAttribute )
            {
                if ( $oldClassAttributeID == $newClassAttribute->attribute( 'id' ) )
                {
                    $attributeExists = true;
                    break;
                }
            }
            if ( !$attributeExists )
            {
                foreach ( eZContentObjectAttribute::fetchSameClassAttributeIDList( $oldClassAttributeID ) as $objectAttribute )
                {
                    $objectAttribute->removeThis( $objectAttribute->attribute( 'id' ) );
                }

                $oldClassAttribute->datatype()->deleteNotVersionedStoredClassAttribute( $oldClassAttribute );
            }
        }
        $class->storeDefined( $attributes );

        // Add object attributes which have been added.
        foreach ( $attributes as $newClassAttribute )
        {
            $attributeExists = false;
            $newClassAttributeID = $newClassAttribute->attribute( 'id' );
            foreach ( $oldClassAttributes as $oldClassAttribute )
            {
                if ( $newClassAttributeID == $oldClassAttribute->attribute( 'id' ) )
                {
                    $attributeExists = true;
                    break;
                }
            }
            if ( !$attributeExists )
            {
                $newClassAttribute->initializeObjectAttributes( $objects );
            }
        }
    }
}

?>
