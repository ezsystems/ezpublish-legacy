<?php
/**
 * File containing the eZClassFunctions class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
