<?php
//
// Created on: <24-Sep-2004 13:20:32 jk>
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

/*! \file ezclassfunctions.php
*/

class eZClassFunctions
{
    static function addGroup( $classID, $classVersion, $selectedGroup )
    {
        //include_once( 'kernel/classes/ezcontentclassclassgroup.php' );
        list ( $groupID, $groupName ) = split( '/', $selectedGroup );
        $ingroup = eZContentClassClassGroup::create( $classID, $classVersion, $groupID, $groupName );
        $ingroup->store();
        return true;
    }

    static function removeGroup( $classID, $classVersion, $selectedGroup )
    {
        //include_once( 'kernel/classes/ezcontentclass.php' );
        //include_once( 'kernel/classes/ezcontentclassclassgroup.php' );

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
