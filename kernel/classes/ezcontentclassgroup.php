<?php
//
// Definition of eZContentClassGroup class
//
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
                                                                'required' => true ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
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
