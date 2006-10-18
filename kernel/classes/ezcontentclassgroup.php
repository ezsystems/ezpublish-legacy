<?php
//
// Definition of eZContentClassGroup class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

//!! eZKernel
//! The class eZContentClassGroup
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );

class eZContentClassGroup extends eZPersistentObject
{
    function eZContentClassGroup( $row )
    {
       $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "creator_id" => array( 'name' => "CreatorID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZUser',
                                                                'foreign_attribute' => 'contentobject_id',
                                                                'multiplicity' => '1..*' ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZUser',
                                                                 'foreign_attribute' => 'contentobject_id',
                                                                 'multiplicity' => '1..*' ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array( 'modifier' => 'modifier',
                                                      'creator' => 'creator' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentClassGroup",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentclassgroup" );
    }

    function create( $userID = false )
    {
        $dateTime = time();
        if ( !$userID )
            $userID = eZUser::currentUserID();
        $row = array(
            "id" => null,
            "name" => "",
            "creator_id" => $userID,
            "modifier_id" => $userID,
            "created" => $dateTime,
            "modified" => $dateTime );
        return new eZContentClassGroup( $row );
    }

    function &modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::fetch( $this->ModifierID );
        }
        else
            $user = null;
        return $user;
    }

    function &creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::fetch( $this->CreatorID );
        }
        else
            $user = null;
        return $user;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeSelected( $id )
    {
        eZPersistentObject::removeObject( eZContentClassGroup::definition(),
                                          array( "id" => $id ) );
    }

    /*!
     Fetch Class group by name, and return first result.

     \param name
     \param asObject
    */
    function fetchByName( $name, $asObject = true )
    {
        $conds = array( 'name' => $name );
        return eZPersistentObject::fetchObject( eZContentClassGroup::definition(),
                                                null,
                                                $conds,
                                                $asObject );
    }

    function fetch( $id, $user_id = false, $asObject = true )
    {
        $conds = array( "id" => $id );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        return eZPersistentObject::fetchObject( eZContentClassGroup::definition(),
                                                null,
                                                $conds,
                                                $asObject );
    }

    function fetchList( $user_id = false, $asObject = true )
    {
        $conds = array();
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        return eZPersistentObject::fetchObjectList( eZContentClassGroup::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    /*!
     Appends the class \a $class to this group.
     \param $class Can either be an eZContentClass object or a class ID.
     \return the class group link object.
     \note tranaction unsafe.
    */
    function &appendClass( &$class, $version = false )
    {
        if ( get_class( $class ) == 'ezcontentclass' )
        {
            $classID = $class->attribute( 'id' );
            $version = $class->attribute( 'version' );
        }
        else
            $classID = $class;
        $classGroupLink = eZContentClassClassGroup::create( $classID, $version,
                                                            $this->attribute( 'id' ),
                                                            $this->attribute( 'name' ) );
        $classGroupLink->store();
        return $classGroupLink;
    }

    var $ID;
    var $Name;
    var $CreatorID;
    var $ModifierID;
    var $Created;
    var $Modified;
}

?>
