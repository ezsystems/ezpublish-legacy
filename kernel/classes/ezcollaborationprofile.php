<?php
//
// Definition of eZCollaborationProfile class
//
// Created on: <28-Jan-2003 16:45:06 amos>
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

/*! \file ezcollaborationprofile.php
*/

/*!
  \class eZCollaborationProfile ezcollaborationprofile.php
  \brief The class eZCollaborationProfile does

*/

//include_once( 'kernel/classes/ezpersistentobject.php' );
//include_once( 'kernel/classes/ezcollaborationgroup.php' );

class eZCollaborationProfile extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationProfile( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         'main_group' => array( 'name' => 'MainGroup',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZCollaborationGroup',
                                                                'foreign_attribute' => 'id',
                                                                'multiplicity' => '1..*' ),
                                         'data_text1' => array( 'name' => 'DataText1',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationProfile',
                      'name' => 'ezcollab_profile' );
    }

    static function create( $userID, $mainGroup = 0 )
    {
        $date_time = time();
        $row = array( 'id' => null,
                      'user_id' => $userID,
                      'main_group' => $mainGroup,
                      'created' => $date_time,
                      'modified' => $date_time );
        $newCollaborationProfile = new eZCollaborationProfile( $row );
        return $newCollaborationProfile;
    }

    static function fetch( $id, $asObject = true )
    {
        $conditions = array( "id" => $id );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    static function fetchByUser( $userID, $asObject = true )
    {
        $conditions = array( "user_id" => $userID );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function instance( $userID = false )
    {
        if ( $userID === false )
        {
            $user = eZUser::currentUser();
            $userID = $user->attribute( 'contentobject_id' );
        }
        $instance =& $GLOBALS["eZCollaborationProfile-$userID"];
        if ( !isset( $instance ) )
        {
            $instance = eZCollaborationProfile::fetchByUser( $userID );
            if ( $instance === null )
            {
                $group = eZCollaborationGroup::instantiate( $userID, ezi18n( 'kernel/classes', 'Inbox' ) );
                $instance = eZCollaborationProfile::create( $userID, $group->attribute( 'id' ) );
                $instance->store();
            }
        }
        return $instance;
    }

}

?>
