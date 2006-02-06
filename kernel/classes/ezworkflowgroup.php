<?php
//
// Definition of eZWorkflowGroup class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
