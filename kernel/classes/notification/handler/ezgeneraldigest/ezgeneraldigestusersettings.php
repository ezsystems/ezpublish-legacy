<?php
//
// Definition of eZGeneralDigestUserSettings class
//
// Created on: <16-May-2003 13:06:24 sp>
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

/*! \file ezgeneraldigestusersettings.php
*/

/*!
  \class eZGeneralDigestUserSettings ezgeneraldigestusersettings.php
  \brief The class eZGeneralDigestUserSettings does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

define( 'EZ_DIGEST_SETTINGS_TYPE_NONE', 0 );
define( 'EZ_DIGEST_SETTINGS_TYPE_WEEKLY', 1 );
define( 'EZ_DIGEST_SETTINGS_TYPE_MONTHLY', 2 );
define( 'EZ_DIGEST_SETTINGS_TYPE_DAILY', 3 );

class eZGeneralDigestUserSettings extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZGeneralDigestUserSettings( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "address" => array( 'name' => "Address",
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "receive_digest" => array( 'name' => "ReceiveDigest",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         "digest_type" => array( 'name' => "DigestType",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "day" => array( 'name' => "Day",
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => true ),
                                         "time" => array( 'name' => "Time",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZGeneralDigestUserSettings",
                      "name" => "ezgeneral_digest_user_settings" );
    }


    function create( $address, $receiveDigest = 0, $digestType = EZ_DIGEST_SETTINGS_TYPE_NONE, $day = '', $time = '' )
    {
        return new eZGeneralDigestUserSettings( array( 'address' => $address,
                                                       'receive_digest' => $receiveDigest,
                                                       'digest_type' => $digestType,
                                                       'day' => $day,
                                                       'time' => $time ) );
    }

    function fetchForUser( $address, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZGeneralDigestUserSettings::definition(),
                                                null,
                                                array( 'address' => $address ),
                                                $asObject );
    }

    /*!
     \static
     Removes all general digest settings for all users.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezgeneral_digest_user_settings" );
    }
}

?>
