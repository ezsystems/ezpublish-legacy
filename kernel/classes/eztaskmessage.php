<?php
//
// Definition of eZTaskMessage class
//
// Created on: <05-Sep-2002 08:26:49 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztaskmessage.php
*/

/*!
  \class eZTaskMessage eztaskmessage.php
  \brief The class eZTaskMessage does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

define( 'EZ_TASK_MESSAGE_CREATOR_SENDER', 1 );
define( 'EZ_TASK_MESSAGE_CREATOR_RECEIVER', 2 );

class eZTaskMessage extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZTaskMessage( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => 'ID',
                                         'task_id' => 'TaskID',
                                         'contentobject_id' => 'ContentObjectID',
                                         'is_published' => 'IsPublished',
                                         'created' => 'Created',
                                         'creator_id' => 'CreatorID',
                                         'creator_type' => 'CreatorType' ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZTaskMessage',
                      'sort' => array( 'task_id' => 'asc',
                                       'created' => 'asc' ),
                      'name' => 'eztask_message' );
    }

    function &create( $taskID, $creatorType, $creatorID, $contentObjectID = 0 )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $date_time = eZDateTime::currentTimeStamp();
        $row = array(
            'id' => null,
            'task_id' => $taskID,
            'contentobject_id' => $contentObjectID,
            'created' => $date_time,
            'creator_id' => $creatorID,
            'creator_type' => $creatorType );
        return new eZTaskMessage( $row );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZTaskMessage::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'creator' or
                 $attr == 'contentobject' or
                 $attr == 'task' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case 'creator':
            {
                $userID = $this->CreatorID;
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                $user =& eZUser::fetch( $userID );
                return $user;
            } break;
            case 'task':
            {
                include_once( 'kernel/classes/eztask.php' );
                return eZTask::fetch( $this->TaskID );
            } break;
            case 'contentobject':
            {
                include_once( 'kernel/classes/ezcontentobject.php' );
                return eZContentObject::fetch( $this->ContentObjectID );
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        return null;
    }


    /// \privatesection
    var $ID;
    var $TaskID;
    var $ContentObjectID;
    var $Created;
    var $CreatorID;
    var $CreatorType;
    var $IsPublished;
}

?>
