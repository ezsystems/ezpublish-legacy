<?php
//
// Definition of eZCollaborationGroup class
//
// Created on: <22-Jan-2003 15:31:16 amos>
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

/*! \file ezcollaborationgroup.php
*/

/*!
  \class eZCollaborationGroup ezcollaborationgroup.php
  \brief The class eZCollaborationGroup does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );
include_once( 'kernel/classes/ezcollaborationitem.php' );

class eZCollaborationGroup extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'parent_group_id' => array( 'name' => 'ParentGroupID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         'depth' => array( 'name' => 'Depth',
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'path_string' => array( 'name' => 'PathString',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'is_open' => array( 'name' => 'IsOpen',
                                                             'datatype' => 'integer',
                                                             'default' => '1',
                                                             'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => '0',
                                                             'required' => true ),
                                         'title' => array( 'name' => 'Title',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => '0',
                                                             'required' => true ),
                                         'modified' =>  array( 'name' => 'Modified',
                                                               'datatype' => 'integer',
                                                               'default' => '0',
                                                               'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationGroup',
                      'sort' => array( 'title' => 'asc' ),
                      'name' => 'ezcollab_group' );
    }

    function addChild( &$group, $store = true )
    {
        $pathString = $this->PathString;
        if ( $pathString != '' )
            $pathString .= '/';
        $pathString .= $this->ID;
        $depth = $this->Depth + 1;
        $parentGroupID = $this->ID;
        $group->setAttribute( 'path_string', $pathString );
        $group->setAttribute( 'parent_group_id', $parentGroupID );
        $group->setAttribute( 'depth', $depth );
        if ( $store )
            $group->sync();
    }

    function &instantiate( $userID, $title, $parentGroupID = 0, $isOpen = true )
    {
        $depth = 0;
        $pathString = '';
        if ( $parentGroupID > 0 )
        {
            $parentGroup =& eZCollaborationGroup::fetch( $parentGroupID, $userID );
            $depth = $parentGroup->attribute( 'depth' ) + 1;
            $pathString = $parentGroup->attribute( 'path_string' );
        }
        $group =& eZCollaborationGroup::create( $userID, $title, '', $depth, $parentGroupID, $isOpen );
        $group->store();
        if ( $pathString == '' )
            $pathString = $group->attribute( 'id' );
        else
            $pathString .= '/' . $group->attribute( 'id' );
        $group->setAttribute( 'path_string', $pathString );
        $group->sync();
        return $group;
    }

    function &create( $userID, $title, $pathString = '', $depth = 0, $parentGroupID = 0, $isOpen = true )
    {
        $date_time = time();
        $row = array(
            'id' => null,
            'parent_group_id' => $parentGroupID,
            'path_string' => $pathString,
            'depth' => $depth,
            'is_open' => $isOpen,
            'user_id' => $userID,
            'title' => $title,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationGroup( $row );
    }

    function &fetch( $id, $userID = false, $asObject = true )
    {
        $conditions = array( "id" => $id );
        if ( $userID !== false )
            $conditions['user_id'] = $userID;
        return eZPersistentObject::fetchObject( eZCollaborationGroup::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    /*!
     \return an array with collaboration items which are in this group.
    */
    function &itemList( $parameters = array() )
    {
        return eZCollaborationItem::fetchList( array_merge( array( 'parent_group_id' => $this->ID ),
                                                            $parameters ) );
    }

    function &subTree( $parameters = array() )
    {
        $parameters = array_merge( array( 'parent_group_id' => false,
                                          'depth' => false,
                                          'sort_by' => false,
                                          'as_object' => true,
                                          'offset' => false,
                                          'limit' => false ),
                                   $parameters );
        $parentGroupID = $parameters['parent_group_id'];
        $depth = $parameters['depth'];
        $asObject = $parameters['as_object'];
        $offset = $parameters['offset'];
        $limit = $parameters['limit'];

        $group = null;
        if ( $parentGroupID > 0 )
            $group =& eZCollaborationGroup::fetch( $parentGroupID );

        $sortCount = 0;
        $sortList = $parameters['sort_by'];
        if ( is_array( $sortList ) and
             count( $sortList ) > 0 )
        {
            if ( count( $sortList ) > 1 and
                 !is_array( $sortList[0] ) )
            {
                $sortList = array( $sortList );
            }
        }
        if ( $sortList !== false )
        {
            $sortingFields = '';
            foreach ( $sortList as $sortBy )
            {
                if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                {
                    if ( $sortCount > 0 )
                        $sortingFields .= ', ';
                    $sortField = $sortBy[0];
                    switch ( $sortField )
                    {
                        case 'path':
                        {
                            $sortingFields .= 'path_string';
                        } break;
                        case 'created':
                        {
                            $sortingFields .= 'created';
                        } break;
                        case 'modified':
                        {
                            $sortingFields .= 'modified';
                        } break;
                        case 'depth':
                        {
                            $sortingFields .= 'depth';
                        } break;
                        case 'priority':
                        {
                            $sortingFields .= 'priority';
                        } break;
                        case 'title':
                        {
                            $sortingFields .= 'title';
                        } break;
                        default:
                        {
                            eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, 'eZCollaboration::subTree' );
                            continue;
                        };
                    }
                    $sortOrder = true; // true is ascending
                    if ( isset( $sortBy[1] ) )
                        $sortOrder = $sortBy[1];
                    $sortingFields .= $sortOrder ? " ASC" : " DESC";
                    ++$sortCount;
                }
            }
        }
        if ( $sortCount == 0 )
        {
            $sortingFields = " path_string ASC";
        }

        $pathString = '';
        if ( $group !== null )
            $pathString = $group->attribute( 'path_string' );

        $depthSQL = "";
        if ( $depth !== false )
            $depthSQL = "depth <= '$depth' AND";
        $pathSQL = '';
        if ( $pathString != '' )
            $pathSQL = "path_string like '$pathString%' AND";

        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $sql = "SELECT *
                FROM
                      ezcollab_group
                WHERE
                      $pathSQL
                      $depthSQL
                      id != '$parentGroupID' AND
                      user_id = '$userID'
                ORDER BY $sortingFields";

        $db =& eZDB::instance();
        $sqlParameters = array();
        if ( $offset !== false and $limit !== false )
        {
            $sqlParameters['offset'] = $offset;
            $sqlParameters['limit'] = $limit;
        }
        $groupListArray =& $db->arrayQuery( $sql, $sqlParameters );
        $returnGroupList =& eZPersistentObject::handleRows( $groupListArray, 'eZCollaborationGroup', $asObject );
        eZDebugSetting::writeDebug( 'collaboration-group-tree', $returnGroupList );
        return $returnGroupList;
    }

    function itemCount( $parameters = array() )
    {
        $parameters = array_merge( array( 'as_object' => true ),
                                   $parameters );
        $asObject = $parameters['as_object'];

        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $groupID = $this->ID;

        $db =& eZDB::instance();
        $sql = "SELECT   count( collaboration_id ) as count
                FROM     ezcollab_item_group_link
                WHERE    user_id = '$userID' AND
                         group_id = '$groupID'";
        $countArray =& $db->arrayQuery( $sql );
        return $countArray[0]['count'];
    }

    function hasAttribute( $attr )
    {
        return ( $attr == 'user' or
                 $attr == 'parent_group' or
                 $attr == 'item_list' or
                 $attr == 'item_count' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'user':
            {
                $userID = $this->UserID;
                include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
                $user =& eZUser::fetch( $userID );
                return $user;
            } break;
            case 'parent_group':
            {
                $parentGroupID = $this->ParentGroupID;
                $parent = null;
                if ( $parentGroupID > 0 )
                    $parent = eZCollaborationGroup::fetch( $parentGroupID );
                return $parent;
            } break;
            case 'item_list':
            {
                return $this->itemList();
            } break;
            case 'item_count':
            {
                return $this->itemCount();
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    /// \privatesection
    var $ID;
    var $ParentGroupID;
    var $UserID;
    var $Title;
    var $Created;
    var $Modified;
}

?>
