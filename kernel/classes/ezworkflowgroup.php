<?php
//
// Definition of eZWorkflowGroup class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
 \class eZWorkflowGroup ezworkflowgroup.php
 \brief Handles grouping of workflows

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZWorkflowGroup extends eZPersistentObject
{
    function eZWorkflowGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "creator_id" => array( 'name' => "CreatorID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZWorkflowGroup",
                      "sort" => array( "name" => "asc" ),
                      "name" => "ezworkflow_group" );
    }

    function &create( $user_id )
    {
        $date_time = time();
        $row = array(
            "id" => null,
            "name" => "",
            "creator_id" => $user_id,
            "modifier_id" => $user_id,
            "created" => $date_time,
            "modified" => $date_time );
        return new eZWorkflowGroup( $row );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWorkflowGroup::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowGroup::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &removeSelected ( $id )
    {
        eZPersistentObject::removeObject( eZWorkflowGroup::definition(),
                                          array( "id" => $id ) );
    }

    /*  function &fetchWorkflowList( $asObject = true, $id = false )
    {
        if ( $id === false )
            $id = $this->attribute( "id" );
        $db =& eZDB::instance();
        if ( $asObject )
        {
            $def =& eZWorkflowGroup::definition();
            $fields =& $def['fields'];
            $select_sql = '';
            $i = 0;
            foreach( $fields as $field => $variable )
            {
                if ( $i > 0 )
                    $select_sql .= ', ';
                $select_sql .= 'ezworkflow.' . $field . ' AS ' . $field;
                ++$i;
            }
        }
        else
            $select_sql = "ezworkflow_group_link.workflow_id AS workflow_id";
        $query = "SELECT $select_sql
FROM ezworkflow_group_link,
     ezworkflow
WHERE ezworkflow_group_link.workflow_id=ezworkflow.id AND
      ezworkflow.is_enabled='1' AND
      ezworkflow_group_link.group_id='$id'
ORDER BY ezworkflow.name ASC";
        $rows =& $db->arrayQuery( $query );
        $workflows = array();
        if ( $asObject )
        {
            foreach( $rows as $row )
                $workflows[] = new eZWorkflowGroup( $row );
        }
        else
        {
            foreach( $rows as $row )
                $workflows[] = $row['workflow_id'];
        }
        return $workflows;
    }*/

    function attributes()
    {
        return array_merge( eZPersistentObject::attributes(),
                            array( "creator",
                                   "modifier",
                                   "workflows" ) );
    }

    function hasAttribute( $attr )
    {
        return ( $attr == "creator" or $attr == "modifier" or
                 $attr == "workflows" or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case "creator":
            {
                $user_id = $this->CreatorID;
            } break;
            case "modifier":
            {
                $user_id = $this->ModifierID;
            } break;
            case "workflows":
            {
                // return $this->fetchWorkflowList( false );
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user =& eZUser::fetch( $user_id );
        return $user;
    }

    /// \privatesection
    var $ID;
    var $Name;
    var $CreatorID;
    var $ModifierID;
    var $Created;
    var $Modified;
}

?>
