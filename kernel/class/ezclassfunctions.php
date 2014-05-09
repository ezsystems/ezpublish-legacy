<?php
/**
 * File containing the eZClassFunctions class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

class eZClassFunctions
{
    static function addGroup( $classID, $classVersion, $selectedGroup )
    {
        list ( $groupID, $groupName ) = explode( '/', $selectedGroup );
        $ingroup = eZContentClassClassGroup::create( $classID, $classVersion, $groupID, $groupName );
        $ingroup->store();
        return true;
    }

    static function removeGroup( $classID, $classVersion, $selectedGroup )
    {
        $class = eZContentClass::fetch( $classID, true, eZContentClass::VERSION_STATUS_DEFINED );
        if ( !$class )
            return false;
        $groups = $class->attribute( 'ingroup_list' );
        foreach ( array_keys( $groups ) as $key )
        {
            if ( in_array( $groups[$key]->attribute( 'group_id' ), $selectedGroup ) )
            {
                unset( $groups[$key] );
            }
        }

        if ( count( $groups ) == 0 )
        {
            return false;
        }
        else
        {
            foreach(  $selectedGroup as $group_id )
            {
                eZContentClassClassGroup::removeGroup( $classID, $classVersion, $group_id );
            }
        }
        return true;
    }
}

?>
