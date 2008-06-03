<?php
//
// Definition of eZUserAccountKey class
//
// Created on: <22-Mar-2003 14:52:37 bf>
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

/*! \file ezuseraccountkey.php
*/

/*!
  \class eZUserAccountKey ezuseraccountkey.php
  \ingroup eZDatatype
  \brief The class eZUserAccountKey does

*/

//include_once( "kernel/classes/ezpersistentobject.php" );

class eZUserAccountKey extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZUserAccountKey( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "user_id" => "UserID",
                                         "hash_key" => "HashKey",
                                         "time" => "Time"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZUserAccountKey",
                      "name" => "ezuser_accountkey" );
    }

    static function createNew( $userID, $hashKey, $time)
    {
        return new eZUserAccountKey( array( "user_id" => $userID,
                                            "hash_key" => $hashKey,
                                            "time" => $time ) );
    }

    static function fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZUserAccountKey::definition(),
                                                null,
                                                array( "hash_key" => $hashKey ),
                                                true );
    }

    /*!
     Remove account keys belonging to user \a $userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZUserAccountKey::definition(),
                                          array( 'user_id' => $userID ) );
    }

}

?>
