<?php
//
// Definition of eZUserSetting class
//
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

//!! eZUser
//! The class eZUserSetting
/*!

*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezpersistentobject.php' );

class eZUserSetting extends eZPersistentObject
{
    function eZUserSetting( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'is_enabled' => array( 'name' => 'IsEnabled',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'max_login' => array( 'name' => 'MaxLogin',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      'keys' => array( 'user_id' ),
                      'relations' => array( 'user_id' => array( 'class' => 'ezuser',
                                                                 'field' => 'contentobject_id' ) ),
                      'class_name' => 'eZUserSetting',
                      'name' => 'ezuser_setting' );
    }

    function &create( $userID, $isEnabled )
    {
        $row = array( 'user_id' => $userID,
                      'is_enabled' => $isEnabled,
                      'max_login' => null );
        return new eZUserSetting( $row );
    }


    /*!
     \reimp
    */
    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'is_enabled':
            {
                if ( !$val )
                {
                    $user =& eZUser::fetch( $this->UserID );
                    if ( $user )
                    {
                        $user->removeSessionData();
                    }
                }
            } break;
        }

        eZPersistentObject::setAttribute( $attr, $val );
    }

    /*!
     \return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        return eZPersistentObject::hasAttribute( $attr ) ;
    }

    function &attribute( $attr )
    {
        return eZPersistentObject::attribute( $attr );
    }

    /*!
     Remove user settings with \a $userID
    */
    function &remove( $userID )
    {
        eZPersistentObject::removeObject( eZUserSetting::definition(),
                                          array( 'user_id' => $userID ) );
    }

    /*!
     Fetch message object with \a $userID
    */
    function &fetch( $userID,  $asObject = true  )
    {
        return eZPersistentObject::fetchObject( eZUserSetting::definition(),
                                                null,
                                                array('user_id' => $userID ),
                                                $asObject );
    }

    /*!
     Fetch all settings from database
    */
    function &fetchAll( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZUserSetting::definition(),
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /// \privatesection
    var $UserID;
    var $IsEnabled;
    var $MaxLogin;
}

?>
