<?php
//
// Definition of eZGeneralDigestUserSettings class
//
// Created on: <16-May-2003 13:06:24 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezgeneraldigestusersettings.php
*/

/*!
  \class eZGeneralDigestUserSettings ezgeneraldigestusersettings.php
  \brief The class eZGeneralDigestUserSettings does

*/

define( 'EZ_DIGEST_SETTINGS_TYPE_NONE', 0 );
define( 'EZ_DIGEST_SETTINGS_TYPE_WEEKLY', 1 );
define( 'EZ_DIGEST_SETTINGS_TYPE_MONTHLY', 2 );

class eZGeneralDigestUserSettings extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZGeneralDigestUserSettings( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
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
                      "function_attributes" => array( 'node' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZGeneralDigestUserSettings",
                      "name" => "ezgeneral_digest_user_settings" );
    }

    function &create( $address, $receiveDigest = 0, $digestType = EZ_DIGEST_SETTINGS_TYPE_NONE, $day = '', $time = '' )
    {
        return new eZGeneralDigestUserSettings( array( 'address' => $address,
                                                       'receive_digest' => $receiveDigest,
                                                       'digest_type' => $digestType,
                                                       'day' => $day,
                                                       'time' => $time ) );
    }

    function &fetchForUser( $address, $asObject = true )
    {
        $settingsList =& eZPersistentObject::fetchObjectList( eZGeneralDigestUserSettings::definition(),
                                                              null, array( 'address' => $address ),
                                                              null,null,true );
        return $settingsList[0];
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
