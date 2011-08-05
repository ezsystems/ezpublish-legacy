<?php
/**
 * File containing the eZContentClassAttributeNameList class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * @deprecated since 4.3, use eZSerializedObjectNameList directly instead!
 */

class eZContentClassAttributeNameList extends eZSerializedObjectNameList
{
    function eZContentClassAttributeNameList( $serializedNameList = false )
    {
        eZSerializedObjectNameList::eZSerializedObjectNameList( $serializedNameList );
    }

    function create( $serializedNamesString = false )
    {
        $object = new eZContentClassAttributeNameList( $serializedNamesString );
        return $object;
    }
}

?>
