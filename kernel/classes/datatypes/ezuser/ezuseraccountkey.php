<?php
//
// Definition of eZUserAccountKey class
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

/*! \file ezuseraccountkey.php
*/

/*!
  \class eZUserAccountKey ezuseraccountkey.php
  \brief The class eZUserAccountKey does

*/

class eZUserAccountKey extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZUserAccountKey( $row = array() )
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
                      "class_name" => "eZUserAccountKey",
                      "name" => "ezuser_accountkey" );
    }

    function &createNew( $userID, $hashKey, $time)
    {
        return new eZUserAccountKey( array( "user_id" => $userID,
                                            "hash_key" => $hashKey,
                                            "time" => $time ) );
    }

    function &fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZUserAccountKey::definition(),
                                                null,
                                                array( "hash_key" => $hashKey ),
                                                true );
    }

}

?>
