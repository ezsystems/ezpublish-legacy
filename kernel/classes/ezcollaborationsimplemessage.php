<?php
//
// Definition of eZCollaborationSimpleMessage class
//
// Created on: <24-Jan-2003 15:38:57 amos>
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

/*! \file ezcollaborationsimplemessage.php
*/

/*!
  \class eZCollaborationSimpleMessage ezcollaborationsimplemessage.php
  \brief The class eZCollaborationSimpleMessage does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZCollaborationSimpleMessage extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationSimpleMessage( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'message_type' => array( 'name' => 'MessageType',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ),
                                         'data_text1' => array( 'name' => 'DataText1',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text2' => array( 'name' => 'DataText2',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_text3' => array( 'name' => 'DataText3',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'data_int1' => array( 'name' => 'DataInt1',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int2' => array( 'name' => 'DataInt2',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_int3' => array( 'name' => 'DataInt3',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'data_float1' => array( 'name' => 'DataFloat1',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float2' => array( 'name' => 'DataFloat2',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'data_float3' => array( 'name' => 'DataFloat3',
                                                                 'datatype' => 'float',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'creator_id' => array( 'name' => 'CreatorID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
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
                      'class_name' => 'eZCollaborationSimpleMessage',
                      'name' => 'ezcollab_simple_message' );
    }

    function &create( $type, $text = false, $creatorID = false )
    {
        $date_time = time();
        if ( $creatorID === false )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            $user =& eZUser::currentUser();
            $creatorID =& $user->attribute( 'contentobject_id' );
        }
        $row = array(
            'message_type' => $type,
            'data_text1' => $text,
            'creator_id' => $creatorID,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationSimpleMessage( $row );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZCollaborationSimpleMessage::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'participant' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'participant':
            {
                // TODO: Get participant trough participant link from item
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    /// \privatesection
    var $ID;
    var $ParticipantID;
    var $Created;
    var $Modified;
    var $DataText1;
    var $DataText2;
    var $DataText3;
    var $DataInt1;
    var $DataInt2;
    var $DataInt3;
    var $DataFloat1;
    var $DataFloat2;
    var $DataFloat3;
}

?>
