<?php
//
// Definition of eZCollaborationProfile class
//
// Created on: <28-Jan-2003 16:45:06 amos>
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

/*! \file ezcollaborationprofile.php
*/

/*!
  \class eZCollaborationProfile ezcollaborationprofile.php
  \brief The class eZCollaborationProfile does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezcollaborationgroup.php' );

class eZCollaborationProfile extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationProfile( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'main_group' => array( 'name' => 'MainGroup',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
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

    function &create( $userID, $mainGroup = 0 )
    {
        $date_time = time();
        $row = array(
            'id' => null,
            'user_id' => $userID,
            'main_group' => $mainGroup,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationProfile( $row );
    }

    function &fetch( $id, $asObject = true )
    {
        $conditions = array( "id" => $id );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    function &fetchByUser( $userID, $asObject = true )
    {
        $conditions = array( "user_id" => $userID );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    function &instance( $userID = false )
    {
        if ( $userID === false )
        {
            $user =& eZUser::currentUser();
            $userID = $user->attribute( 'contentobject_id' );
        }
        $instance =& $GLOBALS["eZCollaborationProfile-$userID"];
        if ( !isset( $instance ) )
        {
            $instance = eZCollaborationProfile::fetchByUser( $userID );
            if ( $instance === null )
            {
                $group =& eZCollaborationGroup::instantiate( $userID, ezi18n( 'kernel/classes', 'Inbox' ) );
                $instance = eZCollaborationProfile::create( $userID, $group->attribute( 'id' ) );
                $instance->store();
            }
        }
        return $instance;
    }

}

?>
