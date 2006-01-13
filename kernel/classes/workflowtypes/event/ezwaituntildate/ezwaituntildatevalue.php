<?php
//
// Definition of eZWaitUntilDateValue class
//
// Created on: <14-ñÎ×-2003 14:49:06 sp>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
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

/*! \file ezwaituntildatevalue.php
*/

/*!
  \class eZWaitUntilDateValue ezwaituntildatevalue.php
  \brief The class eZWaitUntilDateValue does

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZWaitUntilDateValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZWaitUntilDateValue( $row )
    {
        $this->eZPersistentObject( $row );
        $this->ClassName = null;
        $this->ClassAttributeName = null;

    }

    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "workflow_event_id" => array( 'name' => "WorkflowEventID",
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true ),
                                         "workflow_event_version" => array( 'name' => "WorkflowEventVersion",
                                                                            'datatype' => 'integer',
                                                                            'default' => 0,
                                                                            'required' => true ),
                                         "contentclass_id" => array( 'name' => "ContentClassID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         "contentclass_attribute_id" => array( 'name' => "ContentClassAttributeID",
                                                                               'datatype' => 'integer',
                                                                               'default' => 0,
                                                                               'required' => true ) ),
                      "keys" => array( "id", "workflow_event_id", "workflow_event_version" ),
                      "function_attributes" => array( "class_name" => "className",
                                                      "classattribute_name" => "classAttributeName" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZWaitUntilDateValue",
                      "name" => "ezwaituntildatevalue" );
    }

    function &className()
    {
        if ( $this->ClassName === null )
        {
            $contentClass = eZContentClass::fetch( $this->attribute( 'contentclass_id' ) );
            $this->ClassName =& $contentClass->attribute( 'name' );
        }
        return $this->ClassName;
    }

    function &classAttributeName()
    {
        if ( $this->ClassAttributeName === null )
        {
            $contentClassAttribute =& eZContentClassAttribute::fetch( $this->attribute( 'contentclass_attribute_id' ) );
            $this->ClassAttributeName =& $contentClassAttribute->attribute( 'name' );
        }
        return $this->ClassAttributeName;
    }

    function &clone()
    {
        $row = array( "id" => null,
                      "workflow_event_id" => $this->attribute( 'workflow_event_id' ),
                      "workflow_event_version" => $this->attribute( 'workflow_event_version' ),
                      "contentclass_id" => $this->attribute( "contentclass_id" ),
                      "contentclass_attribute_id" => $this->attribute( 'contentclass_attribute_id' ) );
        $newWaitUntilDateValue = new eZWaitUntilDateValue( $row );
        return $newWaitUntilDateValue;
    }

    function create( $workflowEventID, $workflowEventVersion, $contentClassAttributeID, $contentClassID )
    {
        $row = array( "id" => null,
                      "workflow_event_id" => $workflowEventID,
                      "workflow_event_version" => $workflowEventVersion,
                      "contentclass_id" => $contentClassID,
                      "contentclass_attribute_id" => $contentClassAttributeID
                      );
        return new eZWaitUntilDateValue( $row );
    }

    function createCopy( $id, $workflowEventID, $workflowEventVersion,  $contentClassID , $contentClassAttributeID )
    {
        $row = array( "id" => $id,
                      "workflow_event_id" => $workflowEventID,
                      "workflow_event_version" => $workflowEventVersion,
                      "contentclass_id" => $contentClassID,
                      "contentclass_attribute_id" => $contentClassAttributeID );
        return new eZWaitUntilDateValue( $row );
    }


    function removeAllElements( $workflowEventID, $version )
    {
        eZPersistentObject::removeObject( eZWaitUntilDateValue::definition(),
                                          array( "workflow_event_id" => $workflowEventID,
                                                 "workflow_event_version" => $version) );
    }

    function remove( $id , $version )
    {
        eZPersistentObject::removeObject( eZWaitUntilDateValue::definition(),
                                          array( "id" => $id,
                                                 "workflow_event_version" => $version) );
    }

    function &fetch( $id, $version, $asObject = true )
    {
        $object = eZPersistentObject::fetchObject( eZWaitUntilDateValue::definition(),
                                                    null,
                                                    array( "id" => $id,
                                                           "workflow_event_version" => $version),
                                                    $asObject );
        return $object;
    }

    function &fetchAllElements( $workflowEventID, $version, $asObject = true )
    {
        $objectList = eZPersistentObject::fetchObjectList( eZWaitUntilDateValue::definition(),
                                                            null,
                                                            array( "workflow_event_id" => $workflowEventID,
                                                                   "workflow_event_version" => $version ),
                                                            null,
                                                            null,
                                                            $asObject );
        return $objectList;
    }

    var $ClassName;
    var $ClassAttributeName;
}

?>
