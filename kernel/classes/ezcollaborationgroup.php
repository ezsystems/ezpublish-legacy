<?php
//
// Definition of eZCollaborationGroup class
//
// Created on: <22-Jan-2003 15:31:16 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezcollaborationgroup.php
*/

/*!
  \class eZCollaborationGroup ezcollaborationgroup.php
  \brief The class eZCollaborationGroup does

*/

//include_once( 'kernel/classes/ezpersistentobject.php' );
//include_once( 'kernel/classes/ezcollaborationitem.php' );

class eZCollaborationGroup extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'parent_group_id' => array( 'name' => 'ParentGroupID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZCollaborationGroup',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
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
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
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
                      "function_attributes" => array( 'user' => 'user',
                                                      'parent_group' => 'parentGroup',
                                                      'item_list' => 'itemList',
                                                      'item_count' => 'itemCount' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationGroup',
                      'sort' => array( 'title' => 'asc' ),
                      'name' => 'ezcollab_group' );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function addChild( $group, $store = true )
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

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function instantiate( $userID, $title, $parentGroupID = 0, $isOpen = true )
    {
        $depth = 0;
        $pathString = '';
        if ( $parentGroupID > 0 )
        {
            $parentGroup = eZCollaborationGroup::fetch( $parentGroupID, $userID );
            $depth = $parentGroup->attribute( 'depth' ) + 1;
            $pathString = $parentGroup->attribute( 'path_string' );
        }
        $group = eZCollaborationGroup::create( $userID, $title, '', $depth, $parentGroupID, $isOpen );

        $db = eZDB::instance();
        $db->begin();

        $group->store();
        if ( $pathString == '' )
            $pathString = $group->attribute( 'id' );
        else
            $pathString .= '/' . $group->attribute( 'id' );
        $group->setAttribute( 'path_string', $pathString );
        $group->sync();

        $db->commit();
        return $group;
    }

    static function create( $userID, $title, $pathString = '', $depth = 0, $parentGroupID = 0, $isOpen = true )
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

    static function fetch( $id, $userID = false, $asObject = true )
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
    function itemList( $parameters = array() )
    {
        return eZCollaborationItem::fetchList( array_merge( array( 'parent_group_id' => $this->ID ),
                                                                 $parameters ) );
    }

    static function subTree( $parameters = array() )
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
            $group = eZCollaborationGroup::fetch( $parentGroupID );

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

        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $sql = "SELECT *
                FROM
                      ezcollab_group
                WHERE
                      $pathSQL
                      $depthSQL
                      id != '$parentGroupID' AND
                      user_id = '$userID'
                ORDER BY $sortingFields";

        $db = eZDB::instance();
        $sqlParameters = array();
        if ( $offset !== false and $limit !== false )
        {
            $sqlParameters['offset'] = $offset;
            $sqlParameters['limit'] = $limit;
        }
        $groupListArray = $db->arrayQuery( $sql, $sqlParameters );
        $returnGroupList = eZPersistentObject::handleRows( $groupListArray, 'eZCollaborationGroup', $asObject );
        eZDebugSetting::writeDebug( 'collaboration-group-tree', $returnGroupList );
        return $returnGroupList;
    }

    function itemCount( $parameters = array() )
    {
        $parameters = array_merge( array( 'as_object' => true ),
                                   $parameters );
        $asObject = $parameters['as_object'];

        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $groupID = $this->ID;

        $db = eZDB::instance();
        $sql = "SELECT   count( collaboration_id ) as count
                FROM     ezcollab_item_group_link
                WHERE    user_id = '$userID' AND
                         group_id = '$groupID'";
        $countArray = $db->arrayQuery( $sql );
        return $countArray[0]['count'];
    }

    function user()
    {
        if ( isset( $this->UserID ) and $this->UserID )
        {
            //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            return eZUser::fetch( $this->UserID );
        }
        return null;
    }

    function parentGroup()
    {
        if ( isset( $this->ParentGroupID ) and $this->ParentGroupID )
        {
            return eZCollaborationGroup::fetch( $this->ParentGroupID );
        }
        return null;
    }

    /// \privatesection
    public $ID;
    public $ParentGroupID;
    public $UserID;
    public $Title;
    public $Created;
    public $Modified;
}

?>
