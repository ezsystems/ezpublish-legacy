<?php
//
// Definition of eZForgotPassword class
//
// Created on: <22-Mar-2003 14:52:37 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezforgotpassword.php
*/

/*!
  \class eZForgotPassword ezforgotpassword.php
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

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "user_id" => "UserID",
                                         "hash_key" => "HashKey",
                                         "time" => "Time"
                                         ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZForgotPassword",
                      "name" => "ezforgot_password" );
    }

    function& createNew( $userID, $hashKey, $time)
    {
        return new eZForgotPassword( array( "user_id" => $userID,
                                            "hash_key" => $hashKey,
                                            "time" => $time ) );
    }

    function& fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZForgotPassword::definition(),
                                                null,
                                                array( "hash_key" => $hashKey ),
                                                true );
    }

}

?>
