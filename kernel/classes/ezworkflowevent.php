<?php
//
// Definition of eZWorkflowEvent class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

//!! eZKernel
//! The class eZWorkflowEvent does
/*!

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezworkflowtype.php" );

class eZWorkflowEvent extends eZPersistentObject
{
    function eZWorkflowEvent( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "version" => "Version",
                                         "workflow_id" => "WorkflowID",
                                         "workflow_type_string" => "TypeString",
                                         "description" => "Description",
                                         "data_int1" => "DataInt1",
                                         "data_int2" => "DataInt2",
                                         "data_int3" => "DataInt3",
                                         "data_int4" => "DataInt4",
                                         "data_text1" => "DataText1",
                                         "data_text2" => "DataText2",
                                         "data_text3" => "DataText3",
                                         "data_text4" => "DataText4",
                                         "placement" => "Placement" ),
                      "keys" => array( "id", "version" ),
                      "increment_key" => "id",
                      "sort" => array( "placement" => "asc" ),
                      "class_name" => "eZWorkflowEvent",
                      "name" => "ezworkflow_event" );
    }

    function &create( $workflow_id, $type_string )
    {
        $row = array(
            "id" => null,
            "version" => 1,
            "workflow_id" => $workflow_id,
            "workflow_type_string" => $type_string,
            "description" => "",
            "placement" => eZPersistentObject::newObjectOrder( eZWorkflowEvent::definition(),
                                                               "placement",
                                                               array( "version" => 1,
                                                                      "workflow_id" => $workflow_id ) ) );
        return new eZWorkflowEvent( $row );
    }

    function &fetch( $id, $as_object = true, $version = 0, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZWorkflowEvent::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $as_object );
    }

    function &fetchList( $as_object = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                    null, null, null, null,
                                                    $as_object );
    }

    function &fetchFilteredList( $cond, $as_object = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowEvent::definition(),
                                                    null, $cond, null, null,
                                                    $as_object );
    }

    /*!
     Moves the object down if $down is true, otherwise up.
     If object is at either top or bottom it is wrapped around.
    */
    function &move( $down, $params = null )
    {
        if ( is_array( $params ) )
        {
            $pos = $params["placement"];
            $wid = $params["workflow_id"];
            $version = $params["version"];
        }
        else
        {
            $pos = $this->Placement;
            $wid = $this->WorkflowID;
            $version = $this->Version;
        }
        return eZPersistentObject::reorderObject( eZWorkflowEvent::definition(),
                                                  array( "placement" => $pos ),
                                                  array( "workflow_id" => $wid,
                                                         "version" => $version ),
                                                  $down );
    }

    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(), array( "workflow_type" ) );
    }

    function hasAttribute( $attr )
    {
        return $attr == "workflow_type" or eZPersistentObject::hasAttribute( $attr );
    }

    function attribute( $attr )
    {
        if ( $attr == "workflow_type" )
            return $this->eventType();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &eventType()
    {
        return eZWorkflowType::createType( $this->TypeString );
    }

    /// \privatesection
    var $ID;
    var $Version;
    var $WorkflowID;
    var $TypeString;
    var $Description;
    var $Placement;
    var $DataInt1;
    var $DataInt2;
    var $DataInt3;
    var $DataInt3;
    var $DataText1;
    var $DataText2;
    var $DataText3;
    var $DataText4;
}

?>
