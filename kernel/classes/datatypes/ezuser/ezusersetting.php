<?php
//
// Definition of eZUserSetting class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*!
  \class eZUserSetting ezusersetting.php
  \ingroup eZDatatype

*/

//include_once( 'kernel/classes/ezpersistentobject.php' );

class eZUserSetting extends eZPersistentObject
{
    function eZUserSetting( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '0..1' ),
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

    static function create( $userID, $isEnabled )
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
                    $user = eZUser::fetch( $this->UserID );
                    if ( $user )
                    {
                        eZUser::removeSessionData( $this->UserID );
                    }
                }
            } break;
        }

        eZPersistentObject::setAttribute( $attr, $val );
    }

    /*!
     Fetch message object with \a $userID
    */
    static function fetch( $userID,  $asObject = true  )
    {
        return eZPersistentObject::fetchObject( eZUserSetting::definition(),
                                                    null,
                                                    array('user_id' => $userID ),
                                                    $asObject );
    }

    /*!
     Fetch all settings from database
    */
    static function fetchAll( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZUserSetting::definition(),
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZUserSetting::definition(),
                                          array( 'user_id' => $userID ) );
    }

    /// \privatesection
    public $UserID;
    public $IsEnabled;
    public $MaxLogin;
}

?>
