<?php
//
// Definition of eZForgotPassword class
//
// Created on: <17-Мар-2003 11:40:49 sp>
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

/*! \file
*/

/*!
  \class eZForgotPassword ezforgotpassword.php
  \ingroup eZDatatype
  \brief The class eZForgotPassword does

*/

class eZForgotPassword extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZForgotPassword( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => "UserID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '0..*' ),
                                         "hash_key" => array( 'name' => "HashKey",
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "time" => array( 'name' => "Time",
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZForgotPassword",
                      "name" => "ezforgot_password" );
    }

    static function createNew( $userID, $hashKey, $time)
    {
        return new eZForgotPassword( array( "user_id" => $userID,
                                            "hash_key" => $hashKey,
                                            "time" => $time ) );
    }

    static function fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZForgotPassword::definition(),
                                                null,
                                                array( "hash_key" => $hashKey ),
                                                true );
    }

    /*!
     \static
     Removes all password reminders in the database.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezforgot_password" );
    }

    /*!
     Remove forgot password entries belonging to user \a $userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZForgotPassword::definition(),
                                          array( 'user_id' => $userID ) );
    }
}

?>
